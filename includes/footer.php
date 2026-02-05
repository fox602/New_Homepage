<?php
/**
 * 頁面底部
 * Page Footer
 */
?>
    <!-- JavaScript -->
    <script src="<?php echo ASSETS_PATH; ?>js/main.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/sidebar.js"></script>
    <?php if (isset($extraJS)): ?>
        <?php foreach ($extraJS as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
