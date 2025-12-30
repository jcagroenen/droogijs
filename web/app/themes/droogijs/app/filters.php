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

            // Get delivery date
            $delivery_date = $cart_item['delivery_date'] ?? '';
            ?>
            <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                <div class="flex gap-3">
                    <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                        <?php echo $thumbnail; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-sm truncate"><?php echo esc_html($product_name); ?></h4>
                        <p class="text-xs text-gray-500">Aantal: <?php echo esc_html($quantity); ?></p>
                        <?php if ($delivery_date) : ?>
                            <p class="text-xs text-brand-600 font-medium mt-1">
                                <svg class="w-3 h-3 inline-block mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <?php echo esc_html($delivery_date); ?>
                            </p>
                        <?php endif; ?>
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

/**
 * Change WooCommerce button texts to Dutch.
 */
add_filter('woocommerce_order_button_text', fn() => 'Bestelling plaatsen');

/**
 * Permalink settings are auto-configured by FlatPermalinks class.
 *
 * @see App\WooCommerce\FlatPermalinks::ensureCorrectSettings()
 * @see docs/flat-permalinks.md
 */
add_filter('gettext', function ($translated, $text, $domain) {
    if ($domain === 'woocommerce') {
        $translations = [
            'Proceed to checkout' => 'Afrekenen',
            'Add to cart' => 'In winkelwagen',
            'View cart' => 'Bekijk winkelwagen',
            'Update cart' => 'Winkelwagen bijwerken',
            'Apply coupon' => 'Toepassen',
            'Subtotal' => 'Subtotaal',
            'Total' => 'Totaal',
            'Shipping' => 'Verzending',
        ];

        if (isset($translations[$text])) {
            return $translations[$text];
        }
    }
    return $translated;
}, 10, 3);

