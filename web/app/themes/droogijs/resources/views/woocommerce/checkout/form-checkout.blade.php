<?php
/**
 * Checkout Form
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
  exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
  echo '<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">';
  echo '<p class="text-gray-600">' . esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('Je moet ingelogd zijn om af te rekenen.', 'flavor'))) . '</p>';
  echo '<a href="' . esc_url(wc_get_page_permalink('myaccount')) . '" class="inline-block mt-4 px-6 py-3 bg-brand-600 text-white font-bold rounded-lg hover:bg-brand-700 transition-colors">Inloggen</a>';
  echo '</div>';
  return;
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  {{-- Page Header --}}
  <div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Afrekenen</h1>
    <p class="text-gray-600 mt-2">Vul je gegevens in om de bestelling af te ronden</p>
  </div>

  <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
      {{-- Left Column: Customer Details --}}
      <div class="lg:col-span-2 space-y-6">
        <?php if ($checkout->get_checkout_fields()) : ?>

          <?php do_action('woocommerce_checkout_before_customer_details'); ?>

          <div id="customer_details" class="space-y-6">
            {{-- Billing Details --}}
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
              <?php do_action('woocommerce_checkout_billing'); ?>
            </div>

            {{-- Shipping Details --}}
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
              <?php do_action('woocommerce_checkout_shipping'); ?>
            </div>
          </div>

          <?php do_action('woocommerce_checkout_after_customer_details'); ?>

        <?php endif; ?>
      </div>

      {{-- Right Column: Order Review --}}
      <div class="mt-8 lg:mt-0">
        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
        <?php do_action('woocommerce_checkout_before_order_review'); ?>

        <div id="order_review" class="woocommerce-checkout-review-order sticky top-28 space-y-6">
          <?php do_action('woocommerce_checkout_order_review'); ?>
        </div>

        <?php do_action('woocommerce_checkout_after_order_review'); ?>
      </div>
    </div>

  </form>
</div>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
