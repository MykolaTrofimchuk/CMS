<?php

namespace Controllers;

use core\Controller;
use core\Template;

class SiteController extends Controller
{
    public function actionIndex()
    {
        \core\Core::get()->session->remove('redirect_params');
        // Перевіряємо, чи дані були надіслані методом POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validData = $this->validate($_POST);
            if (!empty($validData)) {
                // Якщо дані пройшли валідацію, перенаправляємо користувача
                $this->redirect('announcements/view/1', ['route' => 'announcements/view/1', 'additionalData' => $validData]);
            } else {
                // Якщо дані не пройшли валідацію, відображаємо сторінку з повідомленням про помилку
                return $this->render('Views/error/error.php');
            }
        }

        // Якщо дані не були надіслані, просто відображаємо сторінку
        return $this->render();
    }

    private function validate($data)
    {
        $validData = [];

        // Перевіряємо кожне значення у масиві $data
        foreach ($data as $key => $value) {
            // Якщо значення не порожнє, додаємо його до масиву $validData
            if (strlen($value) !== 0) {
                $validData[$key] = $value;
            } else
                $validData[$key] = '';
        }

        return $validData; // Повертаємо масив з валідними даними
    }
}