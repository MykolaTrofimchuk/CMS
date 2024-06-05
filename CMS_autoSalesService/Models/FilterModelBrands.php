<?php

namespace Models;

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
}