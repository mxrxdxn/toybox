<?php

add_shortcode('example-shortcode', function ($attributes) {
    // Build the attributes
    $attributes = shortcode_atts([
        // 'key' => 'default value',
    ], $attributes, 'example-shortcode');

    // Return a string
    // return "";

    // Return a template
    ob_start();
    get_template_part('shortcodes/example-shortcode/template', null, $attributes);
    return ob_get_clean();
});