{{--
  Mobile Navigation Component
  Full menu panel for mobile
--}}

@php
  $is_logged_in = is_user_logged_in();
  $account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '/my-account/';
  $orders_url = function_exists('wc_get_account_endpoint_url') ? wc_get_account_endpoint_url('orders') : $account_url . 'orders/';
  $logout_url = wp_logout_url(home_url('/'));
  $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/shop/';

  if ($is_logged_in) {
    $current_user = wp_get_current_user();
    $user_name = $current_user->display_name;
    $user_email = $current_user->user_email;
    $user_initials = strtoupper(substr($user_name, 0, 2));
  }
@endphp

<div
  x-show="mobileMenuOpen"
  x-cloak
  x-transition:enter="transition ease-out duration-200"
  x-transition:enter-start="opacity-0 -translate-y-1"
  x-transition:enter-end="opacity-100 translate-y-0"
  x-transition:leave="transition ease-in duration-150"
  x-transition:leave-start="opacity-100 translate-y-0"
  x-transition:leave-end="opacity-0 -translate-y-1"
  class="md:hidden border-t border-gray-100 bg-white"
>
  <nav class="px-4 py-4 space-y-2">
    {{-- User info (if logged in) --}}
    @if($is_logged_in)
      <div class="py-3 border-b border-gray-100 mb-2">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-brand-600 flex items-center justify-center text-white font-bold">
            {{ $user_initials }}
          </div>
          <div>
            <p class="font-semibold text-sm">{{ $user_name }}</p>
            <p class="text-xs text-gray-500">{{ $user_email }}</p>
          </div>
        </div>
      </div>
    @endif

    {{-- Navigation links --}}
    <a
      x-on:click="mobileMenuOpen = false"
      href="#toepassingen"
      class="block px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors"
    >
      Toepassingen
    </a>
    <a
      x-on:click="mobileMenuOpen = false"
      href="#hoe-het-werkt"
      class="block px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors"
    >
      Hoe het werkt
    </a>
    <a
      x-on:click="mobileMenuOpen = false"
      href="#veiligheid"
      class="block px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors"
    >
      Veiligheid
    </a>

    {{-- Account links (if logged in) --}}
    @if($is_logged_in)
      <a
        x-on:click="mobileMenuOpen = false"
        href="{{ $orders_url }}"
        class="block px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors"
      >
        Mijn bestellingen
      </a>
      <a
        x-on:click="mobileMenuOpen = false"
        href="{{ $account_url }}"
        class="block px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors"
      >
        Account instellingen
      </a>
    @else
      <a
        x-on:click="mobileMenuOpen = false"
        href="{{ $account_url }}"
        class="block px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors"
      >
        Inloggen
      </a>
    @endif

    {{-- Logout (if logged in) --}}
    @if($is_logged_in)
      <a
        href="{{ $logout_url }}"
        class="block px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 font-medium transition-colors"
      >
        Uitloggen
      </a>
    @endif

    {{-- Shop CTA --}}
    <a
      x-on:click="mobileMenuOpen = false"
      href="{{ $shop_url }}"
      class="block mt-4 px-4 py-3 rounded-lg font-semibold text-center text-white bg-brand-600 hover:bg-brand-700 transition-colors"
    >
      @svg('icon-box', 'w-5 h-5 inline-block mr-2')
      Naar Webshop
    </a>
  </nav>
</div>
