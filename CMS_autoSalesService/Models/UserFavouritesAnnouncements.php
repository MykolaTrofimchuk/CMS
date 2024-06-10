<?php

namespace Models;

use core\Core;
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

    public static function CountAll($where = null)
    {
        $result = self::findRowsByCondition('COUNT(*) as count', $where);
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }

    public static function CountByAnnouncementId($announcementId)
    {
        return self::findRowsByCondition('COUNT(*) as count', ['announcement_id' => $announcementId]);
    }

    public static function DeleteRow($where){
        if (empty($where))
            $where = null;
        return Core::get()->db->delete(self::$tableName, $where);
    }

    public static function getPaginatedRows($limit, $offset, $where = null)
    {
        if (empty($where))
            $where = null;
        $rows = Core::get()->db->select(self::$tableName, '*', $where, $limit, $offset, );
        return $rows;
    }
}