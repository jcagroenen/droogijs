<?php
/**
 * Checkout billing information form
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-billing-fields">
  {{-- Section Header --}}
  <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
    <?php if (wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()) : ?>
      <h3 class="text-lg font-bold text-gray-900">Factuur- & verzendadres</h3>
    <?php else : ?>
      <h3 class="text-lg font-bold text-gray-900">Factuurgegevens</h3>
    <?php endif; ?>
  </div>

  <div class="p-6">
    <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

    <div class="billing-fields__field-wrapper grid grid-cols-1 md:grid-cols-2 gap-4">
      <?php
      $fields = $checkout->get_checkout_fields('billing');

      foreach ($fields as $key => $field) {
        woocommerce_form_field($key, $field, $checkout->get_value($key));
      }
      ?>
    </div>

    <?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>
  </div>
</div>

<?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
  <div class="woocommerce-account-fields px-6 pb-6">
    <?php if (!$checkout->is_registration_required()) : ?>
      <div class="form-row form-row-wide create-account">
        <label class="flex items-center gap-3 cursor-pointer p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
          <input
            class="w-5 h-5 text-brand-600 border-gray-300 rounded focus:ring-brand-500"
            id="createaccount"
            <?php checked((true === $checkout->get_value('createaccount') || (true === apply_filters('woocommerce_create_account_default_checked', false))), true); ?>
            type="checkbox"
            name="createaccount"
            value="1"
          />
          <span class="text-gray-700 font-medium">Account aanmaken?</span>
        </label>
      </div>
    <?php endif; ?>

    <?php do_action('woocommerce_before_checkout_registration_form', $checkout); ?>

    <?php if ($checkout->get_checkout_fields('account')) : ?>
      <div class="create-account mt-4 space-y-4">
        <?php foreach ($checkout->get_checkout_fields('account') as $key => $field) : ?>
          <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
  </div>
<?php endif; ?>
