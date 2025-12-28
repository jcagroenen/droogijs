{{--
  Product Delivery Date Picker Component
--}}

@php
  global $product;
  // Minimum days ahead for delivery (can be set per product via custom field)
  $minDays = get_post_meta($product->get_id(), '_delivery_min_days', true) ?: 1;

  // Default date (tomorrow or next available)
  $defaultDate = new DateTime();
  $defaultDate->modify('+' . $minDays . ' days');
  // Skip Sunday
  if ($defaultDate->format('w') == 0) {
    $defaultDate->modify('+1 day');
  }
  $formattedDefault = $defaultDate->format('d-m-Y');
@endphp

<div class="delivery-date-wrapper mb-6">
  <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
    Selecteer de gewenste leverdatum
  </label>

  <input
    type="text"
    id="delivery_date"
    name="delivery_date"
    class="delivery-date-picker w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 cursor-pointer bg-white"
    data-min-days="{{ $minDays }}"
    value="{{ $formattedDefault }}"
    placeholder="Kies een datum"
    required
  />

  <p class="mt-2 text-sm text-gray-500">
    Levering is mogelijk vanaf morgen (behalve op zondag)
  </p>
</div>
