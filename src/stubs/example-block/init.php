<?php

use Maxweb\Toybox\Theme;

$assets = [
    "css" => [
        "example-block-css" => mix("/assets/css/blocks/example-block.css") ?? false,
    ],

    "js" => [
        "example-block-js" => mix("/assets/js/blocks/example-block.js") ?? false,
    ],
];

Theme::registerBlockAssets($assets);
register_block_type(__DIR__);
