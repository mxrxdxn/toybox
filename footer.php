<?php

use Toybox\Core\Components\Assets;
use Toybox\Core\Components\Footer;

?>
    <footer>
        <!-- Footer -->
    </footer>

    <?php wp_footer() ?>
    <script src="<?= Assets::getPath("/resources/js/app.js") ?>" type="module"></script>

    <?= Footer::code() ?>
</body>
</html>
