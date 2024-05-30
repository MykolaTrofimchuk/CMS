<?php

namespace core;

class Model
{
    protected $fieldsArray;
    protected $primaryKey = 'id';

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
        return $this->fieldsArray[$name];
    }

    public function save()
    {
        $value = $this->{$this->primaryKey};
        if (empty($value))
            // insert
        {
            Core::get()->db->insert($this->table, $this->fieldsArray);
        } else
            // update
        {
            Core::get()->db->update($this->table, $this->fieldsArray,
                [
                    $this->primaryKey => $value
                ]);
        }
    }
}