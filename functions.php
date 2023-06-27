<?php

use Toybox\Core\Components\AdminBar;
use Toybox\Core\Components\Comments;
use Toybox\Core\Components\Embeds;
use Toybox\Core\Components\Emoji;
use Toybox\Core\Components\HTTP;
use Toybox\Core\Components\Menus;
use Toybox\Core\Components\Misc;
use Toybox\Core\Components\Settings;
use Toybox\Core\Components\Visitor;
use Toybox\Core\Components\XMLRPC;
use Toybox\Core\Debug\ErrorHandler;
use Toybox\Core\Theme;

if (! defined("TOYBOX_DIR")) {
    const TOYBOX_DIR = __DIR__;
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

// Set custom image sizes
Misc::setImageSizes([
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
AdminBar::disable();

// Disable emoji
Emoji::disable();

// Disable embeds
Embeds::disable();

// Disable comments
Comments::disable();

// Stop WordPress auto-changing "Wordpress" to "WordPress".
Misc::disableCapitalPDangit();

// Disable XMLRPC.
XMLRPC::disable();

// Adds support for additional file types
Misc::addFileSupport([
    "svg"  => "image/svg+xml",
    "webp" => "image/webp",
]);

// Setup settings pages
Settings::registerPage([
    'page_title' => 'Theme General Settings',
    'menu_title' => 'Theme Settings',
    'menu_slug'  => 'theme-general-settings',
    'capability' => 'edit_posts',
    'redirect'   => false,
]);

Settings::registerPage([
    'page_title'  => 'Contact Information',
    'menu_title'  => 'Contact',
    'parent_slug' => 'theme-general-settings',
]);

Settings::registerPage([
    'page_title'  => 'Social Media',
    'menu_title'  => 'Social Media',
    'parent_slug' => 'theme-general-settings',
]);

// Add early hints
HTTP::hint(mix('/assets/css/critical.css'), "rel=preload; as=style");

// You're all done.
