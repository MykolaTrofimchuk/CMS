<?php

namespace Controllers;

use core\Controller;
use Models\Users;

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
}