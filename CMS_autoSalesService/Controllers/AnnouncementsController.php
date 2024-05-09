<?php

namespace Controllers;

use core\Template;

class AnnouncementsController
{
    public function actionAdd()
    {
        $template = new Template('views/announcements/add.php');
        return [
            'Content' => $template->getHTML(),
            'Title' => 'Додавання оголошення'
        ];
    }

    public function actionIndex()
    {
        $template = new Template('views/announcements/index.php');
        return [
            'Content' => $template->getHTML(),
            'Title' => 'Список оголошень'
        ];
    }

    public function actionView($params)
    {
        return [
            'Content' => 'Announcement View',
            'Title' => 'Перегляд оголошень'
        ];
    }
}