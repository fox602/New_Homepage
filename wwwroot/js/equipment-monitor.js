// 機台監控功能
(function() {
    'use strict';

    // 自動重新整理設備狀態 (可選)
    let autoRefreshInterval = null;

    // 啟用自動重新整理
    function enableAutoRefresh(intervalSeconds = 30) {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }

        autoRefreshInterval = setInterval(() => {
            refreshEquipmentStatus();
        }, intervalSeconds * 1000);
    }

    // 停用自動重新整理
    function disableAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
        }
    }

    // 重新整理設備狀態
    function refreshEquipmentStatus() {
        // 重新載入頁面或使用 AJAX 更新
        // 目前使用簡單的重新載入
        // 可擴充為 AJAX 呼叫或 SignalR
        console.log('Refreshing equipment status...');
        // location.reload(); // 取消註解以啟用自動重新整理
    }

    // 初始化設備監控表格
    function initEquipmentTable() {
        // 為表格行添加點擊效果
        const tableRows = document.querySelectorAll('.equipment-table tbody tr');
        
        tableRows.forEach(row => {
            row.addEventListener('click', function() {
                this.classList.toggle('selected');
            });
        });
    }

    // 格式化參數值
    function formatParameter(value, unit) {
        return `${value.toFixed(1)}${unit}`;
    }

    // 取得狀態顏色
    function getStatusColor(status) {
        switch(status.toLowerCase()) {
            case 'online':
                return '#6bcf7f';
            case 'warning':
                return '#ffd93d';
            case 'offline':
                return '#ff6b6b';
            default:
                return '#a8b2d1';
        }
    }

    // 頁面載入時初始化
    document.addEventListener('DOMContentLoaded', function() {
        initEquipmentTable();
        
        // 如果需要自動重新整理，取消註解下面這行
        // enableAutoRefresh(60); // 每 60 秒重新整理
    });

    // 匯出給其他腳本使用
    window.EquipmentMonitor = {
        enableAutoRefresh: enableAutoRefresh,
        disableAutoRefresh: disableAutoRefresh,
        refreshEquipmentStatus: refreshEquipmentStatus,
        formatParameter: formatParameter,
        getStatusColor: getStatusColor
    };
})();
