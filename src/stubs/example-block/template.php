<?php

/**
 * Example Block Template.
 *
 * @var array      $block      The block settings and attributes.
 * @var string     $content    The block inner HTML (empty).
 * @var bool       $is_preview True during AJAX preview.
 * @var int|string $post_id    The post ID this block is saved to.
 */

$id = 'example-' . $block['id'];

?>

<div class="block-example <?= $block['className'] ?>" id="<?= $id ?>">
    <!-- Your block content goes here. -->
</div>
