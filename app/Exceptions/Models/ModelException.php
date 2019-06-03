<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/3/5
 * Time: 19:56
 */

namespace App\Exceptions\Models;


use App\Exceptions\Handler422Exception;

class ModelException extends Handler422Exception
{

    private $model;

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }
}