<?php

namespace Models;

use core\Core;
use core\Model;
/**
 * @property int $id ID
 * @property string $veh_condition Стан авто
 * @property string $brand Марка авто
 * @property string $model Модель авто
 * @property string $model_year Рік випуску авто
 * @property int $millage пробіг авто
 * @property double $engine_capacity Об'єм двигуна
 * @property string $fuel_type Тип палива двигуна авто
 * @property int $horse_power Потужність авто (кінських сил)
 * @property string $body_type Тип кузова авто
 * @property string $transmission Тип коробки передач авто
 * @property string $drive Тип приводу авто
 * @property string $color Колір авто
 * @property string $vin_code VIN-код авто
 * @property string $plate Державний номерний знак авто
 * @property string $region Регіон
 */
class Vehicles extends Model
{
    public static $tableName = 'vehicles';

    public static function FindVehicleById($vehId)
    {
        $result = self::findByCondition(['id' => $vehId]);
        return !empty($result) ? (object)$result[0] : null;
    }

    public static function LogVehicle($veh)
    {
        Core::get()->session->remove('vehicle');
        Core::get()->session->set('vehicle', $veh);
    }

    public static function GetInfo($id)
    {
        return self::findByCondition(['id' => $id]);
    }

    public static function AddVehicle($condition, $brand, $model, $modelYear, $millage, $fuelType, $transmission, $drive, $color,
                                        $engineCapacity = null, $horsePower = null, $bodyType = null, $vinCode = null,
                                        $plate = null, $region = null)
    {
        $vehicle = new Vehicles();
        $vehicle->veh_condition = $condition;
        $vehicle->brand = $brand;
        $vehicle->model = $model;
        $vehicle->model_year = $modelYear;
        $vehicle->millage = $millage;
        $vehicle->fuel_type = $fuelType;
        $vehicle->transmission = $transmission;
        $vehicle->drive = $drive;
        $vehicle->color = $color;
        $vehicle->engine_capacity = $engineCapacity;
        $vehicle->horse_power = $horsePower;
        $vehicle->body_type = $bodyType;
        $vehicle->vin_code = $vinCode;
        $vehicle->plate = $plate;
        $vehicle->region = $region;
        $vehicle->save();
    }

    public static function lastInsertedId()
    {
        $result = self::findRowsByCondition("LAST_INSERT_ID() as last_id");
        if (!empty($result)) {
            return $result[0]['last_id'];
        }
        return null;
    }

    public static function EditVehicleInfo($vehId, $dataToUpdate)
    {
        $vehicle = Vehicles::selectRowById($vehId, 'Models\Vehicles');

        if ($vehicle) {
            foreach ($dataToUpdate as $field => $value) {
                if (isset($value) && !empty($value)) {
                    $vehicle->{$field} = $value;
                }
            }
            $vehicle->save();
            return true;
        } else {
            return false;
        }
    }

    public static function CountAll($where = null, $tableParams = '')
    {
        $result = self::findRowsByCondition("COUNT(*) as count", $where, $tableParams);
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }
}