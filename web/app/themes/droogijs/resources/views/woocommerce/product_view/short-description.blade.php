{{--
  Product Short Description Component
--}}

@php
  global $product;
  $shortDescription = $product->get_short_description();
@endphp

@if($shortDescription)
  <div class="prose prose-lg text-gray-600 mb-6">
    {!! $shortDescription !!}
  </div>
@endif
