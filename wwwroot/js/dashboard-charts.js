// 儀表板圖表功能
(function() {
    'use strict';

    // 等待 DOM 和 Chart.js 載入完成
    document.addEventListener('DOMContentLoaded', function() {
        initWipChart();
    });

    // 初始化 WIP 趨勢圖表
    function initWipChart() {
        const chartCanvas = document.getElementById('wipTrendChart');
        if (!chartCanvas) return;

        // 檢查 Chart.js 是否已載入
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return;
        }

        // 從 API 獲取資料
        fetch('/Home/GetWipChartData')
            .then(response => response.json())
            .then(data => {
                createWipChart(chartCanvas, data);
            })
            .catch(error => {
                console.error('Error loading WIP chart data:', error);
            });
    }

    // 建立 WIP 圖表
    function createWipChart(canvas, data) {
        const ctx = canvas.getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#a8b2d1',
                            font: {
                                size: 12,
                                family: "'Segoe UI', 'Microsoft JhengHei', Arial, sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 31, 58, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#a8b2d1',
                        borderColor: '#00a8ff',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            color: '#a8b2d1',
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        },
                        grid: {
                            color: 'rgba(42, 49, 80, 0.5)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#a8b2d1',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(42, 49, 80, 0.3)',
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
    }

    // 更新設備卡片 (模擬即時更新)
    function updateEquipmentCards() {
        // 這裡可以實作即時更新設備狀態
        // 目前使用靜態資料，可擴充為 SignalR 或定時輪詢
    }

    // 匯出給其他腳本使用
    window.DashboardCharts = {
        initWipChart: initWipChart,
        updateEquipmentCards: updateEquipmentCards
    };
})();
