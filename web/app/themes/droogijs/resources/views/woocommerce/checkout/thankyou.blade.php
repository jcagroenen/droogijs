<?php
/**
 * Thankyou page
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-order max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

  <?php if ($order) :

    do_action('woocommerce_before_thankyou', $order->get_id());
    ?>

    <?php if ($order->has_status('failed')) : ?>

      {{-- Order Failed --}}
      <div class="text-center mb-8">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-red-100 flex items-center justify-center">
          <svg class="w-10 h-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Betaling mislukt</h1>
        <p class="text-gray-600 mb-6">
          Helaas kon je bestelling niet worden verwerkt. De bank heeft de transactie geweigerd.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="px-6 py-3 bg-brand-600 text-white font-bold rounded-lg hover:bg-brand-700 transition-colors">
            Opnieuw betalen
          </a>
          <?php if (is_user_logged_in()) : ?>
            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
              Mijn account
            </a>
          <?php endif; ?>
        </div>
      </div>

    <?php else : ?>

      {{-- Order Success --}}
      <div class="text-center mb-8">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-100 flex items-center justify-center">
          <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Bedankt voor je bestelling!</h1>
        <p class="text-gray-600">
          Je ontvangt een bevestigingsmail op <strong><?php echo esc_html($order->get_billing_email()); ?></strong>
        </p>
      </div>

      {{-- Order Details Card --}}
      <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
          <h2 class="text-lg font-bold text-gray-900">Bestellingsgegevens</h2>
        </div>

        <div class="p-6">
          <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <dt class="text-sm text-gray-500">Bestelnummer</dt>
              <dd class="font-semibold text-gray-900"><?php echo $order->get_order_number(); ?></dd>
            </div>

            <div>
              <dt class="text-sm text-gray-500">Datum</dt>
              <dd class="font-semibold text-gray-900"><?php echo wc_format_datetime($order->get_date_created()); ?></dd>
            </div>

            <?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()) : ?>
              <div>
                <dt class="text-sm text-gray-500">E-mail</dt>
                <dd class="font-semibold text-gray-900"><?php echo esc_html($order->get_billing_email()); ?></dd>
              </div>
            <?php endif; ?>

            <div>
              <dt class="text-sm text-gray-500">Totaal</dt>
              <dd class="font-semibold text-gray-900"><?php echo $order->get_formatted_order_total(); ?></dd>
            </div>

            <?php if ($order->get_payment_method_title()) : ?>
              <div>
                <dt class="text-sm text-gray-500">Betaalmethode</dt>
                <dd class="font-semibold text-gray-900"><?php echo wp_kses_post($order->get_payment_method_title()); ?></dd>
              </div>
            <?php endif; ?>
          </dl>
        </div>
      </div>

      {{-- Continue Shopping --}}
      <div class="mt-8 text-center">
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="inline-flex items-center gap-2 text-brand-600 hover:text-brand-700 font-medium transition-colors">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Terug naar de shop
        </a>
      </div>

    <?php endif; ?>

    <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
    <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

  <?php else : ?>

    {{-- No Order --}}
    <div class="text-center">
      <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 mb-3">Bestelling ontvangen</h1>
      <p class="text-gray-600">Bedankt voor je bestelling.</p>
    </div>

  <?php endif; ?>

</div>
