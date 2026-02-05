<?php
/**
 * 驗證邏輯
 * Authentication Logic
 */

require_once __DIR__ . '/config.php';

/**
 * 驗證使用者登入
 * @param string $username
 * @param string $password
 * @return bool
 */
function authenticateUser($username, $password) {
    // 示範用途：預設帳號密碼
    // 正式環境請改為資料庫驗證或 LDAP/SSO 整合
    $validUsers = [
        'admin' => password_hash('admin123', PASSWORD_DEFAULT)
    ];
    
    if (isset($validUsers[$username])) {
        return password_verify($password, $validUsers[$username]);
    }
    
    return false;
}

/**
 * 檢查是否已登入
 * @return bool
 */
function isLoggedIn() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
        return false;
    }
    
    // 檢查 Session 是否過期
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        logoutUser();
        return false;
    }
    
    // 更新最後活動時間
    $_SESSION['last_activity'] = time();
    
    return true;
}

/**
 * 執行登入
 * @param string $username
 */
function loginUser($username) {
    session_regenerate_id(true); // 防止 Session Fixation
    $_SESSION['user_id'] = uniqid('user_', true);
    $_SESSION['username'] = $username;
    $_SESSION['login_time'] = time();
    $_SESSION['last_activity'] = time();
}

/**
 * 執行登出
 */
function logoutUser() {
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    
    session_destroy();
}

/**
 * 要求登入（未登入則導向登入頁）
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /index.php');
        exit;
    }
}
?>
