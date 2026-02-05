<?php
/**
 * 頁面頭部
 * Page Header
 */
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/../config/config.php';
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/style.css">
    <?php if (isset($extraCSS)): ?>
        <?php foreach ($extraCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Favicon (可選) -->
    <link rel="icon" type="image/x-icon" href="<?php echo ASSETS_PATH; ?>images/favicon.ico">
</head>
<body>
    <!-- 頂部導航列 -->
    <nav class="top-navbar">
        <div class="navbar-left">
            <button class="menu-toggle" id="menuToggle">☰</button>
            <h1 class="navbar-title"><?php echo SITE_NAME; ?></h1>
        </div>
        <div class="navbar-right">
            <div class="user-info">
                <strong><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></strong>
                <span style="color: #606060;"> | <?php echo date('Y-m-d H:i'); ?></span>
            </div>
        </div>
    </nav>
