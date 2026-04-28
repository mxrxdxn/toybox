# Toybox Changelog

## v3.0.2 (28/04/2026)
* Enhance lazy-loading with configurable `rootMargin` support.
* Add support for attribute-based lazy loading.
* Add CHANGELOG.md.
* Bump theme version to 3.0.2.

## v3.0.1 (22/04/2026)
* Update Composer dependencies.
* Remove `composer.lock` to decouple dependency management from the theme.
* Bump theme version to 3.0.1.

## v3.0.0 (22/04/2026)
* Migrate asset builds from Laravel Mix to Vite.
* Add Vite build outputs and manifest for asset bundling and lazy-loading.
* Remove Laravel Mix setup and dependencies.
* Move build placeholders into the `public` and `public/build` directories.
* Replace `Globals` asset loading with `Header` and `Footer` components.
* Update asset paths to use `Assets::getPath`.
* Replace individual component configuration with `Theme::safeDefaults()`.
* Remove unused code from `functions.php`.
* Update PHP requirement to `^8.4`.
* Bump `toybox/core` to `^3.0`.
* Update WordPress and PHP compatibility requirements.
* Remove `flexya` dependency.
* Add SCSS utilities for the grid system.
* Refactor SCSS from `@import` to `@use`.
* Refactor SCSS `rem` conversion utilities.
* Refactor lazy-loading logic for better attribute handling.
* Change lazy-loaded scripts to append to `<body>` instead of `<head>`.
* Add WebP and AVIF support.
* Enable image conversion to WebP on upload.
* Remove unused assets and legacy lazy-loading scripts.
* Add, then revert, the new lazy-loading and critical asset changes.
* Restore previously removed legacy lazy-loading assets.
* Disable custom image sizes configuration.
* Disable admin bar deactivation.
* Refactor conditional layout logic in `index.php`.
* Simplify `Kernel` instantiation.
* Update dashboard logo SVG.
* Update Composer dependencies.

## v2.23.0 (25/04/2025)
* Remove global `overflow-x` styling from `_globals.scss`.
* Update content rendering logic in `index.php`.
* Merge changes from `origin/master`.
* Update Composer dependencies, including `brick/math`, `filp/whoops`, and `monolog/monolog`.
* Adjust development dependencies for compatibility and package updates.

## v2.13.0 (12/07/2024)
* Add new components to core.
* Add new login page design.
* Add new dashboard widget.
* Add page templates options page.
* Add new page templates section.
* Update dependencies.
* Add `.nvmrc`.

## v2.9.4 (18/12/2023)
* Update release version metadata in `package.json` and `composer.lock`.

## v2.9.3 (18/12/2023)
* Fix ACF fields not appearing for social media settings.
* Update release version metadata in `package.json`.

## v2.9.2 (18/12/2023)
* Update to Toybox v2.9.2.
* Add default options files in the correct JSON folders.
* Update release version metadata in `package.json`.

## v2.9.1 (13/12/2023)
* Update project dependencies.
* Update global SCSS and generated asset references.

## v2.8.0 (13/11/2023)
* Add new Archive component to core.
* Fix image conversion command not picking up image files with capitalized extensions.
* Add permission callback attribute to API component.
* Fix `makeResponsive` method in Image component.
* Update ACF save paths for different JSON types.
* Populate Maps API key from ACF field.
* Update npm dependencies.

## v2.6.0 (18/09/2023)
* Update core dependencies.
* Add new Device component to core.

## v2.5.2 (16/09/2023)
* Add `data-lazy-attr` support for lazy loading in any attribute.
* Update `flexya` dependency to `^1.9`.
* Change settings pages to work via ACF JSON files.

## v2.5.0 (13/09/2023)
* Add block render method for rendering blocks outside the block editor and block editor-powered pages.
* Add deprecation notice to `export:block` command.
* Add support for block patterns.
* Save block ACF data to the block directory by default.
* Update theme dependencies.

## v2.3.1 (25/08/2023)
* Add new Image component.

## v2.3.0 (25/08/2023)
* Auto-save block ACF fields to the block directory.
* Add new `lazy()` helper function.

## v2.2.0 (03/07/2023)
* Add new API component.
* Add new block preloading methodology.

## v2.1.1 (27/06/2023)
* Update README documentation.
* Fix duplicate `TOYBOX_DIR` definition.
* Switch constants to `define`.
* Update theme dependencies.

## v2.1.0-1 (24/05/2023)
* Fix `composer.lock`.

## v2.1.0 (22/05/2023)
* Modularize components.

## v2.0.1 (15/05/2023)
* Split codebase to improve modularization.
* Update project dependencies.

## v2.0.0 (15/05/2023)
* Require PHP 8.1 or newer.
* Add support for the new ACF block format with backward compatibility for Toybox v1 blocks.
* Add snippets feature.
* Add WordPress-friendly replacement functions for ACF's `get_field()`.
* Add theme functions to disable core WordPress features.
* Add finer control over error reporting and exception handling.
* Update dependencies.

## v1.1.1 (19/03/2023)
* Add quote updates.
* Add this changelog.
* Update npm dependencies, including `flexya`, `@glidejs/glide`, and `sass`.
* Change `MakeBlockCommand` arguments to automatically create style and JS assets without requiring an argument.
* Replace `--with-styles` and `--with-js` with `--without-styles` and `--without-js`.

## v1.1.0 (28/10/2022)
* Alter default block stubs.
* Update dependencies.
* Add `inspire` command.
* Add make shortcode command.
* Register new commands.
* Add shortcode assets to the build process.
* Auto-detect shortcodes and post types.
* Update older stubs.
* Move assets into `/assets`.
* Add `rosell-dk/webp-convert` library.
* Fix docblock and return type issues.
* Ensure directories are created.
* Fix formatting and detected issues.
* Add `MakePostTypeCommand`.
* Remove unnecessary import.
* Add WebP image conversion.
* Update README documentation.

## v1.0.1 (12/08/2022)
* Add `--location` flag to `ExportBlockCommand`.
* Enforce PHP version check in `composer.json`.
* Prepare the 1.0.1 release.
* Update package metadata and theme version references.

## v1.0.0 (11/08/2022)
* Initial tagged release.
* Add screenshot.
* Add Laravel Mix configuration changes.
* Add menu registration logic.
* Add block export functionality.
* Add `_variables.scss` to block style stub.
* Add register post type function.
* Update README documentation.
* Update dependencies.
* Change error reporting logic.
* Fix duplicate nav menus.
* Apply typo fixes and release cleanup.
