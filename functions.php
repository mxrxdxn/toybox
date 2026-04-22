<?php

use Toybox\Core\Components\Menus;
use Toybox\Core\Debug\ErrorHandler;
use Toybox\Core\Theme;

if (! defined("TOYBOX_DIR")) {
    define("TOYBOX_DIR", __DIR__);
}

require_once(get_theme_file_path("vendor/autoload.php"));

// Enable pretty page errors
ErrorHandler::boot(E_ALL & ~E_DEPRECATED);

// Boot the theme up
Theme::boot();

// Register any nav menus
Menus::set([
    'header_nav' => __("Header Navigation", "toybox"),
    'footer_nav' => __("Footer Navigation", "toybox"),
]);

// Set default theme settings
Theme::safeDefaults();

// You're all done.
