<?php

use Toybox\Core\Components\Blocks;

$pageTemplates = get_field("page_templates", "options");

?>

<?php get_header() ?>
    <main>
        <?= Blocks::renderContentString(get_the_content(post: $pageTemplates["404"])) ?>
    </main>
<?php get_footer() ?>