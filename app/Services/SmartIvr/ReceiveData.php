<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/3/27
 * Time: 15:44
 */

namespace App\Services\SmartIvr;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class ReceiveData
 * @package App\Services\SmartIvr
 */
class ReceiveData implements ArrayAccess, Arrayable
{

    private $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public static function make($data = [])
    {
        return new static($data);
    }

    public function has($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->data[$key];
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->data[] = $value;
        } else {
            $this->data[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->data[$key]);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    public function __get($name)
    {
        return array_get($this->data, $name);
    }
}