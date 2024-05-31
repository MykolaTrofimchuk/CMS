<?php
/** @var string $error_message Повідомлення про помилку */

$this->Title = 'Реєстрація користувача';
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
<form method="post" action="">
    <?php
    if (!empty($error_message)) : ?>
        <div style="color: red">
            <?= $error_message ?>
        </div>
    <?php endif; ?>
    <table>
        <tr>
            <td><label for="login">Логін: </label></td>
            <td><input type="text" id="login" name="login" value="<?=$this->controller->post->login ?>"></td>
        </tr>
        <tr>
            <td><label for="password">Пароль: </label></td>
            <td><input type="password" id="password" name="password" ></td>
        </tr>
        <tr>
            <td><label for="password2">Пароль (повторити): </label></td>
            <td><input type="password" id="password2" name="password2" ></td>
        </tr>
        <tr>
            <td><label for="lastName">Прізвище: </label></td>
            <td><input type="text" id="lastName" name="lastName" value="<?=$this->controller->post->lastName ?>"></td>
        </tr>
        <tr>
            <td><label for="firstName">Ім'я: </label></td>
            <td><input type="text" id="firstName" name="firstName" value="<?=$this->controller->post->firstName ?>"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Зареєструвати"></td>
        </tr>
    </table>
</form>
</body>
</html>
