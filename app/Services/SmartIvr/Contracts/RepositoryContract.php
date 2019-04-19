<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/2/25
 * Time: 17:58
 */

namespace App\Services\SmartIvr\Contracts;

use Illuminate\Support\Collection;

/**
 * 话术资源
 * Interface RepositoryContract
 * @package App\Services\SmartIvr\Contracts
 */
interface RepositoryContract
{
    public function getNormal();

    /**
     * 获取应对噪音问题的处理话术
     * @param string $contextId 上下文ID
     * @return Collection
     */
    public function getSpecialInvalid($contextId = '');

    /**
     * 获取挂断处理话术
     * @param string $contextId
     * @param string $keyword 匹配词条
     * @return Collection
     */
    public function getSpecialHangup($contextId = '', $keyword = '*');

    /**
     * 获取用户不说话时的处理话术
     * @param string $contextId 上下文ID
     * @return Collection
     */
    public function getSpecialNoAnswer($contextId = '');


    /**
     * 获取 默认兜底话术
     * @param string $contextId 上下文ID
     * @return Collection
     */
    public function getSpecialDefault($contextId = '');

    /**
     * 获取转接处理失败后的话术
     * @param string $contextId
     * @return Collection
     */
    public function getSpecialBridgeFailed($contextId = '');

    /**
     * 匹配 上下文或全局
     * @param string $keyword
     * @param string $contextId
     * @return Collection
     */
    public function match($keyword, $contextId = '');

    /**
     * 获取指定话术
     * @param $id
     * @return ModelContract|null
     */
    public function getById($id);
}