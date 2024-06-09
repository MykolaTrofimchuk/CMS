<?php

namespace Controllers;

use core\Controller;
use Models\Announcements;
use Models\CarImages;
use Models\FilterModelBrands;
use Models\UserFavouritesAnnouncements;
use Models\Users;
use Models\Vehicles;

class AdminpanelController extends Controller
{
    public function actionIndex()
    {
        if (!Users::IsUserLogged())
            $this->redirect('/');

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');
        return $this->render();
    }

    public function actionUsers()
    {
        if (!Users::IsUserLogged())
            $this->redirect('/');

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $currentPage = end($queryParts);

        if ($currentPage === null || $currentPage === 'null') {
            $currentPage = 1;
        } else {
            $currentPage = (int)$currentPage;
        }

        if ($currentPage < 1) {
            $this->redirect("/adminpanel/users/1");
        }

        $usersPerPage = 8;

        $totalUsers = Users::countAllUsers();
        $totalUsersCount = isset($totalUsers) ? (int)$totalUsers : 0;
        $totalPages = ceil($totalUsersCount / $usersPerPage);

        if ($currentPage > $totalPages) {
            $this->redirect("/adminpanel/users/$totalPages");
        }

        $offset = ($currentPage - 1) * $usersPerPage;

        $users = Users::getPaginatedUsers($usersPerPage, $offset);

        $GLOBALS['admPanelUsers'] = $users;
        $GLOBALS['currentPage'] = $currentPage;
        $GLOBALS['totalPages'] = $totalPages;

        return $this->render();
    }

    public function actionAnnouncements()
    {
        if (!Users::IsUserLogged())
            $this->redirect('/');

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $currentPage = end($queryParts);

        if ($currentPage === null || $currentPage === 'null') {
            $currentPage = 1;
        } else {
            $currentPage = (int)$currentPage;
        }

        if ($currentPage < 1) {
            $this->redirect("/adminpanel/announcements/1");
        }

        $announcementsPerPage = 8;

        $totalAnnouncements = Announcements::countAllAnnouncements();
        $totalAnnouncementsCount = isset($totalAnnouncements) ? (int)$totalAnnouncements : 0;
        $totalPages = ceil($totalAnnouncementsCount / $announcementsPerPage);

        if ($currentPage > $totalPages) {
            $this->redirect("/adminpanel/announcements/$totalPages");
        }

        $offset = ($currentPage - 1) * $announcementsPerPage;

        $announcements = Announcements::getPaginatedAnnouncements($announcementsPerPage, $offset);
        $totalAnnouncements = Announcements::countAllAnnouncements();

        $totalPages = ceil($totalAnnouncements / $announcementsPerPage);

        $GLOBALS['admPanelAnnouncements'] = $announcements;
        $GLOBALS['currentPage'] = $currentPage;
        $GLOBALS['totalPages'] = $totalPages;

        return $this->render();
    }

    public function actionAnnouncementedit()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;
            $this->redirect("/announcements/edit/{$announcementId}");
        }
    }

    public function actionAnnouncementdelete()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $announcementId = $id;
            $deletedRows = Announcements::DeleteRow(['id' => $announcementId]);
            $deleteImgPath = CarImages::DeleteRow(['announcement_id' => $announcementId]);
            $deleteFavorite = UserFavouritesAnnouncements::DeleteRow(['announcement_id' => $announcementId]);
            if ($deletedRows === 0 && $deleteImgPath === 0 && $deleteFavorite === 0)
                $this->redirect('/adminpanel/index');
            $this->redirect('/adminpanel/announcements');
        }
    }

    public function actionUseredit()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $userToUpdateId = $id;
            $this->redirect("/users/edit/{$userToUpdateId}");
        }
    }

    public function actionUserdelete()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $userToDelete = $id;
            $deletedRows = Users::DeleteRow(['id' => $userToDelete]);
            if ($deletedRows === 0)
                $this->redirect('/adminpanel/index');
            $this->redirect('/adminpanel/users');
        }
    }

    public function actionAnnouncementadd()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $this->redirect('/announcements/add');
    }

    public function actionUseradd()
    {
        if (!Users::IsUserLogged())
            $this->redirect('/');

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $this->redirect('/users/register');
    }

    public function actionCarbrands()
    {
        if (!Users::IsUserLogged())
            $this->redirect('/');

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $currentPage = end($queryParts);

        if ($currentPage === null || $currentPage === 'null') {
            $currentPage = 1;
        } else {
            $currentPage = (int)$currentPage;
        }

        if ($currentPage < 1) {
            $this->redirect("/adminpanel/carbrands/1");
        }

        $rowsPerPage = 10;

        $totalUsers = FilterModelBrands::countAllRows();
        $totalUsersCount = isset($totalUsers) ? (int)$totalUsers : 0;
        $totalPages = ceil($totalUsersCount / $rowsPerPage);

        if ($currentPage > $totalPages) {
            $this->redirect("/adminpanel/carbrands/$totalPages");
        }

        $offset = ($currentPage - 1) * $rowsPerPage;

        $users = FilterModelBrands::getPaginatedRows($rowsPerPage, $offset);

        $GLOBALS['admPanelCarBrands'] = $users;
        $GLOBALS['currentPage'] = $currentPage;
        $GLOBALS['totalPages'] = $totalPages;

        return $this->render();
    }

    public function actionCarbrand()
    {
        $this->clearErrorMessage();
        if (!Users::IsUserLogged())
            $this->redirect('/');

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $currentAction = end($queryParts);

        if ($currentAction === 'add') {
            if ($this->isPost) {
                if (strlen($this->post->brand) === 0)
                    $this->addErrorMessage('Обов\'язково вкажіть марку авто!');
                if (strlen($this->post->model) === 0)
                    $this->addErrorMessage('Обов\'язково вкажіть модель авто!');

                if (!$this->isErrorMessagesExists()) {
                    FilterModelBrands::AddRow(
                        $this->post->brand,
                        $this->post->model
                    );

                    $this->redirect('/adminpanel/carbrands/1');
                }
            }
            return $this->render();
        } elseif (is_numeric($currentAction) && (int)$currentAction == $currentAction) {
            $rowToUpdate = $currentAction;
            $GLOBALS['brandInfo'] = FilterModelBrands::findById($rowToUpdate);
            if ($this->isPost) {
                if (strlen($this->post->brand) === 0)
                    $this->addErrorMessage('Обов\'язково вкажіть марку авто!');
                if (strlen($this->post->model) === 0)
                    $this->addErrorMessage('Обов\'язково вкажіть модель авто!');

                if (!$this->isErrorMessagesExists()) {
                    FilterModelBrands::EditRowInfo(
                        $rowToUpdate,
                        [
                            'brand' => $this->post->brand,
                            'model' => $this->post->model
                        ]
                    );

                    $this->redirect('/adminpanel/carbrands/1');
                }
            }
            return $this->render();
        } else
            $this->redirect('/adminpanel/index');
        return $this->render();
    }

    public function actionCarbrandsdelete()
    {
        if (!Users::IsUserLogged()) {
            $this->redirect('/');
        }

        $userId = \core\Core::get()->session->get('user')['id'];
        if (!Users::IsAdmin($userId))
            $this->redirect('/');

        $routeParams = $this->get->route;
        $queryParts = explode('/', $routeParams);
        $id = end($queryParts);

        if ($id !== null) {
            $rowToDelete = $id;
            $deletedRows = FilterModelBrands::DeleteRow(['id' => $rowToDelete]);

            if ($deletedRows === 0)
                $this->redirect('/adminpanel/index');
            $this->redirect('/adminpanel/carbrands');
        }
    }
}