<?php

namespace core;

class Core
{
    public $moduleName;
    public $actionName;
    public $router;
    public $template;
    public $defaultLayoutPath = 'Views/layouts/index.php';
    private static $instance;

    private function __construct()
    {

        $this->template = new Template($this->defaultLayoutPath);
    }

    public function run($route)
    {
        $this->router = new \core\Router($route);
        $params = $this->router->run();
        $this->template->setParams($params);
    }

    public function finish()
    {
        $this->template->display();
        $this->router->finish();
    }

    public static function get()
    {
        if(empty(self::$instance))
            self::$instance = new Core();

        return self::$instance;
    }
}