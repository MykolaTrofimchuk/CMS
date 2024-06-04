<?php if (isset($GLOBALS['successMessage'])): ?>
    <div class="modal-content rounded-4 shadow">
        <div class="modal-body p-5 pt-0">
            <div class="alert alert-info" role="alert">
                <?= $GLOBALS['successMessage'] ?>
            </div>
        </div>
    </div>
<?php endif; ?>
