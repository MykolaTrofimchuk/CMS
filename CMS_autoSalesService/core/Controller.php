<?php

namespace core;

class Controller
{
    protected $template;

    public function __construct()
    {
        $action = Core::get()->actionName;
        $module = Core::get()->moduleName;
        $path = "Views/{$module}/{$action}.php";
        $this->template = new Template($path);
    }

    public function render()
    {
        return [
            'Content' => $this->template->getHTML()
        ];
    }
}