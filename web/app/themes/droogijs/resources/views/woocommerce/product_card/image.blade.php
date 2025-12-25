{{--
  Product Card Image Component
--}}

<a href="{{ $productUrl }}" class="relative block h-48 overflow-hidden bg-gray-50">
  {{-- Background gradient --}}
  <div class="absolute inset-0 bg-gradient-to-br from-brand-500 to-brand-700 opacity-10"></div>

  @if($productImage)
    <img
      src="{{ $productImage }}"
      alt="{{ $productName }}"
      class="w-full h-full object-contain object-center transition-transform duration-500 group-hover:scale-110 p-4"
    >
  @else
    {{-- Fallback ice cube representation --}}
    <div class="absolute inset-0 flex items-center justify-center">
      <div class="relative">
        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-brand-400 to-brand-600 opacity-30 blur-xl"></div>
        <div class="absolute inset-0 w-24 h-24 rounded-2xl border-4 border-white/40 backdrop-blur-sm"></div>
      </div>
    </div>
  @endif

  {{-- Sale Badge --}}
  @if($onSale)
    <div class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
      Sale
    </div>
  @endif

  {{-- Stock Badge --}}
  @if(isset($stockStatus) && $stockStatus === 'outofstock')
    <div class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
      Uitverkocht
    </div>
  @endif
</a>
