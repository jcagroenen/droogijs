{{--
  Product Attributes/Specifications Component
--}}

@php
  global $product;
  $attributes = $product->get_attributes();
@endphp

@if(!empty($attributes))
  <div class="border-t border-gray-200 pt-8 mt-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Specificaties</h2>
    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @foreach($attributes as $attribute)
        @php
          $name = wc_attribute_label($attribute->get_name());
          $values = $attribute->is_taxonomy()
            ? implode(', ', wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']))
            : $attribute->get_options();

          if (is_array($values)) {
            $values = implode(', ', $values);
          }
        @endphp
        @if($values)
          <div class="bg-gray-50 rounded-lg p-4">
            <dt class="text-sm text-gray-500 mb-1">{{ $name }}</dt>
            <dd class="font-medium text-gray-900">{{ $values }}</dd>
          </div>
        @endif
      @endforeach
    </dl>
  </div>
@endif
