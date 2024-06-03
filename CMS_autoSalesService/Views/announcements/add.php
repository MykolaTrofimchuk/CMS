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
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="modal-content rounded-4 shadow">
    <div class="modal-body p-5 pt-0">
        <form>
            <div class="form-row">
                <h3>Додайте фото автомобіля: </h3>
                <div class="form-group col">
                    <div id="fileInputContainer">
                        <input type="file" class="form-control file-input" name="carImages[]">
                    </div>
                    <button type="button" id="addPhoto" class="btn btn-secondary mt-2">Додати фото</button>
                </div>
                <div id="preview" class="preview"></div>
            </div>
            <br><br><br>
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="titleAnnouncement"
                       placeholder="Заголовок оголошення" name="titleAnnouncement">
                <label for="titleAnnouncement">Заголовок оголошення</label>
            </div>
            <div class="form-group">
                <label for="description">Опис</label>
                <textarea class="form-control rounded-3" id="description" rows="5"
                          placeholder="Основний опис автомобіля..."></textarea>
            </div>
            <br>
            <div class="form-row">
                <h3>Основна інформація про автомобіль: </h3>
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="carBrand">Марка</label>
                            <select class="form-control" id="carBrand">
                                <option>Оберіть марку авто</option>
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
                            <input type="text" class="form-control" id="carModel" placeholder="модель авто">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="modelYear">Рік випуску</label>
                            <input type="date" class="form-control" id="modelYear" placeholder="Рік випуску">
                        </div>
                        <div class="col">
                            <label for="millage">Пробіг (км)</label>
                            <input type="number" class="form-control" id="millage" placeholder="пробіг авто (у км)">
                        </div>
                        <div class="col">
                            <label for="bodyType">Тип кузова</label>
                            <select class="form-control" id="bodyType">
                                <option>Оберіть тип кузова</option>
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
                            <input type="text" class="form-control" id="regionObl" placeholder="Область перебування">
                        </div>
                        <div class="col">
                            <label for="regionCity">Місто</label>
                            <input type="text" class="form-control" id="regionCity" placeholder="Місто у якому перебуваєте">
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
                            <select class="form-control" id="driveType">
                                <option value="0"> Оберіть</option>
                                <option value="1"> Ручна / Механіка</option>
                                <option value="2"> Автомат</option>
                                <option value="3"> Типтронік</option>
                                <option value="4"> Робот</option>
                                <option value="5"> Варіатор</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="fuelType">Паливо</label>
                            <select class="form-control" id="fuelType">
                                <option value="0"> Оберіть</option>
                                <option value="1"> Бензин</option>
                                <option value="3"> Газ</option>
                                <option value="4"> Газ пропан-бутан / Бензин</option>
                                <option value="8"> Газ метан / Бензин</option>
                                <option value="5"> Гібрид (HEV)</option>
                                <option value="10"> Гібрид (PHEV)</option>
                                <option value="11"> Гібрид (MHEV)</option>
                                <option value="2"> Дизель</option>
                                <option value="6"> Електро</option>
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
                                   placeholder="Об'єм двигуна (літри)">
                        </div>
                        <div class="col">
                            <label for="horsePowers">Потужність двигуна (к.с.)</label>
                            <input type="number" class="form-control" id="horsePowers"
                                   placeholder="Потужність (кінських сил)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <div class="row">
                        <div class="col">
                            <label for="driveType">Привід</label>
                            <select class="form-control" id="driveType">
                                <option value="0"> Оберіть</option>
                                <option value="1"> Повний</option>
                                <option value="2"> Передній</option>
                                <option value="3"> Задній</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="color">Колір</label>
                            <select class="form-control" id="color">
                                <option value="0">Оберіть</option>
                                <option value="1">Чорний</option>
                                <option value="6">Коричневий</option>
                                <option value="3">Білий</option>
                                <option value="2">Сірий</option>
                                <option value="6">Бежевий</option>
                                <option value="4">Зелений</option>
                                <option value="5">Жовтий</option>
                                <option value="8">Синій</option>
                                <option value="8">Фіолетовий</option>
                                <option value="10">Помаранчевий</option>
                                <option value="11">Червоний</option>
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
                                   placeholder="Номерний знак (АА0000АА)">
                        </div>
                        <div class="col">
                            <label for="vinCode">VIN-код</label>
                            <input type="text" class="form-control" id="vinCode"
                                   placeholder="VIN-код (XXXXYYYYYXXXYXYXYXX)">
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

        document.getElementById('addPhoto').addEventListener('click', function() {
            var newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.classList.add('form-control', 'file-input');
            newInput.name = 'carImages[]';
            document.getElementById('fileInputContainer').appendChild(newInput);

            newInput.addEventListener('change', handleFileSelect);
        });

        function handleFileSelect(event) {
            var files = event.target.files;
            var previewContainer = document.getElementById('preview');

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
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