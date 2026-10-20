</div><!-- .app-layout -->
<div id="app-toast"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/kasir/assets/js/app.js?v=<?= filemtime(__DIR__ . '/../assets/js/app.js') ?>"></script>
<?php if (isset($extra_js)): ?>
<script src="<?= $extra_js ?>"></script>
<?php endif; ?>
<?php if (isset($inline_js)): ?>
<script><?= $inline_js ?></script>
<?php endif; ?>
</body>
</html>
