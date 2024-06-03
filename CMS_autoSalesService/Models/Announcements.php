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

    public static function SelectAll()
    {
        $rows = self::findAll();
        $validAnnouncements = [];

        $oneDayAgo = new DateTime();
        $oneDayAgo->modify('-1 day');

        foreach ($rows as $announcement) {
            $statusId = $announcement['status_id']; // Retrieve status ID
            $deactivationDate = isset($announcement['deactivationDate']) ? new DateTime($announcement['deactivationDate']) : null; // Parse deactivation date if not null

            // Check if the announcement should be excluded
            if (($statusId == 2 || $statusId == 3) && $deactivationDate !== null && $deactivationDate < $oneDayAgo) {
                continue; // Skip this announcement
            }

            $validAnnouncements[] = $announcement;
        }

        return $validAnnouncements;
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

}