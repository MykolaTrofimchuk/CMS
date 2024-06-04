<?php

namespace Controllers;

use core\Controller;
use core\Core;
use core\DB;
use core\Template;
use Models\Announcements;
use Models\CarImages;
use Models\Users;
use Models\Vehicles;

class AnnouncementsController extends Controller
{
    public $announcements;

    public function actionAdd()
    {
        if ($this->isPost) {
            $userId = \core\Core::get()->session->get('user')['id'];

            if (strlen($this->post->title) === 0)
                $this->addErrorMessage('Заголовок не вказаний!');
            if (strlen($this->post->price) === 0)
                $this->addErrorMessage('Ціну не вказано!');
            if (strlen($this->post->brand) === 0) {
                $this->addErrorMessage('Марку не вказано!');
                $model = "";
            } else {
                $model = $this->post->model;
            }
            if (strlen($this->post->modelYear) === 0)
                $this->addErrorMessage('Рік випуску не вказано!');
            if (strlen($this->post->millage) === 0)
                $this->addErrorMessage('Пробіг не вказано!');
            if (strlen($this->post->bodyType) === 0)
                $this->addErrorMessage('Тип кузова не вказано!');
            if (strlen($this->post->transmission) === 0)
                $this->addErrorMessage('Тип коробки передач не вказано!');
            if (strlen($this->post->fuelType) === 0)
                $this->addErrorMessage('Тип палива двигуна не вказано!');
            if (strlen($this->post->engineCapacity) === 0)
                $this->addErrorMessage('Об\'єм двигуна не вказано!');
            if (strlen($this->post->color) === 0)
                $this->addErrorMessage('Колір кузова не вказано!');
            $region = "";
            if (strlen($this->post->regionObl) !== 0 || strlen($this->post->regionCity) !== 0) {
                $region = "{$this->post->regionObl} область, місто {$this->post->regionCity}";
            }

            if (!$this->isErrorMessagesExists()) {
                Vehicles::AddVehicle(
                    $this->post->brand,
                    $model,
                    $this->post->modelYear,
                    $this->post->millage,
                    $this->post->fuelType,
                    $this->post->transmission,
                    $this->post->drive,
                    $this->post->color,
                    $this->post->engineCapacity,
                    $this->post->horsePower,
                    $this->post->bodyType,
                    $this->post->vinCode,
                    $this->post->plate,
                    $region
                );

                $vehicleId = Vehicles::lastInsertedId();

                $price = (int)$this->post->price;
                $publicationDate = date('Y-m-d'); // Assuming publication date is today
                $statusId = 1;

                Announcements::AddAnnouncement(
                    $this->post->title,
                    $price,
                    $publicationDate,
                    $userId,
                    $statusId,
                    $vehicleId,
                    $this->post->description
                );

                $announcementId = Announcements::lastInsertedId();

                // Обробка завантажених фотографій
                if (isset($_FILES['carImages'])) {
                    $uploadDir = "src/database/announcements/announcement" . $announcementId . "/";
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    foreach ($_FILES['carImages']['tmp_name'] as $index => $tmpName) {
                        if ($_FILES['carImages']['error'][$index] === UPLOAD_ERR_OK) {
                            $uploadFile = $uploadDir . basename($_FILES['carImages']['name'][$index]);
                            move_uploaded_file($tmpName, $uploadFile);
                        } else {
                            $this->addErrorMessage('Не вдалося завантажити файл: ' . $_FILES['carImages']['name'][$index]);
                        }
                    }

                    CarImages::AddVehicleImages($announcementId, $uploadDir);
                }

                // Переадресація на сторінку успіху або виконання інших необхідних дій
                $this->redirect('/announcements/addsuccess');
            }
        } else {
            if (!Users::IsUserLogged()) {
                $this->redirect('/');
            }
            return $this->render();
        }

        return $this->render();
    }

    public function actionAddsuccess()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }
        return $this->render();
    }

    public function actionIndex()
    {
        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;

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

    public function actionView()
    {
        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $currentPage = end($queryParts);

        if ($currentPage === null || $currentPage === 'null') {
            $currentPage = 1;
        } else {
            $currentPage = (int)$currentPage;
        }
        if ($currentPage < 1) {
            $this->redirect("1");
        }

        if ($currentPage !== null) {
            $announcementsPerPage = 6;
            $totalAnnouncements = Announcements::CountAll(); // Get the total number of announcements
            $totalAnnouncementsCount = isset($totalAnnouncements[0]['count']) ? (int)$totalAnnouncements[0]['count'] : 0;

            $totalPages = ceil($totalAnnouncementsCount / $announcementsPerPage);
            if ($currentPage > $totalPages) {
                $this->redirect("$totalPages");
            }
            $offset = ($currentPage - 1) * $announcementsPerPage;
            $announcements = Announcements::SelectPaginated($announcementsPerPage, $offset);

            foreach ($announcements as &$announcement) {
                $statusId = $announcement['status_id'];
                $announcement['statusText'] = $this->mapStatusToText($statusId);
                $announcement['pathToImages'] = CarImages::FindPathByAnnouncementId($announcement['id']);
            }

            $GLOBALS['announcements'] = $announcements;
            $GLOBALS['currentPage'] = $currentPage;
            $GLOBALS['totalPages'] = $totalPages;
            return $this->render();
        }
        return $this->render('Views/announcements/view.php');
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