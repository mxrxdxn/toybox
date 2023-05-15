# Toybox
Toybox is a simple block-based theme boilerplate for WordPress v6+ and PHP 8+.

## Features
* Full support for Gutenberg.
* OOP-based theme engine.
* Support for deferred loading of images, background images, CSS and Javascript files.
* Simplified asset build system via Laravel Mix.
* Command line interface.

## Installation
* Run the following command in your `wp-content/themes` directory to download and install the theme (and its dependencies):
```bash
composer create-project maxweb/toybox toybox --repository="{\"url\": \"git@bitbucket.org:liam-maxweb/toybox.git\", \"type\": \"vcs\"}" --stability=dev --remove-vcs
```
* Run `npm i`.

## Compiling Assets
Toybox makes use of Laravel Mix for asset compilation.

To compile assets, run `npm run build`.

To compile a production build, run `npm run build:prod`. This will minify all output files and omit map files. (It will not remove existing map files by default).

To run the watcher script to recompile assets when their contents change, run `npm run watch`.

## Deferred Loading
Toybox includes built-in deferring systems to increase performance.
### Defer Images
To defer load images, swap the `src` attribute on an `img` element with `data-lazy`:
```html
<img data-lazy="/path/to/image.jpg">
```

### Defer Background Images
To defer load background images, add the background image URL to the `data-lazy` attribute of any non-`img` element:
```html
<div data-lazy="/path/to/image.jpg">
    Content
</div>
```

### Defer CSS
To defer load CSS, add the stylesheet's URL to the `data-lazy-css` attribute of any element:
```html
<div data-lazy-css="/path/to/stylesheet.css"></div>
```

### Defer JS
To defer load JS, add the script's URL to the `data-lazy-js` attribute of any element:
```html
<div data-lazy-js="/path/to/script.js"></div>
```

## Helpers
Toybox ships with some theme-wide helper functions:

### `mix(string $fileName, string|null $manifestPath = null)`
The `mix()` function retrieves the path to a given CSS/JS file, relative to the base directory of the theme. This includes any cachebuster strings added by Mix.

### `uri(string $fileName)`
The `uri()` function fetches the path to a given file, relative to the base directory of the theme.

### `slugify($title, $separator = '-')`
The `slugify()` function retrieves a URL-friendly representation of a string.

### `singularize($value)`
The `singularize()` function returns the singular form of a given word or phrase.

### `pluralize($value, $count)`
The `pluralize()` function returns the plural form of a given word or phrase, provided `$count` is not `1`.

### `matchCase($value, $comparison)`
The `matchCase()` function attempts to match the casing between two given strings.

### `field($fieldName, $postID)`
A WordPress-friendly replacement for ACF's `get_field()` function.

### `repeater($fieldName, $postID, $subFields)`
A WordPress-friendly replacement for ACF's `get_field()` function that returns fields from a repeater.

### `group($fieldName, $postID, $subFields)`
A WordPress-friendly replacement for ACF's `get_field()` function that returns fields from a group.

### `image_url($imageID)`
A function to retrieve an attachment's URL from an attachment ID.

### `image_alt($imageID)`
A function to retrieve an attachment's `alt` text from an attachment ID.

## Command Line
Toybox includes a command line interface. To access Toybox on the command line, enter the theme directory in your favourite terminal client and run the following:

```bash
php toybox
```

### Make Block
You can create a block with the `make:block` command.
```bash
php toybox make:block "My Block" --without-styles --without-js
```

| Argument/Option  | Example    | Required | Description                                   |
|------------------|------------|----------|-----------------------------------------------|
| Block Name       | "My Block" | Yes      | The name of your block.                       |
| --without-styles |            | No       | Skip creating the block style and directory.  |
| --without-js     |            | No       | Skip creating the block script and directory. |

This will create your block under the `/blocks` directory.

### Make Shortcode
You can create a shortcode with the `make:shortcode` command.
```bash
php toybox make:shortcode "My Shortcode" --with-styles --with-js
```

| Argument/Option | Example        | Required | Description                                               |
|-----------------|----------------|----------|-----------------------------------------------------------|
| Shortcode Name  | "My Shortcode" | Yes      | The name of your shortcode.                               |
| --with-styles   |                | No       | Creates the shortcode style (and directory if required).  |
| --with-js       |                | No       | Creates the shortcode script (and directory if required). |

This will create your shortcode under the `/shortcodes` directory.

### Make Post Type
You can create a post type with the `make:post-type` command.
```bash
php toybox make:post-type "Services"
```

| Argument/Option | Example        | Required | Description                 |
|-----------------|----------------|----------|-----------------------------|
| Post Type Name  | "My Post Type" | Yes      | The name of your post type. |

This will create your post type under the `/post-types

### WebP Conversion
You can convert a single image, or a path containing images to WebP format with the `images:convert` command.
```bash
php toybox images:convert -p images/image.png
```

```bash
php toybox images:convert -p images
```

Note that this command will not overwrite the original image - it will create a new image from the original source and append the ".webp" extension to the end.

It will also only convert the following file types when processing a directory: .jpg, .jpeg, .png.

| Argument/Option | Example  | Required | Description                                                                                                                                                                                                                                                                |
|-----------------|----------|----------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Path            | "images" | Yes      | The path to convert. You can either use a path relative to the theme root, or an absolute path. Additionally, if the path given is a directory, the command will recurse over all files and directories inside the given path and convert all supported filetypes to WebP. |
| Quality         | 75       | No       | The quality to use during conversion, 0-100. The closer to 100, the better quality (and larger filesize) the output will be.                                                                                                                                               |

### Export Block
The `export:block` command exports any ACF field groups associated with a given block into the block's own `acf-json` folder. You must run this command if you are making the block redistributable.

```bash
php toybox export:block "My Block"
```

| Argument/Option                      | Example             | Required                                         | Description                                                                                                                                    |
|--------------------------------------|---------------------|--------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------|
| Block Name                           | "My Block"          | Yes                                              | The name of the block to export settings for.                                                                                                  |
| --domain=mysite.com                  | mysite.com          | No (Yes when on multisite WordPress)             | When using Toybox on a WordPress multisite, you must tell WordPress which domain you are using so that it can connect to the correct database. |
| --fieldgroup / -g "Field Group Name" | "My Block Settings" | No (prompts for the field group if not provided) | The name of the field group to export.                                                                                                         |
| --location / -l                      |                     | No                                               | Export all field groups that have been assigned to the block.                                                                                  |

## Snippets
Toybox ships with a built-in snippets feature. The idea of snippets is they are individual pieces of code that you want to load as part of the theme that don't need to be built into the theme core.
As you may want these on different hooks or filters, they don't load on a particlar hook, and instead load during the theme's `boot()` method.

To create a snippet, put your code into a PHP file in the `snippets` directory. Be sure to use WordPress' `add_action` or `add_filter` (or another such function) with an appropriate priority to avoid adding unnecessary overhead to every page load.

