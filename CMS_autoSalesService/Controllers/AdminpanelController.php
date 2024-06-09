<?php

namespace Controllers;

use core\Controller;
use Models\Announcements;
use Models\CarImages;
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
        $this->redirect('/users/register');
    }
}