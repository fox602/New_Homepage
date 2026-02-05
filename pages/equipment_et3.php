<?php
/**
 * ET3 機況頁面
 * ET3 Equipment Status Page
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/auth.php';

// 驗證登入
requireLogin();

// 設定頁面標題
$pageTitle = 'ET3 機況監控';

// 包含頁面頭部
include __DIR__ . '/../includes/header.php';

// 包含側邊欄
include __DIR__ . '/../includes/sidebar.php';

// 模擬 ET3 機台數據
$et3Equipment = [
    [
        'id' => 'ET3-EQ010',
        'name' => '檢測設備 #10',
        'status' => 'online',
        'statusText' => '運行中',
        'utilization' => '93.7%',
        'throughput' => '234 片/天',
        'defectRate' => '0.15%',
        'inspectionTime' => '45 秒/片',
        'lastCalibration' => '2024-01-25'
    ],
    [
        'id' => 'ET3-EQ011',
        'name' => '檢測設備 #11',
        'status' => 'online',
        'statusText' => '運行中',
        'utilization' => '89.2%',
        'throughput' => '223 片/天',
        'defectRate' => '0.18%',
        'inspectionTime' => '48 秒/片',
        'lastCalibration' => '2024-01-28'
    ],
    [
        'id' => 'ET3-EQ012',
        'name' => '檢測設備 #12',
        'status' => 'warning',
        'statusText' => '待機中',
        'utilization' => '72.5%',
        'throughput' => '181 片/天',
        'defectRate' => '0.12%',
        'inspectionTime' => '52 秒/片',
        'lastCalibration' => '2024-02-01'
    ]
];
?>

<!-- 主要內容 -->
<main class="main-content" id="mainContent">
    
    <div style="margin-bottom: 25px;">
        <h1 style="color: #0066cc; font-size: 28px; margin-bottom: 10px;">⚙️ ET3 檢測設備監控</h1>
        <p style="color: #a0a0a0;">EQ3 檢測設備即時狀態與品質數據</p>
    </div>
    
    <!-- 整體統計 -->
    <div class="dashboard-grid" style="margin-bottom: 30px;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">總體稼動率</h3>
                <span class="card-icon">📊</span>
            </div>
            <div class="card-content" style="text-align: center;">
                <div style="font-size: 48px; font-weight: 700; color: #0066cc; margin: 20px 0;">85.1%</div>
                <p style="color: #6bcf7f; font-size: 14px;">↑ 3.2% 相較昨日</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">日產量</h3>
                <span class="card-icon">📦</span>
            </div>
            <div class="card-content" style="text-align: center;">
                <div style="font-size: 48px; font-weight: 700; color: #0066cc; margin: 20px 0;">638</div>
                <p style="color: #a0a0a0; font-size: 14px;">片 / 目標 700 片</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">平均不良率</h3>
                <span class="card-icon">✓</span>
            </div>
            <div class="card-content" style="text-align: center;">
                <div style="font-size: 48px; font-weight: 700; color: #6bcf7f; margin: 20px 0;">0.15%</div>
                <p style="color: #6bcf7f; font-size: 14px;">良好（目標 < 0.3%）</p>
            </div>
        </div>
    </div>
    
    <!-- 設備詳細資訊 -->
    <h2 style="margin-bottom: 20px; color: #0066cc;">檢測設備詳細資訊</h2>
    <div class="dashboard-grid">
        <?php foreach ($et3Equipment as $equipment): ?>
            <div class="card equipment-card">
                <div class="equipment-header">
                    <div>
                        <div class="equipment-id"><?php echo htmlspecialchars($equipment['id']); ?></div>
                        <p style="color: #a0a0a0; font-size: 14px; margin-top: 5px;">
                            <?php echo htmlspecialchars($equipment['name']); ?>
                        </p>
                    </div>
                    <span class="status-indicator <?php echo $equipment['status']; ?>"></span>
                </div>
                
                <div style="margin: 15px 0;">
                    <span class="status-badge <?php 
                        echo $equipment['status'] === 'online' ? 'success' : 'warning'; 
                    ?>">
                        <?php echo htmlspecialchars($equipment['statusText']); ?>
                    </span>
                </div>
                
                <div class="equipment-details">
                    <div class="detail-row">
                        <span class="detail-label">稼動率</span>
                        <span class="detail-value"><?php echo htmlspecialchars($equipment['utilization']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">產能</span>
                        <span class="detail-value"><?php echo htmlspecialchars($equipment['throughput']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">不良率</span>
                        <span class="detail-value" style="color: #6bcf7f;">
                            <?php echo htmlspecialchars($equipment['defectRate']); ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">檢測時間</span>
                        <span class="detail-value"><?php echo htmlspecialchars($equipment['inspectionTime']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">上次校正</span>
                        <span class="detail-value" style="font-size: 12px;">
                            <?php echo htmlspecialchars($equipment['lastCalibration']); ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- 品質趨勢提示 -->
    <div style="margin-top: 30px;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">品質監控提示</h3>
                <span class="card-icon">💡</span>
            </div>
            <div class="card-content">
                <ul style="list-style: none; padding: 0;">
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <span style="color: #6bcf7f;">✓</span> 
                        所有設備不良率均在控制範圍內
                    </li>
                    <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <span style="color: #6bcf7f;">✓</span> 
                        檢測時間符合標準作業程序
                    </li>
                    <li style="padding: 10px 0;">
                        <span style="color: #ffd93d;">⚠</span> 
                        EQ012 建議進行例行性校正檢查
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 20px; padding: 20px; background: rgba(0, 102, 204, 0.1); border-radius: 8px; border: 1px solid rgba(0, 102, 204, 0.3);">
        <p style="color: #a0a0a0; font-size: 14px;">
            ℹ️ 資料更新時間：<?php echo date('Y-m-d H:i:s'); ?> | 
            自動更新：每 30 秒（功能開發中）
        </p>
    </div>
    
</main>

<?php
// 包含頁面底部
include __DIR__ . '/../includes/footer.php';
?>
