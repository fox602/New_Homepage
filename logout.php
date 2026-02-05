<?php
/**
 * 登出處理
 * Logout Handler
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/auth.php';

// 執行登出
logoutUser();

// 導向登入頁面
header('Location: /index.php');
exit;
?>
