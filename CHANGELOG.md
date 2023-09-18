# Toybox Changelog
## v2.6.0 (18/09/2023)
* Add new Device component to core.

## v2.5.0 (13/09/2023)
* Add block render method for rendering blocks outside the block editor (and block editor-powered pages).
* Add deprecation notice to `export:block` command.
* Update theme dependencies.

## v2.4.0 (03/09/2023)
* Adds support for block patterns.
* Blocks now save their ACF data to the block directory by default (requires ACF 6.2). 

## v2.3.1 (25/08/2023)
* Adds new Image component.

## v2.3.0 (25/08/2023)
* Block ACF fields now auto-save to the block (requires ACF 6.2).
* Added new `lazy()` helper function.

## v2.2.0 (03/07/2023)
* New API component.
* New block preloading methodology.

## v2.1.0 (22/05/2023)
* Modularize components.

## v2.0.0 (15/05/2023)
* Split theme into two packages: toybox/theme and toybox/core.
* New enhancements to tweak more of WordPress.

## v1.1.1 (19/03/2023)
* Adds this changelog.
* Updates npm dependencies (flexya, @glidejs/glide, sass).
* Change MakeBlockCommand arguments to automatically create style and JS assets without requiring an argument.
  * This removes the `--with-styles` and `--with-js` arguments, and adds `--without-styles` and `--without-js` as replacements.