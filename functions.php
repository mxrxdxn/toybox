<?php

use Maxweb\Toybox\Theme;

require_once(get_theme_file_path("vendor/autoload.php"));

// Boot the theme up
Theme::boot();

// Register any nav menus
Theme::setMenus([
    'header_nav' => __("Header Navigation", "toybox"),
    'footer_nav' => __("Footer Navigation", "toybox"),
]);

// You're all done.
