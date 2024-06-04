<?php

namespace Models;

use core\Model;
use DateTime;

/**
 * @property int $id ID запису
 * @property int $user_id ID користувача
 * @property int $announcement_id ID оголошення
 */

class UserFavouritesAnnouncements extends Model
{
    public static $tableName = 'user_favorite_announcements';

    public static function AddRow($user_id, $announcement_id)
    {
        $announcement = new UserFavouritesAnnouncements();
        $announcement->user_id = $user_id;
        $announcement->announcement_id = $announcement_id;
        $announcement->save();
    }

    public static function IsFavorite($userId, $announcementId)
    {
        $favorite = self::findByCondition(['user_id' => $userId, 'announcement_id' => $announcementId]);

        if ($favorite)
            return true;
        else
            return false;
    }

    public static function RemoveRow($user_id, $announcement_id)
    {
        self::deleteByCondition(["user_id" => $user_id, "announcement_id" => $announcement_id]);
    }

    public static function getSelectedAnnouncements($userId)
    {
        // Отримуємо обрані оголошення для певного користувача
        return self::findByCondition(['user_id' => $userId]);
    }

    public static function SelectByUserIdPaginated($userId, $limit, $offset)
    {
        // Вибираємо оголошення, які належать користувачеві з обмеженням та зміщенням
        $condition = ['user_id' => $userId];
        $rows = self::findByCondition($condition, $limit, $offset);
        $validAnnouncements = [];

        if (!empty($rows)) {
            return $rows;
        }

        return $validAnnouncements;
    }

    public static function CountAll()
    {
        return self::findRowsByCondition('COUNT(*) as count');
    }
}