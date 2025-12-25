{{--
  Product Add to Cart Component
--}}

@php
  global $product;
@endphp

<div class="mb-8">
  @php
    woocommerce_template_single_add_to_cart();
  @endphp
</div>
