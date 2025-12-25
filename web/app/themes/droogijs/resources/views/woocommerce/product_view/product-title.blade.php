{{--
  Product Title Component
--}}

@php
  global $product;
@endphp

<h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
  {{ $product->get_name() }}
</h1>
