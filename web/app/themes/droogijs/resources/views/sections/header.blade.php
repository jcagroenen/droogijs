{{-- Site Switcher (dev/staging only) --}}
@php $showSwitcher = in_array(wp_get_environment_type(), ['development', 'staging', 'local']); @endphp
@if($showSwitcher)
@php
  $brand = \App\get_current_brand();
  $domain = parse_url(home_url(), PHP_URL_HOST);
  // Get base domain (e.g., droogijs.test or droogijs.groenen-webdev.nl)
  $baseDomain = preg_replace('/^(thuis|horeca|industrie)\./', '', $domain);
  $scheme = is_ssl() ? 'https' : 'http';
@endphp
<div class="fixed top-0 left-0 right-0 z-50 bg-gray-900 text-white text-sm py-2">
  <div class="max-w-7xl mx-auto px-4 flex items-center justify-center gap-4">
    <span class="text-gray-400">Switch site:</span>
    <a href="{{ $scheme }}://thuis.{{ $baseDomain }}" class="px-3 py-1 rounded {{ $brand === 'thuis' ? 'bg-cyan-500' : 'bg-gray-700 hover:bg-gray-600' }}">Thuis</a>
    <a href="{{ $scheme }}://horeca.{{ $baseDomain }}" class="px-3 py-1 rounded {{ $brand === 'horeca' ? 'bg-amber-500' : 'bg-gray-700 hover:bg-gray-600' }}">Horeca</a>
    <a href="{{ $scheme }}://industrie.{{ $baseDomain }}" class="px-3 py-1 rounded {{ $brand === 'industrie' ? 'bg-slate-500' : 'bg-gray-700 hover:bg-gray-600' }}">Industrie</a>
  </div>
</div>
@endif

<header class="fixed left-0 right-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100 {{ $showSwitcher ? 'top-9' : 'top-0' }}">
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
      <button type="button" class="md:hidden p-2 text-gray-600 hover:text-gray-900" aria-label="Menu">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
      </button>
    </div>
  </div>
</header>
