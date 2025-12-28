<?php
/**
 * Checkout Payment Section
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 */

defined('ABSPATH') || exit;

if (!wp_doing_ajax()) {
  do_action('woocommerce_review_order_before_payment');
}
?>

<div id="payment" class="woocommerce-checkout-payment bg-white rounded-2xl border border-gray-200 overflow-hidden">
  {{-- Header --}}
  <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
    <h3 class="text-lg font-bold text-gray-900">Betaalmethode</h3>
  </div>

  <div class="p-6">
    <?php if (WC()->cart->needs_payment()) : ?>
      <ul class="wc_payment_methods payment_methods methods space-y-3 list-none m-0 p-0">
        <?php
        if (!empty($available_gateways)) {
          foreach ($available_gateways as $gateway) {
            wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
          }
        } else {
          echo '<li class="p-4 bg-amber-50 text-amber-800 rounded-lg text-sm">';
          wc_print_notice(
            apply_filters(
              'woocommerce_no_available_payment_methods_message',
              WC()->customer->get_billing_country()
                ? esc_html__('Geen betaalmethoden beschikbaar. Neem contact met ons op.', 'flavor')
                : esc_html__('Vul je gegevens hierboven in om betaalmethoden te zien.', 'flavor')
            ),
            'notice'
          );
          echo '</li>';
        }
        ?>
      </ul>
    <?php endif; ?>

    {{-- Place Order Section --}}
    <div class="form-row place-order mt-6">
      <noscript>
        <p class="text-sm text-amber-600 mb-4">
          <?php printf(esc_html__('Je browser ondersteunt geen JavaScript. Klik op %1$sTotalen bijwerken%2$s voordat je bestelt.', 'flavor'), '<em>', '</em>'); ?>
        </p>
        <button type="submit" class="w-full mb-4 px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e('Totalen bijwerken', 'flavor'); ?>">
          <?php esc_html_e('Totalen bijwerken', 'flavor'); ?>
        </button>
      </noscript>

      {{-- Terms --}}
      <div class="woocommerce-terms-and-conditions-wrapper mb-4">
        <?php wc_get_template('checkout/terms.php'); ?>
      </div>

      <?php do_action('woocommerce_review_order_before_submit'); ?>

      {{-- Place Order Button --}}
      <?php
      echo apply_filters(
        'woocommerce_order_button_html',
        '<button type="submit" class="checkout-place-order-btn w-full px-6 py-4 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg transition-colors' . esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : '') . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'
      );
      ?>

      <?php do_action('woocommerce_review_order_after_submit'); ?>

      <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
    </div>
  </div>
</div>

<?php
if (!wp_doing_ajax()) {
  do_action('woocommerce_review_order_after_payment');
}
?>
