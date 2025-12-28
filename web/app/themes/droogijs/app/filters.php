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

/**
 * WooCommerce AJAX cart fragments.
 * Updates header cart count and mini-cart content without page reload.
 */
add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    $cart_count = WC()->cart->get_cart_contents_count();

    // Update cart count badge
    $fragments['#header-cart-count'] = sprintf(
        '<span id="header-cart-count" class="absolute -top-1 -right-1 w-5 h-5 bg-brand-600 text-white text-xs font-bold rounded-full flex items-center justify-center %s">%d</span>',
        $cart_count === 0 ? 'hidden' : '',
        $cart_count
    );

    // Update cart count text
    $fragments['#header-cart-count-text'] = sprintf(
        '<span id="header-cart-count-text">%d</span>',
        $cart_count
    );

    // Update cart total
    $fragments['#header-cart-total'] = sprintf(
        '<span id="header-cart-total" class="font-bold text-lg">%s</span>',
        WC()->cart->get_cart_total()
    );

    // Update mini-cart contents
    ob_start();
    if (! WC()->cart->is_empty()) {
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $product = $cart_item['data'];
            $product_name = $product->get_name();
            $product_price = WC()->cart->get_product_price($product);
            $quantity = $cart_item['quantity'];
            $thumbnail = $product->get_image('thumbnail', ['class' => 'w-full h-full object-cover']);
            ?>
            <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                <div class="flex gap-3">
                    <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                        <?php echo $thumbnail; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-sm truncate"><?php echo esc_html($product_name); ?></h4>
                        <p class="text-xs text-gray-500">Aantal: <?php echo esc_html($quantity); ?></p>
                        <span class="text-sm font-bold"><?php echo $product_price; ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="p-8 text-center text-gray-500">
            <p>Je winkelwagen is leeg</p>
        </div>
        <?php
    }
    $fragments['#header-mini-cart'] = '<div id="header-mini-cart" class="max-h-80 overflow-y-auto">' . ob_get_clean() . '</div>';

    return $fragments;
});
