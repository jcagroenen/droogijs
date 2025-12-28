<?php
/**
 * Cart totals
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined('ABSPATH') || exit;
?>

<div class="cart_totals bg-white rounded-2xl border border-gray-200 overflow-hidden <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

  <?php do_action('woocommerce_before_cart_totals'); ?>

  {{-- Header --}}
  <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
    <h2 class="text-lg font-bold text-gray-900">Overzicht</h2>
  </div>

  <div class="p-6 space-y-4">
    {{-- Subtotal --}}
    <div class="cart-subtotal flex justify-between items-center w-full">
      <span class="text-gray-600 shrink-0">Subtotaal</span>
      <span class="font-medium text-gray-900 text-right" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
        <?php wc_cart_totals_subtotal_html(); ?>
      </span>
    </div>

    {{-- Coupons --}}
    <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
      <div class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?> flex justify-between items-center text-green-600">
        <span><?php wc_cart_totals_coupon_label($coupon); ?></span>
        <span data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>">
          <?php wc_cart_totals_coupon_html($coupon); ?>
        </span>
      </div>
    <?php endforeach; ?>

    {{-- Shipping --}}
    <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
      <div class="pt-4 border-t border-gray-200">
        <h3 class="font-semibold text-gray-900 mb-3">Verzending</h3>

        <?php do_action('woocommerce_cart_totals_before_shipping'); ?>
        <?php wc_cart_totals_shipping_html(); ?>
        <?php do_action('woocommerce_cart_totals_after_shipping'); ?>
      </div>

    <?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')) : ?>
      <div class="pt-4 border-t border-gray-200">
        <div class="flex justify-between items-center">
          <span class="text-gray-600">Verzending</span>
          <span class="text-gray-500 text-sm" data-title="<?php esc_attr_e('Shipping', 'woocommerce'); ?>">
            <?php woocommerce_shipping_calculator(); ?>
          </span>
        </div>
      </div>
    <?php endif; ?>

    {{-- Fees --}}
    <?php foreach (WC()->cart->get_fees() as $fee) : ?>
      <div class="fee flex justify-between items-center">
        <span class="text-gray-600"><?php echo esc_html($fee->name); ?></span>
        <span data-title="<?php echo esc_attr($fee->name); ?>">
          <?php wc_cart_totals_fee_html($fee); ?>
        </span>
      </div>
    <?php endforeach; ?>

    {{-- Tax --}}
    <?php
    if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) {
      $taxable_address = WC()->customer->get_taxable_address();
      $estimated_text = '';

      if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
        $estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
      }

      if ('itemized' === get_option('woocommerce_tax_total_display')) {
        foreach (WC()->cart->get_tax_totals() as $code => $tax) {
          ?>
          <div class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?> flex justify-between items-center">
            <span class="text-gray-600"><?php echo esc_html($tax->label) . $estimated_text; ?></span>
            <span data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></span>
          </div>
          <?php
        }
      } else {
        ?>
        <div class="tax-total flex justify-between items-center">
          <span class="text-gray-600"><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; ?></span>
          <span data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>">
            <?php wc_cart_totals_taxes_total_html(); ?>
          </span>
        </div>
        <?php
      }
    }
    ?>

    <?php do_action('woocommerce_cart_totals_before_order_total'); ?>

    {{-- Order Total --}}
    <div class="order-total pt-4 border-t border-gray-200 flex justify-between items-center w-full">
      <span class="text-lg font-bold text-gray-900 shrink-0">Totaal</span>
      <span class="text-xl font-bold text-gray-900 text-right" data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>">
        <?php wc_cart_totals_order_total_html(); ?>
      </span>
    </div>

    <?php do_action('woocommerce_cart_totals_after_order_total'); ?>
  </div>

  {{-- Checkout Button --}}
  <div class="p-6 pt-0">
    <div class="wc-proceed-to-checkout">
      <?php do_action('woocommerce_proceed_to_checkout'); ?>
    </div>

    {{-- Continue Shopping --}}
    <a
      href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"
      class="mt-3 block text-center text-sm text-gray-600 hover:text-brand-600 transition-colors"
    >
      Verder winkelen
    </a>
  </div>

  <?php do_action('woocommerce_after_cart_totals'); ?>
</div>
