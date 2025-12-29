<?php

namespace App\WooCommerce;

use App\Admin\ShippingCalendar;

/**
 * Delivery Date Picker
 *
 * Allows customers to select a preferred delivery date when ordering.
 * Integrated throughout: product page, cart, checkout, mini-cart, and order emails.
 */
class DeliveryDate
{
    /**
     * Bootstrap the delivery date functionality.
     */
    public function boot(): void
    {
        // Product page: Add date picker to add-to-cart form
        add_action('woocommerce_before_add_to_cart_button', [$this, 'renderProductDatePicker']);

        // Cart: Capture delivery date when adding to cart
        add_filter('woocommerce_add_cart_item_data', [$this, 'addToCartItemData'], 10, 3);

        // Cart: Update delivery date when cart is updated
        add_action('woocommerce_update_cart_action_cart_updated', [$this, 'updateCartDeliveryDate']);

        // Order: Save delivery date to order item meta
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'saveToOrderItemMeta'], 10, 4);

        // Emails: Format delivery date in order emails
        add_filter('woocommerce_order_item_get_formatted_meta_data', [$this, 'formatOrderItemMeta'], 10, 2);

        // Mini-cart: Add delivery date to widget cart item
        add_filter('woocommerce_widget_cart_item_quantity', [$this, 'addToMiniCartItem'], 10, 3);
    }

    /**
     * Get settings from ShippingCalendar.
     */
    protected function getSettings(): array
    {
        return ShippingCalendar::getSettings();
    }

    /**
     * Render the delivery date picker on the product page.
     */
    public function renderProductDatePicker(): void
    {
        // Check if enabled
        if (! ShippingCalendar::isEnabled()) {
            return;
        }

        global $product;

        $settings = $this->getSettings();
        $minDays = $this->getMinDays($product->get_id());
        $defaultDate = $this->getDefaultDate($minDays);
        $disabledWeekdays = $settings['disabled_weekdays'];
        $blockedDates = $this->parseBlockedDates($settings['blocked_dates']);
        $helperText = $settings['helper_text'];
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
                data-disabled-weekdays="<?php echo esc_attr(implode(',', $disabledWeekdays)); ?>"
                data-blocked-dates="<?php echo esc_attr(json_encode($blockedDates)); ?>"
                value="<?php echo esc_attr($defaultDate); ?>"
                placeholder="Kies een datum"
                required
            />
            <?php if ($helperText) : ?>
                <p class="mt-2 text-sm text-gray-500">
                    <?php echo esc_html($helperText); ?>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Parse blocked dates from textarea input.
     *
     * Supports single dates (DD-MM-YYYY) and ranges (DD-MM-YYYY - DD-MM-YYYY).
     */
    protected function parseBlockedDates(string $input): array
    {
        $blocked = [];
        $lines = array_filter(array_map('trim', explode("\n", $input)));

        foreach ($lines as $line) {
            if (strpos($line, ' - ') !== false) {
                // Date range
                $parts = explode(' - ', $line);
                if (count($parts) === 2) {
                    $blocked[] = [
                        'from' => $this->convertDateFormat(trim($parts[0])),
                        'to' => $this->convertDateFormat(trim($parts[1])),
                    ];
                }
            } else {
                // Single date
                $blocked[] = $this->convertDateFormat($line);
            }
        }

        return $blocked;
    }

    /**
     * Convert DD-MM-YYYY to YYYY-MM-DD for Flatpickr.
     */
    protected function convertDateFormat(string $date): string
    {
        $parts = explode('-', $date);
        if (count($parts) === 3) {
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        return $date;
    }

    /**
     * Add delivery date to cart item data when adding to cart.
     */
    public function addToCartItemData(array $cart_item_data, int $product_id, int $variation_id): array
    {
        if (isset($_POST['delivery_date']) && ! empty($_POST['delivery_date'])) {
            $cart_item_data['delivery_date'] = sanitize_text_field($_POST['delivery_date']);
        }
        return $cart_item_data;
    }

    /**
     * Update delivery date when cart is updated.
     */
    public function updateCartDeliveryDate(bool $cart_updated): bool
    {
        if (isset($_POST['cart_delivery_date']) && is_array($_POST['cart_delivery_date'])) {
            foreach ($_POST['cart_delivery_date'] as $cart_item_key => $delivery_date) {
                if (! empty($delivery_date)) {
                    WC()->cart->cart_contents[$cart_item_key]['delivery_date'] = sanitize_text_field($delivery_date);
                }
            }
        }
        return $cart_updated;
    }

    /**
     * Save delivery date to order item meta.
     */
    public function saveToOrderItemMeta($item, string $cart_item_key, array $values, $order): void
    {
        if (isset($values['delivery_date'])) {
            $item->add_meta_data(__('Leverdatum', 'flavor'), $values['delivery_date'], true);
        }
    }

    /**
     * Format delivery date in order emails.
     */
    public function formatOrderItemMeta(array $formatted_meta, $item): array
    {
        foreach ($formatted_meta as $key => $meta) {
            if ($meta->key === 'Leverdatum' || $meta->key === __('Leverdatum', 'flavor')) {
                $formatted_meta[$key]->display_key = __('Leverdatum', 'flavor');
            }
        }
        return $formatted_meta;
    }

    /**
     * Add delivery date to mini-cart item display.
     */
    public function addToMiniCartItem(string $quantity, array $cart_item, string $cart_item_key): string
    {
        if (isset($cart_item['delivery_date'])) {
            $quantity .= '<br><small class="text-gray-500">Levering: ' . esc_html($cart_item['delivery_date']) . '</small>';
        }
        return $quantity;
    }

    /**
     * Get minimum days ahead for delivery.
     * Product-specific setting overrides global setting.
     */
    protected function getMinDays(int $product_id): int
    {
        // Check for product-specific override
        $productMinDays = get_post_meta($product_id, '_delivery_min_days', true);
        if ($productMinDays) {
            return (int) $productMinDays;
        }

        // Fall back to global setting
        $settings = $this->getSettings();
        return (int) $settings['min_days'];
    }

    /**
     * Get the default delivery date.
     * Skips disabled weekdays from settings.
     */
    protected function getDefaultDate(int $minDays): string
    {
        $settings = $this->getSettings();
        $disabledWeekdays = $settings['disabled_weekdays'];

        $date = new \DateTime();
        $date->modify('+' . $minDays . ' days');

        // Skip disabled weekdays (max 7 attempts to avoid infinite loop)
        $attempts = 0;
        while (in_array((int) $date->format('w'), $disabledWeekdays) && $attempts < 7) {
            $date->modify('+1 day');
            $attempts++;
        }

        return $date->format('d-m-Y');
    }
}
