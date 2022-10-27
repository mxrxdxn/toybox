const fs   = require('fs');
const mix  = require('laravel-mix');
const path = require('path');

// Create some aliases
mix.alias({
    '@base':          path.join(__dirname, 'resources/scss'),
    '@blockDir':      path.join(__dirname, 'blocks'),
    '@shortcodesDir': path.join(__dirname, 'shortcodes')
});

// Set the public path
mix.setPublicPath('.');

// Don't perform any CSS URL rewriting
mix.options({
    processCssUrls: false,
});

// Compile JS
mix.js('resources/js/app.js', 'assets/js');
mix.js('resources/js/editor.js', 'assets/js');

// Compile SCSS
mix.sass('resources/scss/critical.scss', 'assets/css');
mix.sass('resources/scss/style.scss', 'assets/css');
mix.sass('resources/scss/editor.scss', 'assets/css');

// Compile block assets
fs.readdirSync("blocks").forEach(dirName => {
    // JS
    if (fs.existsSync(`blocks/${dirName}/resources/js/${dirName}.js`)) {
        mix.js(`blocks/${dirName}/resources/js/${dirName}.js`, 'assets/js/blocks');
    }

    // SCSS
    if (fs.existsSync(`blocks/${dirName}/resources/scss/${dirName}.scss`)) {
        mix.sass(`blocks/${dirName}/resources/scss/${dirName}.scss`, 'assets/css/blocks');
    }
});

// Compile shortcode assets
fs.readdirSync("shortcodes").forEach(dirName => {
    // JS
    if (fs.existsSync(`shortcodes/${dirName}/resources/js/${dirName}.js`)) {
        mix.js(`shortcodes/${dirName}/resources/js/${dirName}.js`, 'assets/js/shortcodes');
    }

    // SCSS
    if (fs.existsSync(`shortcodes/${dirName}/resources/scss/${dirName}.scss`)) {
        mix.sass(`shortcodes/${dirName}/resources/scss/${dirName}.scss`, 'assets/css/shortcodes');
    }
});

// Enable versioning
mix.version();

// Enable source mapping
mix.sourceMaps(false, 'source-map');
