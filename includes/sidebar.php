<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$navItems = [
    ['file' => 'dashboard.php', 'icon' => 'bi-grid-1x2-fill', 'label' => 'Dashboard'],
    ['file' => 'devices.php', 'icon' => 'bi-hdd-network-fill', 'label' => 'Devices'],
    ['file' => 'monitoring.php', 'icon' => 'bi-activity', 'label' => 'Monitoring'],
    ['file' => 'logs.php', 'icon' => 'bi-clock-history', 'label' => 'Logs'],
    ['file' => 'reports.php', 'icon' => 'bi-file-earmark-bar-graph-fill', 'label' => 'Reports'],
    ['file' => 'settings.php', 'icon' => 'bi-gear-fill', 'label' => 'Settings'],
];
?>
<aside class="app-sidebar" id="appSidebar">
    <a class="brand" href="dashboard.php">
        <span class="brand-icon"><i class="bi bi-router-fill"></i></span>
        <span>
            <strong><?= e(APP_NAME); ?></strong>
            <small>Network Lab</small>
        </span>
    </a>
    <nav class="sidebar-nav">
        <?php foreach ($navItems as $item): ?>
            <a href="<?= e($item['file']); ?>" class="<?= $currentPage === $item['file'] ? 'active' : ''; ?>">
                <i class="bi <?= e($item['icon']); ?>"></i>
                <span><?= e($item['label']); ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>

