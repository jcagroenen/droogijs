<?php
/**
 * Shipping Methods Display
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 */

defined('ABSPATH') || exit;

$formatted_destination = isset($formatted_destination) ? $formatted_destination : WC()->countries->get_formatted_address($package['destination'], ', ');
$has_calculated_shipping = !empty($has_calculated_shipping);
$show_shipping_calculator = !empty($show_shipping_calculator);
$calculator_text = '';
?>

<div class="woocommerce-shipping-totals shipping">
  <?php if (!empty($available_methods) && is_array($available_methods)) : ?>

    <ul id="shipping_method" class="woocommerce-shipping-methods space-y-2 list-none m-0 p-0">
      <?php foreach ($available_methods as $method) : ?>
        <li class="shipping-method-item">
          <label
            for="shipping_method_<?php echo esc_attr($index . '_' . sanitize_title($method->id)); ?>"
            class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all
              <?php echo checked($method->id, $chosen_method, false) ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-gray-300'; ?>"
          >
            <?php
            if (1 < count($available_methods)) {
              printf(
                '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method w-4 h-4 text-brand-600 border-gray-300 focus:ring-brand-500" %4$s />',
                $index,
                esc_attr(sanitize_title($method->id)),
                esc_attr($method->id),
                checked($method->id, $chosen_method, false)
              );
            } else {
              printf(
                '<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" />',
                $index,
                esc_attr(sanitize_title($method->id)),
                esc_attr($method->id)
              );
            }
            ?>

            <span class="flex-1 flex justify-between items-center">
              <span class="font-medium text-gray-900">
                <?php echo wc_cart_totals_shipping_method_label($method); ?>
              </span>
            </span>

            <?php do_action('woocommerce_after_shipping_rate', $method, $index); ?>
          </label>
        </li>
      <?php endforeach; ?>
    </ul>

    <?php if (is_cart()) : ?>
      <p class="woocommerce-shipping-destination text-sm text-gray-500 mt-3">
        <?php
        if ($formatted_destination) {
          printf(esc_html__('Verzenden naar: %s', 'flavor'), '<strong>' . esc_html($formatted_destination) . '</strong>');
          $calculator_text = esc_html__('Adres wijzigen', 'woocommerce');
        } else {
          echo wp_kses_post(apply_filters('woocommerce_shipping_estimate_html', __('Verzendopties worden bij het afrekenen bijgewerkt.', 'flavor')));
        }
        ?>
      </p>
    <?php endif; ?>

  <?php
  elseif (!$has_calculated_shipping || !$formatted_destination) :
    if (is_cart() && 'no' === get_option('woocommerce_enable_shipping_calc')) {
      echo '<p class="text-sm text-gray-500">' . wp_kses_post(apply_filters('woocommerce_shipping_not_enabled_on_cart_html', __('Verzendkosten worden bij het afrekenen berekend.', 'flavor'))) . '</p>';
    } else {
      echo '<p class="text-sm text-gray-500">' . wp_kses_post(apply_filters('woocommerce_shipping_may_be_available_html', __('Voer je adres in om verzendopties te bekijken.', 'flavor'))) . '</p>';
    }
  elseif (!is_cart()) :
    echo '<p class="text-sm text-amber-600">' . wp_kses_post(apply_filters('woocommerce_no_shipping_available_html', __('Er zijn geen verzendopties beschikbaar. Controleer je adres of neem contact met ons op.', 'flavor'))) . '</p>';
  else :
    echo wp_kses_post(
      apply_filters(
        'woocommerce_cart_no_shipping_available_html',
        sprintf('<p class="text-sm text-amber-600">' . esc_html__('Geen verzendopties gevonden voor %s.', 'flavor') . '</p>', '<strong>' . esc_html($formatted_destination) . '</strong>'),
        $formatted_destination
      )
    );
    $calculator_text = esc_html__('Ander adres invoeren', 'woocommerce');
  endif;
  ?>

  <?php if ($show_package_details) : ?>
    <p class="woocommerce-shipping-contents text-xs text-gray-400 mt-2">
      <?php echo esc_html($package_details); ?>
    </p>
  <?php endif; ?>

  <?php if ($show_shipping_calculator) : ?>
    <div class="mt-3">
      <?php woocommerce_shipping_calculator($calculator_text); ?>
    </div>
  <?php endif; ?>
</div>
