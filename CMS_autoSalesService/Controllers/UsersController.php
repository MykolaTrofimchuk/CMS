<?php

namespace Controllers;

use core\Controller;
use core\Core;
use http\Client\Curl\User;
use Models\Users;

class UsersController extends Controller
{
    public function actionRegister()
    {
        if ($this->isPost) {
            $user = Users::FindByLogin($this->post->login);

            if (strlen($this->post->login) === 0)
                $this->addErrorMessage('Логін не вказаний!');
            if (strlen($this->post->password) <= 8)
                $this->addErrorMessage('Пароль має містити мінімум 8 символів!');
            if (strlen($this->post->firstName) === 0)
                $this->addErrorMessage('Ім\'я не вказано!');
            if (strlen($this->post->lastName) === 0)
                $this->addErrorMessage('Прізвище не вказано!');
            if (!empty($user)) {
                $this->addErrorMessage('Користувач із таким логіном вже існує!');
            }
            if ($this->post->password != $this->post->password2) {
                $this->addErrorMessage('Паролі не збігаються!');
            }
            if (!$this->isErrorMessagesExists()) {
                Users::RegisterUser($this->post->login, $this->post->password, $this->post->firstName,
                    $this->post->lastName, $this->post->email);
                $this->redirect("/users/registersuccess");
            }
        }
        return $this->render();
    }

    public function actionRegistersuccess()
    {
        return $this->render();
    }

    public function actionLogin()
    {
        if (Users::IsUserLogged())
            $this->redirect('/');
        if ($this->isPost) {
            $user = Users::FindLoginAndPassword($this->post->login, $this->post->password);
            if (!empty($user)) {
                Users::LoginUser($user);
                $this->redirect('/');
            } else {
                $this->addErrorMessage('Неправильний логін та/або пароль!');
            }
        }
        return $this->render();
    }

    public function actionLogout()
    {
        Users::LogoutUser();
        $this->redirect('/users/login');
    }
}