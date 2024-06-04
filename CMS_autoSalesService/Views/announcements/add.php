<?php
/** @var string $error_message Повідомлення про помилку */
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
            transition: filter 0.3s ease; /* Add transition effect for blurring */
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
                <div id="preview" class="preview"></div>
            </div>
            <br><br><br>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="titleAnnouncement"
                       placeholder="Заголовок оголошення" name="title" value="<?= $this->controller->post->title ?>">
                <label for="titleAnnouncement">Заголовок оголошення</label>
            </div>
            <div class="form-group">
                <label for="description">Опис</label>
                <textarea class="form-control rounded-3" id="description" rows="5"
                          placeholder="Основний опис автомобіля..."><?= $this->controller->post->description ?></textarea>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control rounded-3" id="price"
                       placeholder="Заголовок оголошення" name="price" value="<?= $this->controller->post->price ?>">
                <label for="price">Ціна ($)</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-control" id="condition" name="condition">
                    <option value="">Оберіть стан автомобіля</option>
                    <option>Нове</option>
                    <option>З пробігом</option>
                </select>
                <label for="condition">Стан авто</label>
            </div>
            <br>
            <div class="form-row">
                <h3>Основна інформація про автомобіль: </h3>
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="carBrand">Марка</label>
                            <select class="form-control" id="carBrand" name="brand" onselect="<?= $this->controller->post->brand ?>">
                                <option value="">Оберіть марку авто</option>
                                <?php
                                $brands = \Models\FilterModelBrands::SelectAllBrands();
                                sort($brands);
                                foreach ($brands as $brand) {
                                    echo "<option value='$brand'>$brand</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="carModel">Модель</label>
                            <input type="text" class="form-control" id="carModel" placeholder="модель авто" name="model" value="<?= $this->controller->post->model ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="modelYear">Рік випуску</label>
                            <input type="date" class="form-control" id="modelYear" placeholder="Рік випуску" name="modelYear" value="<?= $this->controller->post->modelYear ?>">
                        </div>
                        <div class="col">
                            <label for="millage">Пробіг (км)</label>
                            <input type="number" class="form-control" id="millage" placeholder="пробіг авто (у км)" name="millage" value="<?= $this->controller->post->millage ?>">
                        </div>
                        <div class="col">
                            <label for="bodyType">Тип кузова</label>
                            <select class="form-control" id="bodyType" name="bodyType">
                                <option value="">Оберіть тип кузова</option>
                                <option>Седан</option>
                                <option>Універсал</option>
                                <option>Ліфтбек</option>
                                <option>Хетчбек</option>
                                <option>Купе</option>
                                <option>Кабріолет</option>
                                <option>Родстер</option>
                                <option>Лімузин</option>
                                <option disabled></option>
                                <option>Позашляховик</option>
                                <option>Кросовер</option>
                                <option disabled></option>
                                <option>Фургон</option>
                                <option>Мінівен</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="regionObl">Область</label>
                            <input type="text" class="form-control" id="regionObl" placeholder="Область перебування" name="regionObl" value="<?= $this->controller->post->regionObl ?>">
                        </div>
                        <div class="col">
                            <label for="regionCity">Місто</label>
                            <input type="text" class="form-control" id="regionCity" placeholder="Місто у якому перебуваєте" name="regionCity" value="<?= $this->controller->post->regionCity ?>">
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
                                <option value="Ручна / Механіка"> Ручна / Механіка</option>
                                <option value="Автомат"> Автомат</option>
                                <option value="Типтронік"> Типтронік</option>
                                <option value="Робот"> Робот</option>
                                <option value="Варіатор"> Варіатор</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="fuelType">Паливо</label>
                            <select class="form-control" id="fuelType" name="fuelType">
                                <option value=""> Оберіть</option>
                                <option value="Бензин"> Бензин</option>
                                <option value="Газ"> Газ</option>
                                <option value="Газ пропан-бутан / Бензин"> Газ пропан-бутан / Бензин</option>
                                <option value="Газ метан / Бензин"> Газ метан / Бензин</option>
                                <option value="Гібрид (HEV)"> Гібрид (HEV)</option>
                                <option value="Гібрид (PHEV)"> Гібрид (PHEV)</option>
                                <option value="Гібрид (MHEV)"> Гібрид (MHEV)</option>
                                <option value="Дизель"> Дизель</option>
                                <option value="Електро"> Електро</option>
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
                                   placeholder="Об'єм двигуна (літри \ кВт)" name="engineCapacity" value="<?= $this->controller->post->engineCapacity ?>">
                        </div>

                        <div class="col">
                            <label for="horsePowers">Потужність двигуна (к.с.)</label>
                            <input type="number" class="form-control" id="horsePowers"
                                   placeholder="Потужність (кінських сил)" name="horsePower" value="<?= $this->controller->post->horsePower ?>">
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
                                <option value="Повний"> Повний</option>
                                <option value="Передній"> Передній</option>
                                <option value="Задній"> Задній</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="color">Колір</label>
                            <select class="form-control" id="color" name="color">
                                <option value="">Оберіть</option>
                                <option value="Чорний">Чорний</option>
                                <option value="Коричневий">Коричневий</option>
                                <option value="Білий">Білий</option>
                                <option value="Сірий">Сірий</option>
                                <option value="Бежевий">Бежевий</option>
                                <option value="Зелений">Зелений</option>
                                <option value="Жовтий">Жовтий</option>
                                <option value="Синій">Синій</option>
                                <option value="Фіолетовий">Фіолетовий</option>
                                <option value="Помаранчевий">Помаранчевий</option>
                                <option value="Червоний">Червоний</option>
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
                                   placeholder="Номерний знак (АА0000АА)" name="plate" value="<?= $this->controller->post->plate ?>">
                        </div>
                        <div class="col">
                            <label for="vinCode">VIN-код</label>
                            <input type="text" class="form-control" id="vinCode"
                                   placeholder="VIN-код (XXXXYYYYYXXXYXYXYXX)" name="vinCode" value="<?= $this->controller->post->vinCode ?>">
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Створити оголошення</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var carModelInput = document.getElementById('carModel');
        carModelInput.disabled = true;

        document.getElementById('carBrand').addEventListener('change', function() {
            var selectedBrand = this.value;
            carModelInput.disabled = selectedBrand === "Оберіть марку авто";
        });

        var bodyTypeSelect = document.getElementById('condition');
        var millageInput = document.getElementById('millage');

        function toggleMillageInput() {
            if (bodyTypeSelect.value === 'Нове') {
                millageInput.disabled = true;
            } else {
                millageInput.disabled = false;
            }
        }
        toggleMillageInput();

        bodyTypeSelect.addEventListener('change', toggleMillageInput);

        var previewContainer = document.getElementById('preview');

        document.getElementById('addPhoto').addEventListener('click', function() {
            var newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.classList.add('form-control', 'file-input');
            newInput.name = 'carImages[]';
            newInput.style.display = 'none';
            document.querySelector('.form-group').appendChild(newInput);

            newInput.addEventListener('change', handleFileSelect);
            newInput.click();
        });

        function handleFileSelect(event) {
            var files = event.target.files;

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.addEventListener('click', function() {
                        this.remove();
                    });
                    img.addEventListener('mouseover', function() {
                        this.style.filter = 'blur(3px)';
                    });
                    img.addEventListener('mouseout', function() {
                        this.style.filter = 'none';
                    });
                    previewContainer.appendChild(img);
                };

                reader.readAsDataURL(file);
            }
        }

        document.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', handleFileSelect);
        });
    });
</script>
</body>
</html>