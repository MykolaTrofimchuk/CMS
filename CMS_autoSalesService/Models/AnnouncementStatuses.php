<?php

namespace Models;

use core\Core;
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

    public static function CountAll($where = null)
    {
        $result = self::findRowsByCondition('COUNT(*) as count', $where);
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }

    public static function getPaginatedRows($limit, $offset, $where = null)
    {
        if (empty($where))
            $where = null;
        $rows = Core::get()->db->select(self::$tableName, '*', $where, $limit, $offset, );
        return $rows;
    }

    public static function AddRow($status)
    {
        $row = new AnnouncementStatuses();
        $row->status = $status;
        $row->save();
    }

    public static function EditRowInfo($rowId, $dataToUpdate)
    {
        $row = AnnouncementStatuses::selectRowById($rowId, 'Models\AnnouncementStatuses');

        if ($row) {
            foreach ($dataToUpdate as $field => $value) {
                if (isset($value) && !empty($value)) {
                    $row->{$field} = $value;
                }
            }
            $row->save();
            return true;
        } else {
            return false;
        }
    }

    public static function DeleteRow($where){
        if (empty($where))
            $where = null;
        return Core::get()->db->delete(self::$tableName, $where);
    }
}