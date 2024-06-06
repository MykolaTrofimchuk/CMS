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

    public static function SelectPaginated($limit, $offset, $where = null)
    {
        $rows = self::findByLimitAndOffset($limit, $offset);
        $validAnnouncements = [];

        $oneDayAgo = new DateTime();
        $oneDayAgo->modify('-1 day');

        $whereVehArray = [];
        if ($where !== null) {
            foreach ($where as $key => $value) {
                if (strlen($value) !== 0) {
                    $whereVehArray[$key] = $value;
                }
            }
        }
        if (empty($whereVehArray)) {
            $whereVehArray = null;
        }

        foreach ($rows as $announcement) {
            $statusId = $announcement['status_id'];
            $deactivationDate = isset($announcement['deactivationDate']) ? new DateTime($announcement['deactivationDate']) : null;

            if (($statusId == 2 || $statusId == 3) && $deactivationDate !== null && $deactivationDate < $oneDayAgo) {
                continue;
            }

            $vehicleData = Vehicles::selectRowById($announcement['vehicle_id'], 'Models\Vehicles');

            $filterVehicles = Vehicles::findRowsByCondition('*', $whereVehArray);
            $isValidVehicle = false;

            if (!empty($filterVehicles))
                foreach ($filterVehicles as $vehicle) {
                    if ($vehicle['id'] === $announcement['vehicle_id']) {
                        $isValidVehicle = true;
                        break;
                    }
                }

            if ($isValidVehicle) {
                $validAnnouncements[] = $announcement;
            }
        }

        return $validAnnouncements;
    }

    public static function CountAll()
    {
        return self::findRowsByCondition('COUNT(*) as count');
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

    public static function SelectStatusAnnouncementById($announcementId)
    {
        $announcement = self::findById($announcementId);
        if ($announcement) {
            $statusId = $announcement->status_id;
            if ($statusId) {
                $status = AnnouncementStatuses::FindStatusById($statusId);
                return $status;
            }
        }
        return null;
    }

    public static function GetInfo($id)
    {
        return self::findByCondition(['id' => $id]);
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

    public static function SelectByUserIdPaginated($userId, $limit, $offset)
    {
        $condition = ['user_id' => $userId];
        $rows = self::findByCondition($condition, $limit, $offset);
        $validAnnouncements = [];

        $oneDayAgo = new DateTime();
        $oneDayAgo->modify('-1 day');
        if (!empty($rows))
            foreach ($rows as $announcement) {
                $statusId = $announcement['status_id'];
                $deactivationDate = isset($announcement['deactivationDate']) ? new DateTime($announcement['deactivationDate']) : null;

                if (($statusId == 2 || $statusId == 3) && $deactivationDate !== null && $deactivationDate < $oneDayAgo) {
                    continue;
                }

                $validAnnouncements[] = $announcement;
            }

        return $validAnnouncements;
    }

    public static function EditAnnouncementInfo($announcementId, $dataToUpdate)
    {
        $announcement = Announcements::selectRowById($announcementId, 'Models\Announcements');

        if ($announcement) {
            foreach ($dataToUpdate as $field => $value) {
                if (isset($value) && !empty($value)) {
                    // Встановлюємо інші поля, якщо вони не порожні
                    $announcement->{$field} = $value;
                }
            }
            $announcement->save(); // Зберігаємо зміни в базі даних
            return true;
        } else {
            return false;
        }
    }
}