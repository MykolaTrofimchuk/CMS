<?php

namespace Controllers;

use core\Controller;
use core\Core;
use core\DB;
use core\Template;
use Models\Announcements;
use Models\Vehicles;

class AnnouncementsController extends Controller
{
    public $announcements;

    public function actionAdd()
    {
        return $this->render();
    }

    public function actionIndex()
    {
        // Отримати значення параметра id зі шляху
        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        // Перевіряємо, чи є в запиті параметр 'id'
        if ($id !== null) {
            $announcementId = $id;

            // Отримати дані про оголошення за його ID
            $announcement = Announcements::SelectById($announcementId);
            $vehicle = Announcements::SelectVehicleFromAnnouncement($announcementId);

            if (!$announcement) {
                return $this->render("Views/site/index.php");
            }

            $GLOBALS['announcement'] = $announcement;
            $GLOBALS['vehicle'] = $vehicle;

            return $this->render();
        }
    }

    public function actionView($params)
    {
        $announcements = Announcements::SelectAll();

        foreach ($announcements as &$announcement) {
            $statusId = $announcement['status_id'];
            $announcement['statusText'] = $this->mapStatusToText($statusId);
        }

        $GLOBALS['announcements'] = $announcements;
        return $this->render();
    }

    private function mapStatusToText($statusId)
    {
        switch ($statusId) {
            case 1:
                return 'Активно';
            case 2:
                return 'Продано';
            case 3:
                return 'Видалено';
            default:
                return 'Невідомо';
        }
    }

}