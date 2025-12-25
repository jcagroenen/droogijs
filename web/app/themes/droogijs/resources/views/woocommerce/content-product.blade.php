{{--
  The template for displaying product content within loops
  Uses modular product_card partials

  @see https://woocommerce.com/document/template-structure/
  @package WooCommerce\Templates
  @version 3.6.0
--}}

@php
  global $product;

  if (empty($product) || !$product->is_visible()) {
    return;
  }
@endphp

@include('woocommerce.product_card.card')
