<?php

namespace Controllers;

use core\Controller;
use core\Core;
use http\Client\Curl\User;
use Models\Users;

class UsersController extends Controller
{
    public function actionLogin()
    {
        if($this->isPost){
            $user = Users::FindLoginAndPassword($this->post->login, $this->post->password);
            if(!empty($user)){
                Users::LoginUser($user);
                $this->redirect('/');
            }else
                $this->template->setParam('error_message', 'Нерпавильний логін та/або пароль!');
        }
        return $this->render();
    }

    public function actionLogout()
    {
        Users::LogoutUser();
        $this->redirect('/users/login');
    }
}