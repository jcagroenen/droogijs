{{--
  The Template for displaying all single products

  @see https://docs.woocommerce.com/document/template-structure/
  @package WooCommerce\Templates
  @version 1.6.4
--}}

@extends('layouts.app')

@section('content')
  @php
    do_action('get_header', 'shop');
  @endphp

  @while(have_posts())
    @php
      the_post();
      global $product;
    @endphp

    {{-- Product Header with Breadcrumb --}}
    @include('woocommerce.product_view.product-header')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">
        {{-- Left Column: Gallery --}}
        <div>
          @include('woocommerce.product_view.gallery')
        </div>

        {{-- Right Column: Product Info --}}
        <div>
          @include('woocommerce.product_view.product-title')
          @include('woocommerce.product_view.short-description')
          @include('woocommerce.product_view.stock-status')
          @include('woocommerce.product_view.pricing')
          @include('woocommerce.product_view.add-to-cart')
          @include('woocommerce.product_view.delivery-info')
        </div>
      </div>

      {{-- Full Width: Description & Attributes --}}
      <div class="mt-8">
        @include('woocommerce.product_view.description')
        @include('woocommerce.product_view.attributes')
      </div>

      {{-- Related Products --}}
      <div class="border-t border-gray-200 pt-8 mt-8">
        @php
          woocommerce_output_related_products();
        @endphp
      </div>
    </div>

  @endwhile

  @php
    do_action('woocommerce_after_main_content');
    do_action('get_sidebar', 'shop');
    do_action('get_footer', 'shop');
  @endphp
@endsection
