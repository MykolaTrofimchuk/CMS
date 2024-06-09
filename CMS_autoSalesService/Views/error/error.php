<?php
/** @var string $errorCode */
/** @var string $error_message Повідомлення про помилку */

$errorCode = isset($errorCode) ? $errorCode : '';
?>
    <div class="modal-body p-5 pt-0">
        <div class="alert alert-danger" role="alert">
            <h1>Error <?= $errorCode ?></h1>
            <p>Sorry, the page you are looking for does not exist.</p>
            <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>
        </div>
    </div>