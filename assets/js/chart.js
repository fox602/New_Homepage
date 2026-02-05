/**
 * 圖表功能（使用 Chart.js）
 * Chart Functions using Chart.js
 */

/**
 * 初始化 WIP 趨勢圖表
 */
function initWIPTrendChart() {
    const ctx = document.getElementById('wipTrendChart');
    if (!ctx) return;
    
    // 預設數據（模擬用）
    const defaultData = generateMockWIPData();
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: defaultData.labels,
            datasets: [{
                label: 'WIP 數量',
                data: defaultData.values,
                borderColor: '#0066cc',
                backgroundColor: 'rgba(0, 102, 204, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#0066cc',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#ffffff',
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(26, 31, 58, 0.95)',
                    titleColor: '#0066cc',
                    bodyColor: '#ffffff',
                    borderColor: '#0066cc',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return ' WIP: ' + context.parsed.y + ' 片';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#a0a0a0',
                        callback: function(value) {
                            return value;
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: '#a0a0a0'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)',
                        drawBorder: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
    
    // 嘗試從 API 載入實際數據
    loadWIPDataFromAPI(chart);
    
    return chart;
}

/**
 * 生成模擬 WIP 數據
 */
function generateMockWIPData() {
    const labels = [];
    const values = [];
    const today = new Date();
    
    for (let i = 6; i >= 0; i--) {
        const date = new Date(today);
        date.setDate(date.getDate() - i);
        
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        labels.push(`${month}/${day}`);
        
        // 生成模擬數據（1800-2500 之間）
        values.push(Math.floor(Math.random() * 700) + 1800);
    }
    
    return { labels, values };
}

/**
 * 從 API 載入 WIP 數據
 */
function loadWIPDataFromAPI(chart) {
    fetch('/api/get_wip_data.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.labels && data.values) {
                chart.data.labels = data.labels;
                chart.data.datasets[0].data = data.values;
                chart.update();
            }
        })
        .catch(error => {
            console.log('使用模擬數據（API 尚未實作）:', error.message);
        });
}

/**
 * 初始化機台狀態圓餅圖（範例）
 */
function initEquipmentStatusChart(canvasId) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['運行中', '待機中', '維修中'],
            datasets: [{
                data: [12, 5, 2],
                backgroundColor: [
                    '#6bcf7f',
                    '#ffd93d',
                    '#ff6b6b'
                ],
                borderWidth: 2,
                borderColor: '#1a1f3a'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#ffffff',
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(26, 31, 58, 0.95)',
                    titleColor: '#0066cc',
                    bodyColor: '#ffffff',
                    borderColor: '#0066cc',
                    borderWidth: 1
                }
            }
        }
    });
    
    return chart;
}

// 頁面載入時初始化圖表
document.addEventListener('DOMContentLoaded', function() {
    initWIPTrendChart();
});
