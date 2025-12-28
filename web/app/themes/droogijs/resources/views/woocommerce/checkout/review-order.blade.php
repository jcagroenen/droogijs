<?php
/**
 * Review order table
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-checkout-review-order-table bg-white rounded-2xl border border-gray-200 overflow-hidden">
  {{-- Header --}}
  <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
    <h3 class="text-lg font-bold text-gray-900">Je bestelling</h3>
  </div>

  <div class="p-6">
    {{-- Cart Items --}}
    <div class="space-y-4 mb-6">
      <?php
      do_action('woocommerce_review_order_before_cart_contents');

      foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
          ?>
          <div class="flex items-center gap-4 <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
            {{-- Product Image --}}
            <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden shrink-0">
              <?php
              $thumbnail = $_product->get_image('thumbnail', ['class' => 'w-full h-full object-cover']);
              echo $thumbnail;
              ?>
            </div>

            {{-- Product Info --}}
            <div class="flex-1 min-w-0">
              <h4 class="font-medium text-gray-900 text-sm truncate">
                <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)); ?>
              </h4>
              <p class="text-sm text-gray-500">
                <?php echo apply_filters('woocommerce_checkout_cart_item_quantity', sprintf('&times; %s', $cart_item['quantity']), $cart_item, $cart_item_key); ?>
              </p>
              <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
            </div>

            {{-- Price --}}
            <div class="text-right shrink-0">
              <span class="font-medium text-gray-900">
                <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
              </span>
            </div>
          </div>
          <?php
        }
      }

      do_action('woocommerce_review_order_after_cart_contents');
      ?>
    </div>

    {{-- Totals --}}
    <div class="border-t border-gray-200 pt-4 space-y-3">
      {{-- Subtotal --}}
      <div class="cart-subtotal flex justify-between items-center">
        <span class="text-gray-600">Subtotaal</span>
        <span class="font-medium text-gray-900"><?php wc_cart_totals_subtotal_html(); ?></span>
      </div>

      {{-- Coupons --}}
      <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
        <div class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?> flex justify-between items-center text-green-600">
          <span><?php wc_cart_totals_coupon_label($coupon); ?></span>
          <span><?php wc_cart_totals_coupon_html($coupon); ?></span>
        </div>
      <?php endforeach; ?>

      {{-- Shipping --}}
      <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
        <?php do_action('woocommerce_review_order_before_shipping'); ?>
        <?php wc_cart_totals_shipping_html(); ?>
        <?php do_action('woocommerce_review_order_after_shipping'); ?>
      <?php endif; ?>

      {{-- Fees --}}
      <?php foreach (WC()->cart->get_fees() as $fee) : ?>
        <div class="fee flex justify-between items-center">
          <span class="text-gray-600"><?php echo esc_html($fee->name); ?></span>
          <span><?php wc_cart_totals_fee_html($fee); ?></span>
        </div>
      <?php endforeach; ?>

      {{-- Tax --}}
      <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
        <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
          <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
            <div class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?> flex justify-between items-center">
              <span class="text-gray-600"><?php echo esc_html($tax->label); ?></span>
              <span><?php echo wp_kses_post($tax->formatted_amount); ?></span>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="tax-total flex justify-between items-center">
            <span class="text-gray-600"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></span>
            <span><?php wc_cart_totals_taxes_total_html(); ?></span>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <?php do_action('woocommerce_review_order_before_order_total'); ?>

      {{-- Order Total --}}
      <div class="order-total pt-3 border-t border-gray-200 flex justify-between items-center">
        <span class="text-lg font-bold text-gray-900">Totaal</span>
        <span class="text-xl font-bold text-gray-900"><?php wc_cart_totals_order_total_html(); ?></span>
      </div>

      <?php do_action('woocommerce_review_order_after_order_total'); ?>
    </div>
  </div>
</div>
