{{--
  Cart Dropdown Component
  Mini-cart with AJAX updates
--}}

@php
  $cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
  $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : '/cart/';
  $checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '/checkout/';
@endphp

<div x-data="{ cartOpen: false }" class="relative">
  {{-- Cart button --}}
  <button
    x-on:click="cartOpen = !cartOpen"
    type="button"
    class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors"
    aria-label="Winkelwagen"
  >
    @svg('heroicon-o-shopping-cart', 'w-6 h-6')

    {{-- Cart count badge --}}
    <span
      id="header-cart-count"
      class="absolute -top-1 -right-1 w-5 h-5 bg-brand-600 text-white text-xs font-bold rounded-full flex items-center justify-center {{ $cart_count === 0 ? 'hidden' : '' }}"
    >
      {{ $cart_count }}
    </span>
  </button>

  {{-- Dropdown panel --}}
  <div
    x-show="cartOpen"
    x-on:click.outside="cartOpen = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-1"
    x-cloak
    class="absolute top-full right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50"
  >
    {{-- Header --}}
    <div class="p-4 border-b border-gray-100">
      <h3 class="font-bold text-lg">Winkelwagen</h3>
      <p class="text-sm text-gray-500">
        <span id="header-cart-count-text">{{ $cart_count }}</span> {{ $cart_count === 1 ? 'item' : 'items' }}
      </p>
    </div>

    {{-- Cart contents (AJAX updated) --}}
    <div id="header-mini-cart" class="max-h-80 overflow-y-auto">
      @if(function_exists('WC') && WC()->cart && !WC()->cart->is_empty())
        @foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item)
          @php
            $product = $cart_item['data'];
            $product_name = $product->get_name();
            $product_price = WC()->cart->get_product_price($product);
            $quantity = $cart_item['quantity'];
            $thumbnail = $product->get_image('thumbnail', ['class' => 'w-full h-full object-cover']);
            $delivery_date = $cart_item['delivery_date'] ?? '';
          @endphp
          <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
            <div class="flex gap-3">
              <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                {!! $thumbnail !!}
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="font-semibold text-sm truncate">{{ $product_name }}</h4>
                <p class="text-xs text-gray-500">Aantal: {{ $quantity }}</p>
                @if($delivery_date)
                  <p class="text-xs text-brand-600 font-medium mt-1">
                    <svg class="w-3 h-3 inline-block mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    {{ $delivery_date }}
                  </p>
                @endif
                <span class="text-sm font-bold">{!! $product_price !!}</span>
              </div>
            </div>
          </div>
        @endforeach
      @else
        <div class="p-8 text-center text-gray-500">
          <p>Je winkelwagen is leeg</p>
        </div>
      @endif
    </div>

    {{-- Footer with totals and buttons --}}
    @if(function_exists('WC') && WC()->cart && !WC()->cart->is_empty())
      <div class="p-4 bg-gray-50 border-t border-gray-100">
        <div class="flex justify-between mb-4">
          <span class="font-semibold">Totaal</span>
          <span id="header-cart-total" class="font-bold text-lg">{!! WC()->cart->get_cart_total() !!}</span>
        </div>
        <div class="space-y-2">
          <a
            href="{{ $cart_url }}"
            x-on:click="cartOpen = false"
            class="block w-full text-center px-6 py-2.5 rounded-lg font-semibold border-2 border-brand-600 text-brand-600 hover:bg-brand-50 transition-colors"
          >
            Bekijk winkelwagen
          </a>
          <a
            href="{{ $checkout_url }}"
            x-on:click="cartOpen = false"
            class="block w-full text-center px-6 py-3 rounded-lg font-bold text-white bg-brand-600 hover:bg-brand-700 transition-colors"
          >
            Afrekenen
          </a>
        </div>
      </div>
    @endif
  </div>
</div>
