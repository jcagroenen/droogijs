{{--
  The Template for displaying product archives, including the main shop page

  @see https://docs.woocommerce.com/document/template-structure/
  @package WooCommerce/Templates
  @version 3.4.0
--}}

@extends('layouts.app')

@section('content')
  @php
    do_action('get_header', 'shop');
  @endphp

  {{-- Shop Header with Trust Badges --}}
  @include('woocommerce.catalog_view.shop-header')

  {{-- Main Content Container --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-8">
    {{-- Breadcrumb --}}
    @php
      do_action('woocommerce_before_main_content');
    @endphp
    @if (woocommerce_product_loop())
      {{-- Sorting/Filtering Bar --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 pb-4 border-b border-gray-200">
        @php
          do_action('woocommerce_before_shop_loop');
        @endphp
      </div>

      @if (wc_get_loop_prop('total'))
        <ul class="list-none m-0 p-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
          @while (have_posts())
            @php
              the_post();
              do_action('woocommerce_shop_loop');
              wc_get_template_part('content', 'product');
            @endphp
          @endwhile
        </ul>
      @endif

      {{-- Pagination --}}
      <div class="mt-12">
        @php
          do_action('woocommerce_after_shop_loop');
        @endphp
      </div>
    @else
      {{-- No Products Found --}}
      <div class="text-center py-16">
        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gray-100 flex items-center justify-center">
          @svg('icon-box', 'w-10 h-10 text-gray-400')
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Geen producten gevonden</h2>
        <p class="text-gray-600">Probeer een andere zoekterm of bekijk al onze producten.</p>
      </div>
    @endif
  </div>

  {{-- Info Section --}}
  @include('woocommerce.catalog_view.shop-info')

  @php
    do_action('woocommerce_after_main_content');
    do_action('get_sidebar', 'shop');
    do_action('get_footer', 'shop');
  @endphp
@endsection
