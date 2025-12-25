{{--
  Product Delivery Info Component
--}}

@php
  global $product;
  $deliveryTime = $product->get_attribute('levertijd') ?: '24 uur';
@endphp

<div class="bg-gray-50 rounded-xl p-6 mb-8">
  <h3 class="font-bold text-gray-900 mb-4">Levering & Service</h3>
  <ul class="space-y-3">
    <li class="flex items-center gap-3 text-gray-700">
      @svg('icon-truck', 'w-5 h-5 text-brand-600')
      <span>Levertijd: <strong>{{ $deliveryTime }}</strong></span>
    </li>
    <li class="flex items-center gap-3 text-gray-700">
      @svg('icon-shield', 'w-5 h-5 text-brand-600')
      <span>Veilig verpakt in isolatie</span>
    </li>
    <li class="flex items-center gap-3 text-gray-700">
      @svg('icon-sparkles', 'w-5 h-5 text-brand-600')
      <span>Inclusief veiligheidsinstructies</span>
    </li>
  </ul>
</div>
