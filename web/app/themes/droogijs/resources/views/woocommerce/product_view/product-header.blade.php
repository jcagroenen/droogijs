{{--
  Product Header Component
  Displays product title and breadcrumb area
--}}

@php
  $brand = App\get_current_brand();
@endphp

<div class="pt-32 pb-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-brand-50 to-brand-100">
  <div class="max-w-7xl mx-auto">
    @php
      do_action('woocommerce_before_main_content');
    @endphp
  </div>
</div>
