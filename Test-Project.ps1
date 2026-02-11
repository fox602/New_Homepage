# New_Homepage 專案測試腳本
# 用於開發環境功能測試

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "半導體產業入口網站 - 功能測試腳本" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# 測試清單
$tests = @(
    @{Name="專案檔案存在"; Test={Test-Path "New_Homepage.csproj"}},
    @{Name="Program.cs 存在"; Test={Test-Path "Program.cs"}},
    @{Name="appsettings.json 存在"; Test={Test-Path "appsettings.json"}},
    @{Name="Controllers 目錄存在"; Test={Test-Path "Controllers"}},
    @{Name="Models 目錄存在"; Test={Test-Path "Models"}},
    @{Name="Views 目錄存在"; Test={Test-Path "Views"}},
    @{Name="wwwroot 目錄存在"; Test={Test-Path "wwwroot"}},
    @{Name="Bootstrap 本地檔案存在"; Test={Test-Path "wwwroot/lib/bootstrap/css/bootstrap.min.css"}},
    @{Name="jQuery 本地檔案存在"; Test={Test-Path "wwwroot/lib/jquery/jquery.min.js"}},
    @{Name="Chart.js 本地檔案存在"; Test={Test-Path "wwwroot/lib/chart.js/chart.min.js"}},
    @{Name="Bootstrap Icons 本地檔案存在"; Test={Test-Path "wwwroot/lib/bootstrap-icons/bootstrap-icons.css"}},
    @{Name="半導體主題 CSS 存在"; Test={Test-Path "wwwroot/css/semiconductor-theme.css"}},
    @{Name="登入 CSS 存在"; Test={Test-Path "wwwroot/css/login.css"}},
    @{Name="側邊欄 CSS 存在"; Test={Test-Path "wwwroot/css/sidebar.css"}},
    @{Name="側邊欄 JS 存在"; Test={Test-Path "wwwroot/js/sidebar.js"}},
    @{Name="儀表板圖表 JS 存在"; Test={Test-Path "wwwroot/js/dashboard-charts.js"}},
    @{Name="部署腳本存在"; Test={Test-Path "Deploy/IIS_Setup.ps1"}},
    @{Name="web.config 存在"; Test={Test-Path "Deploy/web.config"}},
    @{Name="README.md 存在"; Test={Test-Path "README.md"}},
    @{Name="部署手冊存在"; Test={Test-Path "README_DEPLOYMENT.md"}},
    @{Name="離線設定指南存在"; Test={Test-Path "README_OFFLINE_SETUP.md"}}
)

$passed = 0
$failed = 0

foreach ($test in $tests) {
    Write-Host "測試: $($test.Name)... " -NoNewline
    if (& $test.Test) {
        Write-Host "[通過]" -ForegroundColor Green
        $passed++
    } else {
        Write-Host "[失敗]" -ForegroundColor Red
        $failed++
    }
}

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "測試結果" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "通過: $passed" -ForegroundColor Green
Write-Host "失敗: $failed" -ForegroundColor Red
Write-Host ""

if ($failed -eq 0) {
    Write-Host "✓ 所有測試通過！專案結構完整。" -ForegroundColor Green
    Write-Host ""
    Write-Host "下一步:" -ForegroundColor Yellow
    Write-Host "1. 執行 'dotnet build' 建置專案" -ForegroundColor White
    Write-Host "2. 執行 'dotnet run' 啟動應用程式" -ForegroundColor White
    Write-Host "3. 在瀏覽器開啟 https://localhost:5001" -ForegroundColor White
    Write-Host "4. 使用預設帳號登入:" -ForegroundColor White
    Write-Host "   帳號: admin@semiconductor.com" -ForegroundColor White
    Write-Host "   密碼: Admin@123" -ForegroundColor White
} else {
    Write-Host "✗ 部分測試失敗，請檢查專案結構。" -ForegroundColor Red
}
