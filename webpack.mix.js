const fs   = require('fs');
const mix  = require('laravel-mix');
const path = require('path');

// Create some aliases
mix.alias({
    '@base':     path.join(__dirname, 'resources/scss'),
    '@blockDir': path.join(__dirname, 'blocks')
});

// Set the public path
mix.setPublicPath('.');

// Don't perform any CSS URL rewriting
mix.options({
    processCssUrls: false,
});

// Compile JS
mix.js('resources/js/app.js', 'js');
mix.js('resources/js/editor.js', 'js');

// Compile SCSS
mix.sass('resources/scss/critical.scss', 'css');
mix.sass('resources/scss/style.scss', 'css');
mix.sass('resources/scss/editor.scss', 'css');

// Compile block assets
fs.readdirSync("blocks").forEach(dirName => {
    // Create an alias
    let alias = `@${dirName}`;
    mix.alias({
        alias: path.join(__dirname, `blocks/${dirName}`)
    });
    
    // JS
    if (fs.existsSync(`blocks/${dirName}/resources/js/${dirName}.js`)) {
        mix.js(`blocks/${dirName}/resources/js/${dirName}.js`, 'js/blocks');
    }

    // SCSS
    if (fs.existsSync(`blocks/${dirName}/resources/scss/${dirName}.scss`)) {
        mix.sass(`blocks/${dirName}/resources/scss/${dirName}.scss`, 'css/blocks');
    }
});

// Enable versioning
mix.version();

// Enable source mapping
mix.sourceMaps(false, 'source-map');
