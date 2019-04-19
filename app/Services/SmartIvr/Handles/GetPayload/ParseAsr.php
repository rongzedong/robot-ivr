<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/2/25
 * Time: 15:10
 */

namespace App\Services\SmartIvr\Handles\GetPayload;

use App\Services\SmartIvr\Contracts\HandleGetPayloadContract;
use App\Services\SmartIvr\Contracts\HandleContract;
use App\Services\SmartIvr\Contracts\ModelContract;
use App\Services\SmartIvr\Contracts\NotifyContract;
use App\Services\SmartIvr\Contracts\RepositoryContract;
use App\Services\SmartIvr\Exceptions\PayloadException;
use App\Services\SmartIvr\Handles\BaseHandle;
use App\Services\SmartIvr\Payload\Bridge;
use App\Services\SmartIvr\Payload\ConsolePlayback;
use App\Services\SmartIvr\Payload\PlayAfterHangup;
use App\Services\SmartIvr\Payload\Playback;
use Illuminate\Database\Eloquent\Collection;

/**
 * 解析通话中用户说的话做出相应回答的处理
 * Class ParseAsr
 * @package App\Services\SmartIvr\Handles\Get
 */
class ParseAsr implements HandleGetPayloadContract
{
    /**
     * @var BaseHandle $handle
     */
    protected $handle;

    protected $message;

    protected $collect;

    /**
     * 询问词
     * @var Collection $wordClassQuery
     */
    protected $wordClassQuery;

    /**
     * 否定
     * @var Collection $wordClassNo
     */
    protected $wordClassNo;

    /**
     * 肯定
     * @var Collection $wordClassYes
     */
    protected $wordClassYes;

    /**
     * 特殊词性
     * @var Collection $actionRebroadcast
     */
    protected $actionRebroadcast;

    /**
     * @var Collection $hangups
     */
    protected $hangups;

    /**
     * @var bool $actionNext
     */
    protected $actionNext = false;
    /**
     * @var Collection $hangups
     */
    protected $bridge;

    /**
     * @var RepositoryContract 话术资源
     */
    protected $repository;

    protected $callId;

    /**
     * @var bool 是否有匹配到相应的话术
     */
    protected $hasData = false;

    /**
     * ParseAsr constructor.
     * @param HandleContract $handle
     */
    public function __construct(HandleContract $handle)
    {
        $this->handle = $handle;
        $this->init();
    }

    protected function init()
    {
        $this->callId = $this->handle->getHandleEntity()->callId;

        $this->repository = $this->handle->getRepository();

        $this->collect = new Collection();
    }

    protected function match($message)
    {
        //搜索场景应答话术
        /**
         * @var Collection $models
         */
        $models = $this->repository->match($message, $this->handle->scene())->filter->filterUsedCallRecord($this->callId);
        if ($models->isEmpty() && $this->handle->scene()) {
            //全局搜索对应的话术
            $models = $this->repository->match($message)->filter->filterUsedCallRecord($this->callId);
        }

        $this->collect = $models;

        $this->hasData = $models->isNotEmpty();

    }

    /**
     * 是否有匹配到相应的话术
     * @return bool
     */
    public function has()
    {
        return $this->hasData;
    }

    /**
     * @param $message
     * @return $this
     */
    public function message($message)
    {
        $this->match($message);
        return $this;
    }

    /**
     * 获取匹配话术载体
     * @param NotifyContract $notify (话术测试运行时，为null)
     * @return ConsolePlayback|mixed
     */
    public function getPayload(NotifyContract $notify = null)
    {
        //分析话术
        $this->parseVerbalTrick();
        try {
            //调节匹配话术
            $this->modulation();

            //匹配到结束语
            $this->playAfterHangup();

            if ($this->actionNext) {
                //跳传下个流程话术
                $this->playNextNormal();
            }
            //转接
            $this->playBridge();

            if ($notify) {
                //正在放音时，没有匹配到话术，恢复放音
                if ($notify->isPlayState() &&
                    $notify->getPlayMs() > 0
                ) {
                    //被打断放音时，没有匹配到关键词时,恢复放音
                    return (new ConsolePlayback())->resume()
                        ->setContext($notify->getContext())
                        ->setEnquire($notify->getEnquire());
                }
            }

            //默认话术(兜底)
            $this->playDefault();

            //下个流程话术
            $this->playNextNormal();
            //挂电话
            $this->playHangup();

        } catch (PayloadException $payloadException) {
            $payload = $payloadException->getPayload();
            //添加通话使用记录缓存
            if ($model = $payload->getModel()) {
                $this->handle->useRecord($model);
            }
            return $this->handle->finalTreatmentPayload($payload);
        }
    }

    /**
     * 调节匹配到的话术
     * @throws PayloadException
     */
    protected function modulation()
    {
        if ($this->wordClassQuery->isNotEmpty()) {
            $model = $this->wordClassQuery->shift();
        } elseif ($this->wordClassNo->isNotEmpty()) {
            $model = $this->wordClassNo->shift();
        } elseif ($this->wordClassYes->isNotEmpty()) {
            $model = $this->wordClassYes->shift();
        } elseif ($this->actionRebroadcast->isNotEmpty()) {
            $model = $this->actionRebroadcast->shift();
        } else {
            $model = null;
        }

        if ($model instanceof ModelContract) {
            //识别到应答话术
            $playback = new Playback($this->handle->getHandleEntity(), $model);

            $this->handle->currentVerbalTrick($model);

            throw new PayloadException($playback);
        }
    }

    /**
     * 分析匹配到的话术
     */
    protected function parseVerbalTrick()
    {
        $this->wordClassQuery = collect();
        $this->wordClassYes = collect();
        $this->wordClassNo = collect();

        $this->actionRebroadcast = collect();
        //转接
        $this->bridge = collect();
        //声明挂断话术
        $this->hangups = collect();

        //正常应答
        $this->collect->each(function (ModelContract $item) {
            //如果匹配到特殊类型的应答
            if ($item->isWordClassOfQuery()) {
                //询问
                $this->wordClassQuery->push($item);
                return false;
            } elseif ($item->isWordClassOfNo()) {
                //否决-挽留
                $this->wordClassNo->push($item);
                return false;
            } elseif ($item->isWordClassOfYes()) {
                //肯定
                $this->wordClassYes->push($item);
                return false;
            } elseif ($item->isWordClassOfSpecial()) {

                if ($item->isActionRebroadcast()) {
                    //重播
                    if ($id = $this->handle->scene()) {//获取上次应答的内容
                        $this->actionRebroadcast->push($this->repository->getById($id));
                    }
                } elseif ($item->isActionNext()) {
                    //跳转下个流程
                    $this->actionNext = true;
                } elseif ($item->isActionHangup()) {
                    //挂断
                    $this->hangups->push($item);
                } elseif ($item->isActionBridge()) {
                    $this->bridge->push($item);
                }

                return false;
            }
        });

    }

    /**
     * 挂断话术
     * @throws PayloadException
     */
    protected function playAfterHangup()
    {
        if ($this->hangups->isNotEmpty()) {

            $model = $this->hangups->shift();

            $this->handle->currentVerbalTrick($model);

            $payload = $this->handle->hangup($model);

            throw new PayloadException($payload);
        }

    }

    /**
     * 播放下个常规话术
     * @throws PayloadException
     */
    protected function playNextNormal()
    {
        //获取常规流程
        $model = $this->handle->getNormal();
        if ($model instanceof ModelContract) {
            $playback = new Playback($this->handle->getHandleEntity(), $model);

            //重置新流程用户回应标记
            $this->handle->userSpeak(false);

            throw new PayloadException($playback);
        }
    }

    /**
     * 电话转接处理
     * @throws PayloadException
     */
    protected function playBridge()
    {
        if ($this->bridge->isNotEmpty()) {
            $model = $this->bridge->shift();

            $this->handle->currentVerbalTrick($model);

            $payload = new Bridge($this->handle->getHandleEntity(), $model);

            throw new PayloadException($payload);
        }
    }


    /**
     * 匹配不到关键词时兜底
     * @throws PayloadException
     */
    protected function playDefault()
    {
        /**
         * @var Collection $models
         */
        $models = $this->repository->getSpecialDefault($this->handle->scene())->filter->filterUsedCallRecord($this->callId);
        if ($models->isEmpty() && $this->handle->scene()) {
            $models = $this->repository->getSpecialDefault()->filter->filterUsedCallRecord($this->callId);
        }

        if ($models->isEmpty()) {
            return null;
        }

        $model = $models->first();

        if ($model instanceof ModelContract) {
            throw new PayloadException($this->parseDefault($model));
        }

    }

    /**
     * 处理兜底话术
     * @param ModelContract $model
     * @return Bridge|PlayAfterHangup|Playback
     */
    protected function parseDefault(ModelContract $model)
    {
        if ($model->isActionHangup()) {
            //兜底后直接挂断
            $payload = (new PlayAfterHangup($this->handle->getHandleEntity(), $model))->userMsg('default-hangup');
        } elseif ($model->isActionBridge()) {
            //兜底后直接转接
            $payload = new Bridge($this->handle->getHandleEntity(), $model);
        } else {
            $payload = new Playback($this->handle->getHandleEntity(), $model);
        }

        return $payload;
    }

    /**
     * 播放挂断话术
     * @throws PayloadException
     */
    protected function playHangup()
    {
        $playback = $this->handle->hangup($this->handle->firstSpecialHangup());

        throw new PayloadException($playback);
    }
}