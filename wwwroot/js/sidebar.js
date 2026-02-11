// 側邊欄互動功能
(function() {
    'use strict';

    // DOM 元素
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const mainContent = document.querySelector('.main-content');
    const menuItems = document.querySelectorAll('.sidebar-menu-item.has-submenu');

    // 從 localStorage 載入狀態
    function loadSidebarState() {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed && window.innerWidth > 768) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('sidebar-collapsed');
        }
    }

    // 儲存狀態到 localStorage
    function saveSidebarState(isCollapsed) {
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }

    // 切換側邊欄展開/收合
    function toggleSidebar() {
        if (window.innerWidth > 768) {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('sidebar-collapsed');
            saveSidebarState(sidebar.classList.contains('collapsed'));
        } else {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('active');
        }
    }

    // 關閉側邊欄 (手機版)
    function closeMobileSidebar() {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('active');
    }

    // 切換子選單
    function toggleSubmenu(menuItem) {
        menuItem.classList.toggle('open');
    }

    // 設定當前活動連結
    function setActiveLink() {
        const currentPath = window.location.pathname;
        const links = document.querySelectorAll('.sidebar-menu-link, .sidebar-submenu-link');
        
        links.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href) && href !== '/') {
                link.classList.add('active');
                
                // 如果是子選單項目，展開父選單
                const parentSubmenu = link.closest('.sidebar-submenu');
                if (parentSubmenu) {
                    const parentItem = parentSubmenu.closest('.sidebar-menu-item');
                    if (parentItem) {
                        parentItem.classList.add('open');
                    }
                }
            } else if (href === '/' && currentPath === '/') {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    // 事件監聽器
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', toggleSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeMobileSidebar);
    }

    // 子選單切換
    menuItems.forEach(item => {
        const link = item.querySelector('.sidebar-menu-link');
        if (link) {
            link.addEventListener('click', (e) => {
                // 如果有子選單且不是收合狀態
                if (!sidebar.classList.contains('collapsed')) {
                    e.preventDefault();
                    toggleSubmenu(item);
                }
            });
        }
    });

    // 視窗大小改變時處理
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            closeMobileSidebar();
            loadSidebarState();
        } else {
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('sidebar-collapsed');
        }
    });

    // 初始化
    loadSidebarState();
    setActiveLink();
})();
