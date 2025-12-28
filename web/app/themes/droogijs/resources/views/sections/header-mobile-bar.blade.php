{{--
  Mobile Bestellen Bar
  Sticky subheader with full-width Bestellen button
  Hidden on shop/product pages
--}}

@php
  $is_shop_page = function_exists('is_shop') && (is_shop() || is_product() || is_product_category() || is_product_tag() || is_cart() || is_checkout());
  $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/shop/';
@endphp

@unless($is_shop_page)
  <div class="md:hidden sticky top-20 z-30 bg-white border-b border-gray-100 shadow-sm">
    <div class="px-4 py-3">
      <a
        href="{{ $shop_url }}"
        class="flex items-center justify-center gap-2 w-full px-6 py-3 rounded-lg font-bold text-white bg-brand-600 hover:bg-brand-700 transition-colors"
      >
        @svg('icon-box', 'w-5 h-5')
        Bestellen
      </a>
    </div>
  </div>
@endunless
