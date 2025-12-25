{{--
  Product Pricing Component
--}}

@php
  global $product;
  $priceHtml = $product->get_price_html();
@endphp

<div class="mb-6">
  <div class="flex items-baseline gap-3">
    <span class="text-4xl font-bold text-gray-900 product-price">{!! $priceHtml !!}</span>
  </div>

  @if($product->is_on_sale() && $product->get_regular_price())
    <p class="text-sm text-green-600 font-medium mt-1">
      Je bespaart {{ wc_price($product->get_regular_price() - $product->get_sale_price()) }}
    </p>
  @endif
</div>
