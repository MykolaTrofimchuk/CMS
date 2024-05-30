<?php

namespace Controllers;

use core\Controller;
use core\Core;
use core\DB;
use core\Template;

class AnnouncementsController extends Controller
{
    public function actionAdd()
    {
        return $this->render();
    }

    public function actionIndex()
    {
        $db = Core::get()->db;
        $rows = $db->select("announcements");

        // $db->insert("announcements", ['title' => "Example Title", 'text' => "Some text", 'date' => '2024-05-30 15:12:00']);
        //$db->delete("announcements", ['id' => 3]);
        // $db->update("announcements", ['title' => "!!!!!!!!!"], ['id' => 4]);
        return $this->render();
    }

    public function actionView($params)
    {
        return $this->render();
    }
}