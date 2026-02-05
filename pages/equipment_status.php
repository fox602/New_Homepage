<?php
/**
 * 機況詳細頁面 - ET1/2 EQ1/EQ2
 * Equipment Status Page
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/auth.php';

// 驗證登入
requireLogin();

// 設定頁面標題
$pageTitle = '機台管理 - ET1/2 機況';

// 包含頁面頭部
include __DIR__ . '/../includes/header.php';

// 包含側邊欄
include __DIR__ . '/../includes/sidebar.php';

// 模擬機台詳細數據
$equipmentList = [
    [
        'id' => 'ET1-EQ001',
        'name' => '蝕刻機 #1',
        'status' => 'online',
        'statusText' => '運行中',
        'utilization' => '95.5%',
        'temperature' => '125.3°C',
        'pressure' => '2.5 mTorr',
        'processTime' => '125 秒',
        'waferCount' => '245',
        'lastMaintenance' => '2024-01-15'
    ],
    [
        'id' => 'ET1-EQ002',
        'name' => '蝕刻機 #2',
        'status' => 'online',
        'statusText' => '運行中',
        'utilization' => '88.3%',
        'temperature' => '124.8°C',
        'pressure' => '2.4 mTorr',
        'processTime' => '128 秒',
        'waferCount' => '221',
        'lastMaintenance' => '2024-01-18'
    ],
    [
        'id' => 'ET2-EQ003',
        'name' => '薄膜機 #3',
        'status' => 'online',
        'statusText' => '運行中',
        'utilization' => '91.2%',
        'temperature' => '320.5°C',
        'pressure' => '1.8 mTorr',
        'processTime' => '145 秒',
        'waferCount' => '228',
        'lastMaintenance' => '2024-01-20'
    ],
    [
        'id' => 'ET2-EQ004',
        'name' => '薄膜機 #4',
        'status' => 'warning',
        'statusText' => '待機中',
        'utilization' => '65.8%',
        'temperature' => '85.2°C',
        'pressure' => '0.5 mTorr',
        'processTime' => '-',
        'waferCount' => '165',
        'lastMaintenance' => '2024-01-12'
    ],
    [
        'id' => 'ET2-EQ005',
        'name' => '薄膜機 #5',
        'status' => 'offline',
        'statusText' => '維修中',
        'utilization' => '0%',
        'temperature' => '25.0°C',
        'pressure' => '760 Torr',
        'processTime' => '-',
        'waferCount' => '0',
        'lastMaintenance' => '2024-02-05 (進行中)'
    ]
];
?>

<!-- 主要內容 -->
<main class="main-content" id="mainContent">
    
    <div style="margin-bottom: 25px;">
        <h1 style="color: #0066cc; font-size: 28px; margin-bottom: 10px;">📊 ET1/2 機台狀態監控</h1>
        <p style="color: #a0a0a0;">EQ1 / EQ2 設備即時狀態資訊</p>
    </div>
    
    <!-- 機台統計摘要 -->
    <div class="dashboard-grid" style="margin-bottom: 30px;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">運行中</h3>
                <span class="status-indicator online"></span>
            </div>
            <div class="card-content" style="text-align: center;">
                <div style="font-size: 48px; font-weight: 700; color: #6bcf7f; margin: 20px 0;">3</div>
                <p style="color: #a0a0a0;">台設備正常運行</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">待機中</h3>
                <span class="status-indicator warning"></span>
            </div>
            <div class="card-content" style="text-align: center;">
                <div style="font-size: 48px; font-weight: 700; color: #ffd93d; margin: 20px 0;">1</div>
                <p style="color: #a0a0a0;">台設備待機</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">維修中</h3>
                <span class="status-indicator offline"></span>
            </div>
            <div class="card-content" style="text-align: center;">
                <div style="font-size: 48px; font-weight: 700; color: #ff6b6b; margin: 20px 0;">1</div>
                <p style="color: #a0a0a0;">台設備維修</p>
            </div>
        </div>
    </div>
    
    <!-- 機台詳細列表 -->
    <h2 style="margin-bottom: 20px; color: #0066cc;">機台詳細資訊</h2>
    <div class="dashboard-grid">
        <?php foreach ($equipmentList as $equipment): ?>
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
                        echo $equipment['status'] === 'online' ? 'success' : 
                            ($equipment['status'] === 'warning' ? 'warning' : 'danger'); 
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
                        <span class="detail-label">溫度</span>
                        <span class="detail-value"><?php echo htmlspecialchars($equipment['temperature']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">壓力</span>
                        <span class="detail-value"><?php echo htmlspecialchars($equipment['pressure']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">製程時間</span>
                        <span class="detail-value"><?php echo htmlspecialchars($equipment['processTime']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">產量</span>
                        <span class="detail-value"><?php echo htmlspecialchars($equipment['waferCount']); ?> 片</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">上次保養</span>
                        <span class="detail-value" style="font-size: 12px;"><?php echo htmlspecialchars($equipment['lastMaintenance']); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div style="margin-top: 30px; padding: 20px; background: rgba(0, 102, 204, 0.1); border-radius: 8px; border: 1px solid rgba(0, 102, 204, 0.3);">
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
