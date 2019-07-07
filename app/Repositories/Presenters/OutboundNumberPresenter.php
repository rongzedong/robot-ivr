<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/7
 * Time: 17:44
 */

namespace App\Repositories\Presenters;


use App\Repositories\Transformers\OutboundNumberTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class OutboundNumberPresenter extends FractalPresenter
{

    public function getTransformer()
    {
        return new OutboundNumberTransformer();
    }
}