<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'url' => 'https://cdn.jsdelivr.net/npm/@hotwired/stimulus@3.2.2/+esm',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => '@symfony/stimulus-bundle/loader.js',
    ],
    '@hotwired/turbo' => [
        'url' => 'https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/+esm',
    ],
    'chart.js/auto' => [
        'url' => 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/auto/+esm',
    ],
    'chart.js' => [
        'url' => 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/+esm',
    ],
];
