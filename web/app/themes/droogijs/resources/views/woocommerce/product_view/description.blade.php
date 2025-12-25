{{--
  Product Full Description Component
--}}

@php
  global $product;
  $description = $product->get_description();
@endphp

@if($description)
  <div class="border-t border-gray-200 pt-8 mt-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Productomschrijving</h2>
    <div class="prose prose-lg max-w-none text-gray-600">
      {!! $description !!}
    </div>
  </div>
@endif
