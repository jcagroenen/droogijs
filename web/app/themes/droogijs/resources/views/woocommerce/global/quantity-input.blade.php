{{--
  Custom Quantity Input with +/- buttons
  Overrides: woocommerce/global/quantity-input.php
--}}

@php
  $min_value = $args['min_value'] ?? 1;
  $max_value = $args['max_value'] ?? '';
  $input_value = $args['input_value'] ?? 1;
  $input_name = $args['input_name'] ?? 'quantity';
  $input_id = $args['input_id'] ?? uniqid('quantity_');
  $step = $args['step'] ?? 1;
@endphp

<div class="quantity-wrapper flex items-center mb-4" x-data="{ qty: {{ $input_value }} }">
  {{-- Minus button --}}
  <button
    type="button"
    class="quantity-btn minus w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-l-lg border border-r-0 border-gray-300 transition-colors"
    x-on:click="qty = Math.max({{ $min_value }}, qty - {{ $step }})"
    aria-label="Verminder aantal"
  >
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
    </svg>
  </button>

  {{-- Quantity input --}}
  <input
    type="number"
    id="{{ $input_id }}"
    name="{{ $input_name }}"
    class="quantity-input w-14 h-10 text-center border-y border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
    x-model="qty"
    min="{{ $min_value }}"
    @if($max_value) max="{{ $max_value }}" @endif
    step="{{ $step }}"
  />

  {{-- Plus button --}}
  <button
    type="button"
    class="quantity-btn plus w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-r-lg border border-l-0 border-gray-300 transition-colors"
    x-on:click="qty = {{ $max_value ? "Math.min({$max_value}, qty + {$step})" : "qty + {$step}" }}"
    aria-label="Verhoog aantal"
  >
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
  </button>
</div>
