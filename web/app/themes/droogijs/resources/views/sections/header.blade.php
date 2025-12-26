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

<header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-20">
      {{-- Logo --}}
      <a class="block" href="{{ home_url('/') }}">
        @include('partials.logo', ['class' => 'h-12'])
      </a>

      {{-- Desktop Navigation --}}
      <nav class="hidden md:flex items-center gap-8">
        <a href="#toepassingen" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
          Toepassingen
        </a>
        <a href="#hoe-het-werkt" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
          Hoe het werkt
        </a>
        <a href="#veiligheid" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
          Veiligheid
        </a>
        <a href="{{ function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/shop/' }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border-2 font-semibold transition-all hover:shadow-md border-brand-600 text-brand-600 hover:bg-brand-50">
          @svg('icon-box', 'w-5 h-5')
          Bestellen
        </a>
      </nav>

      {{-- Mobile menu button --}}
      <button
        x-on:click="mobileMenuOpen = !mobileMenuOpen"
        type="button"
        class="md:hidden p-2 text-gray-600 hover:text-gray-900"
        aria-label="Menu"
        :aria-expanded="mobileMenuOpen"
      >
        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>

  {{-- Mobile menu panel --}}
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
      <a x-on:click="mobileMenuOpen = false" href="#toepassingen" class="block px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors">
        Toepassingen
      </a>
      <a x-on:click="mobileMenuOpen = false" href="#hoe-het-werkt" class="block px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors">
        Hoe het werkt
      </a>
      <a x-on:click="mobileMenuOpen = false" href="#veiligheid" class="block px-4 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-colors">
        Veiligheid
      </a>
      <a x-on:click="mobileMenuOpen = false" href="{{ function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/shop/' }}" class="block px-4 py-3 rounded-lg font-semibold text-center text-white bg-brand-600 hover:bg-brand-700 transition-colors">
        @svg('icon-box', 'w-5 h-5 inline-block mr-2')
        Bestellen
      </a>
    </nav>
  </div>
</header>
