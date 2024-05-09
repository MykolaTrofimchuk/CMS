<?php

namespace core;

class Template
{
    protected $tempFilePath;
    protected $paramsArray;
    public function __construct($tempPath)
    {
        $this->tempFilePath = $tempPath;
        $this->paramsArray = [];
    }

    public function setParam($paramName, $paramValue)
    {
        $this->paramsArray[$paramName] = $paramValue;
    }

    public function setParams($params)
    {
        foreach ($params as $key => $value) {
            $this->setParam($key, $value);
        }
    }

    public function getHTML()
    {
        ob_start();
        extract($this->paramsArray);
        include($this->tempFilePath);
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    public function display()
    {
        echo $this->getHTML();
    }
}