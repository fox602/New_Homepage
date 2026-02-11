# IIS 自動設定腳本
# 用於在 Windows Server 2016 + IIS 10.0 上部署半導體入口網站

param(
    [string]$SiteName = "SemiconductorPortal",
    [int]$Port = 80,
    [string]$PhysicalPath = "C:\inetpub\SemiconductorPortal",
    [string]$AppPoolName = "SemiconductorPortal"
)

# 檢查是否以管理員身分執行
$currentUser = New-Object Security.Principal.WindowsPrincipal([Security.Principal.WindowsIdentity]::GetCurrent())
if (-not $currentUser.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) {
    Write-Error "此腳本必須以管理員身分執行！"
    exit 1
}

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "半導體產業入口網站 - IIS 部署腳本" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# 1. 檢查環境
Write-Host "[1/6] 檢查環境..." -ForegroundColor Yellow

# 檢查 IIS 是否已安裝
try {
    Import-Module WebAdministration -ErrorAction Stop
    Write-Host "✓ IIS 已安裝" -ForegroundColor Green
} catch {
    Write-Error "IIS 未安裝！請先安裝 IIS 功能。"
    exit 1
}

# 檢查 .NET 8 Runtime
$dotnetPath = "C:\Program Files\dotnet\dotnet.exe"
if (Test-Path $dotnetPath) {
    $dotnetVersion = & $dotnetPath --list-runtimes | Select-String "Microsoft.AspNetCore.App 8"
    if ($dotnetVersion) {
        Write-Host "✓ .NET 8 Runtime 已安裝" -ForegroundColor Green
    } else {
        Write-Warning "未找到 .NET 8 Runtime，但這是 Self-Contained 部署，可能不需要。"
    }
} else {
    Write-Warning ".NET Runtime 未找到，但這是 Self-Contained 部署，應該可以正常運作。"
}

# 2. 建立應用程式集區
Write-Host ""
Write-Host "[2/6] 建立應用程式集區..." -ForegroundColor Yellow

if (Test-Path "IIS:\AppPools\$AppPoolName") {
    Write-Host "應用程式集區 '$AppPoolName' 已存在，正在移除..." -ForegroundColor Yellow
    Remove-WebAppPool -Name $AppPoolName
}

New-WebAppPool -Name $AppPoolName
Set-ItemProperty "IIS:\AppPools\$AppPoolName" -Name "managedRuntimeVersion" -Value ""
Set-ItemProperty "IIS:\AppPools\$AppPoolName" -Name "managedPipelineMode" -Value "Integrated"
Write-Host "✓ 應用程式集區 '$AppPoolName' 已建立" -ForegroundColor Green

# 3. 建立實體目錄
Write-Host ""
Write-Host "[3/6] 建立實體目錄..." -ForegroundColor Yellow

if (-not (Test-Path $PhysicalPath)) {
    New-Item -ItemType Directory -Path $PhysicalPath -Force | Out-Null
    Write-Host "✓ 目錄已建立: $PhysicalPath" -ForegroundColor Green
} else {
    Write-Host "目錄已存在: $PhysicalPath" -ForegroundColor Yellow
}

# 建立 logs 目錄
$logsPath = Join-Path $PhysicalPath "logs"
if (-not (Test-Path $logsPath)) {
    New-Item -ItemType Directory -Path $logsPath -Force | Out-Null
    Write-Host "✓ Logs 目錄已建立" -ForegroundColor Green
}

# 4. 設定權限
Write-Host ""
Write-Host "[4/6] 設定目錄權限..." -ForegroundColor Yellow

$acl = Get-Acl $PhysicalPath

# 新增 IIS_IUSRS 讀取執行權限
$iisIusrsRule = New-Object System.Security.AccessControl.FileSystemAccessRule(
    "IIS_IUSRS",
    "ReadAndExecute",
    "ContainerInherit,ObjectInherit",
    "None",
    "Allow"
)
$acl.AddAccessRule($iisIusrsRule)

# 新增應用程式集區身分修改權限
$appPoolIdentity = "IIS AppPool\$AppPoolName"
$appPoolRule = New-Object System.Security.AccessControl.FileSystemAccessRule(
    $appPoolIdentity,
    "Modify",
    "ContainerInherit,ObjectInherit",
    "None",
    "Allow"
)
$acl.AddAccessRule($appPoolRule)

Set-Acl -Path $PhysicalPath -AclObject $acl
Write-Host "✓ 權限已設定" -ForegroundColor Green

# 5. 建立或更新網站
Write-Host ""
Write-Host "[5/6] 建立 IIS 網站..." -ForegroundColor Yellow

# 檢查網站是否已存在
if (Get-Website -Name $SiteName -ErrorAction SilentlyContinue) {
    Write-Host "網站 '$SiteName' 已存在，正在移除..." -ForegroundColor Yellow
    Remove-Website -Name $SiteName
}

# 建立新網站
New-Website -Name $SiteName `
            -PhysicalPath $PhysicalPath `
            -ApplicationPool $AppPoolName `
            -Port $Port `
            -Force

Write-Host "✓ 網站 '$SiteName' 已建立" -ForegroundColor Green
Write-Host "  - 連接埠: $Port" -ForegroundColor Gray
Write-Host "  - 實體路徑: $PhysicalPath" -ForegroundColor Gray

# 6. 啟動網站
Write-Host ""
Write-Host "[6/6] 啟動網站..." -ForegroundColor Yellow

Start-Website -Name $SiteName
Start-WebAppPool -Name $AppPoolName

Write-Host "✓ 網站已啟動" -ForegroundColor Green

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "部署完成！" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "下一步驟:" -ForegroundColor Yellow
Write-Host "1. 複製應用程式檔案到 $PhysicalPath" -ForegroundColor White
Write-Host "2. 確認 web.config 檔案存在" -ForegroundColor White
Write-Host "3. 在瀏覽器中開啟 http://localhost:$Port" -ForegroundColor White
Write-Host ""
Write-Host "預設登入帳號:" -ForegroundColor Yellow
Write-Host "  帳號: admin@semiconductor.com" -ForegroundColor White
Write-Host "  密碼: Admin@123" -ForegroundColor White
Write-Host ""
