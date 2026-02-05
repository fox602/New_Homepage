<?php
/**
 * 資料庫連線設定（預留）
 * Database Connection Configuration (Reserved)
 */

// 資料庫類型選擇：'mysql' 或 'mssql'
define('DB_TYPE', 'mysql');

// MySQL 設定
define('DB_HOST', 'localhost');
define('DB_NAME', 'semiconductor_portal');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// MSSQL 設定（如使用 MSSQL，請取消註解並填入正確資訊）
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'semiconductor_portal');
// define('DB_USER', 'sa');
// define('DB_PASS', '');

/**
 * 取得資料庫連線
 * @return PDO|null
 */
function getDBConnection() {
    if (!DB_ENABLED) {
        return null;
    }
    
    try {
        if (DB_TYPE === 'mysql') {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            return new PDO($dsn, DB_USER, DB_PASS, $options);
        } else if (DB_TYPE === 'mssql') {
            $dsn = "sqlsrv:Server=" . DB_HOST . ";Database=" . DB_NAME;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            return new PDO($dsn, DB_USER, DB_PASS, $options);
        }
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        return null;
    }
    
    return null;
}
?>
