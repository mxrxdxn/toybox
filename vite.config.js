import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { wordpressPlugin } from '@roots/vite-plugin';
import fs from 'fs';
import * as process from "node:process";

let files = [
    'resources/scss/critical.scss',
    'resources/scss/style.scss',
    'resources/scss/editor.scss',
    'resources/js/app.js',
    'resources/js/editor.js',
];

// Compile block assets
["blocks", "shortcodes"].forEach(assetType => {
    fs.readdirSync(assetType).forEach(dirName => {
        ["css", "js"].forEach(fileType => {
            if (fs.existsSync(`${assetType}/${dirName}/resources/${fileType}/${dirName}.${fileType}`)) {
                files.push(`${assetType}/${dirName}/resources/${fileType}/${dirName}.${fileType}`);
            }
        });
    });
});

export default defineConfig({
    base:    '/assets/',
    plugins: [
        laravel({
            input:   files,
            refresh: true,
        }),

        wordpressPlugin(),
    ],
    resolve: {
        alias: {
            '@scripts': '/resources/js',
            '@styles':  '/resources/scss',
            '@fonts':   '/resources/fonts',
            '@images':  '/images',
            '@blocks':  '/blocks',
        },
    },
});
