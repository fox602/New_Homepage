# 半導體產業入口網站

Semiconductor Industry Portal - 專業的半導體產業風格入口網站

## 🚀 專案簡介

這是一個運行於 IIS Web Server 的專業半導體產業風格入口網站，採用 PHP 開發。提供完整的登入驗證系統、儀表板、機台狀態監控等功能，具備現代化的深色主題與科技藍色調設計。

## ✨ 主要特色

- 🎨 **半導體產業風格設計**：深色主題搭配科技藍色調
- 🔐 **完整登入驗證系統**：Session 管理與安全機制
- 📊 **儀表板功能**：重點機台、WIP 趨勢圖表、機況監控
- 📈 **Chart.js 圖表**：互動式數據視覺化
- 📱 **響應式設計**：支援桌面與平板裝置
- 🎯 **左側抽屜選單**：可展開/收合的導航系統

## 📋 系統需求

### 伺服器環境
- **Web Server**: Microsoft IIS 7.0 或更新版本
- **PHP**: 7.4 或更新版本
- **資料庫**（可選）: MySQL 5.7+ 或 MSSQL Server 2016+

### PHP 擴充套件
- php_pdo
- php_pdo_mysql（使用 MySQL 時）
- php_pdo_sqlsrv（使用 MSSQL 時）
- php_session
- php_json

## 🛠️ 安裝步驟

### 1. 安裝 IIS 與 PHP

#### 在 Windows Server 上安裝 IIS
```powershell
# 使用 PowerShell（管理員權限）
Install-WindowsFeature -name Web-Server -IncludeManagementTools
```

#### 安裝 PHP
1. 下載 PHP for Windows：https://windows.php.net/download/
2. 解壓縮到 `C:\PHP`
3. 複製 `php.ini-production` 為 `php.ini`
4. 編輯 `php.ini`：
   ```ini
   ; 基本設定
   extension_dir = "ext"
   date.timezone = Asia/Taipei
   
   ; 啟用必要擴充
   extension=pdo_mysql
   extension=pdo_sqlsrv
   extension=openssl
   
   ; Session 設定
   session.save_path = "C:\PHP\sessions"
   session.cookie_httponly = 1
   session.use_only_cookies = 1
   
   ; 錯誤報告（正式環境請關閉）
   display_errors = Off
   log_errors = On
   error_log = "C:\PHP\logs\php_errors.log"
   ```

5. 建立 sessions 和 logs 目錄：
   ```powershell
   mkdir C:\PHP\sessions
   mkdir C:\PHP\logs
   ```

#### 設定 IIS 的 PHP Handler
1. 開啟 IIS 管理員
2. 選擇伺服器節點 → Handler Mappings
3. 點選「Add Module Mapping」
4. 設定：
   - Request path: `*.php`
   - Module: `FastCgiModule`
   - Executable: `C:\PHP\php-cgi.exe`
   - Name: `PHP_via_FastCGI`

### 2. 部署網站

#### 複製檔案
```powershell
# 將專案檔案複製到 IIS 網站目錄
xcopy /E /I .\* C:\inetpub\wwwroot\semiconductor_portal\
```

#### 設定 IIS 網站
1. 開啟 IIS 管理員
2. 右鍵「Sites」→「Add Website」
3. 設定：
   - Site name: `Semiconductor Portal`
   - Physical path: `C:\inetpub\wwwroot\semiconductor_portal`
   - Binding: HTTP, Port 80（或其他埠號）
4. 應用程式集區設定：
   - .NET CLR version: `No Managed Code`
   - Managed pipeline mode: `Integrated`

#### 設定資料夾權限
```powershell
# 給予 IIS_IUSRS 讀取權限
icacls "C:\inetpub\wwwroot\semiconductor_portal" /grant "IIS_IUSRS:(OI)(CI)RX" /T
```

### 3. 設定檔調整

#### config/config.php
```php
// 正式環境設定
error_reporting(0);
ini_set('display_errors', 0);

// 如使用 HTTPS
ini_set('session.cookie_secure', 1);
```

#### config/db.php（如需使用資料庫）
```php
// 修改資料庫連線資訊
define('DB_ENABLED', true);
define('DB_TYPE', 'mysql'); // 或 'mssql'
define('DB_HOST', 'your-database-host');
define('DB_NAME', 'semiconductor_portal');
define('DB_USER', 'your-username');
define('DB_PASS', 'your-password');
```

### 4. 測試安裝

1. 開啟瀏覽器
2. 前往 `http://your-server-ip/` 或 `http://your-domain/`
3. 應該會看到登入頁面
4. 使用預設帳號登入：
   - 帳號：`admin`
   - 密碼：`admin123`

## 📁 專案結構

```
New_Homepage/
├── index.php                    # 登入頁面
├── home.php                     # 首頁儀表板
├── logout.php                   # 登出處理
├── config/                      # 設定檔目錄
│   ├── config.php              # 全域設定
│   ├── auth.php                # 驗證邏輯
│   └── db.php                  # 資料庫連線設定
├── includes/                    # 共用元件
│   ├── header.php              # 頁面頭部
│   ├── sidebar.php             # 左側選單
│   └── footer.php              # 頁面底部
├── assets/                      # 靜態資源
│   ├── css/                    # 樣式表
│   │   ├── style.css           # 主要樣式
│   │   └── login.css           # 登入頁樣式
│   ├── js/                     # JavaScript
│   │   ├── main.js             # 主要功能
│   │   ├── sidebar.js          # 側邊欄互動
│   │   └── chart.js            # 圖表功能
│   └── images/                 # 圖片資源
├── pages/                       # 功能頁面
│   ├── equipment_status.php    # ET1/2 機況頁面
│   └── equipment_et3.php       # ET3 機況頁面
├── api/                         # API 端點
│   └── get_wip_data.php        # WIP 數據 API
└── README.md                    # 本文件
```

## 🎨 設計規範

### 色彩配置
- 主背景：`#0a0e27` (深藍黑)
- 次背景：`#1a1f3a` (深藍)
- 卡片背景：`#1e2442` (卡片藍)
- 主要藍色：`#0066cc` (科技藍)
- 深藍色：`#003d7a` (深藍)
- 成功色：`#6bcf7f` (綠)
- 警示色：`#ffd93d` (黃)
- 危險色：`#ff6b6b` (紅)

### 字型
- 主要字型：Segoe UI, Tahoma, Geneva, Verdana, sans-serif

## 🔐 安全性說明

### 目前實作的安全機制
- ✅ Session 管理與驗證
- ✅ Session Timeout 機制
- ✅ Session Regeneration（防止 Session Fixation）
- ✅ XSS 防護（htmlspecialchars）
- ✅ Password Hashing（password_hash/password_verify）
- ✅ HTTP Only Cookies

### 建議加強項目
- 🔒 啟用 HTTPS（SSL/TLS）
- 🔒 實作 CSRF Token
- 🔒 實作帳號鎖定機制（防暴力破解）
- 🔒 整合 LDAP 或 SSO
- 🔒 實作權限管理系統
- 🔒 加入操作日誌記錄

### 重要安全提醒

⚠️ **預設帳號密碼僅供測試使用，正式環境請務必修改！**

修改方式（編輯 `config/auth.php`）：
```php
function authenticateUser($username, $password) {
    // 方法 1: 修改預設帳號密碼
    $validUsers = [
        'your_admin' => password_hash('your_secure_password', PASSWORD_DEFAULT)
    ];
    
    // 方法 2: 改為從資料庫驗證（建議）
    // $db = getDBConnection();
    // $stmt = $db->prepare("SELECT password FROM users WHERE username = ?");
    // ...
}
```

## 📊 功能說明

### 1. 登入系統
- Session 驗證機制
- 自動登出功能
- 未登入自動導向登入頁

### 2. 首頁儀表板
- **今日重點機台**：顯示關鍵機台狀態與指標
- **WIP 趨勢圖表**：過去 7 天的 WIP 數據視覺化
- **機況現況**：即時狀態監控
- **快速連結**：導向詳細機況頁面

### 3. 機台管理頁面
- ET1/2 機況監控
- ET3 檢測設備監控
- 詳細設備參數顯示

### 4. 左側選單
- 可展開/收合
- 當前頁面高亮
- 響應式設計

## 🔄 後續開發建議

### 資料庫整合
```sql
-- 使用者表
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 機台資料表
CREATE TABLE equipment (
    id INT PRIMARY KEY AUTO_INCREMENT,
    equipment_id VARCHAR(50) UNIQUE NOT NULL,
    equipment_name VARCHAR(100),
    status ENUM('online', 'warning', 'offline'),
    utilization DECIMAL(5,2),
    last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- WIP 數據表
CREATE TABLE wip_data (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date DATE NOT NULL,
    wip_count INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 即時更新功能
- 使用 AJAX 輪詢或 WebSocket
- 實作自動刷新機制
- 加入推送通知

### 報表系統
- 匯出 Excel/PDF 報表
- 自定義日期範圍查詢
- 數據比較分析

## 🐛 故障排除

### 問題：無法看到 PHP 頁面，顯示 404 錯誤
**解決方法**：
1. 確認 PHP Handler Mapping 是否正確設定
2. 檢查 IIS 應用程式集區是否啟動
3. 確認檔案權限設定

### 問題：Session 無法正常運作
**解決方法**：
1. 確認 `session.save_path` 目錄存在且有寫入權限
2. 檢查 `php.ini` 中的 session 設定
3. 確認 `config/config.php` 中有 `session_start()`

### 問題：圖表無法顯示
**解決方法**：
1. 確認網路連線正常（Chart.js 使用 CDN）
2. 檢查瀏覽器 Console 是否有 JavaScript 錯誤
3. 確認 `assets/js/chart.js` 已正確載入

### 問題：CSS 樣式未套用
**解決方法**：
1. 檢查 CSS 檔案路徑是否正確
2. 確認 IIS 靜態檔案 MIME 類型設定
3. 清除瀏覽器快取

## 📝 更新日誌

### v1.0.0 (2024-02-05)
- ✅ 初始版本發布
- ✅ 完整登入驗證系統
- ✅ 首頁儀表板
- ✅ 機台狀態監控頁面
- ✅ WIP 趨勢圖表
- ✅ 響應式側邊欄選單

## 👥 支援與聯絡

如有問題或建議，請透過以下方式聯繫：
- 提交 Issue 到 GitHub Repository
- 聯絡系統管理員

## 📄 授權

本專案僅供內部使用，版權所有。

---

**重要提醒：本系統目前使用模擬數據，待後續串接實際資料源。正式上線前請務必完成資料庫整合與安全性強化。**
