<?php

namespace Models;

use core\Model;

class AnnouncementStatuses extends Model
{
    public static $tableName = 'announcement_statuses';

    public static function FindAllStatuses()
    {
        return $rows = self::findAll();
    }

    public static function FindStatusById($statusId)
    {
        $result = self::findByCondition(['id' => $statusId]);
        return !empty($result) ? (object) $result[0] : null;
    }
}