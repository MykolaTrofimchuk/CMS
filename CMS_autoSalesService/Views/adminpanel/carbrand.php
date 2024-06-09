<?php
/** @var string $error_message Повідомлення про помилку */

$this->Title = 'Додавання запису';
if (isset($GLOBALS['brandInfo']))
    $rowInfo = $GLOBALS['brandInfo'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Додавання запису</title>
</head>
<body>
<div class="container mt-5">
    <div class="modal-content rounded-4 shadow">
        <div class="modal-body p-5 pt-0 mt-3">
            <form method="post" action="">
                <?php if (!empty($error_message)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error_message ?>
                    </div>
                <?php endif; ?>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="brand" placeholder="Марка авто" name="brand"
                           value="<?= isset($this->post->model) ? htmlspecialchars($this->post->model) : '' ?><?= isset($rowInfo->brand) && (strlen($rowInfo->brand) !== 0) ? htmlspecialchars($rowInfo->brand) : '' ?>">
                    <label for="brand">Марка</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="model"
                           placeholder="Модель (можливо комплектація / версія) авто" name="model"
                           value="<?= isset($this->post->brand) ? htmlspecialchars($this->post->brand) : '' ?><?= isset($rowInfo->brand) && strlen($rowInfo->model) !== 0 ? htmlspecialchars($rowInfo->model) : '' ?>">
                    <label for="model">Модель</label>
                </div>
                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Додати запис</button>
                <hr class="my-4">
            </form>
        </div>
    </div>
</div>
</body>
</html>