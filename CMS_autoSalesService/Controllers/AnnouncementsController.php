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

            if (strlen($this->post->condition) === 0){
                $this->addErrorMessage('Стан авто не вказано!');
            }
            $millage = $this->post->millage;
            if ($this->post->condition === 'Нове'){
                $millage = 0;
            }
            if (mb_strlen($this->post->title) > 10) {
                $this->addErrorMessage('Заголовок вказано некоректно! Довжина має бути до 10 символів');
            }
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
            if (strlen($millage) === 0)
                $this->addErrorMessage('Пробіг не вказано!');
            if (strlen($this->post->bodyType) === 0)
                $this->addErrorMessage('Тип кузова не вказано!');
            if (strlen($this->post->transmission) === 0)
                $this->addErrorMessage('Тип коробки передач не вказано!');
            if (strlen($this->post->fuelType) === 0)
                $this->addErrorMessage('Тип палива двигуна не вказано!');
            if (!preg_match('/^\d{1}\.\d+$/', $this->post->engineCapacity)) {
                $this->addErrorMessage('Об\'єм двигуна повинен однозначним числом з однією цифрою після крапки!');
            }
            if (strlen($this->post->color) === 0)
                $this->addErrorMessage('Колір кузова не вказано!');
            $region = "";
            if (strlen($this->post->regionObl) !== 0 && strlen($this->post->regionCity) !== 0) {
                $region = "{$this->post->regionObl} область, місто {$this->post->regionCity}";
            } elseif (strlen($this->post->regionObl) !== 0) {
                $region = "{$this->post->regionObl} область";
            } elseif (strlen($this->post->regionCity) !== 0) {
                $region = "місто {$this->post->regionCity}";
            }

            if (!$this->isErrorMessagesExists()) {
                Vehicles::AddVehicle(
                    $this->post->condition,
                    $this->post->brand,
                    $model,
                    $this->post->modelYear,
                    $millage,
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

                $modelYear = substr($this->post->modelYear, 0, 4);
                $title = $this->post->title . " " . $this->post->brand . " " . $this->post->model . " " . $modelYear;
                $price = (int)$this->post->price;

                date_default_timezone_set('Europe/Kiev');
                $publicationDate = date('Y-m-d H:i:s'); // Assuming publication date is today
                $statusId = 1;

                Announcements::AddAnnouncement(
                    $title,
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

                            // Перевірка, чи файл є зображенням
                            $check = getimagesize($tmpName);
                            if ($check !== false) {
                                move_uploaded_file($tmpName, $uploadFile);
                            } else {
                                $this->addErrorMessage('Файл ' . $_FILES['carImages']['name'][$index] . ' не є зображенням.');
                            }
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
            $announcementImages = CarImages::FindPathByAnnouncementId($announcementId);

            if (!$announcement || $announcement->status_id !== 1) {
                return $this->render("Views/site/index.php");
            }

            $GLOBALS['announcement'] = $announcement;
            $GLOBALS['vehicle'] = $vehicle;
            $GLOBALS['images'] = $announcementImages;

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

    public function actionMy()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $currentPage = end($queryParts);

        $userId = \core\Core::get()->session->get('user')['id'];

        if ($currentPage === null || $currentPage === 'null') {
            $currentPage = 1;
        } else {
            $currentPage = (int)$currentPage;
        }
        if ($currentPage < 1) {
            $this->redirect("/announcements/my/1");
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
            $announcements = Announcements::SelectByUserIdPaginated($userId, $announcementsPerPage, $offset);

            foreach ($announcements as &$announcement) {
                $statusId = $announcement['status_id'];
                $announcement['statusText'] = $this->mapStatusToText($statusId);
                $announcement['pathToImages'] = CarImages::FindPathByAnnouncementId($announcement['id']);
            }

            $GLOBALS['announcementsMy'] = $announcements;
            $GLOBALS['currentPageMy'] = $currentPage;
            $GLOBALS['totalPagesMy'] = $totalPages;
            return $this->render();
        }
        return $this->render('Views/announcements/my.php');
    }
}