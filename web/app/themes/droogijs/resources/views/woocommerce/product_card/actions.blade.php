{{--
  Product Card Actions Component
  Price display and add to cart button
--}}

@php
  $stockStatus = $product->get_stock_status();
  $inStock = $stockStatus === 'instock';
@endphp

<div class="p-6 pt-0 mt-auto">
  {{-- Divider --}}
  <div class="border-t border-gray-100 mb-4"></div>

  {{-- Price --}}
  <div class="flex items-baseline gap-2 mb-4">
    <span class="text-2xl font-bold text-gray-900">{!! $price !!}</span>
  </div>

  {{-- Add to Cart --}}
  @if($inStock)
    <button
      type="button"
      class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-bold text-white transition-all bg-brand-600 hover:bg-brand-700"
      onclick="window.location.href='{{ esc_url($product->add_to_cart_url()) }}'"
    >
      @svg('icon-box', 'w-5 h-5')
      <span>{{ $product->add_to_cart_text() }}</span>
    </button>
  @else
    <button
      type="button"
      class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-bold text-white transition-all bg-gray-400 cursor-not-allowed"
      disabled
    >
      <span>Uitverkocht</span>
    </button>
  @endif
</div>
