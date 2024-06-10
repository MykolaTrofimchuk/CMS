<?php
/** @var string $error_message Повідомлення про помилку */

$this->Title = 'Редагування оголошення';

$vehicleInfo = $GLOBALS['vehicleInfo'];
$announcementInfo = $GLOBALS['announcementInfo'];
$ownerInfo = $GLOBALS['userOwnerInfo'];

$loggedUserId = \core\Core::get()->session->get('user')['id'];
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
        .preview img {
            max-width: 100px;
            margin: 5px;
            transition: filter 0.3s ease;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="modal-content rounded-4 shadow">
    <div class="modal-body p-5 pt-0">
        <form method="post" action="" enctype="multipart/form-data">
            <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>
            <div class="form-row">
                <h3>Додайте фото автомобіля: </h3>
                <div class="form-group col">
                    <button type="button" id="addPhoto" class="btn btn-secondary mt-2">Додати фото</button>
                </div>
                <div id="preview" class="preview">
                    <?php
                    $imageSrc = "../../../../src/resourses/no-photo.jpg";
                    $imagesPath = "./" . $announcementInfo[0]['pathToImages'];
                    $firstImageSrc = '../../../src/resourses/no-photo.jpg';
                    $realImagesPath = realpath($imagesPath);
                    $realImagesPath = str_replace('\\', '/', $realImagesPath);

                    if (!is_null($announcementInfo[0]['pathToImages']) && is_dir($realImagesPath)) {
                        $images = scandir($realImagesPath);
                        $images = array_diff($images, array('.', '..'));

                        foreach ($images as $image) {
                            $imageSrc = "../../../../../" . $announcementInfo[0]['pathToImages'] . "/" . $image;
                            echo "<img src='$imageSrc' alt='$image' data-filename='$image'>";
                        }
                    }
                    ?>
                </div>
            </div>
            <p class="text-muted" style="font-style: italic;">(Порядок фотографій відповідає їх відображенню в
                оголошенні. Для видалення - клікніть по картинці)</p>
            <input type="hidden" name="deletedImages" id="deletedImages" value="">
            <br><br><br>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="titleAnnouncement"
                       placeholder="Заголовок оголошення" name="title" value="">
                <label for="titleAnnouncement">Заголовок оголошення</label>
            </div>
            <div class="form-group">
                <label for="description">Опис</label>
                <textarea class="form-control rounded-3" id="description" rows="5" name="description"
                          placeholder="Основний опис автомобіля..."><?= $announcementInfo[0]['description'] ?></textarea>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control rounded-3" id="price"
                       placeholder="Заголовок оголошення" name="price" value="<?= $announcementInfo[0]['price'] ?>">
                <label for="price">Ціна ($)</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-control" id="condition" name="condition">
                    <option value="">Оберіть стан автомобіля</option>
                    <option <?php echo ($vehicleInfo['veh_condition'] === 'Нове') ? 'selected' : ''; ?>>Нове</option>
                    <option <?php echo ($vehicleInfo['veh_condition'] === 'З пробігом') ? 'selected' : ''; ?>>З
                        пробігом
                    </option>
                </select>
                <label for="condition">Стан авто</label>
            </div>
            <?php if (\Models\Users::IsAdmin($loggedUserId)): ?>
                <div class="form-floating mb-3">
                    <select class="form-control bg-light" id="user" name="userId">
                        <?php
                        $users = \Models\Users::findAll();
                        usort($users, function ($a, $b) {
                            return strcmp($a['id'], $b['id']);
                        });
                        foreach ($users as $user) :
                            $selected = ($announcementInfo[0]['user_id'] === $user['id']) ? 'selected' : '';
                            ?>
                            <option value='<?= $user['id'] ?>' <?= $selected ?>><?= $user['id'] ?>: <?= $user['login'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="user">Змінити власника оголошення </label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-control bg-light" id="status" name="statusId">
                        <?php
                        $statuses = \Models\AnnouncementStatuses::FindAllStatuses();
                        usort($statuses, function ($a, $b) {
                            return strcmp($a['id'], $b['id']);
                        });
                        foreach ($statuses as $status) :
                            $selected = ($announcementInfo[0]['status_id'] === $status['id']) ? 'selected' : '';
                            ?>
                            <option value='<?= $status['id'] ?>' <?= $selected ?>><?= $status['id'] ?>
                                : <?= $status['status'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="status">Змінити статус оголошення</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="datetime-local" class="form-control bg-light" id="deactivationDate" placeholder="Дата деактивації оголошення"
                           name="deactivationDate" value="<?= isset($announcementInfo[0]['deactivationDate']) && $announcementInfo[0]['deactivationDate'] !== null ? $announcementInfo[0]['deactivationDate'] : ''?>">
                    <label for="deactivationDate">Дата видалення</label>
                </div>
            <?php endif; ?>
            <br>
            <div class="form-row">
                <h3>Основна інформація про автомобіль: </h3>
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="carBrand">Марка</label>
                            <select class="form-control" id="carBrand" name="brand">
                                <option value="">Оберіть марку авто</option>
                                <?php
                                $brands = \Models\FilterModelBrands::FindAllBrandUnique();
                                sort($brands);
                                foreach ($brands as $brand) {
                                    $selected = ($brand === $vehicleInfo['brand']) ? 'selected' : '';
                                    echo "<option value='$brand' $selected>$brand</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="carModel">Модель</label>
                            <select class="form-control" id="carModel" name="model">
                                <option value="">Оберіть модель авто</option>
                                <?php
                                $models = \Models\FilterModelBrands::FindModelsByBrand($vehicleInfo['brand']);
                                foreach ($models as $model) {
                                    $selected = ($model === $this->controller->post->model) ? 'selected' : '';
                                    echo "<option value='$model' $selected>$model</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="modelYear">Рік випуску</label>
                            <input type="date" class="form-control" id="modelYear" placeholder="Рік випуску"
                                   name="modelYear" value="<?= $vehicleInfo['model_year'] ?>">
                        </div>
                        <div class="col">
                            <label for="millage">Пробіг (км)</label>
                            <input type="number" class="form-control" id="millage" placeholder="пробіг авто (у км)"
                                   name="millage" value="<?= $vehicleInfo['millage'] ?>">
                        </div>
                        <div class="col">
                            <label for="bodyType">Тип кузова</label>
                            <select class="form-control" id="bodyType" name="bodyType">
                                <option value="">Оберіть тип кузова</option>
                                <?php
                                $bodyTypes = [
                                    'Седан', 'Універсал', 'Ліфтбек', 'Хетчбек', 'Купе', 'Кабріолет', 'Родстер', 'Лімузин',
                                    '',
                                    'Позашляховик', 'Кросовер',
                                    '',
                                    'Фургон', 'Мінівен'
                                ];

                                foreach ($bodyTypes as $bodyType) {
                                    $selected = ($bodyType === $vehicleInfo['body_type']) ? 'selected' : '';
                                    echo "<option $selected>$bodyType</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <?php
                    $regionObl = '';
                    $regionCity = '';

                    if (!empty($vehicleInfo['region'])) {
                        $regionParts = explode(',', $vehicleInfo['region']);
                        $regionObl = isset($regionParts[0]) ? trim($regionParts[0]) : '';
                        $regionCity = isset($regionParts[1]) ? trim($regionParts[1]) : '';
                    }
                    ?>

                    <div class="row">
                        <div class="col">
                            <label for="regionObl">Область</label>
                            <input type="text" class="form-control" id="regionObl" placeholder="Область перебування"
                                   name="regionObl" value="<?= htmlspecialchars($regionObl) ?>">
                        </div>
                        <div class="col">
                            <label for="regionCity">Місто</label>
                            <input type="text" class="form-control" id="regionCity"
                                   placeholder="Місто у якому перебуваєте" name="regionCity"
                                   value="<?= htmlspecialchars($regionCity) ?>">
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="form-row">
                <h3>Характеристики автомобіля: </h3>
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="driveType">Коробка передач</label>
                            <select class="form-control" id="driveType" name="transmission">
                                <option value=""> Оберіть</option>
                                <?php
                                $transmissions = [
                                    'Ручна / Механіка', 'Автомат', 'Типтронік', 'Робот', 'Варіатор'
                                ];

                                foreach ($transmissions as $transmission) {
                                    $selected = ($transmission === $vehicleInfo['transmission']) ? 'selected' : '';
                                    echo "<option value='$transmission' $selected>$transmission</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="fuelType">Паливо</label>
                            <select class="form-control" id="fuelType" name="fuelType">
                                <option value=""> Оберіть</option>
                                <?php
                                $fuelTypes = [
                                    'Бензин', 'Газ', 'Газ пропан-бутан / Бензин', 'Газ метан / Бензин',
                                    'Гібрид (HEV)', 'Гібрид (PHEV)', 'Гібрид (MHEV)', 'Дизель', 'Електро'
                                ];

                                foreach ($fuelTypes as $fuelType) {
                                    $selected = ($fuelType === $vehicleInfo['fuel_type']) ? 'selected' : '';
                                    echo "<option value='$fuelType' $selected>$fuelType</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="engineCapacity">Об’єм двигуна (л)</label>
                            <input type="text" class="form-control" id="engineCapacity"
                                   placeholder="Об'єм двигуна (літри \ кВт)" name="engineCapacity"
                                   value="<?= $vehicleInfo['engine_capacity'] ?>">
                        </div>

                        <div class="col">
                            <label for="horsePowers">Потужність двигуна (к.с.)</label>
                            <input type="number" class="form-control" id="horsePowers"
                                   placeholder="Потужність (кінських сил)" name="horsePower"
                                   value="<?= $vehicleInfo['horse_power'] ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="driveType">Привід</label>
                            <select class="form-control" id="driveType" name="drive">
                                <option value=""> Оберіть</option>
                                <?php
                                $drives = ['Повний', 'Передній', 'Задній'];
                                foreach ($drives as $drive) {
                                    $selected = ($drive === $vehicleInfo['drive']) ? 'selected' : '';
                                    echo "<option value='$drive' $selected>$drive</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="color">Колір</label>
                            <select class="form-control" id="color" name="color">
                                <option value="">Оберіть</option>
                                <?php
                                $colors = ['Чорний', 'Коричневий', 'Білий', 'Сірий', 'Бежевий', 'Зелений', 'Жовтий', 'Синій', 'Фіолетовий', 'Помаранчевий', 'Червоний'];
                                foreach ($colors as $color) {
                                    $selected = ($color === $vehicleInfo['color']) ? 'selected' : '';
                                    echo "<option value='$color' $selected>$color</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="form-row">
                <h3>Додатково: </h3>
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="plate">Номерний знак</label>
                            <input type="text" class="form-control" id="plate"
                                   placeholder="Номерний знак (АА0000АА)" name="plate"
                                   value="<?= $vehicleInfo['plate'] ?>">
                        </div>
                        <div class="col">
                            <label for="vinCode">VIN-код</label>
                            <input type="text" class="form-control" id="vinCode"
                                   placeholder="VIN-код (XXXXYYYYYXXXYXYXYXX)" name="vinCode"
                                   value="<?= $vehicleInfo['vin_code'] ?>">
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Зберегти зміни</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var carModelInput = document.getElementById('carModel');
        carModelInput.disabled = true;

        document.getElementById('carBrand').addEventListener('change', function () {
            var selectedBrand = this.value;
            carModelInput.disabled = selectedBrand === "Оберіть марку авто";
        });

        var bodyTypeSelect = document.getElementById('condition');
        var millageInput = document.getElementById('millage');

        function toggleMillageInput() {
            millageInput.disabled = bodyTypeSelect.value === 'Нове';
        }

        toggleMillageInput();

        bodyTypeSelect.addEventListener('change', toggleMillageInput);

        var previewContainer = document.getElementById('preview');

        document.getElementById('addPhoto').addEventListener('click', function () {
            var newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.accept = 'image/jpeg, image/png, image/gif, image/jpg';
            newInput.classList.add('form-control', 'file-input');
            newInput.name = 'carImages[]';
            newInput.style.display = 'none';
            document.querySelector('.form-group').appendChild(newInput);

            newInput.addEventListener('change', handleFileSelect);
            newInput.click();
        });

        var imageCounter = 1;

        function handleFileSelect(event) {
            var files = event.target.files;

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.addEventListener('click', function () {
                        this.remove();
                    });
                    img.addEventListener('mouseover', function () {
                        this.style.filter = 'blur(3px)';
                    });
                    img.addEventListener('mouseout', function () {
                        this.style.filter = 'none';
                    });

                    var extension = file.name.split('.').pop().toLowerCase();
                    var fileName = imageCounter + '.' + extension;
                    imageCounter++;
                    img.setAttribute('data-filename', fileName);

                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }

        document.querySelectorAll('.preview img').forEach(img => {
            img.addEventListener('click', function () {
                var confirmed = confirm('Ви впевнені, що хочете видалити цю фотографію?');
                if (confirmed) {
                    var deletedImagesInput = document.getElementById('deletedImages');
                    var filename = this.getAttribute('data-filename');
                    this.remove();
                    if (deletedImagesInput.value === '') {
                        deletedImagesInput.value = filename;
                    } else {
                        deletedImagesInput.value += ', ' + filename;
                    }
                }
            });
        });

        document.getElementById("carBrand").addEventListener("change", function () {
            var selectedBrand = this.value;
            selectedBrand = selectedBrand.replace(/\s/g, '%20');
            var carModelSelect = document.getElementById("carModel");
            carModelSelect.innerHTML = '<option value="">Завантаження...</option>';
            fetch('/announcements/selectmodelsbybrand/' + encodeURIComponent(selectedBrand))
                .then(response => response.text())
                .then(data => {
                    var endIndex = data.indexOf("]");
                    if (endIndex !== -1) {
                        data = data.slice(0, endIndex + 1);
                    }
                    var models = data.match(/"(.*?)"/g);
                    carModelSelect.innerHTML = '<option value="">Оберіть модель авто</option>';
                    if (models) {
                        models.forEach(function (model) {
                            model = model.replace(/["]/g, "").trim();
                            carModelSelect.innerHTML += '<option value="' + model + '">' + model + '</option>';
                        });
                    } else {
                        carModelSelect.innerHTML = '<option value="">Моделі не знайдено</option>';
                    }
                })
                .catch(error => {
                    console.error('Помилка:', error);
                    carModelSelect.innerHTML = '<option value="">Помилка завантаження моделей</option>';
                });
        });
    });
</script>
</body>
</html>
