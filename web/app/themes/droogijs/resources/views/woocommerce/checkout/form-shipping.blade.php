<?php
/**
 * Checkout shipping information form
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-shipping-fields">
  <?php if (true === WC()->cart->needs_shipping_address()) : ?>

    {{-- Section Header --}}
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
      <h3 id="ship-to-different-address" class="flex items-center gap-3">
        <label class="flex items-center gap-3 cursor-pointer">
          <input
            id="ship-to-different-address-checkbox"
            class="w-5 h-5 text-brand-600 border-gray-300 rounded focus:ring-brand-500"
            <?php checked(apply_filters('woocommerce_ship_to_different_address_checked', 'shipping' === get_option('woocommerce_ship_to_destination') ? 1 : 0), 1); ?>
            type="checkbox"
            name="ship_to_different_address"
            value="1"
          />
          <span class="text-lg font-bold text-gray-900">Naar een ander adres verzenden?</span>
        </label>
      </h3>
    </div>

    <div class="shipping_address p-6">
      <?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>

      <div class="woocommerce-shipping-fields__field-wrapper grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php
        $fields = $checkout->get_checkout_fields('shipping');

        foreach ($fields as $key => $field) {
          woocommerce_form_field($key, $field, $checkout->get_value($key));
        }
        ?>
      </div>

      <?php do_action('woocommerce_after_checkout_shipping_form', $checkout); ?>
    </div>

  <?php endif; ?>
</div>

{{-- Additional Information / Order Notes --}}
<div class="woocommerce-additional-fields">
  <?php do_action('woocommerce_before_order_notes', $checkout); ?>

  <?php if (apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes'))) : ?>

    <?php if (!WC()->cart->needs_shipping() || wc_ship_to_billing_address_only()) : ?>
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-900">Aanvullende informatie</h3>
      </div>
    <?php endif; ?>

    <div class="woocommerce-additional-fields__field-wrapper p-6">
      <?php foreach ($checkout->get_checkout_fields('order') as $key => $field) : ?>
        <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
      <?php endforeach; ?>
    </div>

  <?php endif; ?>

  <?php do_action('woocommerce_after_order_notes', $checkout); ?>
</div>
