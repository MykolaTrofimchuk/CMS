<?php

namespace Controllers;

use core\Controller;
use core\Core;
use core\DB;
use core\Template;
use Models\Announcements;

class AnnouncementsController extends Controller
{
    public function actionAdd()
    {
        return $this->render();
    }

    public function actionIndex()
    {
        $db = Core::get()->db;

        $announcement = new Announcements();
        $announcement->id = 5;
        $announcement->title = '!! announcement 2344 !!';
        $announcement->text = '!! announcement !!';
        $announcement->date = '2024-05-30 16:11:00';
        $announcement->save();

        // $rows = $db->select("announcements");

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