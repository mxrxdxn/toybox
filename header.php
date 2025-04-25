<?php

use Toybox\Core\Components\Customizer;
use Toybox\Core\Components\Globals;

?>

<!doctype html>
<html <?php language_attributes() ?> class="no-js" lang="en-GB">
<head>
    <meta charset="<?php bloginfo('charset') ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Theme Colour -->
    <meta name="theme-color" content="<?= ! empty(Customizer::get("toybox_additional_settings_theme_color")) ? Customizer::get("toybox_additional_settings_theme_color") : "#FFF" ?>">

    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head() ?>

    <!-- Preconnect to Google Font Services -->
    <?php /*
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@100;300;400;500;700&display=swap" rel="stylesheet">
    */ ?>

    <!-- Defers load of style.css -->
    <link rel="preload" href="<?= mix("/assets/css/style.css") ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?= mix("/assets/css/style.css") ?>">
    </noscript>

    <?= Globals::headerCode() ?>
</head>
<body <?php body_class() ?>>
    <header>
        <!-- Header -->
    </header>