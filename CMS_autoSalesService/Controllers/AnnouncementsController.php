<?php

namespace Controllers;

use core\Controller;
use core\Core;
use core\DB;
use core\Template;
use DateTime;
use Models\Announcements;
use Models\CarImages;
use Models\FilterModelBrands;
use Models\UserFavouritesAnnouncements;
use Models\Users;
use Models\Vehicles;

class AnnouncementsController extends Controller
{
    public $announcements;

    private function validate()
    {
        if (strlen($this->post->condition) === 0) {
            $this->addErrorMessage('Стан авто не вказано!');
        }
        if (mb_strlen($this->post->title) > 10) {
            $this->addErrorMessage('Заголовок вказано некоректно! Довжина має бути до 10 символів');
        }
        if (strlen($this->post->price) === 0 || $this->post->price > 100000000)
            $this->addErrorMessage('Ціну не вказано \ Вказано некоректно');
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
        if ($this->post->horsePower > 9999)
            $this->addErrorMessage('Перепрошуємо, такі потужні автомобілі неможливо виставити на продаж!');
    }

    public function actionAdd()
    {
        if ($this->isPost) {
            if (strlen($this->post->userId) === 0)
                $userId = \core\Core::get()->session->get('user')['id'];
            else
                $userId = $this->post->userId;
            $this->validate();
            $millage = $this->post->millage;
            if ($this->post->condition === 'Нове') {
                $millage = 0;
            }
            if ($millage > 9999999)
                $this->addErrorMessage('Ваш автомобіль має завеликий пробіг для продажу!');
            if (strlen($this->post->brand) === 0) {
                $this->addErrorMessage('Марку не вказано!');
                $model = "";
            } else {
                $model = $this->post->model;
            }
            $modelYear = $this->post->modelYear;
            $currentYear = intval(date('Y'));
            if ($modelYear < 1900 || $modelYear >= ($currentYear + 2) || $modelYear === null) {
                $this->addErrorMessage("Рік випуску вказано некоректно!");
            }
            if (strlen($millage) === 0)
                $this->addErrorMessage('Пробіг не вказано!');
            $region = "";
            if (strlen($this->post->regionObl) !== 0 && strlen($this->post->regionCity) !== 0) {
                $region = "{$this->post->regionObl} область, місто {$this->post->regionCity}";
            } elseif (strlen($this->post->regionObl) !== 0 && strlen($this->post->regionCity) === 0) {
                $this->addErrorMessage("Слід вказати конкретне місто!");
            } elseif (strlen($this->post->regionCity) !== 0) {
                $region = "місто {$this->post->regionCity}";
            }

            if (!$this->isErrorMessagesExists()) {
                Vehicles::AddVehicle(
                    $this->post->condition,
                    $this->post->brand,
                    $model,
                    $modelYear,
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
                $publicationDate = date('Y-m-d H:i:s');
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
                            $uploadFile = $uploadDir . $index . '.' . pathinfo($_FILES['carImages']['name'][$index], PATHINFO_EXTENSION);

                            if (exif_imagetype($tmpName) !== false) {
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
            $announcement = Announcements::SelectById($id);
            if (!$announcement)
                $this->redirect("/");
            $statusId = $announcement->status_id;

            if ($statusId !== 1)
                $this->redirect('/');

            $announcementId = $id;

            $announcement = Announcements::SelectById($announcementId);
            $vehicle = Announcements::SelectVehicleFromAnnouncement($announcementId);
            $announcementImages = CarImages::FindPathByAnnouncementId($announcementId);
            $countFavorites = UserFavouritesAnnouncements::CountByAnnouncementId($announcementId);

            if (!$announcement || $announcement->status_id !== 1) {
                return $this->render("Views/site/index.php");
            }

            $GLOBALS['announcement'] = $announcement;
            $GLOBALS['vehicle'] = $vehicle;
            $GLOBALS['images'] = $announcementImages;
            $GLOBALS['countFavorite'] = $countFavorites;

            return $this->render();
        }
        return $this->render();
    }

    public function actionView()
    {
        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $currentPage = end($queryParts);

        if ($currentPage === null || $currentPage === 'null' || strlen($currentPage) === 0) {
            $currentPage = 1;
        } else {
            $currentPage = (int)$currentPage;
        }
        if ($currentPage < 1) {
            $this->redirect("1");
        }

        if ($currentPage !== null || strlen($currentPage) !== 0) {
            if (!empty(\core\Core::get()->session->get('redirect_params'))) {
                $filter = \core\Core::get()->session->get('redirect_params');
                $filterArray = $filter['additionalData'];
                $filterAssocArray = [];
                foreach ($filterArray as $key => $value) {
                    if (strlen($value) !== 0) {
                        if ($key === 'plate') {
                            $key = 'plate IS NOT';
                            $value = null;
                        }
                        if ($key === 'vin_code') {
                            $key = 'vin_code IS NOT';
                            $value = null;
                        }
                        if (strpos($key, 'From') !== false) {
                            $newKey = str_replace('From', '', $key);
                            if ($key === 'model_yearFrom') {
                                $year = intval($value);
                                $filterAssocArray["$newKey >="] = "{$year}-01-01";
                            } else {
                                $filterAssocArray["$newKey >="] = $value;
                            }
                        } elseif (strpos($key, 'To') !== false) {
                            $newKey = str_replace('To', '', $key);
                            if ($key === 'model_yearTo') {
                                $year = intval($value);
                                $filterAssocArray["$newKey <="] = "{$year}-12-31";
                            } else {
                                $filterAssocArray["$newKey <="] = $value;
                            }
                        } elseif (strpos($key, 'Like') !== false) {
                            $newKey = str_replace('Like', '', $key);
                            $filterAssocArray["$newKey LIKE"] = "%$value%";
                        } else {
                            $filterAssocArray[$key] = $value;
                        }
                    }
                }

                $filterAssocArrayWithPrice = $filterAssocArray;

                if (!empty($filterAssocArray)) {
                    if (isset($filterAssocArray['price <='])) {
                        $filterAssocArrayWithPrice['price <='] = $filterAssocArray['price <='];
                        unset($filterAssocArray['price <=']);
                    }
                    if (isset($filterAssocArray['price >='])) {
                        $filterAssocArrayWithPrice['price >='] = $filterAssocArray['price >='];
                        unset($filterAssocArray['price >=']);
                    }
                }

                if (empty($filterAssocArray)) {
                    $filterAssocArray = null;
                }

                $announcementsPerPage = 6;
                $totalAnnouncements = Vehicles::CountAll($filterAssocArrayWithPrice, "INNER JOIN announcements a ON vehicles.id = a.vehicle_id"); // Pass filters to count
                $totalAnnouncementsCount = isset($totalAnnouncements) ? (int)$totalAnnouncements : 0;

                $totalPages = ceil($totalAnnouncementsCount / $announcementsPerPage);

                if ($totalAnnouncementsCount === 0) {
                    $GLOBALS['announcements'] = [];
                    $GLOBALS['currentPage'] = 1;
                    $GLOBALS['totalPages'] = 1;
                    return $this->render();
                }
                if ($currentPage > $totalPages) {
                    $this->redirect("$totalPages");
                }
                $offset = ($currentPage - 1) * $announcementsPerPage;
                $vehicles = Announcements::SelectPaginated($announcementsPerPage, $offset, $filterAssocArrayWithPrice);

                if (!empty($vehicles)) {
                    foreach ($vehicles as &$vehicle) {
                        $announcement = Announcements::findRowsByCondition('*', ['vehicle_id' => $vehicle['id']]);
                        $statusId = $announcement[0]['status_id'];
                        $announcement[0]['statusText'] = $this->mapStatusToText($statusId);
                        $announcement[0]['pathToImages'] = CarImages::FindPathByAnnouncementId($announcement[0]['id']);
                        $announcement[0]['countFavorite'] = UserFavouritesAnnouncements::CountByAnnouncementId($announcement[0]['id']);
                        $announcements [] = $announcement;
                    }
                }

                $GLOBALS['announcements'] = $announcements;
                $GLOBALS['currentPage'] = $currentPage;
                $GLOBALS['totalPages'] = $totalPages;
                return $this->render();
            }
            $announcementsPerPage = 6;
            $totalAnnouncements = Vehicles::CountAll(null, "INNER JOIN announcements a ON vehicles.id = a.vehicle_id");
            $totalAnnouncementsCount = isset($totalAnnouncements) ? (int)$totalAnnouncements : 0;

            $totalPages = ceil($totalAnnouncementsCount / $announcementsPerPage);

            if ($totalAnnouncementsCount === 0) {
                $GLOBALS['announcements'] = [];
                $GLOBALS['currentPage'] = 1;
                $GLOBALS['totalPages'] = 1;
                return $this->render();
            }
            if ($currentPage > $totalPages) {
                $this->redirect("$totalPages");
            }
            $offset = ($currentPage - 1) * $announcementsPerPage;
            $vehicles = Announcements::SelectPaginated($announcementsPerPage, $offset);

            if (!empty($vehicles)) {
                foreach ($vehicles as &$vehicle) {
                    $announcement = Announcements::findRowsByCondition('*', ['vehicle_id' => $vehicle['id']]);
                    $statusId = $announcement[0]['status_id'];
                    $announcement[0]['statusText'] = $this->mapStatusToText($statusId);
                    $announcement[0]['pathToImages'] = CarImages::FindPathByAnnouncementId($announcement[0]['id']);
                    $announcement[0]['countFavorite'] = UserFavouritesAnnouncements::CountByAnnouncementId($announcement[0]['id']);
                    $announcements [] = $announcement;
                }
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

        $userId = \core\Core::get()->session->get('user')['id'];

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $currentPage = end($queryParts);
        $currentPage = ($currentPage === null || $currentPage === 'null' || (int)$currentPage < 1) ? 1 : (int)$currentPage;

        $announcementsPerPage = 6;

        $totalAnnouncements = Vehicles::CountAll(['user_id' => $userId], "INNER JOIN announcements a ON vehicles.id = a.vehicle_id");
        $totalAnnouncementsCount = isset($totalAnnouncements) ? (int)$totalAnnouncements : 0;

        $totalPages = ceil($totalAnnouncementsCount / $announcementsPerPage);

        if ($currentPage > $totalPages) {
            $this->redirect("/announcements/my/$totalPages");
        }

        $offset = ($currentPage - 1) * $announcementsPerPage;
        $vehicles = Announcements::SelectPaginated($announcementsPerPage, $offset, ['user_id' => $userId]);

        if (!empty($vehicles)) {
            foreach ($vehicles as &$vehicle) {
                $announcement = Announcements::findRowsByCondition('*', ['vehicle_id' => $vehicle['id']]);
                $statusId = $announcement[0]['status_id'];
                $announcement[0]['statusText'] = $this->mapStatusToText($statusId);
                $announcement[0]['pathToImages'] = CarImages::FindPathByAnnouncementId($announcement[0]['id']);
                $announcement[0]['countFavorite'] = UserFavouritesAnnouncements::CountByAnnouncementId($announcement[0]['id']);
                $announcements [] = $announcement;
            }
        }

        $GLOBALS['announcementsMy'] = $announcements;
        $GLOBALS['currentPageMy'] = $currentPage;
        $GLOBALS['totalPagesMy'] = $totalPages;

        return $this->render();
    }

    public function actionAddtofavorites()
    {
        if (!\Models\Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcement = Announcements::SelectById($id);
            $statusId = $announcement->status_id;

            if ($statusId !== 1)
                $this->redirect('/');

            $announcementId = $id;
            $userId = \core\Core::get()->session->get('user')['id'];

            $existingFavorite = \Models\UserFavouritesAnnouncements::findByCondition(['user_id' => $userId, 'announcement_id' => $announcementId]);

            if ($existingFavorite) {
                $successMessage = "Це оголошення вже додане в обрані!";
            } else {
                \Models\UserFavouritesAnnouncements::AddRow($userId, $announcementId);
                $successMessage = "Оголошення успішно додане в обрані!";
            }
            $GLOBALS['successMessage'] = isset($successMessage) ? $successMessage : null;
            return $this->render();
        }
    }

    public function actionRemovefromfavorites()
    {
        if (!\Models\Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;

            $userId = \core\Core::get()->session->get('user')['id'];

            $existingFavorite = \Models\UserFavouritesAnnouncements::findByCondition(['user_id' => $userId, 'announcement_id' => $announcementId]);

            if ($existingFavorite) {
                \Models\UserFavouritesAnnouncements::RemoveRow($userId, $announcementId);
                $successMessage = "Оголошення успішно видалено з обраних!";
            } else {
                $successMessage = "Оголошення не знаходиться в обраних!";
            }
            $GLOBALS['successMessage'] = isset($successMessage) ? $successMessage : null;
            return $this->render();
        }
    }

    public function actionSelected()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $userId = \core\Core::get()->session->get('user')['id'];

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $currentPage = end($queryParts);

        if ($currentPage === null || $currentPage === 'null') {
            $currentPage = 1;
        } else {
            $currentPage = (int)$currentPage;
        }

        if ($currentPage < 1) {
            $this->redirect("/announcements/selected/1");
        }

        $announcementsPerPage = 6;

        $totalAnnouncements = UserFavouritesAnnouncements::CountAll();
        $totalAnnouncementsCount = isset($totalAnnouncements[0]['count']) ? (int)$totalAnnouncements[0]['count'] : 0;
        $totalPages = ceil($totalAnnouncementsCount / $announcementsPerPage);

        if ($currentPage > $totalPages) {
            $this->redirect("/announcements/selected/$totalPages");
        }

        $offset = ($currentPage - 1) * $announcementsPerPage;

        $announcements = UserFavouritesAnnouncements::SelectByUserIdPaginated($userId, $announcementsPerPage, $offset);

        $oneDayAgo = new DateTime();
        $oneDayAgo->modify('-1 day');
        $selectedAnnouncements = [];

        foreach ($announcements as &$announcement) {
            $announcementId = $announcement['announcement_id'];
            if ($announcementId !== null && $announcementId !== 0) {
                $announcementData = Announcements::SelectById($announcementId);

                // Перевірка чи існує запис в таблиці Announcements для даного announcement_id
                if ($announcementData !== null && $announcementId === $announcementData->id) {
                    $statusId = $announcementData->status_id ?? null;
                    $deactivationDate = isset($announcementData->deactivationDate) ? new DateTime($announcementData->deactivationDate) : null;

                    $oneDayAgo = new DateTime();
                    $oneDayAgo->modify('-1 day');

                    if (($statusId == 2 || $statusId == 3) && $deactivationDate !== null && $deactivationDate < $oneDayAgo) {
                        continue;
                    }

                    if ($announcementData->id === $announcementId)
                        $newAnnouncementData = (array)$announcementData;

                    $newAnnouncementData['statusText'] = $this->mapStatusToText($statusId);
                    $newAnnouncementData['pathToImages'] = CarImages::FindPathByAnnouncementId($announcementId);
                    $newAnnouncementData['countFavorite'] = UserFavouritesAnnouncements::CountByAnnouncementId($announcementId);

                    $selectedAnnouncements[] = $newAnnouncementData;
                }
            }
        }

        // Передаємо дані у представлення
        $GLOBALS['selectedAnnouncements'] = $selectedAnnouncements;
        $GLOBALS['selectedCurrentPage'] = $currentPage;
        $GLOBALS['selectedTotalPages'] = $totalPages;

        return $this->render();
    }

    public function actionSelectmodelsbybrand()
    {
        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $selectedBrand = end($queryParts);

        $models = FilterModelBrands::FindModelsByBrand($selectedBrand);

        header('Content-Type: application/json');
        echo json_encode($models);
    }

    public function actionEdit()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;
            $userId = \core\Core::get()->session->get('user')['id'];

            $announcementInfo = Announcements::findByCondition(['id' => $announcementId]);
            if (empty($announcementInfo) || ($userId !== $announcementInfo[0]['user_id'] && !Users::IsAdmin($userId))) {
                $this->redirect('/announcements/my');
            }
            $vehicleInfo = Announcements::SelectVehicleFromAnnouncement($announcementId);

            if ($this->isPost) {
                $this->validate();

                $millage = $this->post->millage;
                if ($this->post->condition === 'Нове') {
                    $millage = 0;
                }
                if ($millage > 9999999)
                    $this->addErrorMessage('Ваш автомобіль має завеликий пробіг для продажу!');
                if (strlen($this->post->brand) === 0) {
                    $this->addErrorMessage('Марку не вказано!');
                    $model = "";
                } else {
                    $model = $this->post->model;
                }
                $modelYear = $this->post->modelYear;
                $currentYear = intval(date('Y'));
                if ($modelYear < 1900 || $modelYear >= ($currentYear + 2) || $modelYear === null) {
                    $this->addErrorMessage("Рік випуску вказано НЕ КОРЕКТНО!");
                }
                if (strlen($millage) === 0)
                    $this->addErrorMessage('Пробіг не вказано!');
                $region = "";
                if (strlen($this->post->regionObl) !== 0 && strlen($this->post->regionCity) !== 0) {
                    $region = "{$this->post->regionObl} область, місто {$this->post->regionCity}";
                } elseif (strlen($this->post->regionObl) !== 0 && strlen($this->post->regionCity) === 0) {
                    $this->addErrorMessage("Слід вказати конкретне місто!");
                } elseif (strlen($this->post->regionCity) !== 0) {
                    $region = "місто {$this->post->regionCity}";
                }

                if (!$this->isErrorMessagesExists()) {
                    $vehicleDataToUpdate = [
                        'brand' => $this->post->brand,
                        'model' => $model,
                        'veh_condition' => $this->post->condition,
                        'model_year' => $modelYear,
                        'millage' => $millage,
                        'engine_capacity' => $this->post->engineCapacity,
                        'fuel_type' => $this->post->fuelType,
                        'horse_power' => $this->post->horsePower,
                        'body_type' => $this->post->bodyType,
                        'transmission' => $this->post->transmission,
                        'drive' => $this->post->drive,
                        'color' => $this->post->color,
                        'vin_code' => $this->post->vinCode,
                        'plate' => $this->post->plate,
                        'region' => $region
                    ];

                    $modelYear = substr($this->post->modelYear, 0, 4);
                    $title = $this->post->title . " " . $this->post->brand . " " . $this->post->model . " " . $modelYear;
                    $price = (int)$this->post->price;

                    $announcementDataToUpdate = [
                        'title' => $title,
                        'description' => $this->post->description,
                        'price' => $price
                    ];

                    $resUpdateVeh = Vehicles::EditVehicleInfo($vehicleInfo->id, $vehicleDataToUpdate);
                    $resUpdateAnn = Announcements::EditAnnouncementInfo($announcementId, $announcementDataToUpdate);

                    if (!empty($_FILES['carImages'])) {
                        $uploadDir = "src/database/announcements/announcement" . $announcementId . "/";
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $existingImages = scandir($uploadDir);
                        $existingImages = array_diff($existingImages, array('.', '..'));

                        foreach ($_FILES['carImages']['tmp_name'] as $index => $tmpName) {
                            if ($_FILES['carImages']['error'][$index] === UPLOAD_ERR_OK) {
                                $extension = strtolower(pathinfo($_FILES['carImages']['name'][$index], PATHINFO_EXTENSION));
                                $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

                                if (in_array($extension, $allowedExtensions)) {
                                    // Перевірка наявності файлу з таким ім'ям
                                    $i = 0;
                                    $newFileName = ($index + count($existingImages)) . '.' . $extension;
                                    while (in_array($newFileName, $existingImages)) {
                                        $i++;
                                        $newFileName = ($index + count($existingImages) + $i) . '.' . $extension;
                                    }

                                    $uploadFile = $uploadDir . $newFileName;

                                    if (exif_imagetype($tmpName) !== false) {
                                        move_uploaded_file($tmpName, $uploadFile);
                                    } else {
                                        $this->addErrorMessage('Файл ' . $_FILES['carImages']['name'][$index] . ' не є зображенням.');
                                    }
                                } else {
                                    $this->addErrorMessage('Файл ' . $_FILES['carImages']['name'][$index] . ' має неприпустиме розширення.');
                                }
                            } else {
                                $this->addErrorMessage('Не вдалося завантажити файл: ' . $_FILES['carImages']['name'][$index]);
                            }
                        }
                        if (is_null(CarImages::FindPathByAnnouncementId($announcementId)))
                            CarImages::AddVehicleImages($announcementId, $uploadDir);
                    }

                    if (strlen($this->post->deletedImages) !== 0) {
                        $deletedImagesArray = is_array($this->post->deletedImages) ? $this->post->deletedImages : explode(', ', $this->post->deletedImages);

                        foreach ($deletedImagesArray as $deletedImage) {
                            $imagePath = "src/database/announcements/announcement" . $announcementId . "/" . $deletedImage;
                            if (file_exists($imagePath)) {
                                unlink($imagePath);
                            }
                        }
                    }

                    if ($resUpdateVeh && $resUpdateAnn)
                        $this->redirect("/announcements/index/{$announcementId}");
                }
            }

            $newAnnouncementData = (array)$announcementInfo;
            $newAnnouncementData[0]['statusText'] = $this->mapStatusToText($announcementInfo[0]['status_id']);
            $newAnnouncementData[0]['pathToImages'] = CarImages::FindPathByAnnouncementId($announcementId);

            $GLOBALS['vehicleInfo'] = (array)$vehicleInfo ?? null;
            $GLOBALS['announcementInfo'] = $newAnnouncementData ?? null;
            $GLOBALS['userOwnerInfo'] = Users::GetUserInfo($userId) ?? null;

            return $this->render();
        }
    }

    private function changeStatus($announcementId, $statusId)
    {
        $userId = \core\Core::get()->session->get('user')['id'];
        $announcementInfo = Announcements::findByCondition(['id' => $announcementId]);

        if (empty($announcementInfo)) {
            return false;
        }

        if ($statusId === 1 && $announcementInfo[0]['status_id'] === 1) {
            return false;
        }

        if ($statusId !== 1 && $announcementInfo[0]['status_id'] !== 1) {
            return false;
        }

        if ($userId !== $announcementInfo[0]['user_id'] && !Users::IsAdmin($userId)) {
            return false;
        }

        $deactivationDate = date('Y-m-d H:i:s');

        $announcementDataToUpdate = [
            'status_id' => $statusId,
            'deactivationDate' => $statusId === 1 ? null : $deactivationDate
        ];

        return Announcements::EditAnnouncementInfo($announcementId, $announcementDataToUpdate);
    }

    public function actionSold()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;
            $statusId = 2; // Set status for sold announcement
            $resUpdateAnn = $this->changeStatus($announcementId, $statusId);

            if ($resUpdateAnn) {
                $this->redirect('/announcements/my/1');
            }
        }
        $this->redirect('/');
    }

    public function actionDelete()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;
            $statusId = 3; // Set status for deleted announcement
            $resUpdateAnn = $this->changeStatus($announcementId, $statusId);

            if ($resUpdateAnn) {
                $this->redirect('/announcements/my/1');
            }
        }
        $this->redirect('/');
    }

    public function actionRestore()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;
            $statusId = 1; // Set status for restored announcement
            $resUpdateAnn = $this->changeStatus($announcementId, $statusId);

            if ($resUpdateAnn) {
                $this->redirect('/');
            }
        }
        $this->redirect('/error/error');
    }
}