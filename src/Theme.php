<?php

namespace Maxweb\Toybox;

use Exception;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Theme
{
    /**
     * The theme version.
     */
    const VERSION = "1.0.0";

    /**
     * Boots the theme.
     *
     * @return void
     * @throws Exception
     */
    public static function boot(): void
    {
        // Theme setup
        self::setup();

        // Enqueue styles and scripts
        self::scripts();

        // Register blocks
        self::registerBlocks();

        // Register options page
        self::registerOptionsPage();
    }

    /**
     * Runs actions in the "after_setup_theme" hook.
     *
     * @return void
     * @throws Exception
     */
    private static function setup(): void
    {
        add_action("after_setup_theme", function () {
            // Register the error handler, but only if we're in a debug environment.
            // Otherwise, there could be information disclosure.
            if (defined("WP_DEBUG") && WP_DEBUG === true) {
                // Sets the error reporting level - while we're in debug mode
                // we should really show all errors, even if they're just
                // warnings or notices.
                error_reporting(E_ALL);

                $whoops = new Run();
                $whoops->pushHandler(new PrettyPageHandler());
                $whoops->register();
            }

            // Add default posts and comments RSS feed links to <head>.
            add_theme_support('automatic-feed-links');

            // Enable support for post thumbnails and featured images.
            add_theme_support('post-thumbnails');

            // Add the editor stylesheet (includes some core Gutenberg fixes)
            add_theme_support('editor-styles');
            add_editor_style('css/editor.css');

            // Add support for block styles
            add_theme_support('wp-block-styles');

            // Add support for responsive embeds
            add_theme_support('responsive-embeds');

            // Title tag support
            add_theme_support('title-tag');

            // HTML5 support
            add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script']);

            // Disable the admin bar
            add_filter('show_admin_bar', '__return_false');
        });

        // Enqueue the editor.js file when we're in the block editor.
        add_action('enqueue_block_editor_assets', function () {
            wp_enqueue_script('editor-js', mix('/js/editor.js'), [], '1.0.0', 'true');
        });

        // Tweak ACF WYSIWYG
        add_filter('acf/fields/wysiwyg/toolbars', function ($toolbars) {
            // Edit the "Full" toolbar and add 'fontsizeselect' if not already present.
            if (($key = array_search('fontsizeselect', $toolbars['Full'][2])) !== true) {
                array_push($toolbars['Full'][2], 'fontsizeselect');
            }

            return $toolbars;
        });
    }

    /**
     * Enqueues any styles or scripts required by the theme.
     *
     * @return void
     * @throws Exception
     */
    private static function scripts(): void
    {
        add_action("wp_enqueue_scripts", function () {
            wp_enqueue_style('critical', mix('/css/critical.css'));
        });
    }

    /**
     * Autoload blocks from the /blocks directory.
     *
     * @return void
     */
    private static function registerBlocks(): void
    {
        if (function_exists('acf_register_block_type')) {
            $path = get_theme_file_path() . "/blocks";

            if (file_exists($path)) {
                foreach (glob("{$path}/*") as $blockDir) {
                    // Load the block
                    add_action("init", function () use ($blockDir) {
                        require_once("{$blockDir}/init.php");
                    });

                    // Also load the ACF JSON
                    add_filter('acf/settings/load_json', function ($paths) use ($blockDir) {
                        // Add the path
                        $paths[] = "{$blockDir}/acf-json";

                        return $paths;
                    });
                }
            }
        }
    }

    /**
     * Registers the theme's option pages using ACF. These pages can then be populated via ACF.
     * If ACF is not installed, nothing will fire.
     *
     * @return void
     */
    private static function registerOptionsPage(): void
    {
        if (function_exists('acf_add_options_page')) {
            // Main Settings Page
            acf_add_options_page([
                'page_title' 	=> 'Theme General Settings',
                'menu_title'	=> 'Theme Settings',
                'menu_slug' 	=> 'theme-general-settings',
                'capability'	=> 'edit_posts',
                'redirect'		=> false
            ]);
        }

        if (function_exists('acf_add_options_sub_page')) {
            // Contact Information
            acf_add_options_sub_page([
                'page_title' 	=> 'Contact Information',
                'menu_title'	=> 'Contact',
                'parent_slug'	=> 'theme-general-settings',
            ]);

            // Social Media
            acf_add_options_sub_page([
                'page_title' 	=> 'Social Media',
                'menu_title'	=> 'Social Media',
                'parent_slug'	=> 'theme-general-settings',
            ]);
        }
    }

    /**
     * Registers menu locations and names in WordPress.
     *
     * Uses the same syntax as the native `register_nav_menus` function:
     *
     * ```
     * [
     *     'menu_location' => __("Menu Name", "text-domain")
     * ]
     * ```
     *
     * Text domain isn't required - in fact, you can completely omit the __()
     * call and just pass in a string if you want.
     *
     * @param array $menus
     *
     * @return void
     */
    public static function setMenus(array $menus = []): void
    {
        // Register menus
        add_action("after_setup_theme", function () use ($menus) {
            // Register menus
            register_nav_menus($menus);
        });
    }

    /**
     * Registers a custom post type.
     *
     *
     * `$labels` can either be a full array of supported labels, or just an
     * array containing singular and plural keys.
     *
     *
     * `$args` is the additional data usually passed as the second argument
     * to `register_post_type()`.
     *
     * @param string $name
     * @param array  $labels
     * @param array  $args
     *
     * @return void
     */
    public static function registerPostType(string $name, array $labels, array $args): void
    {
        // Register the post type
        add_action("init", function () use ($name, $labels, $args) {
            // Check $labels - if there's two keys, and they are "singular" and
            // "plural", use default label strings. Otherwise, assume we are
            // overriding the labels entirely.
            if (self::isSingularAndPluralLabels($labels)) {
                $singular = ucwords($rawSingular = strtolower($labels['singular']));
                $plural   = ucwords($rawPlural   = strtolower($labels['plural']));

                $args['labels'] = [
                    "name"                  => _x($plural, "{$singular} General Name", "toybox"),
                    "singular_name"         => _x($singular, "{$singular} Singular Name", "toybox"),
                    "menu_name"             => __($plural, "toybox"),
                    "name_admin_bar"        => __($singular, "toybox"),
                    "archives"              => __("{$singular} Archives", "toybox"),
                    "attributes"            => __("{$singular} Attributes", "toybox"),
                    'parent_item_colon'     => __("Parent {$singular}:", "toybox"),
                    "all_items"             => __("All {$plural}", "toybox"),
                    "add_new_item"          => __("Add New {$singular}", "toybox"),
                    "add_new"               => __("Add New", "toybox"),
                    "new_item"              => __("New {$singular}", "toybox"),
                    "edit_item"             => __("Edit {$singular}", "toybox"),
                    "update_item"           => __("Update {$singular}", "toybox"),
                    "view_item"             => __("View {$singular}", "toybox"),
                    "view_items"            => __("View {$plural}", "toybox"),
                    "search_items"          => __("Search {$singular}", "toybox"),
                    "not_found"             => __("Not found", "toybox"),
                    "not_found_in_trash"    => __("Not found in Trash", "toybox"),
                    "featured_image"        => __("Featured Image", "toybox"),
                    "set_featured_image"    => __("Set featured image", "toybox"),
                    "remove_featured_image" => __("Remove featured image", "toybox"),
                    "use_featured_image"    => __("Use as featured image", "toybox"),
                    "insert_into_item"      => __("Insert into {$rawSingular}", "toybox"),
                    "uploaded_to_this_item" => __("Uploaded to this {$rawSingular}", "toybox"),
                    "items_list"            => __("{$plural} list", "toybox"),
                    "items_list_navigation" => __("{$plural} list navigation", "toybox"),
                    "filter_items_list"     => __("Filter {$rawPlural} list", "toybox"),
                ];
            } else {
                $args['labels'] = $labels;
            }

            // Set the standard label if not already set
            if (! array_key_exists('label', $args)) {
                $args['label'] = __($singular, "toybox");
            }

            register_post_type($name, $args);
        });
    }

    /**
     * Checks $labels to see if it's just singular and plural labels, or an
     * entire override.
     *
     * @param array $labels
     *
     * @return bool
     */
    private static function isSingularAndPluralLabels(array $labels): bool
    {
        // Must have just two elements
        if (count($labels) !== 2) {
            return false;
        }

        // Get the array keys
        $keys = array_keys($labels);

        // Make sure "singular" and "plural" are present as keys
        if (! in_array('singular', $keys) || ! in_array('plural', $keys)) {
            return false;
        }

        return true;
    }
}