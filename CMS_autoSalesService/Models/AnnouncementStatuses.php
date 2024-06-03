<?php

namespace Models;

use core\Model;

/**
 * @property int $id ID статусу
 * @property string $status Статус
 */

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