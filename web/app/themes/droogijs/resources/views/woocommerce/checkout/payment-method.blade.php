<?php
/**
 * Output a single payment method
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if (!defined('ABSPATH')) {
  exit;
}
?>

<li class="wc_payment_method payment_method_<?php echo esc_attr($gateway->id); ?>">
  <label
    for="payment_method_<?php echo esc_attr($gateway->id); ?>"
    class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer transition-all
      <?php echo $gateway->chosen ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-gray-300'; ?>"
  >
    <input
      id="payment_method_<?php echo esc_attr($gateway->id); ?>"
      type="radio"
      class="w-5 h-5 text-brand-600 border-gray-300 focus:ring-brand-500"
      name="payment_method"
      value="<?php echo esc_attr($gateway->id); ?>"
      <?php checked($gateway->chosen, true); ?>
      data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>"
    />

    <span class="flex-1 flex items-center gap-3">
      <span class="font-medium text-gray-900"><?php echo $gateway->get_title(); ?></span>
      <?php if ($gateway->get_icon()) : ?>
        <span class="payment-method-icon"><?php echo $gateway->get_icon(); ?></span>
      <?php endif; ?>
    </span>
  </label>

  <?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
    <div
      class="payment_box payment_method_<?php echo esc_attr($gateway->id); ?> mt-3 ml-9 p-4 bg-gray-50 rounded-lg text-sm text-gray-600"
      <?php if (!$gateway->chosen) : ?>style="display: none;"<?php endif; ?>
    >
      <?php $gateway->payment_fields(); ?>
    </div>
  <?php endif; ?>
</li>
