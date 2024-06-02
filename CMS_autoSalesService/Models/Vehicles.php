<?php

namespace Models;

use core\Core;
use core\Model;

class Vehicles extends Model
{
    public static $tableName = 'vehicles';

    public static function FindVehicleById($vehId)
    {
        return self::findById($vehId);
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
}