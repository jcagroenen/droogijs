<header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      {{-- Logo --}}
      <a class="text-xl font-bold text-gray-900" href="{{ home_url('/') }}">
        {!! $siteName !!}
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
        <a href="#bestellen" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border-2 font-semibold transition-all hover:shadow-md border-brand-600 text-brand-600 hover:bg-brand-50">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
          </svg>
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
