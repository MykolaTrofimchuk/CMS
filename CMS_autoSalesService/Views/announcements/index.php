<?php
$this->Title = '';

$announcement = $GLOBALS['announcement'];
$vehicle = $GLOBALS['vehicle'];
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
        .big-photo {
            width: 100%;
            height: auto;
        }

        .small-photos {
            max-height: 300px;
            overflow-y: auto;
        }

        .small-photos {
            flex: 0 0 calc(33.33% - 10px);
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<section class="py-5" style="margin-top: -100px;">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12 mb-3">
                        <img class="card-img-top big-photo" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                    </div>
                    <div class="col-12">
                        <div class="row small-photos">
                            <div class="col-md-3 mb-2">
                                <img class="card-img-top" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                            </div>
                            <div class="col-md-3 mb-2">
                                <img class="card-img-top" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                            </div>
                            <div class="col-md-3 mb-2">
                                <img class="card-img-top" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                            </div>
                            <div class="col-md-3 mb-2">
                                <img class="card-img-top" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                            </div>
                            <div class="col-md-3 mb-2">
                                <img class="card-img-top" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="margin-top: -250px;">
                <?php if (isset($announcement)): ?>
                    <div class="small mb-1"><?= htmlspecialchars($announcement->publicationDate) ?></div>
                    <h1 class="display-5 fw-bolder"><?= htmlspecialchars($announcement->title) ?></h1>
                    <div class="fs-3 mb-5">
                        <span>$ <?= htmlspecialchars(number_format($announcement->price, 0)) ?></span>
                    </div>
                    <div class="d-flex flex-wrap fs-5 mb-5">
                        <div class="d-flex w-100 justify-content-between">
                            <span><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                       xmlns="http://www.w3.org/2000/svg" class="common-icon mr-8"><path
                                            d="M11 3.80385C10.0879 3.27724 9.05322 3 8 3C6.94678 3 5.91212 3.27724 5 3.80385C4.08788 4.33046 3.33046 5.08789 2.80385 6C2.27724 6.91212 2 7.94678 2 9C2 10.0532 2.27724 11.0879 2.80385 12C3.07999 12.4783 2.91612 13.0899 2.43782 13.366C1.95953 13.6422 1.34794 13.4783 1.0718 13C0.369651 11.7838 0 10.4043 0 9C0 7.59571 0.36965 6.21615 1.0718 5C1.77394 3.78385 2.78385 2.77394 4 2.0718C5.21615 1.36965 6.59571 1 8 1C9.40429 1 10.7838 1.36965 12 2.0718C13.2162 2.77394 14.2261 3.78385 14.9282 5C15.6303 6.21615 16 7.59571 16 9C16 10.4043 15.6304 11.7838 14.9282 13C14.6521 13.4783 14.0405 13.6422 13.5622 13.366C13.0839 13.0899 12.92 12.4783 13.1962 12C13.7228 11.0879 14 10.0532 14 9C14 7.94678 13.7228 6.91212 13.1962 6C12.6695 5.08788 11.9121 4.33046 11 3.80385Z"
                                            fill="#1F2024"></path><path
                                            d="M8.00002 11C9.10459 11 10 10.1046 10 9.00002C10 7.89545 9.10459 7.00002 8.00002 7.00002C7.7952 7.00002 7.59756 7.03081 7.4115 7.08801C7.36317 6.98104 7.29504 6.88083 7.20713 6.79291L6.20713 5.79291C5.8166 5.40239 5.18344 5.40239 4.79291 5.79291C4.40239 6.18344 4.40239 6.8166 4.79291 7.20713L5.79291 8.20713C5.88083 8.29504 5.98104 8.36317 6.08801 8.4115C6.03081 8.59756 6.00002 8.7952 6.00002 9.00002C6.00002 10.1046 6.89545 11 8.00002 11Z"
                                            fill="#1F2024"></path></svg>
                                <?= htmlspecialchars(number_format($vehicle->millage)) . " км" ?></span>
                            <span><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                       xmlns="http://www.w3.org/2000/svg" class="common-icon mr-8"><path
                                            fill-rule="evenodd" clip-rule="evenodd"
                                            d="M2 14V3C2 1.34315 3.34315 0 5 0H9C10.6569 0 12 1.34315 12 3V11L14 11V6.4142L13.2929 5.70709C12.9024 5.31656 12.9024 4.6834 13.2929 4.29288C13.6834 3.90236 14.3166 3.90237 14.7071 4.29291L15.5606 5.14646C15.8419 5.42776 16 5.80929 16 6.2071V11.5C16 12.3284 15.3284 13 14.5 13L12 13V14C12.5523 14 13 14.4477 13 15C13 15.5523 12.5523 16 12 16H2C1.44772 16 1 15.5523 1 15C1 14.4477 1.44772 14 2 14ZM5 2H9C9.55228 2 10 2.44772 10 3V5H4V3C4 2.44772 4.44772 2 5 2ZM4 7H10V14H4V7Z"
                                            fill="#1F2024"></path></svg>
                                <?= htmlspecialchars($vehicle->fuel_type) . ", " . htmlspecialchars($vehicle->engine_capacity) . " л (" .
                                htmlspecialchars($vehicle->horse_power) . " к.с.)" ?></span>
                        </div>
                        <div class="d-flex w-100 justify-content-between">
                            <span><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                       xmlns="http://www.w3.org/2000/svg" class="common-icon mr-8"><path
                                            fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.48346 10L10.0809 11.3939C10.2984 11.9015 10.8863 12.1367 11.3939 11.9191C11.9015 11.7016 12.1367 11.1137 11.9191 10.6061L9.10297 4.03502C8.68737 3.06528 7.31262 3.06529 6.89703 4.03502L4.08085 10.6061C3.8633 11.1137 4.09845 11.7016 4.60608 11.9191C5.11371 12.1367 5.70159 11.9015 5.91915 11.3939L6.51654 10H9.48346ZM8.62632 8L8 6.53859L7.37368 8H8.62632Z"
                                            fill="#1F2024"></path><path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M8 16C12.4183 16 16 12.4183 16 8C16 3.58172 12.4183 0 8 0C3.58172 0 0 3.58172 0 8C0 12.4183 3.58172 16 8 16ZM8 14C11.3137 14 14 11.3137 14 8C14 4.68629 11.3137 2 8 2C4.68629 2 2 4.68629 2 8C2 11.3137 4.68629 14 8 14Z"
                                                                        fill="#1F2024"></path></svg>
                                <?= htmlspecialchars($vehicle->transmission) ?></span>
                            <span><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                       xmlns="http://www.w3.org/2000/svg" class="common-icon mr-8"><path
                                            fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11 7C11 8.65685 9.65685 10 8 10C6.34315 10 5 8.65685 5 7C5 5.34315 6.34315 4 8 4C9.65685 4 11 5.34315 11 7ZM9 7C9 7.55228 8.55228 8 8 8C7.44772 8 7 7.55228 7 7C7 6.44772 7.44772 6 8 6C8.55228 6 9 6.44772 9 7Z"
                                            fill="#1F2024"></path><path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M15 7C15 11 8 16 8 16C8 16 1 11 1 7C1 3.13401 4.13401 0 8 0C11.866 0 15 3.13401 15 7ZM13 7C13 7.44952 12.7871 8.12714 12.2189 9.02C11.6702 9.88226 10.9049 10.7667 10.0858 11.5858C9.34429 12.3273 8.59649 12.9779 8 13.4675C7.40351 12.9779 6.65571 12.3273 5.91421 11.5858C5.09513 10.7667 4.32979 9.88226 3.78107 9.02C3.21289 8.12714 3 7.44952 3 7C3 4.23858 5.23858 2 8 2C10.7614 2 13 4.23858 13 7Z"
                                                                        fill="#1F2024"></path></svg>
                                <?= htmlspecialchars($vehicle->region) ?></span>
                        </div>

                    </div>
                    <p class="lead"><?= htmlspecialchars($announcement->description) ?></p>

                    <div class="d-flex flex-wrap">
                        <?php if (!is_null($vehicle->plate)): ?>
                            <div class="border rounded p-3 mb-3 me-3">
                                <span><?= htmlspecialchars($vehicle->plate) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!is_null($vehicle->vin_code)): ?>
                            <div class="border rounded p-3 mb-3 me-3">
                                <span><?= htmlspecialchars($vehicle->vin_code) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!is_null($vehicle->body_type)): ?>
                            <div class="border rounded p-3 mb-3">
                                <span><?= htmlspecialchars($vehicle->body_type) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="d-flex">
                    <button class="btn btn-outline-dark flex-shrink-0" type="button">
                        <i class="bi-cart-fill me-1"></i>
                        В обрані
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>
