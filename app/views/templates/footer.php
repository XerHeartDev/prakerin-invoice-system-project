<footer class="<?= isset($data['footerClass']) ? $data['footerClass'] : '' ?>">
    <div class="float-end d-none d-sm-inline fst-italic fw-bold">Jangan Lupa Titik Koma;</div>
    <strong>
        Copyright &copy; 2025-2026&nbsp;
        <a href="" class="text-decoration-none">XerHeartID</a>.
    </strong>
    All rights reserved.
</footer>
</div>
</body>
<!-- Third Party Plugin(OverlayScrollbars) -->
<script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
    crossorigin="anonymous"></script>
<!-- Required Plugin -->
<script src="<?= BASEURL ?>/js/bootstrap/bootstrap.js"></script>
<script src="<?= BASEURL ?>/js/adminLTE/adminlte.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- OverlayScrollbars Configure -->
<script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script>

</html>