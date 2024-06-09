<?php
$userInfo = \Models\Users::GetUserInfo(\core\Core::get()->session->get('user')['id']);
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
<div class="container-fluid">
    <div class="row">
        <!-- Бічна панель -->
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px; height: 40vw;">
            <a href="/adminpanel/index" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                <span class="fs-4">Адмін панель</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active" aria-current="page">
                        <svg class="bi me-2 btn-toggle" width="16" height="16"><use xlink:href="#home"></use></svg>
                        Головна
                    </a>
                </li>
                <li>
                    <a href="/adminpanel/reports" class="nav-link link-dark">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                        Звіти
                    </a>
                </li>
                <li>
                    <a href="/adminpanel/announcements/1" class="nav-link link-dark">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                        Оголошення
                    </a>
                </li>
                <li>
                    <a href="/adminpanel/users/1" class="nav-link link-dark">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#people-circle"></use></svg>
                        Користувачі
                    </a>
                </li>
            </ul>
            <hr>
        </div>
        <!-- Контент -->
    </div>
</div>
</body>
</html>
