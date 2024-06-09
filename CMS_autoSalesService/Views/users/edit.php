<?php
/** @var string $error_message Повідомлення про помилку */

$this->Title = 'Змінити дані користувача';

$userInfo = \Models\Users::GetUserInfo(\core\Core::get()->session->get('user')['id']);
$userToUpdateData = $GLOBALS['userToEdit'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="modal-content rounded-4 shadow">

    <div class="modal-body p-5 pt-0">
        <form class="" method="post" action="">
            <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>
            <?php if (\Models\Users::IsAdmin($userInfo[0]['id'])): ?>
            <div class="form-floating mb-3">
                <select class="form-control" id="role" name="role" onselect="<?= $this->controller->post->role ?>">
                    <option value="">Оберіть роль користувача</option>
                    <option value="user" <?php echo ($userToUpdateData->role === 'user') ? 'selected' : ''; ?>>user</option>
                    <option value="admin" <?php echo ($userToUpdateData->role === 'admin') ? 'selected' : ''; ?>>admin</option>
                </select>
            </div>
            <?php endif; ?>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" placeholder="Логін"
                       id="login" name="login" value="<?= $userToUpdateData->login ?>">
                <label for="login">Новий логін*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" placeholder="Прізвище"
                       id="lastName" name="lastName" value="<?= $userToUpdateData->last_name ?>">
                <label for="lastName">Змінити прізвище*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" placeholder="Ім'я"
                       id="firstName" name="firstName" value="<?= $userToUpdateData->first_name ?>">
                <label for="firstName">Змінити ім'я*</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control rounded-3" placeholder="name@mail.com"
                       id="email" name="email" value="<?= $userToUpdateData->email ?>">
                <label for="email">Змінити e-mail</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" placeholder="+380(XXX)YYYEEAA"
                       id="phone_number" name="phone_number" value="<?= $userToUpdateData->phone_number ?>">
                <label for="phone_number">Змінити номер телефону</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" placeholder="Область, місто"
                       id="region" name="region" value="<?= $userToUpdateData->region ?>">
                <label for="region">Змінити адресу</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Зберегти</button>
            <small class="text-body-secondary">Уважно перевірте введені дані для зміни.</small>
            <hr class="my-4">

        </form>
    </div>
</div>
</body>
</html>
