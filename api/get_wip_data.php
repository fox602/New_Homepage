<?php
/**
 * WIP 數據 API
 * WIP Data API Endpoint
 */

header('Content-Type: application/json');

// 模擬 WIP 數據
// 實際使用時，這裡應該從資料庫取得真實數據

$labels = [];
$values = [];
$today = new DateTime();

// 生成過去 7 天的數據
for ($i = 6; $i >= 0; $i--) {
    $date = clone $today;
    $date->modify("-{$i} days");
    
    $labels[] = $date->format('m/d');
    
    // 模擬數據：1800-2500 之間的隨機數
    $values[] = rand(1800, 2500);
}

// 回傳 JSON 格式數據
$response = [
    'success' => true,
    'labels' => $labels,
    'values' => $values,
    'timestamp' => date('Y-m-d H:i:s')
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
