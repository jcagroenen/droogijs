{{--
  User Dropdown Component
  - Logged out: Link to My Account
  - Logged in: Dropdown with user menu
--}}

@php
  $is_logged_in = is_user_logged_in();
  $account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '/my-account/';
  $orders_url = function_exists('wc_get_account_endpoint_url') ? wc_get_account_endpoint_url('orders') : $account_url . 'orders/';
  $logout_url = wp_logout_url(home_url('/'));

  if ($is_logged_in) {
    $current_user = wp_get_current_user();
    $user_name = $current_user->display_name;
    $user_email = $current_user->user_email;
    $user_initials = strtoupper(substr($user_name, 0, 2));
  }
@endphp

@if($is_logged_in)
  {{-- Logged in: Dropdown --}}
  <div x-data="{ userOpen: false }" class="relative">
    <button
      x-on:click="userOpen = !userOpen"
      type="button"
      class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition-colors"
      aria-label="Account menu"
    >
      <div class="w-8 h-8 rounded-full bg-brand-600 flex items-center justify-center text-white font-bold text-sm">
        {{ $user_initials }}
      </div>
    </button>

    {{-- Dropdown panel --}}
    <div
      x-show="userOpen"
      x-on:click.outside="userOpen = false"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 translate-y-1"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 translate-y-1"
      x-cloak
      class="absolute top-full right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50"
    >
      {{-- User info --}}
      <div class="px-4 py-3 border-b border-gray-100">
        <p class="font-semibold text-sm">{{ $user_name }}</p>
        <p class="text-xs text-gray-500 truncate">{{ $user_email }}</p>
      </div>

      {{-- Menu items --}}
      <div class="py-2">
        <a
          href="{{ $orders_url }}"
          x-on:click="userOpen = false"
          class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
        >
          @svg('heroicon-o-shopping-bag', 'w-5 h-5 text-gray-600')
          <span class="text-sm font-medium">Mijn bestellingen</span>
        </a>

        <a
          href="{{ $account_url }}"
          x-on:click="userOpen = false"
          class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
        >
          @svg('heroicon-o-cog-6-tooth', 'w-5 h-5 text-gray-600')
          <span class="text-sm font-medium">Instellingen</span>
        </a>
      </div>

      {{-- Logout --}}
      <div class="border-t border-gray-100 py-2">
        <a
          href="{{ $logout_url }}"
          class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-colors text-red-600"
        >
          @svg('heroicon-o-arrow-right-on-rectangle', 'w-5 h-5')
          <span class="text-sm font-medium">Uitloggen</span>
        </a>
      </div>
    </div>
  </div>
@else
  {{-- Logged out: Simple link --}}
  <a
    href="{{ $account_url }}"
    class="p-2 text-gray-600 hover:text-gray-900 transition-colors"
    aria-label="Inloggen"
  >
    @svg('heroicon-o-user', 'w-6 h-6')
  </a>
@endif
