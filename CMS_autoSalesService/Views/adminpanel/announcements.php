<?php
$this->Title = 'Admin Adds';

$announcements = $GLOBALS['admPanelAnnouncements'];
$currentPage = $GLOBALS['currentPage'];
$totalPages = $GLOBALS['totalPages'];

$vehicles = [];
$statuses = [];
$users = [];
foreach ($announcements as $an) {
    $vehicle = \Models\Vehicles::FindVehicleById($an['vehicle_id']);
    $vehicles[] = $vehicle;
    $status = \Models\AnnouncementStatuses::FindStatusById($an['status_id']);
    $statuses[] = $status;
    $user = \Models\Users::GetUserInfo($an['user_id']);
    $users[] = $user;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
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

        .tooltip {
            position: absolute;
            display: none;
            background-color: white;
            border: 1px solid black;
            padding: 5px;
            z-index: 1000;
        }
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            position: relative;
            z-index: 1;
        }

        .dlist {
            position: absolute;
            z-index: 1000;
        }

        .d-flex {
            position: relative;
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
                Всього зареєстрованих оголошень у системі:
                <strong><?= \Models\Announcements::countAllAnnouncements() ?></strong>
            </div>
            <div class="table-wrapper">
                <table class="table table-bordered w-100">
                    <thead>
                    <tr>
                        <?php $keys = array_keys(current($announcements)); ?>
                        <?php foreach ($keys as $key): ?>
                            <th><?php echo ucfirst($key); ?></th>
                        <?php endforeach; ?>
                        <th>Редагувати</th>
                        <th>Видалити</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($announcements as $index => $announcement): ?>
                        <tr>
                            <?php foreach ($announcement as $key => $value): ?>
                                <?php  if ($key === "vehicle_id"): ?>
                                    <td>
                                        <div class="dropdown dlist dropend">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                                <?php echo $vehicles[$index]->id; ?>
                                            </button>
                                            <ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenu2">
                                                <?php foreach ($vehicles[$index] as $vh) :?>
                                                    <li><button class="dropdown-item text-light" type="button"><?php echo $vh; ?></button></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </td>
                                <?php elseif ($key === "status_id"): ?>
                                    <td>
                                        <div class="dropdown dlist dropend">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                                <?php echo $statuses[$index]->id; ?>
                                            </button>
                                            <ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenu2">
                                                <?php foreach ($statuses[$index] as $status) :?>
                                                    <li><button class="dropdown-item text-light" type="button"><?php echo $status; ?></button></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </td>
                                <?php elseif ($key === "user_id"): ?>
                                    <td>
                                        <div class="dropdown dlist dropstart">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                                <?php echo $users[$index][0]['id']; ?>
                                            </button>
                                            <ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenu2">
                                                <?php foreach ($users[$index][0] as $user) :?>
                                                    <li><button class="dropdown-item text-light" type="button"><?php echo $user; ?></button></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </td>
                                <?php else: ?>
                                    <td><a><?php echo $value; ?></a></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td class="text-center"><a href="/adminpanel/announcementedit/<?= $announcement['id'] ?>"
                                                       class="btn btn-link">&#9998;</a></td>
                            <th class="text-center"><a href="/adminpanel/announcementdelete/<?= $announcement['id'] ?>"
                                                       class="btn btn-link">&#10008;</a></th>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="<?= count($announcements[0]) + 2; ?>" class="text-center bg-secondary text-light">
                            <a href="/adminpanel/announcementadd" class="btn btn-link">&#10010; Додати новий запис
                                оголошення</a>
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
                            <a class="page-link"
                               href="/adminpanel/announcements/<?= $currentPage - 1; ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : ''; ?>">
                            <a class="page-link" href="/adminpanel/announcements/<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/adminpanel/announcements/<?= $currentPage + 1; ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-JcKb8q3lIhq+Zab41lI39Alx5E5/8UzRC0sq4Eegp2x2QJLp7pruJ6p4UW3tBJQZ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.min.js" integrity="sha384-q4U3eH/1dFwtmHrHIXdMKQPjN5f1gRVZ99F6tFZGJxTCvJymiqqWI4Ck/IpHhuC" crossorigin="anonymous"></script>


</body>
</html>