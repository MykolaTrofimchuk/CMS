<?php

namespace core;

use Models\Vehicles;

class Model
{
    protected $fieldsArray;
    protected static $primaryKey = 'id';
    protected static $tableName = '';

    public function __construct()
    {
        $this->fieldsArray = [];
    }

    public function __set($name, $value)
    {
        $this->fieldsArray[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->fieldsArray)) {
            return $this->fieldsArray[$name];
        }
        return null;
        throw new \Exception("Undefined property: $name");
    }

    public function save()
    {
//        $isInsert = false;
//        if (!isset($this->{static::$primaryKey}))
//            $isInsert = true;
//        else {
//            $value = $this->{static::$primaryKey};
//            if (empty($value))
//                $isInsert = true;
//        }
        $value = $this->{static::$primaryKey};
        if (empty($value)) // insert
        {
            Core::get()->db->insert(static::$tableName, $this->fieldsArray);
        } else // update
        {
            Core::get()->db->update(static::$tableName, $this->fieldsArray,
                [
                    static::$primaryKey => $value
                ]);
        }
    }

    public static function deleteById($id)
    {
        Core::get()->db->delete(static::$tableName, [static::$primaryKey => $id]);
    }

    public static function deleteByCondition($conditionAssocArr)
    {
        Core::get()->db->delete(static::$tableName, $conditionAssocArr);
    }

    public static function findById($id)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', [static::$primaryKey => $id]);
        if (count($arr) > 0) {
            $user = new \Models\Users();
            $user->fieldsArray = $arr[0];
            return $user;
        } else {
            return null;
        }
    }

    public static function selectRowById($id, $className)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', [static::$primaryKey => $id]);
        if (count($arr) > 0) {
            $obj = new $className();
            foreach ($arr[0] as $key => $value) {
                $obj->{$key} = $value;
            }
            return $obj;
        } else {
            return null;
        }
    }

    public static function findByCondition($conditionAssocArr, $limit = null, $offset = 0)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', $conditionAssocArr, $limit, $offset);
        if (count($arr) > 0)
            return $arr;
        else
            return null;
    }

    public static function findRowsByCondition($rows, $conditionAssocArr = null, $tableParam = '')
    {
        if (empty($conditionAssocArr))
            $conditionAssocArr = null;
        $arr = Core::get()->db->select(static::$tableName . " ". $tableParam, $rows, $conditionAssocArr);
        if (count($arr) > 0)
            return $arr;
        else
            return null;
    }

    public static function findByLimitAndOffset($limit, $offset, $where = null)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', $where, $limit, $offset);
        if (count($arr) > 0)
            return $arr;
        else
            return null;
    }

    public static function findAll()
    {
        $arr = Core::get()->db->select(static::$tableName);;
        if (count($arr) > 0)
            return $arr;
        else
            return null;
    }

//    public static function count()
//    {
//        $result = Core::get()->db->select('announcements', 'COUNT(*)')[0]['COUNT(*)'];
//        if ($result === false) {
//            // Handle query error
//            echo "Error executing query";
//            return false;
//        }
//        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
//    }
}