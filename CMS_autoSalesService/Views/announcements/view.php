<?php
$this->Title = 'Список оголошень';

$user = \core\Core::get()->session->get('user');
$userId = null;
if ($user !== null && isset($user['id'])) {
    $userId = $user['id'];
    $userInfo = \Models\Users::GetUserInfo($userId);
}

$isAdmin = $userId !== null && \Models\Users::IsAdmin($userId);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Список оголошень</title>
    <style>
        .inactive-announcement {
            position: relative;
            <?php if (!$isAdmin): ?>
            opacity: 0.35;
            pointer-events: none;
            <?php endif; ?>
        }

        .inactive-announcement::before {
            content: attr(data-status);
            position: absolute;
            top: 53%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .inactive-announcement::after {
            content: attr(data-status-date);
            position: absolute;
            top: 67%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            font-style: italic;
            text-align: center;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: #007bff;
            text-decoration: none;
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<div class="album py-5 bg-light">
    <div class="container">
        <div class="row">
            <?php foreach ($GLOBALS['statuses'] as $announcement): ?>
                <?php
                $isInactive = in_array($announcement[0]['statusText'], ['Продано', 'Видалено', 'Невідомо']);
                $inactiveClass = $isInactive ? 'inactive-announcement' : '';

                $vehicleInfo = \Models\Announcements::SelectVehicleFromAnnouncement($announcement[0]['id']);
                $imageSrc = "../../../../src/resourses/no-photo.jpg";
                $imagesPath = "./" . $announcement[0]['pathToImages'];
                $realImagesPath = realpath($imagesPath);
                $realImagesPath = str_replace('\\', '/', $realImagesPath);

                if (!is_null($announcement[0]['pathToImages']) && is_dir($realImagesPath)) {
                    $images = scandir($realImagesPath);
                    $images = array_diff($images, array('.', '..'));
                    $firstImage = !empty($images) ? reset($images) : null;
                    if (!is_null($firstImage)) {
                        $imageSrc = "../../../../../" . $announcement[0]['pathToImages'] . "/" . $firstImage;
                    }
                }

                $deactiveDate = !empty($announcement[0]['deactivationDate']) ? $announcement[0]['deactivationDate'] : '...';
                if ($deactiveDate !== '...') {
                    $currentTime = new DateTime();
                    $deactivationDateTime = new DateTime($deactiveDate);
                    $interval = $currentTime->diff($deactivationDateTime);
                    $hoursAgo = $interval->h;
                    $deactiveDate = "годин тому: $hoursAgo";
                }
                ?>
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow <?= $inactiveClass ?>"
                         data-status="<?= htmlspecialchars($announcement[0]['statusText']) ?>"
                         data-status-date="<?= htmlspecialchars($deactiveDate) ?>">
                        <img class="card-img-top" alt="<?= htmlspecialchars($imageSrc) ?>"
                             style="height: 225px; width: 100%; display: block;" src="<?= htmlspecialchars($imageSrc) ?>"
                             data-holder-rendered="true">
                        <?php if ($isAdmin): ?>
                            <a href="/announcements/edit/<?= htmlspecialchars($announcement[0]['id']) ?>"
                               class="btn btn-info"
                               style="position: absolute; top: 10px; right: 10px;">Редагувати</a>
                            <div class="d-flex justify-content-between align-items-center w-100 mt-3">
                                <a href="/announcements/sold/<?= $announcement[0]['id'] ?>"
                                   class="btn btn-sm btn-outline-success w-50 m-0 text-center">Продано &#36;</a>
                                <a href="/announcements/delete/<?= $announcement[0]['id'] ?>"
                                   class="btn btn-sm btn-outline-danger w-50 m-0 text-center">Видалити &#215;</a>
                            </div>
                            <?php if ($isInactive): ?>
                                <a href="/announcements/restore/<?= $announcement[0]['id'] ?>"
                                   class="btn btn-success"
                                   style="position: absolute; top: 60px; right: 10px;">Відновити</a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="card-text fs-6 mb-1 fw-bold text-muted">
                                    <em><?= htmlspecialchars($vehicleInfo->veh_condition ?? 'З пробігом') ?></em>
                                </p>
                                <p class="card-text fs-6 mb-1 fw-bold text-muted">
                                    &#9829; <?= htmlspecialchars($announcement[0]['countFavorite'][0]['count']) ?></p>
                            </div>
                            <p class="card-text fs-5"
                               style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($announcement[0]['title']) ?>
                            </p>
                            <p class="card-text fs-5 fw-bold"><?= htmlspecialchars(round($announcement[0]['price'])) . " $" ?></p>
                            <p class="card-text"><?= htmlspecialchars(substr($announcement[0]['description'] ?? 'Опис відсутній', 0, 64)) . '...' ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <p class="card-text"><?= htmlspecialchars($vehicleInfo->model_year ?? 'Не вказано') ?></p>
                                </div>
                                <p class="card-text"><?= htmlspecialchars($vehicleInfo->body_type ?? 'Не вказано') ?></p>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <p class="card-text"><?= htmlspecialchars($vehicleInfo->engine_capacity ? $vehicleInfo->engine_capacity . "L" : 'Не вказано') ?></p>
                                </div>
                                <p class="card-text"><?= htmlspecialchars($vehicleInfo->fuel_type ?? 'Не вказано') ?></p>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <p class="card-text"><?= htmlspecialchars($vehicleInfo->transmission ?? 'Не вказано') ?></p>
                                </div>
                                <p class="card-text"><?= htmlspecialchars($vehicleInfo->color ?? 'Не вказано') ?></p>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="/announcements/index/<?= $announcement[0]['id'] ?>"
                                       class="btn btn-sm btn-outline-secondary">Переглянути</a>
                                    <?php if (\Models\Users::IsUserLogged()) : ?>
                                        <?php
                                        $isFavorite = \Models\UserFavouritesAnnouncements::IsFavorite($userInfo[0]['id'], $announcement[0]['id']);
                                        if (!$isFavorite) : ?>
                                            <a href="/announcements/addtofavorites/<?= $announcement[0]['id'] ?>"
                                               class="btn btn-sm btn-outline-secondary">Слідкувати &#9829;</a>
                                        <?php else: ?>
                                            <a href="/announcements/removefromfavorites/<?= $announcement[0]['id'] ?>"
                                               class="btn btn-sm btn-outline-secondary bg-secondary text-white">Відслідковується &#9829;</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted"><?= htmlspecialchars($announcement[0]['publicationDate']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $GLOBALS['totalPages']; $i++): ?>
                <a href="/announcements/view/<?= $i ?>" class="<?= $i == $GLOBALS['currentPage'] ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</div>
</body>
</html>