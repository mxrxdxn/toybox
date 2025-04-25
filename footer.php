<?php

use Toybox\Core\Components\Globals;

?>
    <footer>
        <!-- Footer -->
    </footer>

    <?php wp_footer() ?>
    <script src="<?= mix('/assets/js/app.js') ?>"></script>

    <?= Globals::footerCode() ?>
</body>
</html>
