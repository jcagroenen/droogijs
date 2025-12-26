<section class="relative overflow-hidden bg-gradient-to-br from-brand-50 to-brand-100 min-h-[90vh] flex items-center pt-20">
  {{-- Vapor/Smoke Animation Layer --}}
  <div class="absolute inset-0 pointer-events-none overflow-hidden">
    <div class="absolute -bottom-1/2 -left-1/4 w-[150%] h-[150%] bg-gradient-to-t from-white via-white/50 to-transparent blur-3xl animate-pulse opacity-30 mix-blend-screen" style="animation-duration: 8s;"></div>
    <div class="absolute -bottom-1/3 -right-1/4 w-[120%] h-[120%] bg-gradient-to-t from-white via-white/40 to-transparent blur-3xl animate-pulse opacity-30 mix-blend-screen" style="animation-duration: 12s; animation-delay: 1s;"></div>
  </div>

  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      {{-- Content --}}
      <div class="max-w-2xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/60 backdrop-blur-sm border border-gray-200 text-sm font-medium mb-6 text-brand-600">
          <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-brand-400"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
          </span>
          Direct leverbaar uit voorraad
        </div>

        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight text-gray-900 mb-6 leading-[1.1]">
          {{ $data['title'] }}
        </h1>

        <p class="text-xl text-gray-600 mb-8 leading-relaxed max-w-lg">
          {{ $data['subtitle'] }}
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
          <a href="{{ function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/shop/' }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white rounded-xl shadow-lg transition-all transform hover:-translate-y-1 bg-brand-600 hover:bg-brand-700">
            {{ $data['ctaText'] }}
            @svg('icon-arrow-right', 'ml-2 w-5 h-5')
          </a>

          <a href="#info" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-xl transition-colors text-brand-700 hover:bg-brand-50">
            @svg('icon-info-circle', 'mr-2 w-5 h-5')
            {{ $data['secondaryCtaText'] }}
          </a>
        </div>

        <div class="mt-10 flex items-center gap-6 text-sm text-gray-500">
          <div class="flex -space-x-2">
            @foreach(['A', 'B', 'C', 'D'] as $letter)
              <div class="w-8 h-8 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-xs font-bold">
                {{ $letter }}
              </div>
            @endforeach
          </div>
          <p>
            Al meer dan
            <span class="font-bold text-gray-900">1000+ klanten</span>
            gingen u voor
          </p>
        </div>
      </div>

      {{-- Visual/Image Placeholder --}}
      <div class="relative lg:h-[600px] flex items-center justify-center">
        <div class="relative w-full aspect-square max-w-md lg:max-w-full">
          <div class="absolute inset-0 rounded-3xl rotate-3 opacity-20 bg-brand-200"></div>
          <div class="absolute inset-0 rounded-3xl -rotate-3 bg-white shadow-2xl overflow-hidden border border-gray-100">
            <img src="{{ Vite::asset('resources/images/Droogijs_gaslas.jpg') }}" alt="Droogijs" class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
