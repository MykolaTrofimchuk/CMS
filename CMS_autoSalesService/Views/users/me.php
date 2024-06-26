<?php
/** @var string $error_message Повідомлення про помилку */

$this->Title = 'Профіль користувача';

$userInfo = \Models\Users::GetUserInfo(\core\Core::get()->session->get('user')['id']);
$userImage = '';
if (isset($userInfo[0]['image_path']) && !empty($userInfo[0]['image_path'])) {
    $imagePath = $userInfo[0]['image_path'];
    if (file_exists($imagePath)) {
        $userImage = '../../../../../'. $imagePath;
    }
}

if (empty($userImage)) {
    $userImage = '../../../src/resourses/user-default.png';
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
        .custom-file-upload {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .custom-file-upload:hover {
            background-color: #0056b3;
        }

        .custom-file-upload i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="main-body">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/users">User</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Profile</li>
            </ol>
        </nav>
        <!-- /Breadcrumb -->

        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img src="<?php echo "../../../../../". $userImage; ?>" alt="User" class="rounded-circle" width="150">
                            <div class="mt-3">
                                <h4><?php echo $userInfo[0]['first_name'] ." ". $userInfo[0]['last_name']; ?></h4>
                                <p class="text-secondary mb-1 "><?php echo "ID ". $userInfo[0]['id']; ?></p>
                                <p class="text-muted font-size-sm">
                                    <?php
                                    switch ($userInfo[0]['role']) :
                                        case 'admin':
                                            echo "Продавець (адміністратор)";
                                            break;
                                        default:
                                            echo "Продавець";
                                            break;
                                    endswitch;
                                    ?>
                                </p>
                                <form action="/users/editphoto" method="post" enctype="multipart/form-data">
                                    <button type="button" class="custom-file-upload" onclick="document.getElementById('file-upload').click();">
                                        <i class="fas fa-cloud-upload-alt"></i> Змінити Фото
                                    </button>
                                    <input id="file-upload" name="file-upload" type="file" accept="image/jpeg, image/png, image/gif" style="display: none;" onchange="handleFileChange()"/>
                                    <button id="upload-button" type="submit" class="btn btn-primary" style="display: none;">Завантажити зміни</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram mr-2 icon-inline text-danger"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>Instagram</h6>
                            <span class="text-secondary"><?= '@'. $userInfo[0]['login']?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter mr-2 icon-inline text-info"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>Twitter</h6>
                            <span class="text-secondary"><?= '@'. $userInfo[0]['login']?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook mr-2 icon-inline text-primary"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>Facebook</h6>
                            <span class="text-secondary"><?= '@'. $userInfo[0]['login']?></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Прізвище</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $userInfo[0]['last_name']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Ім'я</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $userInfo[0]['first_name']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $userInfo[0]['email']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Phone</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $userInfo[0]['phone_number']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Address</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $userInfo[0]['region']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <a class="btn btn-info" target="__blank" href="/users/edit/<?= $userInfo[0]['id']?>">Змінити дані</a>
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-info" target="__blank" href="/users/editpassword">Змінити пароль</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    function handleFileChange() {
        var fileUpload = document.getElementById('file-upload');
        var uploadButton = document.getElementById('upload-button');
        if (fileUpload.files && fileUpload.files.length > 0) {
            uploadButton.style.display = 'inline-block';
        } else {
            uploadButton.style.display = 'none';
        }
    }
</script>
</body>
</html>
