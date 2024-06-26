<?php
$users = $GLOBALS['admPanelUsers'];
$currentPage = $GLOBALS['currentPage'];
$totalPages = $GLOBALS['totalPages'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Адмін панель - Користувачі</title>
    <style>
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .table-wrapper table {
            width: auto;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table-wrapper th,
        .table-wrapper td {
            min-width: 50px;
            max-width: 300px;
            overflow: hidden;
            word-wrap: break-word;
        }

        .btn-link {
            text-decoration: none !important;
            color: inherit !important;
        }
    </style>
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
                    <a href="/adminpanel/announcements/1" class="nav-link link-dark">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                        Оголошення
                    </a>
                </li>
                <li>
                    <a href="/adminpanel/announcementstatuses/1" class="nav-link link-dark">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                        Статуси оголошень
                    </a>
                </li>
                <li>
                    <a href="/adminpanel/users/1" class="nav-link link-dark">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#people-circle"></use></svg>
                        Користувачі
                    </a>
                </li>
                <li>
                    <a href="/adminpanel/followedadds/1" class="nav-link link-dark">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#people-circle"></use></svg>
                        Обрані оголошення
                    </a>
                </li>
                <hr>
                <li>
                    <a href="/adminpanel/carbrands/1" class="nav-link link-dark">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                        Марки - моделі авто
                    </a>
                </li>
            </ul>
            <hr>
        </div>
        <!-- Контент -->
        <div class="col-md-9 p-4">
            <div class="alert alert-secondary" role="alert">
                Всього зареєстрованих користувачів у системі: <strong><?= \Models\Users::CountAll() ?></strong>
            </div>
            <div class="table-wrapper">
                <table class="table table-bordered w-100">
                    <thead>
                    <tr>
                        <?php $keys = array_keys(current($users)); ?>
                        <?php foreach ($keys as $key): ?>
                            <?php if ($key === "password") continue; ?>
                            <th><?php echo ucfirst($key); ?></th>
                        <?php endforeach; ?>
                        <th>Редагувати</th>
                        <th>Видалити</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <?php foreach ($user as $key => $value): ?>
                                <?php if ($key === "password") continue; ?>
                                <td><?php echo $value; ?></td>
                            <?php endforeach; ?>
                            <td class="text-center"><a href="useredit/<?= $user['id']?>" class="btn btn-link">&#9998;</a></td>
                            <td class="text-center"><a href="userdelete/<?= $user['id']?>" class="btn btn-link">&#10008;</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="<?= count($keys)+1 ?>" class="text-center bg-secondary text-light">
                            <a href="useradd" class="btn btn-link">&#10010; Додати нового користувача</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- Пагінація -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/adminpanel/users/<?= $currentPage - 1; ?>">&laquo; Попередня</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : ''; ?>">
                            <a class="page-link" href="/adminpanel/users/<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/adminpanel/users/<?= $currentPage + 1; ?>">Наступна &raquo;</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
</body>
</html>