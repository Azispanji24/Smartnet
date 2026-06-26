<?php $user = current_user(); ?>
<header class="top-navbar">
    <button class="icon-button d-lg-none" type="button" data-sidebar-toggle aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
    </button>
    <div>
        <h1><?= e($pageTitle ?? APP_NAME); ?></h1>
        <p><?= e($pageSubtitle ?? APP_DESCRIPTION); ?></p>
    </div>
    <div class="top-navbar-actions">
        <span class="user-chip">
            <i class="bi bi-person-circle"></i>
            <?= e($user['username']); ?>
        </span>
        <a class="btn btn-outline-primary btn-sm" href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>
    </div>
</header>

