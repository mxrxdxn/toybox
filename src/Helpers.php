<?php

/**
 * The path to the mix-manifest.json file.
 */
const MIX_MANIFEST = __DIR__ . "/../mix-manifest.json";

/**
 * Fetch a versioned asset URL from the mix manifest file.
 *
 * @param string      $fileName     Path of the file to load, relative to the theme base URI.
 * @param string|null $manifestPath Path to the mix-manifest.json file.
 *
 * @return string
 * @throws Exception
 */
function mix(string $fileName, string|null $manifestPath = null): string
{
    // Fetch the manifest
    if (! empty($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
    } else {
        $manifest = json_decode(file_get_contents(MIX_MANIFEST), true);
    }

    // If the file can't be found, throw an exception
    if (! array_key_exists($fileName, $manifest)) {
        throw new Exception("Could not find {$fileName} in manifest. Try rebuilding your assets with `npm run build`.");
    }

    return uri($manifest[$fileName]);
}

/**
 * Fetches an asset's URI relative to the theme base path. Shorthand for get_theme_file_uri().
 *
 * @param string $fileName Path of the file to load, relative to the theme base URI.
 *
 * @return string
 */
function uri(string $fileName): string
{
    return get_theme_file_uri($fileName);
}

/**
 * Generate a URL friendly "slug" from a given string.
 *
 * @param        $title
 * @param string $separator
 *
 * @return string
 */
function slugify($title, $separator = '-'): string
{
    // Convert all dashes/underscores into separator
    $flip = $separator === '-' ? '_' : '-';

    $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);

    // Replace @ with the word 'at'
    $title = str_replace('@', $separator . 'at' . $separator, $title);

    // Remove all characters that are not the separator, letters, numbers, or whitespace.
    $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($title, 'UTF-8'));

    // Replace all separator characters and whitespace by a single separator
    $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);

    return trim($title, $separator);
}

if (! function_exists('ray')) {
    /**
     * Placeholder function to prevent error messages.
     *
     * @param ...$args
     */
    function ray(...$args) {
        // Ray isn't installed, just be quiet.
    }
}