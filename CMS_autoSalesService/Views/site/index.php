<?php

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
<body >

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-body p-5">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col">
                                <div class="btn-group d-flex" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="options" id="optionAll" autocomplete="off" value="" checked>
                                    <label class="btn btn-light flex-fill" for="optionAll"><span class="text-light">&#10003;</span> Усі</label>
                                    <input type="radio" class="btn-check" name="options" id="optionOld" autocomplete="off" value="З пробігом">
                                    <label class="btn btn-light flex-fill" for="optionOld"><span class="text-light">&#10003;</span>Вживані</label>
                                    <input type="radio" class="btn-check" name="options" id="optionNew" autocomplete="off" value="Нове">
                                    <label class="btn btn-light flex-fill" for="optionNew"><span class="text-light">&#10003;</span>Нові</label>
                                </div>
                            </div>
                            <div class="col">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <select class="form-control" id="carBrand" name="brand" onselect="<?= $this->controller->post->brand ?>">
                                    <option value="">Оберіть марку авто</option>
                                    <?php
                                    $brands = \Models\FilterModelBrands::FindAllBrandUnique();
                                    sort($brands);
                                    foreach ($brands as $brand) {
                                        echo "<option value='$brand'>$brand</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <select class="form-control" id="carModel" name="model" onselect="<?= $this->controller->post->model ?>">
                                    <option value="">Спочатку оберіть марку авто</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <select class="form-control" id="bodyType" name="body_type">
                                    <option value="">Тип кузова</option>
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
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <select class="form-control" id="modelYearFrom" name="modelYearFrom">
                                            <option value="">Рік від:</option>
                                            <?php for ($i = date('Y') + 1; $i >= 1900; $i--): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" id="modelYearTo" name="modelYearTo">
                                            <option value="">Рік до:</option>
                                            <?php for ($i = date('Y') + 1; $i >= 1900; $i--): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <select class="form-control" id="regionObl" name="region">
                                    <option value="">Регіон (область):</option>
                                    <option value="Київська">Київська</option>
                                    <option value="Вінницька">Вінницька</option>
                                    <option value="Волинська">Волинська</option>
                                    <option value="Дніпропетровська">Дніпропетровська</option>
                                    <option value="Донецька">Донецька</option>
                                    <option value="Житомирська">Житомирська</option>
                                    <option value="Закарпатська">Закарпатська</option>
                                    <option value="Запорізька">Запорізька</option>
                                    <option value="Івано-Франківська">Івано-Франківська</option>
                                    <option value="Кіровоградська">Кіровоградська</option>
                                    <option value="Луганська">Луганська</option>
                                    <option value="Львівська">Львівська</option>
                                    <option value="Миколаївська">Миколаївська</option>
                                    <option value="Одеська">Одеська</option>
                                    <option value="Полтавська">Полтавська</option>
                                    <option value="Рівненська">Рівненська</option>
                                    <option value="Сумська">Сумська</option>
                                    <option value="Тернопільська">Тернопільська</option>
                                    <option value="Харківська">Харківська</option>
                                    <option value="Херсонська">Херсонська</option>
                                    <option value="Хмельницька">Хмельницька</option>
                                    <option value="Черкаська">Черкаська</option>
                                    <option value="Чернівецька">Чернівецька</option>
                                    <option value="Чернігівська">Чернігівська</option>
                                </select>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <input type="number" class="form-control" placeholder="Ціна $, Від:" min="0" name="priceFrom">
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" placeholder="Ціна $, До:" min="0" name="priceTo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <button type="button" class="btn btn-light btn-expand-search w-100">Розширений пошук</button>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-secondary w-100">Пошук</button>
                            </div>
                        </div>

                        <div class="row mt-3" id="additionalFields" style="display: none;">
                            <div class="col mt-3 d-flex justify-content-between">
                                <label>Пробіг</label>
                            </div>
                            <div class="col d-flex justify-content-between">
                                <input type="number" class="form-control" placeholder="Від: " min="0" name="millageFrom">
                                <input type="number" class="form-control" placeholder="До:" min="0" name="millageTo">
                            </div>
                            <div class="col mt-3 d-flex justify-content-between">
                                <label>Тип палива: </label>
                                <label>Коробка передач: </label>
                            </div>
                            <div class="col d-flex justify-content-between">
                                <select class="form-control" id="fuelType" name="fuel_type">
                                    <option value="">Паливо</option>
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
                                <select class="form-control" id="driveType" name="transmission">
                                    <option value="">Трансмісія</option>
                                    <option value="Ручна / Механіка"> Ручна / Механіка</option>
                                    <option value="Автомат"> Автомат</option>
                                    <option value="Типтронік"> Типтронік</option>
                                    <option value="Робот"> Робот</option>
                                    <option value="Варіатор"> Варіатор</option>
                                </select>
                            </div>
                            <div class="col mt-3 d-flex justify-content-between">
                                <label>Об'єм двигуна</label>
                            </div>
                            <div class="col d-flex justify-content-between">
                                <input type="number" class="form-control" placeholder="Від: " min="0" name="engineCapacityFrom">
                                <input type="number" class="form-control" placeholder="До:" min="0" name="engineCapacityTo">
                            </div>
                            <div class="col mt-3 d-flex justify-content-between">
                                <label>Потужність (к.с.)</label>
                            </div>
                            <div class="col d-flex justify-content-between">
                                <input type="number" class="form-control" placeholder="Від: " min="0" name="horsePowerFrom">
                                <input type="number" class="form-control" placeholder="До:" min="0" name="horsePowerTo">
                            </div>
                            <div class="col mt-3 d-flex justify-content-between">
                                <label>Привід:</label>
                            </div>
                            <div class="col d-flex justify-content-between">
                                <select class="form-control" id="driveType" name="drive">
                                    <option value=""> Оберіть</option>
                                    <option value="Повний"> Повний</option>
                                    <option value="Передній"> Передній</option>
                                    <option value="Задній"> Задній</option>
                                </select>
                            </div>
                            <div class="col mt-3 d-flex justify-content-between">
                                <label>Колір</label>
                            </div>
                            <div class="col d-flex justify-content-between">
                                <select class="form-control" id="color" name="color">
                                    <option value="">Колір:</option>
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
                            <div class="col mt-3 d-flex justify-content-between">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="isPlate" name="plate">
                                    <label class="form-check-label" for="isPlate">Наявний держ.номер</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="isVinCode" name="vin_code">
                                    <label class="form-check-label" for="isVinCode">Наявний VIN-код</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var additionalFields = document.getElementById('additionalFields');
        var btnExpandSearch = document.querySelector('.btn-expand-search');

        btnExpandSearch.addEventListener('click', function() {
            if (additionalFields.style.display === 'none') {
                additionalFields.style.display = 'block';
            } else {
                additionalFields.style.display = 'none';
            }
        });

        var carModelInput = document.getElementById('carModel');

        document.getElementById('carBrand').addEventListener('change', function() {
            var selectedBrand = this.value;
            carModelInput.disabled = selectedBrand === "Оберіть марку авто";
        });

        document.getElementById("carBrand").addEventListener("change", function() {
            var selectedBrand = this.value;
            selectedBrand = selectedBrand.replace(/\s/g, '%20');
            var carModelSelect = document.getElementById("carModel");
            carModelSelect.innerHTML = '<option value="">Завантаження...</option>';
            fetch('announcements/selectmodelsbybrand/' + encodeURIComponent(selectedBrand))
                .then(response => response.text())
                .then(data => {
                    var endIndex = data.indexOf("]");
                    if (endIndex !== -1) {
                        data = data.slice(0, endIndex + 1);
                    }
                    var models = data.match(/"(.*?)"/g);
                    carModelSelect.innerHTML = '<option value="">Оберіть модель авто</option>';
                    if (models) {
                        models.forEach(function(model) {
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
