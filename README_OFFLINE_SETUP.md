# 半導體產業入口網站 - 離線環境設定指南

本指南說明如何在完全離線、封閉網路環境中準備和部署應用程式。

---

## 📋 目錄

1. [離線環境說明](#離線環境說明)
2. [離線資源清單](#離線資源清單)
3. [在開發機上的準備工作](#在開發機上的準備工作)
4. [檔案傳輸方式](#檔案傳輸方式)
5. [離線安裝驗證](#離線安裝驗證)
6. [常見問題](#常見問題)

---

## 離線環境說明

### 環境特性

本專案專門設計用於以下離線環境:

- ✅ **無 Internet 連線**: 完全封閉網路
- ✅ **無法使用 CDN**: 所有前端資源已本地化
- ✅ **無法線上還原套件**: 使用 Self-Contained 部署模式
- ✅ **無法 git clone**: 只能透過 GitHub 網頁下載 ZIP

### 部署方式

```
開發機 (有網路)           目標伺服器 (離線)
      │                          │
      │  1. 建置專案              │
      │  2. 下載依賴              │
      │  3. 打包檔案              │
      │                          │
      └──► USB/內網 ────────────►│
                                 │  4. 安裝 Runtime
                                 │  5. 部署應用程式
                                 │  6. 啟動服務
```

---

## 離線資源清單

### 必要檔案清單

在開發機上準備以下檔案，稍後傳輸到目標伺服器:

#### 1. .NET 8 Hosting Bundle (建議)

即使使用 Self-Contained 部署，仍建議安裝 Hosting Bundle 以確保 IIS 整合功能完整。

- **檔案名稱**: `dotnet-hosting-8.0.x-win.exe`
- **下載位置**: https://dotnet.microsoft.com/en-us/download/dotnet/8.0
- **選擇版本**: "Hosting Bundle" (Windows x64)
- **檔案大小**: 約 200 MB
- **版本範例**: 8.0.0 或更新版本

**下載步驟:**
1. 在有網路的電腦上前往上述網址
2. 找到「ASP.NET Core Runtime 8.0.x」區塊
3. 點擊「Hosting Bundle」下載
4. 儲存檔案到 USB 或準備傳輸

**SHA256 校驗 (範例 - 請以實際版本為準):**
```
檔案: dotnet-hosting-8.0.0-win.exe
SHA256: (請查閱 Microsoft 官網提供的校驗碼)
```

#### 2. 應用程式發行套件

- **檔案名稱**: `SemiconductorPortal.zip`
- **內容**: 完整的應用程式檔案 (Self-Contained)
- **檔案大小**: 約 150-200 MB
- **包含內容**:
  - 應用程式執行檔 (.exe)
  - .NET Runtime 檔案
  - 所有相依性 DLL
  - 靜態資源 (CSS, JS, 圖片)
  - 設定檔

#### 3. 部署腳本

- **IIS_Setup.ps1**: IIS 自動設定腳本
- **web.config**: IIS 設定檔
- **README_DEPLOYMENT.md**: 部署手冊

### 選用檔案

- **README.md**: 專案說明文件
- **README_OFFLINE_SETUP.md**: 本離線設定指南

---

## 在開發機上的準備工作

### 步驟 1: 下載原始碼

由於目標伺服器無法 git clone，請在開發機上:

**選項 A: 使用 Git (推薦)**
```bash
git clone https://github.com/fox602/New_Homepage.git
cd New_Homepage
```

**選項 B: 下載 ZIP**
1. 前往 https://github.com/fox602/New_Homepage
2. 點擊「Code」按鈕
3. 選擇「Download ZIP」
4. 解壓縮到工作目錄

### 步驟 2: 在 Visual Studio 2022 中開啟專案

1. 啟動 Visual Studio 2022
2. 開啟 `New_Homepage.sln`
3. 等待 NuGet 套件還原完成

### 步驟 3: 驗證建置

在發行前先確認專案可以正常建置:

```powershell
# 在開發機上執行
cd C:\Path\To\New_Homepage
dotnet build -c Release
```

確認沒有錯誤後繼續。

### 步驟 4: 發行應用程式 (Self-Contained)

**使用 Visual Studio:**

1. 在方案總管中，對專案按右鍵
2. 選擇「發行」
3. 設定發行組態:
   - 目標: 資料夾
   - 設定: Release
   - 目標執行階段: win-x64
   - **部署模式: 獨立式 (Self-Contained)** ← 重要！
   - 產生 Ready to Run 映像: ✓
4. 點擊「發行」

**或使用命令列:**

```powershell
cd C:\Path\To\New_Homepage

dotnet publish `
  -c Release `
  -r win-x64 `
  --self-contained true `
  -p:PublishSingleFile=false `
  -p:PublishReadyToRun=true `
  -o .\publish
```

### 步驟 5: 驗證發行內容

檢查發行目錄 `bin\Release\net8.0\win-x64\publish\`:

```
必要檔案:
✓ New_Homepage.exe          (主程式)
✓ New_Homepage.dll
✓ appsettings.json
✓ wwwroot/ 資料夾           (靜態資源)
✓ 大量 .dll 檔案            (.NET Runtime 與相依性)
```

**重要**: 確認 wwwroot/lib/ 目錄包含:
- bootstrap/
- jquery/
- chart.js/
- bootstrap-icons/

### 步驟 6: 複製 web.config

```powershell
Copy-Item Deploy\web.config bin\Release\net8.0\win-x64\publish\
```

### 步驟 7: 建立完整的部署套件

建立一個資料夾包含所有需要的檔案:

```
DeploymentPackage/
├── App/                              # 應用程式檔案
│   └── [publish 目錄的所有內容]
├── Runtime/                          # Runtime 安裝檔
│   └── dotnet-hosting-8.0.x-win.exe
├── Scripts/                          # 部署腳本
│   ├── IIS_Setup.ps1
│   └── web.config
└── Documentation/                    # 文件
    ├── README_DEPLOYMENT.md
    └── README_OFFLINE_SETUP.md
```

### 步驟 8: 壓縮套件

```powershell
# 壓縮為 ZIP 檔案
Compress-Archive -Path DeploymentPackage\* -DestinationPath SemiconductorPortal_Complete.zip
```

### 步驟 9: 驗證檔案完整性

```powershell
# 計算 SHA256 校驗碼
Get-FileHash SemiconductorPortal_Complete.zip -Algorithm SHA256

# 記錄校驗碼供稍後驗證
```

---

## 檔案傳輸方式

### 方式 1: USB 隨身碟 (推薦)

**優點**: 簡單、直接、可靠

**步驟:**
1. 準備足夠容量的 USB 隨身碟 (建議 2GB 以上)
2. 格式化為 NTFS 格式
3. 複製 `SemiconductorPortal_Complete.zip` 到 USB
4. 安全退出 USB
5. 將 USB 帶到目標伺服器
6. 在目標伺服器上複製檔案到本機硬碟

**注意事項:**
- ✓ 確認檔案複製完成後再退出 USB
- ✓ 建議準備兩個 USB 作為備份
- ✓ 複製到伺服器後驗證檔案完整性

### 方式 2: 內部網路共享

如果開發機與目標伺服器在同一內部網路:

**在開發機上:**
```powershell
# 建立網路共享
New-SmbShare -Name "Deployment" -Path "C:\DeploymentPackage" -ReadAccess "Everyone"
```

**在目標伺服器上:**
```powershell
# 掛載網路磁碟機
net use Z: \\DevelopmentPC\Deployment

# 複製檔案
Copy-Item Z:\* C:\Deployment\ -Recurse
```

### 方式 3: 光碟燒錄

適用於需要長期保存或多次部署:

1. 將檔案燒錄到 DVD
2. 在目標伺服器上讀取光碟
3. 複製檔案到本機

### 檔案完整性驗證

在目標伺服器上驗證檔案是否完整:

```powershell
# 計算 SHA256
Get-FileHash C:\Deployment\SemiconductorPortal_Complete.zip -Algorithm SHA256

# 與開發機上記錄的校驗碼比對
# 如果相同，表示檔案傳輸無誤
```

---

## 離線安裝驗證

### 測試清單

在完全離線的環境中驗證:

#### 1. 確認網路已斷線

```powershell
# 測試網路連線 (應該失敗)
Test-NetConnection google.com

# 確認網路介面卡狀態
Get-NetAdapter
```

#### 2. 安裝 Hosting Bundle

```powershell
# 執行安裝 (離線模式)
.\dotnet-hosting-8.0.x-win.exe /quiet /norestart

# 驗證安裝
dotnet --list-runtimes
# 應該看到 Microsoft.AspNetCore.App 8.0.x
```

#### 3. 部署應用程式

按照 README_DEPLOYMENT.md 中的步驟部署。

#### 4. 測試應用程式

```powershell
# 啟動網站
Start-Website -Name "SemiconductorPortal"

# 在本機瀏覽器測試
Start-Process "http://localhost"
```

#### 5. 功能測試

- [ ] 登入頁面正常顯示
- [ ] CSS 樣式正確載入 (半導體深色主題)
- [ ] 可以使用預設帳號登入
- [ ] 首頁儀表板正常顯示
- [ ] WIP 圖表正常繪製
- [ ] 機台列表正常顯示
- [ ] 側邊欄可以展開收合
- [ ] 所有靜態資源 (圖片、圖示) 正常載入

---

## 常見問題

### Q1: 為什麼使用 Self-Contained 部署還需要安裝 Hosting Bundle?

**A:** Self-Contained 部署已包含 .NET Runtime，理論上可以獨立運作。但安裝 Hosting Bundle 有以下好處:
- 提供 AspNetCoreModuleV2 模組給 IIS
- 確保 IIS 與 ASP.NET Core 的整合功能完整
- 簡化疑難排解
- 提供更好的效能和穩定性

如果無法安裝 Hosting Bundle，Self-Contained 部署仍可正常運作。

### Q2: 如何確認是否真的是離線部署?

**A:** 在部署過程中:
1. 拔除網路線或停用網路卡
2. 執行部署步驟
3. 如果所有步驟都成功，表示確實可以離線部署

### Q3: 發行的檔案很大 (200MB+)，正常嗎?

**A:** 是的。Self-Contained 部署會包含整個 .NET Runtime，因此檔案較大。這是為了確保在離線環境中可以獨立運作。

### Q4: 可以使用更舊的 Windows Server 版本嗎?

**A:** 本專案測試環境為 Windows Server 2016。更舊的版本 (如 2012 R2) 可能需要:
- 安裝額外的更新
- 確認 IIS 版本支援
- 可能需要調整 web.config

### Q5: 可以不使用 IIS，直接執行 .exe 檔嗎?

**A:** 可以。在開發或測試環境中:

```powershell
cd C:\inetpub\SemiconductorPortal
.\New_Homepage.exe
```

應用程式會啟動在 http://localhost:5000 (HTTP) 和 https://localhost:5001 (HTTPS)

但生產環境建議使用 IIS 以獲得更好的管理和穩定性。

### Q6: 如何更新應用程式?

**A:** 
1. 在開發機建置新版本
2. 傳輸到目標伺服器
3. 停止 IIS 網站和應用程式集區
4. 備份舊版檔案
5. 替換新版檔案
6. 重新啟動網站

```powershell
# 停止
Stop-Website -Name "SemiconductorPortal"
Stop-WebAppPool -Name "SemiconductorPortal"

# 備份
Copy-Item C:\inetpub\SemiconductorPortal C:\Backup\SemiconductorPortal_Old -Recurse

# 更新 (保留 app.db 和 logs)
Remove-Item C:\inetpub\SemiconductorPortal\* -Exclude app.db,logs -Recurse
Copy-Item C:\Temp\NewVersion\* C:\inetpub\SemiconductorPortal\ -Recurse

# 啟動
Start-WebAppPool -Name "SemiconductorPortal"
Start-Website -Name "SemiconductorPortal"
```

### Q7: 靜態資源是否真的本地化?

**A:** 是的。專案中不使用任何 CDN 連結。所有資源都在 `wwwroot/lib/` 目錄:
- Bootstrap 5.3.2
- jQuery 3.7.1
- Chart.js (最新版本)
- Bootstrap Icons

可以通過檢查 HTML 原始碼確認沒有外部 CDN 連結。

### Q8: 可以在虛擬機中部署嗎?

**A:** 可以。本專案支援:
- VMware
- Hyper-V
- VirtualBox
- 其他虛擬化平台

只要虛擬機執行 Windows Server 2016 或更新版本即可。

---

## 離線環境最佳實務

### 1. 準備清單文件

建立檢查清單，記錄:
- 傳輸的檔案名稱
- 檔案大小
- SHA256 校驗碼
- 傳輸日期
- 傳輸方式

### 2. 建立備份

在目標伺服器上:
- 定期備份應用程式檔案
- 備份 IIS 設定
- 備份資料庫檔案 (app.db)

### 3. 記錄變更

維護變更記錄:
- 部署日期
- 版本號
- 變更內容
- 執行人員

### 4. 測試環境

如果可能，在離線環境中準備一個測試環境:
- 先在測試環境驗證
- 確認無誤後再部署到生產環境

### 5. 文件保存

保存以下文件的紙本或離線電子檔:
- README_DEPLOYMENT.md
- README_OFFLINE_SETUP.md
- 故障排除指南

---

## 聯絡支援

如果在離線環境設定過程中遇到問題:

1. 收集以下資訊:
   - Windows Server 版本
   - 錯誤訊息
   - 記錄檔內容
   - 已執行的步驟

2. 將資訊記錄到 USB 隨身碟

3. 在有網路的環境中聯絡技術支援

---

**版本**: 1.0.0  
**最後更新**: 2026-02-11  
**適用環境**: 完全離線、封閉網路環境
