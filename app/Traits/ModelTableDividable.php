<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/8/12
 * Time: 0:20
 */

namespace App\Traits\Models;

use App\Exceptions\Models\ModelException;

/**
 * 可分表的模型
 * Trait TableDividable
 * @package App\Traits\Models
 */
trait ModelTableDividable
{

    /**
     * 固定分表数量
     */
    protected static function tablePartNum()
    {
        return self::PART_NUM ?? 1;
    }

    /**
     * @var string $table_part_identification 分表标识
     */
    private static $table_part_identification;

    /**
     * 设置分表标识
     * @param string $identification
     */
    public static function setTableIdentification($identification)
    {
        self::$table_part_identification = $identification;
    }

    /**
     * 获取分表标识
     * @return string
     */
    public static function getTableIdentification()
    {
        return self::$table_part_identification;
    }

    /**
     * 分用户id分表
     */
    public function getTable()
    {
        $table_part_identification = $this->getTableIdentification();
        if (empty($table_part_identification)) {
            throw (new ModelException('分表标识不存在'))->setModel($this);
        }

        return $this->table . '_' . $this->calculateSuffix($table_part_identification, self::tablePartNum());
    }

    /**
     * 计算表名后缀
     * @param string $value 参考值
     * @param int $number 分表数
     * @return int
     */
    protected function calculateSuffix($value, $number)
    {
        return abs(sprintf('%u', crc32($value))) % $number;
    }
}