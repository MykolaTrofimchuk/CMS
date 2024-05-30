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
        return $this->render('Views/announcements/view.php');
    }

    public function actionView($params)
    {
        return $this->render();
    }
}