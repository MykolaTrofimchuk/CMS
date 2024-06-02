<?php

namespace Models;

use core\Core;
use core\Model;

/**
 * @property int $id ID оголошення
 * @property string $title Заголовок оголошення
 * @property string $description Опис оголошення
 * @property double $price Ціна авто в оголошенні
 * @property string $publicationDate Дата публікації оголошення
 * @property string $deactivationDate Дата деактивації оголошення
 * @property int $user_id ID власника оголошення
 * @property int $status_id ID сатуту оголошення
 * @property int $vehicle_id ID авто оголошення
*/
class Announcements extends Model
{
    public static $tableName = 'announcements';

    public static function SelectAll()
    {
        return $rows = self::findAll();
    }

    public static function SelectById($announcementId)
    {
        $result = self::findByCondition(['id' => $announcementId]);
        return !empty($result) ? (object) $result[0] : null;
    }

    public static function SelectVehicleFromAnnouncement($announcementId)
    {
        $announcement = self::findById($announcementId);
        if ($announcement) {
            $vehicleId = $announcement->vehicle_id;
            if ($vehicleId) {
                $vehicle = Vehicles::FindVehicleById($vehicleId);

                return $vehicle;
            }
        }
        return null;
    }

//    public static function LogAnnouncement($announcement)
//    {
//        Core::get()->session->remove('announcement');
//        Core::get()->session->set('announcement', $announcement);
//    }

    public static function GetInfo($id)
    {
        return self::findByCondition(['id' => $id]);
    }
}