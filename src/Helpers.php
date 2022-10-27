<?php

/**
 * The path to the mix-manifest.json file.
 */

use Doctrine\Inflector\InflectorFactory;

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
 * @param string $title
 * @param string $separator
 *
 * @return string
 */
function slugify(string $title, string $separator = '-'): string
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

/**
 * Attempt to match the case on two strings.
 *
 * @param string $value
 * @param string $comparison
 *
 * @return string
 */
function matchCase(string $value, string $comparison): string
{
    $functions = ['mb_strtolower', 'mb_strtoupper', 'ucfirst', 'ucwords'];

    foreach ($functions as $function) {
        if ($function($comparison) === $comparison) {
            return $function($value);
        }
    }

    return $value;
}

/**
 * Get the singular form of an English word.
 *
 * @param  string  $value
 * @return string
 */
function singularize(string $value): string
{
    $inflector = InflectorFactory::createForLanguage('english')->build();
    $singular  = $inflector->singularize($value);

    return matchCase($singular, $value);
}

/**
 * Get the plural form of an English word.
 *
 * @param string              $value
 * @param Countable|array|int $count
 *
 * @return string
 */
function pluralize(string $value, Countable|array|int $count = 2): string
{
    if (is_countable($count)) {
        $count = count($count);
    }

    if ((int) abs($count) === 1 || uncountable($value) || preg_match('/^(.*)[A-Za-z0-9\x{0080}-\x{FFFF}]$/u', $value) == 0) {
        return $value;
    }

    $inflector = InflectorFactory::createForLanguage('english')->build();
    $plural    = $inflector->pluralize($value);

    return matchCase($plural, $value);
}

/**
 * Determine if the given value is uncountable.
 *
 * @param string $value
 *
 * @return bool
 */
function uncountable(string $value): bool
{
    return in_array(strtolower($value), [
        'cattle',
        'kin',
        'recommended',
        'related',
    ]);
}

if (! function_exists('ray')) {
    /**
     * Placeholder function to prevent error messages.
     *
     * @param ...$args
     */
    function ray(...$args): void
    {
        // Ray isn't installed, just be quiet.
    }
}