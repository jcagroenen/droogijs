<?php
/**
 * Empty cart page
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
  <div class="text-center max-w-md mx-auto">
    {{-- Empty Cart Icon --}}
    <div class="w-24 h-24 mx-auto mb-6 rounded-2xl bg-gray-100 flex items-center justify-center">
      <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
      </svg>
    </div>

    {{-- Message --}}
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
      Je winkelwagen is leeg
    </h1>

    <p class="text-gray-600 mb-8">
      <?php echo wp_kses_post(apply_filters('wc_empty_cart_message', __('Voeg wat producten toe aan je winkelwagen om verder te gaan.', 'flavor'))); ?>
    </p>

    <?php if (wc_get_page_id('shop') > 0) : ?>
      {{-- Shop Button --}}
      <a
        href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>"
        class="inline-flex items-center gap-2 px-8 py-3 rounded-lg font-bold text-white bg-brand-600 hover:bg-brand-700 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
        </svg>
        <?php esc_html_e('Bekijk producten', 'flavor'); ?>
      </a>
    <?php endif; ?>

    <?php do_action('woocommerce_cart_is_empty'); ?>
  </div>
</div>
