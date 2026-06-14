    </div>
</main>
<div class="toast-container" id="toastContainer"></div>
<script src="<?php echo app_url('assets/js/main.js'); ?>"></script>
<script src="<?php echo app_url('assets/js/dashboard.js'); ?>"></script>
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('<?php echo app_url('sw.js'); ?>');
    });
}
</script>
</body>
</html>
