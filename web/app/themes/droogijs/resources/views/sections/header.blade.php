{{--
  Main Header Section
  Orchestrates all header components
--}}

{{-- Site Switcher (dev/staging only) --}}
@if(in_array(wp_get_environment_type(), ['development', 'staging', 'local']))
  @php
    $brand = \App\get_current_brand();
    $domain = parse_url(home_url(), PHP_URL_HOST);
    $baseDomain = preg_replace('/^(thuis|horeca|industrie)\./', '', $domain);
    $scheme = is_ssl() ? 'https' : 'http';
  @endphp
  <div class="bg-gray-900 text-white text-sm py-2">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-center gap-4">
      <span class="text-gray-400">Switch site:</span>
      <a href="{{ $scheme }}://thuis.{{ $baseDomain }}" class="px-3 py-1 rounded {{ $brand === 'thuis' ? 'bg-cyan-500' : 'bg-gray-700 hover:bg-gray-600' }}">Thuis</a>
      <a href="{{ $scheme }}://horeca.{{ $baseDomain }}" class="px-3 py-1 rounded {{ $brand === 'horeca' ? 'bg-amber-500' : 'bg-gray-700 hover:bg-gray-600' }}">Horeca</a>
      <a href="{{ $scheme }}://industrie.{{ $baseDomain }}" class="px-3 py-1 rounded {{ $brand === 'industrie' ? 'bg-slate-500' : 'bg-gray-700 hover:bg-gray-600' }}">Industrie</a>
    </div>
  </div>
@endif

{{-- Main Header --}}
<header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-20">
      {{-- Logo --}}
      @include('header.logo')

      {{-- Desktop Navigation --}}
      @include('header.nav-desktop')

      {{-- Mobile Header Actions --}}
      <div class="flex md:hidden items-center gap-2">
        {{-- Mobile Cart Icon --}}
        @include('header.cart-dropdown')

        {{-- Mobile Menu Button --}}
        <button
          x-on:click="mobileMenuOpen = !mobileMenuOpen"
          type="button"
          class="p-2 text-gray-600 hover:text-gray-900"
          aria-label="Menu"
          :aria-expanded="mobileMenuOpen"
        >
          <template x-if="!mobileMenuOpen">
            @svg('heroicon-o-bars-3', 'w-6 h-6')
          </template>
          <template x-if="mobileMenuOpen">
            @svg('heroicon-o-x-mark', 'w-6 h-6')
          </template>
        </button>
      </div>
    </div>
  </div>

  {{-- Mobile Navigation Panel --}}
  @include('header.nav-mobile')
</header>

{{-- Mobile Bestellen Bar --}}
@include('sections.header-mobile-bar')
