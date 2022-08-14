<?php

acf_register_block_type([
    // A unique name for the block.
    "name" => "example-block",

    // The block title.
    "title" => __("Example Block"),

    // The block description.
    "description" => __("An example block."),

    // The category for the block. Options are [ common | formatting | layout | widgets | embed ].
    "category" => "common",

    // The icon to display for the block.
    "icon" => "align-wide",

    // These are the keywords that will help you search for the block.
    "keywords" => [
        //
    ],

    // If uncommented, this will control what  post types this block is enabled for.
    // "post_types" => [
    //     'post',
    //     'page',
    // ],

    // The display mode for the block. Options are [ auto | preview | edit ].
    "mode" => "preview",

    // The template to render for the block. You shouldn't need to alter this.
    "render_template" => __DIR__ . "/template.php",

    // Enqueue a style to load with the block.
    // "enqueue_style" => mix("/css/blocks/example-block.css"),

    // Enqueue a script to load with the block.
    // "enqueue_script" => mix("/js/blocks/example-block.js"),

    // Enqueue styles and scripts for the block.
    // "enqueue_assets" => function () {
    //     wp_enqueue_style();
    //     wp_enqueue_script();
    // },

    // An array of features to support. See https://developer.wordpress.org/block-editor/reference-guides/block-api/block-supports/ for supported values.
    // "supports" => [
        // "jsx" => true,
    // ],
]);
