<?php
/**
 * 首頁儀表板
 * Home Dashboard
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/auth.php';

// 驗證登入
requireLogin();

// 設定頁面標題
$pageTitle = '首頁儀表板';
$extraJS = [ASSETS_PATH . 'js/chart.js'];

// 包含頁面頭部
include __DIR__ . '/includes/header.php';

// 包含側邊欄
include __DIR__ . '/includes/sidebar.php';

// 模擬機台數據
$todayEquipment = [
    [
        'id' => 'ET1-EQ001',
        'status' => 'online',
        'statusText' => '運行中',
        'utilization' => '95.5%',
        'throughput' => '245 片/天',
        'lastUpdate' => '5 分鐘前'
    ],
    [
        'id' => 'ET2-EQ005',
        'status' => 'warning',
        'statusText' => '待機中',
        'utilization' => '78.2%',
        'throughput' => '198 片/天',
        'lastUpdate' => '12 分鐘前'
    ],
    [
        'id' => 'ET3-EQ012',
        'status' => 'online',
        'statusText' => '運行中',
        'utilization' => '92.8%',
        'throughput' => '232 片/天',
        'lastUpdate' => '2 分鐘前'
    ]
];

$equipmentStatus = [
    [
        'id' => 'ET1-EQ001',
        'name' => '蝕刻機 #1',
        'status' => 'online',
        'utilization' => '95.5%',
        'capacity' => '245/250'
    ],
    [
        'id' => 'ET1-EQ002',
        'name' => '蝕刻機 #2',
        'status' => 'online',
        'utilization' => '88.3%',
        'capacity' => '221/250'
    ],
    [
        'id' => 'ET2-EQ005',
        'name' => '薄膜機 #5',
        'status' => 'warning',
        'utilization' => '78.2%',
        'capacity' => '198/250'
    ],
    [
        'id' => 'ET3-EQ012',
        'name' => '檢測設備 #12',
        'status' => 'online',
        'utilization' => '92.8%',
        'capacity' => '232/250'
    ]
];
?>

<!-- 主要內容 -->
<main class="main-content" id="mainContent">
    
    <!-- 今日重點機台 -->
    <h2 style="margin-bottom: 20px; color: #0066cc;">📌 今日重點機台</h2>
    <div class="dashboard-grid">
        <?php foreach ($todayEquipment as $equipment): ?>
            <div class="card equipment-card">
                <div class="equipment-header">
                    <div class="equipment-id"><?php echo htmlspecialchars($equipment['id']); ?></div>
                    <span class="status-indicator <?php echo $equipment['status']; ?>"></span>
                </div>
                <div style="margin-bottom: 10px;">
                    <span class="status-badge <?php echo $equipment['status'] === 'online' ? 'success' : 'warning'; ?>">
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
                        <span class="detail-label">更新時間</span>
                        <span class="detail-value" style="font-size: 12px;"><?php echo htmlspecialchars($equipment['lastUpdate']); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- WIP Trend Chart -->
    <div class="chart-container">
        <div class="card-header">
            <h2 class="card-title">📈 WIP 趨勢圖表（過去 7 天）</h2>
        </div>
        <div class="chart-wrapper">
            <canvas id="wipTrendChart"></canvas>
        </div>
    </div>
    
    <!-- 重點機台機況現況 -->
    <h2 style="margin: 30px 0 20px; color: #0066cc;">⚙️ 重點機台機況現況</h2>
    <div class="dashboard-grid">
        <?php foreach ($equipmentStatus as $eq): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo htmlspecialchars($eq['id']); ?></h3>
                    <span class="status-indicator <?php echo $eq['status']; ?>"></span>
                </div>
                <div class="card-content">
                    <p style="margin-bottom: 15px; color: #ffffff; font-weight: 600;">
                        <?php echo htmlspecialchars($eq['name']); ?>
                    </p>
                    <div class="detail-row">
                        <span class="detail-label">稼動率</span>
                        <span class="detail-value"><?php echo htmlspecialchars($eq['utilization']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">產能</span>
                        <span class="detail-value"><?php echo htmlspecialchars($eq['capacity']); ?> 片</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- 機況頁面連結 -->
    <h2 style="margin: 30px 0 20px; color: #0066cc;">🔗 機況詳細頁面</h2>
    <div class="dashboard-grid">
        <a href="/pages/equipment_status.php" style="text-decoration: none;">
            <div class="card link-card">
                <div class="card-icon">📊</div>
                <h3 class="card-title">ET1/2 機況</h3>
                <p style="margin-top: 10px; font-size: 14px;">EQ1 / EQ2 設備狀態</p>
            </div>
        </a>
        
        <a href="/pages/equipment_et3.php" style="text-decoration: none;">
            <div class="card link-card">
                <div class="card-icon">⚙️</div>
                <h3 class="card-title">ET3 機況</h3>
                <p style="margin-top: 10px; font-size: 14px;">EQ3 設備狀態</p>
            </div>
        </a>
        
        <a href="#" onclick="alert('功能開發中...'); return false;" style="text-decoration: none;">
            <div class="card link-card">
                <div class="card-icon">📈</div>
                <h3 class="card-title">數據報表</h3>
                <p style="margin-top: 10px; font-size: 14px;">完整數據分析報表</p>
            </div>
        </a>
    </div>
    
</main>

<?php
// 包含頁面底部
include __DIR__ . '/includes/footer.php';
?>
