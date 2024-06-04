<?php
$this->Title = 'Список оголошень';
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
            opacity: 0.5;
            pointer-events: none;
        }

        .inactive-announcement::before {
            content: attr(data-status);
            position: absolute;
            top: 50%;
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
            <?php foreach ($GLOBALS['announcements'] as $announcement): ?>
                <?php
                $isInactive = in_array($announcement['statusText'], ['Продано', 'Видалено']);
                $inactiveClass = $isInactive ? 'inactive-announcement' : '';

                // Default image path
                $imageSrc = "../../../../src/resourses/no-photo.jpg";
                $imagesPath = "./" . $announcement['pathToImages'];

                // Use realpath to debug the path issue
                $realImagesPath = realpath($imagesPath);
                $realImagesPath = str_replace('\\', '/', $realImagesPath);

                if (!is_null($announcement['pathToImages']) && is_dir($realImagesPath)) {
                    $images = scandir($realImagesPath);
                    $images = array_diff($images, array('.', '..'));
                    $firstImage = !empty($images) ? reset($images) : null;
                    $imageSrc = "../../../../../". $announcement['pathToImages'] . "/" . $firstImage;
                }
                ?>
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow <?= $inactiveClass ?>" data-status="<?= htmlspecialchars($announcement['statusText']) ?>">
                        <img class="card-img-top" alt="<?php echo($imageSrc) ?>" style="height: 225px; width: 100%; display: block;" src="<?php echo($imageSrc) ?>" data-holder-rendered="true">
                        <div class="card-body">
                            <p class="card-text"><?= htmlspecialchars($announcement['title']) ?></p>
                            <p class="card-text"><?= htmlspecialchars($announcement['price']) . " $"?></p>
                            <p class="card-text"><?= $announcement['description'] ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="/announcements/index/<?= $announcement['id'] ?>" class="btn btn-sm btn-outline-secondary">Переглянути</a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary">В обране</button>
                                </div>
                                <small class="text-muted"><?= htmlspecialchars($announcement['publicationDate']) ?></small>
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