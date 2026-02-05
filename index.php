<?php
/**
 * 登入頁面
 * Login Page
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/auth.php';

// 如果已登入，導向首頁
if (isLoggedIn()) {
    header('Location: /home.php');
    exit;
}

$error = '';

// 處理登入表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // 防止 XSS
    $username = htmlspecialchars(trim($username), ENT_QUOTES, 'UTF-8');
    
    if (empty($username) || empty($password)) {
        $error = '請輸入帳號和密碼';
    } elseif (authenticateUser($username, $password)) {
        loginUser($username);
        header('Location: /home.php');
        exit;
    } else {
        $error = '帳號或密碼錯誤';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>登入 - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><?php echo SITE_NAME; ?></h1>
            <p><?php echo SITE_TITLE; ?></p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form class="login-form" method="POST" action="">
            <div class="form-group">
                <label for="username">帳號 Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="請輸入帳號"
                    required
                    autocomplete="username"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="password">密碼 Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="請輸入密碼"
                    required
                    autocomplete="current-password"
                >
            </div>
            
            <button type="submit" class="login-button">登入 Login</button>
        </form>
        
        <div class="login-footer">
            <p>預設帳號：admin / admin123</p>
            <p style="margin-top: 10px; color: #606060;">&copy; <?php echo date('Y'); ?> Semiconductor Industry Portal</p>
        </div>
    </div>
</body>
</html>
