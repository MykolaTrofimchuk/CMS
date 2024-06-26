<?php

namespace Models;

use core\Core;
use core\Model;
use DateTime;

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

    public static function SelectPaginatedByCondition($where = null)
    {
        if (empty($where))
            $where = null;
        $rows = Core::get()->db->select('vehicles v INNER JOIN announcements a ON v.id = a.vehicle_id', 'v.*, a.*', $where );
        return $rows;
    }

    public static function CountAll($where = null)
    {
        $result = self::findRowsByCondition('COUNT(*) as count', $where);
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }

    public static function SelectById($announcementId)
    {
        $result = self::findByCondition(['id' => $announcementId]);
        return !empty($result) ? (object)$result[0] : null;
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

    public static function AddAnnouncement($title, $price, $publicationDate, $userId, $statusId, $vehicleId,
                                           $description = null, $deactivationDate = null)
    {
        $announcement = new Announcements();
        $announcement->title = $title;
        $announcement->description = $description;
        $announcement->price = $price;
        $announcement->publicationDate = $publicationDate;
        $announcement->deactivationDate = $deactivationDate;
        $announcement->user_id = $userId;
        $announcement->status_id = $statusId;
        $announcement->vehicle_id = $vehicleId;
        $announcement->save();
    }

    public static function lastInsertedId()
    {
        $result = self::findRowsByCondition("LAST_INSERT_ID() as last_id");
        if (!empty($result)) {
            return $result[0]['last_id'];
        }
        return null;
    }

    public static function EditAnnouncementInfo($announcementId, $dataToUpdate)
    {
        $announcement = Announcements::selectRowById($announcementId, 'Models\Announcements');

        if ($announcement) {
            foreach ($dataToUpdate as $field => $value) {
                if (isset($value) && !empty($value)) {
                    $announcement->{$field} = $value;
                }
            }
            $announcement->save();
            return true;
        } else {
            return false;
        }
    }

    public static function getPaginatedAnnouncements($limit, $offset, $where = null)
    {
        if (empty($where))
            $where = null;
        $rows = Core::get()->db->select('announcements a INNER JOIN vehicles v ON a.vehicle_id = v.id', 'a.*', $where, $limit, $offset, );
        return $rows;
    }

    public static function countAllAnnouncements()
    {
        return self::CountAll();
    }

    public static function DeleteRow($where){
        if (empty($where))
            $where = null;
        return Core::get()->db->delete(self::$tableName, $where);
    }
}