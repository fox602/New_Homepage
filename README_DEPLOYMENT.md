# 半導體產業入口網站 - 部署手冊

完整的離線環境部署指南，適用於 Windows Server 2016 + IIS 10.0

---

## 📋 目錄

1. [前置準備](#前置準備)
2. [步驟一: 建立部署套件](#步驟一-建立部署套件)
3. [步驟二: 準備目標伺服器](#步驟二-準備目標伺服器)
4. [步驟三: 部署應用程式](#步驟三-部署應用程式)
5. [步驟四: 設定 IIS](#步驟四-設定-iis)
6. [步驟五: 測試驗證](#步驟五-測試驗證)
7. [故障排除](#故障排除)

---

## 前置準備

### 開發機需求

- **作業系統**: Windows (有網路連線)
- **開發工具**: Visual Studio 2022 Professional (版本 17.14.15 或更新)
- **SDK**: .NET 8 SDK

### 目標伺服器需求

- **作業系統**: Windows Server 2016 或更新
- **IIS 版本**: 10.0.14393.0 或更新
- **網路狀態**: 離線 (無 Internet 連線)

### 需要準備的檔案

✅ .NET 8 Hosting Bundle (離線安裝檔)
- 檔案名稱: `dotnet-hosting-8.0.x-win.exe`
- 下載位置: https://dotnet.microsoft.com/download/dotnet/8.0
- 檔案大小: 約 200 MB
- ⚠️ **重要**: 即使是 Self-Contained 部署，仍建議安裝 Hosting Bundle 以確保 IIS 整合正常運作

✅ 應用程式發行套件
- 從開發機建置並發行

✅ 部署腳本
- `Deploy/IIS_Setup.ps1`
- `Deploy/web.config`

---

## 步驟一: 建立部署套件

### 使用 Visual Studio 2022 發行

1. **開啟專案**
   - 在 Visual Studio 2022 中開啟 `New_Homepage.sln`

2. **選擇發行方式**
   - 在方案總管中，對專案名稱按右鍵
   - 選擇「發行」(Publish)

3. **設定發行目標**
   - 選擇「資料夾」作為發行目標
   - 設定路徑: `bin\Release\net8.0\win-x64\publish\`
   - 點擊「下一步」

4. **設定發行選項**
   
   選擇「編輯」來修改設定，確認以下選項:
   
   ```
   設定: Release
   目標框架: net8.0
   部署模式: 獨立式 (Self-Contained)
   目標執行階段: win-x64
   檔案發行選項:
     - 產生 Ready to Run 映像: ✓
     - 修剪未使用的組件: ✗
   ```

5. **執行發行**
   - 點擊「發行」按鈕
   - 等待發行完成 (可能需要 1-2 分鐘)
   - 發行完成後，檔案會在 `bin\Release\net8.0\win-x64\publish\` 目錄中

### 使用命令列發行

如果偏好使用命令列:

```powershell
# 進入專案目錄
cd C:\Path\To\New_Homepage

# 執行發行命令
dotnet publish -c Release -r win-x64 --self-contained true -p:PublishSingleFile=false -p:PublishReadyToRun=true
```

### 驗證發行檔案

發行完成後，確認以下檔案存在:

```
publish/
├── New_Homepage.exe          # 主程式執行檔
├── web.config                # IIS 設定檔
├── appsettings.json          # 應用程式設定
├── wwwroot/                  # 靜態資源
│   ├── lib/                  # 本地化的 JS/CSS 庫
│   ├── css/
│   └── js/
└── [其他 DLL 檔案]            # .NET 相依性
```

### 打包檔案

1. **複製 web.config**
   ```powershell
   Copy-Item Deploy\web.config bin\Release\net8.0\win-x64\publish\
   ```

2. **壓縮為 ZIP 檔案** (方便傳輸)
   - 選擇 `publish` 資料夾
   - 右鍵選擇「傳送到」→「壓縮的 (zipped) 資料夾」
   - 命名為 `SemiconductorPortal.zip`

3. **準備部署腳本**
   - 複製 `Deploy/IIS_Setup.ps1` 到 USB 或內部網路共享位置

---

## 步驟二: 準備目標伺服器

### 1. 傳輸檔案到伺服器

使用以下任一方式:
- **USB 隨身碟**: 複製 `SemiconductorPortal.zip` 和 `IIS_Setup.ps1`
- **內部網路**: 透過網路共享資料夾傳輸

### 2. 安裝 .NET 8 Hosting Bundle (建議)

即使是 Self-Contained 部署，仍建議安裝 Hosting Bundle:

```powershell
# 以管理員身分執行 PowerShell

# 執行安裝程式
.\dotnet-hosting-8.0.x-win.exe /quiet /norestart

# 等待安裝完成後，重新啟動 IIS
iisreset
```

**注意事項:**
- 安裝過程可能需要 5-10 分鐘
- 安裝完成後必須重新啟動 IIS
- 如果未安裝 Hosting Bundle，Self-Contained 模式也能正常運作，但可能缺少某些 IIS 整合功能

### 3. 確認 IIS 功能已安裝

檢查以下 IIS 功能是否已啟用:

```powershell
# 檢查 IIS 安裝狀態
Get-WindowsFeature -Name Web-Server

# 如果未安裝，執行以下命令安裝
Install-WindowsFeature -name Web-Server -IncludeManagementTools
```

必要的 IIS 功能:
- ✓ Web Server (IIS)
- ✓ ASP.NET 4.x
- ✓ .NET Extensibility
- ✓ Static Content
- ✓ Default Document
- ✓ HTTP Errors
- ✓ HTTP Logging

### 4. 解壓縮應用程式檔案

```powershell
# 建立目標目錄
New-Item -ItemType Directory -Path "C:\inetpub\SemiconductorPortal" -Force

# 解壓縮檔案
Expand-Archive -Path "C:\Temp\SemiconductorPortal.zip" -DestinationPath "C:\inetpub\SemiconductorPortal" -Force
```

---

## 步驟三: 部署應用程式

### 選項 A: 使用自動化 PowerShell 腳本 (建議)

這是最簡單快速的方式:

```powershell
# 1. 以管理員身分開啟 PowerShell

# 2. 設定執行原則 (如果需要)
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope Process

# 3. 進入腳本目錄
cd C:\Temp

# 4. 執行部署腳本
.\IIS_Setup.ps1 -SiteName "SemiconductorPortal" -Port 80

# 使用自訂參數
.\IIS_Setup.ps1 -SiteName "MyPortal" -Port 8080 -PhysicalPath "D:\WebSites\Portal"
```

**腳本會自動完成:**
- ✓ 檢查環境
- ✓ 建立應用程式集區
- ✓ 建立實體目錄
- ✓ 設定權限
- ✓ 建立 IIS 網站
- ✓ 啟動網站

### 選項 B: 手動設定 IIS

如果無法執行 PowerShell 腳本，可以手動設定:

#### 1. 開啟 IIS 管理員

- 開始 → 執行 → `inetmgr`

#### 2. 建立應用程式集區

1. 在左側樹狀檢視中，展開伺服器節點
2. 選擇「應用程式集區」
3. 右鍵選擇「新增應用程式集區」
4. 設定:
   - 名稱: `SemiconductorPortal`
   - .NET CLR 版本: `沒有 Managed 程式碼`
   - 管線模式: `整合式`
5. 點擊「確定」

#### 3. 建立網站

1. 在左側樹狀檢視中，選擇「站台」
2. 右鍵選擇「新增網站」
3. 設定:
   - 站台名稱: `SemiconductorPortal`
   - 應用程式集區: `SemiconductorPortal`
   - 實體路徑: `C:\inetpub\SemiconductorPortal`
   - 連接類型: `http`
   - IP 位址: `全部未指定`
   - 連接埠: `80`
4. 點擊「確定」

#### 4. 設定權限

```powershell
# 以管理員身分執行 PowerShell

$path = "C:\inetpub\SemiconductorPortal"

# 取得 ACL
$acl = Get-Acl $path

# 新增 IIS_IUSRS 權限
$iisIusrs = New-Object System.Security.AccessControl.FileSystemAccessRule(
    "IIS_IUSRS",
    "ReadAndExecute",
    "ContainerInherit,ObjectInherit",
    "None",
    "Allow"
)
$acl.AddAccessRule($iisIusrs)

# 新增應用程式集區權限
$appPool = New-Object System.Security.AccessControl.FileSystemAccessRule(
    "IIS AppPool\SemiconductorPortal",
    "Modify",
    "ContainerInherit,ObjectInherit",
    "None",
    "Allow"
)
$acl.AddAccessRule($appPool)

# 套用 ACL
Set-Acl -Path $path -AclObject $acl

Write-Host "權限已設定完成" -ForegroundColor Green
```

#### 5. 確認 web.config

確保 `C:\inetpub\SemiconductorPortal\web.config` 存在且內容正確:

```xml
<?xml version="1.0" encoding="utf-8"?>
<configuration>
  <location path="." inheritInChildApplications="false">
    <system.webServer>
      <handlers>
        <add name="aspNetCore" path="*" verb="*" modules="AspNetCoreModuleV2" resourceType="Unspecified" />
      </handlers>
      <aspNetCore processPath=".\New_Homepage.exe" 
                  stdoutLogEnabled="true" 
                  stdoutLogFile=".\logs\stdout" 
                  hostingModel="inprocess" />
    </system.webServer>
  </location>
</configuration>
```

#### 6. 建立 logs 目錄

```powershell
New-Item -ItemType Directory -Path "C:\inetpub\SemiconductorPortal\logs" -Force
```

---

## 步驟四: 設定 IIS

### 1. 啟動網站

```powershell
# 啟動網站
Start-Website -Name "SemiconductorPortal"

# 啟動應用程式集區
Start-WebAppPool -Name "SemiconductorPortal"
```

或在 IIS 管理員中:
- 選擇網站
- 點擊右側的「啟動」

### 2. 設定防火牆 (如果需要)

如果需要外部存取:

```powershell
# 允許 HTTP (Port 80)
New-NetFirewallRule -DisplayName "SemiconductorPortal HTTP" -Direction Inbound -LocalPort 80 -Protocol TCP -Action Allow

# 允許 HTTPS (Port 443) - 如果啟用 HTTPS
New-NetFirewallRule -DisplayName "SemiconductorPortal HTTPS" -Direction Inbound -LocalPort 443 -Protocol TCP -Action Allow
```

### 3. 重新啟動 IIS

```powershell
iisreset
```

---

## 步驟五: 測試驗證

### 1. 基本連線測試

在伺服器上開啟瀏覽器:

```
http://localhost
```

應該會看到登入頁面。

### 2. 登入測試

使用預設帳號登入:
- **帳號**: `admin@semiconductor.com`
- **密碼**: `Admin@123`

### 3. 功能測試清單

依序測試以下功能:

- [ ] **登入頁面**
  - [ ] 頁面正常顯示
  - [ ] 半導體風格 CSS 已載入
  - [ ] 可以使用預設帳號登入

- [ ] **首頁儀表板**
  - [ ] 側邊欄正常顯示
  - [ ] 重點機台卡片顯示 4 台設備
  - [ ] WIP 趨勢圖表正常繪製
  - [ ] 機台列表顯示所有設備
  - [ ] 快速連結卡片可以點擊

- [ ] **ET1/2 機況頁面**
  - [ ] 點擊快速連結後正確導向
  - [ ] 統計卡片顯示正確數據
  - [ ] 設備列表正常顯示
  - [ ] 進度條正確顯示

- [ ] **ET3 機況頁面**
  - [ ] 頁面正常顯示
  - [ ] 設備卡片正常顯示
  - [ ] 表格資料正確

- [ ] **側邊欄功能**
  - [ ] 展開/收合功能正常
  - [ ] 選單項目可以點擊導航
  - [ ] 子選單可以展開

- [ ] **登出功能**
  - [ ] 點擊登出後回到登入頁
  - [ ] 登出後無法直接訪問受保護頁面

### 4. 效能測試

```powershell
# 檢查應用程式集區狀態
Get-WebAppPoolState -Name "SemiconductorPortal"

# 檢查網站狀態
Get-WebsiteState -Name "SemiconductorPortal"
```

### 5. 記錄檔檢查

```powershell
# 查看最近的記錄
Get-Content "C:\inetpub\SemiconductorPortal\logs\stdout*.log" -Tail 50
```

如果沒有錯誤記錄，表示應用程式運作正常。

---

## 故障排除

### 問題 1: HTTP Error 500.0 - 應用程式無法啟動

**可能原因:**
- .NET Hosting Bundle 未安裝或版本不符
- 應用程式檔案損壞

**解決方法:**
1. 確認 Hosting Bundle 已安裝: `dotnet --list-runtimes`
2. 檢查 logs 目錄中的錯誤記錄
3. 重新部署應用程式檔案

### 問題 2: HTTP Error 500.19 - web.config 錯誤

**可能原因:**
- web.config 格式錯誤
- AspNetCoreModuleV2 未安裝

**解決方法:**
1. 驗證 web.config XML 格式
2. 安裝 .NET Hosting Bundle
3. 執行 `iisreset`

### 問題 3: HTTP Error 403 - 存取被拒

**可能原因:**
- 檔案權限不足

**解決方法:**
```powershell
# 重新設定權限
$path = "C:\inetpub\SemiconductorPortal"
icacls $path /grant "IIS_IUSRS:(OI)(CI)RX" /T
icacls $path /grant "IIS AppPool\SemiconductorPortal:(OI)(CI)M" /T
```

### 問題 4: 無法登入

**可能原因:**
- 資料庫未初始化

**解決方法:**
1. 刪除 `app.db` 檔案 (如果存在)
2. 重新啟動應用程式集區
3. 應用程式會自動建立資料庫並新增預設帳號

### 問題 5: 靜態檔案 (CSS/JS) 無法載入

**可能原因:**
- IIS 靜態內容處理常式未啟用

**解決方法:**
```powershell
# 安裝靜態內容功能
Install-WindowsFeature -name Web-Static-Content
```

### 問題 6: 應用程式集區自動停止

**可能原因:**
- 記憶體不足
- 應用程式錯誤

**解決方法:**
1. 檢查事件檢視器中的錯誤
2. 增加應用程式集區的記憶體限制
3. 檢查 stdout 記錄檔

### 檢查清單

如果遇到問題，依序檢查:

1. ✓ IIS 是否正在執行
2. ✓ 應用程式集區是否已啟動
3. ✓ 網站是否已啟動
4. ✓ 檔案權限是否正確
5. ✓ web.config 是否存在且格式正確
6. ✓ logs 目錄中是否有錯誤記錄
7. ✓ 防火牆是否允許連線
8. ✓ Hosting Bundle 是否已安裝 (建議)

---

## 進階設定

### 啟用 HTTPS

1. 取得 SSL 憑證
2. 在 IIS 中匯入憑證
3. 新增 HTTPS 繫結到網站

### 設定自訂網域

```powershell
# 新增 Host Header
New-WebBinding -Name "SemiconductorPortal" -Protocol "http" -Port 80 -HostHeader "portal.company.local"
```

### 效能調校

在 IIS 管理員中調整:
- 應用程式集區 → 進階設定
- 調整「閒置逾時」、「佇列長度」等參數

---

## 備份與還原

### 備份應用程式

```powershell
# 備份檔案
Copy-Item "C:\inetpub\SemiconductorPortal" "D:\Backup\SemiconductorPortal_$(Get-Date -Format 'yyyyMMdd')" -Recurse

# 匯出 IIS 設定
Export-IISConfiguration -PhysicalPath "D:\Backup\IIS_Config_$(Get-Date -Format 'yyyyMMdd')"
```

### 還原應用程式

```powershell
# 還原檔案
Copy-Item "D:\Backup\SemiconductorPortal_20260211" "C:\inetpub\SemiconductorPortal" -Recurse -Force

# 重新啟動
iisreset
```

---

## 聯絡支援

如果按照本手冊仍無法成功部署，請聯絡開發團隊並提供:

1. Windows Server 版本
2. IIS 版本
3. 錯誤記錄檔 (logs 目錄)
4. 事件檢視器中的錯誤訊息

---

**版本**: 1.0.0  
**最後更新**: 2026-02-11  
**適用環境**: Windows Server 2016 + IIS 10.0
