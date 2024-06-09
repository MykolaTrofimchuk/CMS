<?php

namespace Models;

use core\Core;
use core\Model;

/**
 * @property int $id ID запису
 * @property string $brand марка
 * @property string $model модель
 */

class FilterModelBrands extends Model
{
    public static $tableName = 'filter_model_brands';

    public static function FindAllBrandUnique()
    {
        $data = self::findAll();
        $uniqueBrands = [];

        foreach ($data as $item){
            $brand = $item['brand'];
            if (!in_array($brand, $uniqueBrands)) {
                $uniqueBrands[] = $brand;
            }
        }

        var_dump($uniqueBrands);
        return $uniqueBrands;
    }

    public static function FindModelsByBrand($selectedBrand)
    {
        $models = self::findRowsByCondition(["model"], ['brand' => $selectedBrand]);
        $uniqModels = [];
        foreach ($models as $item){
            $model = $item['model'];
            if (!in_array($model, $uniqModels)) {
                $uniqModels[] = $model;
            }
        }
        return $uniqModels;
    }

    public static function AddRow($brand, $model)
    {
        $row = new FilterModelBrands();
        $row->brand = $brand;
        $row->model = $model;
        $row->save();
    }

    public static function EditRowInfo($rowId, $dataToUpdate)
    {
        $row = FilterModelBrands::selectRowById($rowId, 'Models\FilterModelBrands');

        if ($row) {
            foreach ($dataToUpdate as $field => $value) {
                if (isset($value) && !empty($value)) {
                    $row->{$field} = $value;
                }
            }
            $row->save();
            return true;
        } else {
            return false;
        }
    }

    public static function DeleteRow($where){
        if (empty($where))
            $where = null;
        return Core::get()->db->delete(self::$tableName, $where);
    }

    public static function CountAll($where = null)
    {
        $result = self::findRowsByCondition('COUNT(*) as count', $where);
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }

    public static function getPaginatedRows($limit, $offset, $where = null)
    {
        if (empty($where))
            $where = null;
        $rows = Core::get()->db->select(self::$tableName, '*', $where, $limit, $offset, );
        return $rows;
    }

    public static function countAllRows()
    {
        return self::CountAll();
    }
}