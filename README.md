# 半導體產業入口網站

專為離線封閉環境設計的 ASP.NET Core 8 MVC 應用程式，適用於半導體製造業的設備監控與數據管理。

## 📋 專案簡介

這是一個完全適合離線環境的專業半導體產業風格入口網站，提供設備狀態監控、WIP 趨勢分析等功能。

### 主要特色

- ✅ **完全離線運作**: 所有資源本地化，不依賴 CDN
- ✅ **Self-Contained 部署**: 包含 .NET Runtime，無需額外安裝
- ✅ **專業半導體風格**: 深色主題 + 科技藍配色
- ✅ **響應式設計**: 支援桌面與平板裝置
- ✅ **ASP.NET Core Identity**: 完整的身分驗證系統
- ✅ **即時監控**: 設備狀態、稼動率、參數監控
- ✅ **數據視覺化**: Chart.js 圖表展示 WIP 趨勢

## 🛠️ 技術架構

### 後端
- **框架**: ASP.NET Core 8.0 (LTS)
- **架構**: MVC Pattern
- **資料庫**: SQLite (In-Memory 模式)
- **ORM**: Entity Framework Core 8.0
- **驗證**: ASP.NET Core Identity

### 前端
- **UI 框架**: Bootstrap 5 (本地檔案)
- **JavaScript**: jQuery, Chart.js (本地檔案)
- **圖示**: Bootstrap Icons (本地檔案)
- **主題**: 自訂半導體產業風格 CSS

## 📦 專案結構

```
New_Homepage/
├── Controllers/           # MVC 控制器
│   ├── AccountController.cs
│   ├── HomeController.cs
│   └── EquipmentController.cs
├── Models/               # 資料模型
│   ├── Equipment.cs
│   ├── WipData.cs
│   └── LoginViewModel.cs
├── Views/                # Razor 視圖
│   ├── Account/
│   ├── Home/
│   ├── Equipment/
│   └── Shared/
├── wwwroot/              # 靜態資源 (全部本地化)
│   ├── lib/              # 第三方庫
│   │   ├── bootstrap/
│   │   ├── jquery/
│   │   ├── chart.js/
│   │   └── bootstrap-icons/
│   ├── css/              # 自訂樣式
│   └── js/               # 自訂 JavaScript
├── Data/                 # 資料存取層
│   ├── ApplicationDbContext.cs
│   └── SeedData.cs
├── Services/             # 業務邏輯層
│   ├── IEquipmentService.cs
│   └── EquipmentService.cs
└── Deploy/               # 部署相關檔案
    ├── web.config
    ├── IIS_Setup.ps1
    └── publish-profile.pubxml
```

## 🚀 快速開始 (開發環境)

### 前置需求

- Visual Studio 2022 (版本 17.14.15 或更新)
- .NET 8 SDK

### 步驟

1. **克隆或下載專案**
   ```bash
   git clone https://github.com/fox602/New_Homepage.git
   ```

2. **使用 Visual Studio 開啟**
   - 開啟 `New_Homepage.sln`

3. **還原 NuGet 套件**
   - Visual Studio 會自動還原套件
   - 或手動執行: `dotnet restore`

4. **建置專案**
   - 按 `Ctrl+Shift+B` 或
   - 執行: `dotnet build`

5. **執行應用程式**
   - 按 `F5` 啟動偵錯模式
   - 瀏覽器會自動開啟 `https://localhost:5001`

6. **登入**
   - 帳號: `admin@semiconductor.com`
   - 密碼: `Admin@123`

## 📊 功能說明

### 1. 登入驗證系統
- ASP.NET Core Identity 整合
- Cookie-based Authentication
- Session 管理 (30 分鐘逾時)

### 2. 儀表板 (首頁)
- **今日重點機台**: 顯示 4 台重點設備狀態
- **WIP Trend Chart**: 過去 7 天的 WIP 數據折線圖
- **機台機況現況**: 所有設備的詳細狀態列表
- **快速連結**: 導向詳細機況頁面

### 3. ET1/2 機況頁面
- 蝕刻機與沉積機設備監控
- 即時狀態、稼動率、溫度、壓力等參數
- 統計卡片顯示設備概況

### 4. ET3 機況頁面
- 檢測設備狀態監控
- 良率統計、缺陷率、校正狀態
- 設備詳細資訊表格

### 5. 側邊欄導航
- 可展開/收合設計
- 響應式: 手機版顯示漢堡選單
- 狀態保存 (localStorage)

## 🎨 設計風格

### 色彩系統
- **主背景**: `#0a0e27` (深藍黑)
- **次背景**: `#1a1f3a` (深藍)
- **強調色**: `#00a8ff` (科技藍)
- **成功色**: `#6bcf7f` (綠)
- **警告色**: `#ffd93d` (黃)
- **危險色**: `#ff6b6b` (紅)

## 📖 部署指南

詳細的部署說明請參閱:

- **[README_DEPLOYMENT.md](README_DEPLOYMENT.md)**: 完整部署手冊
- **[README_OFFLINE_SETUP.md](README_OFFLINE_SETUP.md)**: 離線環境設定指南

### 快速部署步驟

1. 在開發機使用 Visual Studio 發行專案 (Self-Contained, win-x64)
2. 將發行的檔案複製到目標伺服器
3. 在伺服器上執行 `Deploy/IIS_Setup.ps1` PowerShell 腳本
4. 複製應用程式檔案到 IIS 目錄
5. 在瀏覽器開啟網站

## 🔐 預設登入資訊

```
帳號: admin@semiconductor.com
密碼: Admin@123
```

⚠️ **重要**: 部署後請立即修改預設密碼！

## 🧪 測試資料

應用程式包含模擬的測試資料:

- **設備**: 8 台 (ET1/2/3 系列)
- **WIP 資料**: 過去 7 天的數據
- **狀態**: Online, Warning, Offline

## 📝 開發說明

### 新增設備

編輯 `Data/SeedData.cs` 檔案，在 `InitializeAsync` 方法中新增設備資料。

### 修改主題顏色

編輯 `wwwroot/css/semiconductor-theme.css` 檔案中的 `:root` CSS 變數。

### 自訂圖表

編輯 `wwwroot/js/dashboard-charts.js` 檔案，修改 Chart.js 設定。

## 🐛 故障排除

### 應用程式無法啟動

1. 檢查 .NET 8 Runtime 是否已安裝
2. 確認 IIS 已啟用 ASP.NET Core Module
3. 查看 `logs/stdout` 目錄中的錯誤記錄

### 登入失敗

1. 確認資料庫已初始化
2. 刪除 `app.db` 檔案後重新啟動應用程式

### 靜態資源無法載入

1. 確認 `wwwroot` 目錄中的檔案完整
2. 檢查 IIS 靜態內容處理常式是否已啟用

## 📄 授權

此專案為內部使用專案。

## 👥 支援

如有問題，請聯絡開發團隊。

---

**版本**: 1.0.0  
**最後更新**: 2026-02-11
