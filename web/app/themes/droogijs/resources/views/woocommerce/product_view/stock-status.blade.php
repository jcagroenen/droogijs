{{--
  Product Stock Status Component
--}}

@php
  global $product;
  $stockStatus = $product->get_stock_status();
  $stockQty = $product->get_stock_quantity();
@endphp

<div class="mb-6">
  @if($stockStatus === 'instock')
    <div class="flex items-center gap-2 text-green-600">
      @svg('icon-shield', 'w-5 h-5')
      <span class="font-medium">
        @if($stockQty && $stockQty < 10)
          Nog {{ $stockQty }} op voorraad
        @else
          Op voorraad
        @endif
      </span>
    </div>
  @elseif($stockStatus === 'onbackorder')
    <div class="flex items-center gap-2 text-amber-600">
      @svg('icon-clock', 'w-5 h-5')
      <span class="font-medium">Leverbaar op bestelling</span>
    </div>
  @else
    <div class="flex items-center gap-2 text-red-600">
      @svg('icon-info-circle', 'w-5 h-5')
      <span class="font-medium">Uitverkocht</span>
    </div>
  @endif
</div>
