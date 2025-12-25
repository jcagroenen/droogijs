{{--
  Product Card Info Component
  Displays product title, description, and features
--}}

@php
  $shortDescription = $product->get_short_description();
  $shortDescription = wp_strip_all_tags($shortDescription);
  $shortDescription = wp_trim_words($shortDescription, 15, '...');

  $deliveryTime = $product->get_attribute('levertijd') ?: '24 uur';
@endphp

<div class="p-6 flex-grow">
  {{-- Title --}}
  <a href="{{ $productUrl }}" class="block no-underline">
    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-brand-600 transition-colors">
      {{ $productName }}
    </h3>
  </a>

  {{-- Short Description --}}
  @if($shortDescription)
    <p class="text-gray-600 text-sm mb-4 leading-relaxed">
      {{ $shortDescription }}
    </p>
  @endif

  {{-- Delivery Info --}}
  <div class="flex items-center gap-4 text-sm text-gray-600">
    <div class="flex items-center gap-2">
      @svg('icon-clock', 'w-4 h-4')
      <span>{{ $deliveryTime }}</span>
    </div>
    <div class="flex items-center gap-2">
      @svg('icon-shield', 'w-4 h-4')
      <span>Kwaliteitsgarantie</span>
    </div>
  </div>
</div>
