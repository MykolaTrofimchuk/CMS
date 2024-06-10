<?php
$rows = $GLOBALS['admPanelCarBrands'];
$currentPage = $GLOBALS['currentPage'];
$totalPages = $GLOBALS['totalPages'];

function createPaginationLinks($currentPage, $totalPages, $delta = 2) {
    $range = range(max(1, $currentPage - $delta), min($totalPages, $currentPage + $delta));
    if ($range[0] > 1) array_unshift($range, 1, '...');
    if (end($range) < $totalPages) array_push($range, '...', $totalPages);
    return $range;
}
$paginationLinks = createPaginationLinks($currentPage, $totalPages);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Адмін панель - Бренди автомобілів</title>
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

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
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
                Всього зареєстрованих брендів та моделей у системі: <strong><?= \Models\FilterModelBrands::CountAll() ?></strong>
            </div>
            <div class="table-wrapper">
                <table class="table table-bordered w-100">
                    <thead>
                    <tr>
                        <?php $keys = array_keys(current($rows)); ?>
                        <?php foreach ($keys as $key): ?>
                            <?php if ($key === "password") continue; ?>
                            <th><?php echo ucfirst($key); ?></th>
                        <?php endforeach; ?>
                        <th>Редагувати</th>
                        <th>Видалити</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <?php foreach ($row as $key => $value): ?>
                                <?php if ($key === "password") continue; ?>
                                <td><?php echo $value; ?></td>
                            <?php endforeach; ?>
                            <td class="text-center"><a href="/adminpanel/carbrand/<?= $row['id']?>" class="btn btn-link">&#9998;</a></td>
                            <td class="text-center"><a href="/adminpanel/carbrandsdelete/<?= $row['id']?>" class="btn btn-link">&#10008;</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="<?= count($keys)+2 ?>" class="text-center bg-secondary text-light">
                            <a href="/adminpanel/carbrand/add" class="btn btn-link">&#10010; Додати новий запис</a>
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
                            <a class="page-link" href="/adminpanel/carbrands/<?= $currentPage - 1; ?>">&laquo; Попередня</a>
                        </li>
                    <?php endif; ?>
                    <?php foreach ($paginationLinks as $link): ?>
                        <?php if ($link === '...'): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php else: ?>
                            <li class="page-item <?= ($link == $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" href="/adminpanel/carbrands/<?= $link; ?>"><?= $link; ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/adminpanel/carbrands/<?= $currentPage + 1; ?>">Наступна &raquo;</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
</body>
</html>