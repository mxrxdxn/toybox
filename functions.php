<?php

use Toybox\Core\Theme;

const TOYBOX_DIR = __DIR__;

require_once(get_theme_file_path("vendor/autoload.php"));

// Enable pretty page errors
Theme::bootErrorHandler(E_ALL & ~E_DEPRECATED);

// Boot the theme up
Theme::boot();

// Register any nav menus
Theme::setMenus([
    'header_nav' => __("Header Navigation", "toybox"),
    'footer_nav' => __("Footer Navigation", "toybox"),
]);

// Set custom image sizes
Theme::setImageSizes([
    "toybox-small" => [
        "name"   => "Square (small)",
        "width"  => 64,
        "height" => 64,
        "crop"   => true,
    ],

    "toybox-medium" => [
        "name"   => "Square (medium)",
        "width"  => 128,
        "height" => 128,
        "crop"   => true,
    ],

    "toybox-large" => [
        "name"   => "Square (large)",
        "width"  => 256,
        "height" => 256,
        "crop"   => true,
    ],
]);

// Disable the WordPress admin bar
Theme::disableAdminBar();

// Disable emoji
Theme::disableEmoji();

// Disable embeds
Theme::disableEmbeds();

// Disable comments
Theme::disableComments();

// You're all done.
