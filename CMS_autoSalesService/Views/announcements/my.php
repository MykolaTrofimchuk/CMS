<?php
$this->Title = 'Мої оголошення';

$userInfo = \Models\Users::GetUserInfo(\core\Core::get()->session->get('user')['id']);
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
            opacity: 0.35;
            pointer-events: none;
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
            <?php foreach ($GLOBALS['announcementsMy'] as $announcement): ?>
                <?php
                $isInactive = in_array($announcement['statusText'], ['Продано', 'Видалено']);
                $inactiveClass = $isInactive ? 'inactive-announcement' : '';

                $vehicleInfo = \Models\Announcements::SelectVehicleFromAnnouncement($announcement['id']);

                $imageSrc = "../../../../src/resourses/no-photo.jpg";
                $imagesPath = "./" . $announcement['pathToImages'];
                $realImagesPath = realpath($imagesPath);
                $realImagesPath = str_replace('\\', '/', $realImagesPath);

                if (!is_null($announcement['pathToImages']) && is_dir($realImagesPath)) {
                    $images = scandir($realImagesPath);
                    $images = array_diff($images, array('.', '..'));
                    $firstImage = !empty($images) ? reset($images) : null;
                    if (!is_null($firstImage)) {
                        $imageSrc = "../../../../../" . $announcement['pathToImages'] . "/" . $firstImage;
                    }

                }

                $deactiveDate = !empty($announcement['deactivationDate']) ? $announcement['deactivationDate'] : '...';
                if ($deactiveDate !== null) {
                    $currentTime = new DateTime();
                    $deactivationDateTime = new DateTime($deactiveDate);
                    $interval = $currentTime->diff($deactivationDateTime);
                    $hoursAgo = $interval->h;

                    $deactiveDate = "годин тому: $hoursAgo";
                } else {
                    $deactiveDate = "...";
                }
                ?>
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow <?= $inactiveClass ?>" data-status="<?= htmlspecialchars($announcement['statusText']) ?>" data-status-date="<?= htmlspecialchars($deactiveDate) ?>">
                        <div class="card-img-top-container" style="position: relative;">
                            <img class="card-img-top" alt="<?php echo($imageSrc) ?>" style="height: 225px; width: 100%; display: block;" src="<?php echo($imageSrc) ?>" data-holder-rendered="true">
                            <a href="/announcements/edit/<?= htmlspecialchars($announcement['id']) ?>" class="btn btn-primary" style="position: absolute; top: 10px; right: 10px;">Редагувати</a>
                        </div>
                        <div class="card-body">
                            <?php if ($vehicleInfo->veh_condition === 'З пробігом' || $vehicleInfo->veh_condition === 'Нове') : ?>
                                <p class="card-text fs-6 mb-1 fw-bold text-muted">
                                    <em><?= htmlspecialchars($vehicleInfo->veh_condition) ?></em>
                                </p>
                            <?php else: ?>
                                <p class="card-text fs-6 mb-1 fw-bold text-muted">
                                    <em>З пробігом</em>
                                </p>
                            <?php endif; ?>
                            <p class="card-text fs-5" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($announcement['title']) ?>
                            </p>
                            <p class="card-text fs-5 fw-bold"><?= htmlspecialchars(round($announcement['price'])) . " $"?></p>
                            <?php if ($announcement['description'] !== null): ?>
                                <p class="card-text"><?= substr($announcement['description'], 0, 64) . '...' ?></p>
                            <?php else: ?>
                                <p class="card-text">Опис відсутній</p>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group" style="display: flex; justify-content: space-between;">
                                    <p class="card-text"><?= htmlspecialchars(substr($vehicleInfo->model_year, 0, 4)) ?></p>
                                </div>
                                <p class="card-text"><?php
                                    if (is_null($vehicleInfo->body_type)) {
                                        echo "Не вказано";
                                    } else {
                                        echo htmlspecialchars($vehicleInfo->body_type);
                                    }
                                    ?>
                                </p>
                            </div><hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group" style="display: flex; justify-content: space-between;">
                                    <p class="card-text"><?php
                                        if (is_null($vehicleInfo->engine_capacity)) {
                                            echo "Не вказано";
                                        } else {
                                            echo htmlspecialchars($vehicleInfo->engine_capacity) . "L";
                                        }
                                        ?></p>
                                </div>
                                <p class="card-text"><?= htmlspecialchars($vehicleInfo->fuel_type) ?></p>
                            </div><hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group" style="display: flex; justify-content: space-between;">
                                    <p class="card-text"><?= htmlspecialchars($vehicleInfo->transmission) ?></p>
                                </div>
                                <p class="card-text"><?= htmlspecialchars($vehicleInfo->color) ?></p>
                            </div><hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="/announcements/index/<?= $announcement['id'] ?>" class="btn btn-sm btn-outline-secondary">Переглянути</a>
                                    <?php if (\Models\Users::IsUserLogged()) : ?>
                                        <?php
                                        $isFavorite = \Models\UserFavouritesAnnouncements::IsFavorite($userInfo[0]['id'], $announcement['id']);

                                        if (!$isFavorite) : ?>
                                            <a href="/announcements/addtofavorites/<?= $announcement['id']?>"
                                               class="btn btn-sm btn-outline-secondary">Слідкувати &#9829;</a>
                                        <?php endif; ?>

                                        <?php if ($isFavorite) : ?>
                                            <a href="/announcements/removefromfavorites/<?= $announcement['id'] ?>"
                                               class="btn btn-sm btn-outline-secondary bg-secondary text-white">Відслідковується &#9829;</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted"><?= htmlspecialchars($announcement['publicationDate']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (!empty($GLOBALS['totalPagesMy'])) : ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $GLOBALS['totalPagesMy']; $i++): ?>
                <a href="/announcements/my/<?= $i ?>" class="<?= $i == $GLOBALS['currentPageMy'] ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>