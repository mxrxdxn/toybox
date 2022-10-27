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

## Command Line
Toybox includes a command line interface. To access Toybox on the command line, enter the theme directory in your favourite terminal client and run the following:

```bash
php toybox
```

### Make Block
You can create a block with the `make:block` command.
```bash
php toybox make:block "My Block" --with-styles --with-js
```

| Argument/Option | Example    | Required | Description                                           |
|-----------------|------------|----------|-------------------------------------------------------|
| Block Name      | "My Block" | Yes      | The name of your block.                               |
| --with-styles   |            | No       | Creates the block style (and directory if required).  |
| --with-js       |            | No       | Creates the block script (and directory if required). |

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
