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

/**
 * ==========================================================================
 * Delivery Date Picker - WooCommerce Integration
 * ==========================================================================
 */

/**
 * Add delivery date picker to product page (inside the add-to-cart form).
 */
add_action('woocommerce_before_add_to_cart_button', function () {
    global $product;

    // Minimum days ahead for delivery (can be set per product via custom field)
    $minDays = get_post_meta($product->get_id(), '_delivery_min_days', true) ?: 1;

    // Default date (tomorrow or next available)
    $defaultDate = new \DateTime();
    $defaultDate->modify('+' . $minDays . ' days');
    // Skip Sunday
    if ($defaultDate->format('w') == 0) {
        $defaultDate->modify('+1 day');
    }
    $formattedDefault = $defaultDate->format('d-m-Y');
    ?>
    <div class="delivery-date-wrapper mb-4">
        <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
            Selecteer de gewenste leverdatum
        </label>
        <input
            type="text"
            id="delivery_date"
            name="delivery_date"
            class="delivery-date-picker w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 cursor-pointer bg-white"
            data-min-days="<?php echo esc_attr($minDays); ?>"
            value="<?php echo esc_attr($formattedDefault); ?>"
            placeholder="Kies een datum"
            required
        />
        <p class="mt-2 text-sm text-gray-500">
            Levering is mogelijk vanaf morgen (behalve op zondag)
        </p>
    </div>
    <?php
});

/**
 * Add delivery date to cart item data when adding to cart.
 */
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id) {
    if (isset($_POST['delivery_date']) && ! empty($_POST['delivery_date'])) {
        $cart_item_data['delivery_date'] = sanitize_text_field($_POST['delivery_date']);
    }
    return $cart_item_data;
}, 10, 3);

// Note: Delivery date is displayed manually in cart/checkout templates, not via woocommerce_get_item_data

/**
 * Update delivery date when cart is updated.
 */
add_action('woocommerce_update_cart_action_cart_updated', function ($cart_updated) {
    if (isset($_POST['cart_delivery_date']) && is_array($_POST['cart_delivery_date'])) {
        foreach ($_POST['cart_delivery_date'] as $cart_item_key => $delivery_date) {
            if (! empty($delivery_date)) {
                WC()->cart->cart_contents[$cart_item_key]['delivery_date'] = sanitize_text_field($delivery_date);
            }
        }
    }
    return $cart_updated;
});

/**
 * Save delivery date to order item meta.
 */
add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (isset($values['delivery_date'])) {
        $item->add_meta_data(__('Leverdatum', 'flavor'), $values['delivery_date'], true);
    }
}, 10, 4);

/**
 * Display delivery date in order emails.
 */
add_filter('woocommerce_order_item_get_formatted_meta_data', function ($formatted_meta, $item) {
    foreach ($formatted_meta as $key => $meta) {
        if ($meta->key === 'Leverdatum' || $meta->key === __('Leverdatum', 'flavor')) {
            $formatted_meta[$key]->display_key = __('Leverdatum', 'flavor');
        }
    }
    return $formatted_meta;
}, 10, 2);

/**
 * Add delivery date to mini-cart item display.
 */
add_filter('woocommerce_widget_cart_item_quantity', function ($quantity, $cart_item, $cart_item_key) {
    if (isset($cart_item['delivery_date'])) {
        $quantity .= '<br><small class="text-gray-500">Levering: ' . esc_html($cart_item['delivery_date']) . '</small>';
    }
    return $quantity;
}, 10, 3);
