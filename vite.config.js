import { defineConfig } from 'vite';
import fg from 'fast-glob';
import path from 'node:path';

const rootDir = process.cwd();

function makeInputKey(file) {
    return file
        .replace(/\\/g, '/')
        .replace(/\.[^/.]+$/, '')
        .replace(/\//g, '-');
}

function getInputs() {
    const inputs = {};

    const patterns = [
        'resources/scss/**/*.{scss,css}',
        'resources/js/**/*.{js,ts}',
        'blocks/*/resources/scss/**/*.{scss,css}',
        'blocks/*/resources/js/**/*.{js,ts}',
        'shortcodes/*/resources/scss/**/*.{scss,css}',
        'shortcodes/*/resources/js/**/*.{js,ts}'
    ];

    const files = fg.sync(patterns, {
        cwd: rootDir,
        onlyFiles: true,
        absolute: false
    });

    files.forEach((file) => {
        inputs[makeInputKey(file)] = path.resolve(rootDir, file);
    });

    return inputs;
}

function getAssetOutputPath(assetInfo) {
    const originalName = assetInfo.name ? assetInfo.name.replace(/\\/g, '/') : '';

    if (originalName.startsWith('resources/scss/')) {
        return 'css/[name]-[hash][extname]';
    }

    if (originalName.startsWith('resources/js/')) {
        return 'js/[name]-[hash][extname]';
    }

    const blockScssMatch = originalName.match(/^blocks\/([^/]+)\/resources\/scss\/(.+)$/);
    if (blockScssMatch) {
        return `blocks/${blockScssMatch[1]}/css/[name]-[hash][extname]`;
    }

    const blockJsMatch = originalName.match(/^blocks\/([^/]+)\/resources\/js\/(.+)$/);
    if (blockJsMatch) {
        return `blocks/${blockJsMatch[1]}/js/[name]-[hash][extname]`;
    }

    const shortcodeScssMatch = originalName.match(/^shortcodes\/([^/]+)\/resources\/scss\/(.+)$/);
    if (shortcodeScssMatch) {
        return `shortcodes/${shortcodeScssMatch[1]}/css/[name]-[hash][extname]`;
    }

    const shortcodeJsMatch = originalName.match(/^shortcodes\/([^/]+)\/resources\/js\/(.+)$/);
    if (shortcodeJsMatch) {
        return `shortcodes/${shortcodeJsMatch[1]}/js/[name]-[hash][extname]`;
    }

    return 'assets/[name]-[hash][extname]';
}

function getChunkOutputPath(chunkInfo) {
    const facade = chunkInfo.facadeModuleId
        ? path.relative(rootDir, chunkInfo.facadeModuleId).replace(/\\/g, '/')
        : '';

    if (facade.startsWith('resources/js/')) {
        return 'js/[name]-[hash].js';
    }

    const blockJsMatch = facade.match(/^blocks\/([^/]+)\/resources\/js\/(.+)$/);
    if (blockJsMatch) {
        return `blocks/${blockJsMatch[1]}/js/[name]-[hash].js`;
    }

    const shortcodeJsMatch = facade.match(/^shortcodes\/([^/]+)\/resources\/js\/(.+)$/);
    if (shortcodeJsMatch) {
        return `shortcodes/${shortcodeJsMatch[1]}/js/[name]-[hash].js`;
    }

    return 'js/[name]-[hash].js';
}

export default defineConfig(({ command }) => {
    const isDev = command === 'serve';

    return {
        base: isDev ? '/' : '',
        publicDir: false,
        resolve: {
            alias: {
                '@': path.resolve(rootDir, 'resources'),
                '@js': path.resolve(rootDir, 'resources/js'),
                '@scss': path.resolve(rootDir, 'resources/scss'),
                '@blocks': path.resolve(rootDir, 'blocks'),
                '@shortcodes': path.resolve(rootDir, 'shortcodes')
            }
        },
        server: {
            host: 'localhost',
            port: 5173,
            strictPort: true,
            cors: true,
            origin: 'https://localhost:5173',
            https: false,
            hmr: {
                host: 'localhost',
            },
            watch: {
                usePolling: true,
                interval: 100,
                ignored: [
                    '**/node_modules/**',
                    '**/.git/**',
                    '**/public/build/**'
                ]
            }
        },
        css: {
            devSourcemap: true
        },
        build: {
            outDir: 'public/build',
            emptyOutDir: true,
            manifest: true,
            sourcemap: true,
            rollupOptions: {
                input: getInputs(),
                output: {
                    entryFileNames: (chunkInfo) => getChunkOutputPath(chunkInfo),
                    chunkFileNames: 'js/chunks/[name]-[hash].js',
                    assetFileNames: (assetInfo) => getAssetOutputPath(assetInfo)
                }
            }
        }
    };
});