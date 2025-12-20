<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Get the current brand based on the multisite site.
 *
 * @return string thuis|horeca|industrie
 */
function get_current_brand(): string
{
    if (! is_multisite()) {
        return 'thuis';
    }

    $site_id = get_current_blog_id();

    // Map site IDs to brand names
    $brands = [
        1 => 'thuis',      // Main site / droogijs.test (fallback)
        2 => 'thuis',      // thuis.droogijs.test
        3 => 'horeca',     // horeca.droogijs.test
        4 => 'industrie',  // industrie.droogijs.test
    ];

    return $brands[$site_id] ?? 'thuis';
}

/**
 * Add brand class to body.
 *
 * @param array $classes
 * @return array
 */
add_filter('body_class', function (array $classes): array {
    $classes[] = 'brand-' . get_current_brand();

    return $classes;
});
