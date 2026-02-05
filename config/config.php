<?php
/**
 * 全域設定檔
 * Global Configuration File
 */

// 網站基本設定
define('SITE_NAME', '半導體產業入口網站');
define('SITE_TITLE', 'Semiconductor Industry Portal');

// Session 設定
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // 如使用 HTTPS 請設為 1
session_start();

// 時區設定
date_default_timezone_set('Asia/Taipei');

// 錯誤報告設定（開發環境）
// 正式環境請改為：error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 資料庫設定（預留）
define('DB_ENABLED', false); // 設為 true 啟用資料庫連線

// 安全性設定
define('SESSION_TIMEOUT', 3600); // Session 過期時間（秒）

// 路徑設定
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_PATH', '/assets/');
?>
