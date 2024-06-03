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

    public static function SelectAllBrands()
    {
        // Шлях до CSV файлу
        $csvFile = "./src/all-vehicles-model.csv";

        // Відкриття CSV файлу для читання
        $file = fopen($csvFile, "r");

        // Масив для збереження унікальних значень brand
        $uniqueBrands = [];

        // Читання файлу рядок за рядком
        while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
            $brand = $data[0];

            // Перевірка, чи бренд ще не додано до масиву унікальних брендів
            if (!in_array($brand, $uniqueBrands)) {
                // Додавання унікального бренду до масиву
                $uniqueBrands[] = $brand;


            }
        }
        fclose($file);

        return $uniqueBrands;
    }

    public static function SelectAllModelsByBrand($selectedBrand)
    {
        // Шлях до CSV файлу
        $csvFile = "./src/all-vehicles-model.csv";

        // Відкриття CSV файлу для читання
        $file = fopen($csvFile, "r");

        // Масив для збереження унікальних моделей для вказаної марки
        $uniqueModelsByBrand = [];

        // Читання файлу рядок за рядком
        while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
            $brand = $data[0];
            $model = $data[1];

            // Перевірка, чи поточний бренд співпадає з вибраним
            if ($brand === $selectedBrand && !in_array($model, $uniqueModelsByBrand)) {
                // Додавання унікальної моделі до масиву моделей для вказаної марки
                $uniqueModelsByBrand[] = $model;
            }
        }
        fclose($file);

        return $uniqueModelsByBrand;
    }
}