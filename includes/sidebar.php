<?php
/**
 * 左側選單
 * Sidebar Menu
 */
?>
<!-- 左側選單 -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>系統選單</h2>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="/home.php">
                <span class="icon">🏠</span>
                <span>首頁</span>
            </a>
        </li>
        <li>
            <a href="/pages/equipment_status.php">
                <span class="icon">📊</span>
                <span>機台管理</span>
            </a>
        </li>
        <li>
            <a href="/pages/equipment_et3.php">
                <span class="icon">⚙️</span>
                <span>ET3 機況</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="alert('功能開發中...'); return false;">
                <span class="icon">📈</span>
                <span>數據分析</span>
            </a>
        </li>
        <li>
            <a href="#" onclick="alert('功能開發中...'); return false;">
                <span class="icon">⚙️</span>
                <span>系統設定</span>
            </a>
        </li>
        <li style="margin-top: 30px; border-top: 1px solid rgba(0, 102, 204, 0.2); padding-top: 10px;">
            <a href="/logout.php" style="color: #ff6b6b;">
                <span class="icon">🚪</span>
                <span>登出</span>
            </a>
        </li>
    </ul>
</aside>
