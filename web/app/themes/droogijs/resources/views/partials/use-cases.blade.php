<section class="py-24 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
        {{ $data['title'] }}
      </h2>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
      @foreach($data['items'] as $index => $item)
        @php
          $isLarge = $index === 0 || $index === 3;
          $colSpan = $isLarge ? 'md:col-span-2' : 'md:col-span-1';
        @endphp
        <a href="{{ $item['url'] }}" class="{{ $colSpan }} relative group overflow-hidden rounded-2xl h-80 bg-brand-800 block no-underline">
          {{-- Featured image --}}
          @if($item['image'])
            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
          @endif

          {{-- Overlay gradient --}}
          <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent opacity-70 group-hover:opacity-80 transition-opacity duration-500"></div>

          <div class="absolute inset-0 p-8 flex flex-col justify-end">
            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
              <span class="text-xs font-bold tracking-wider uppercase text-white/80 mb-2 block">
                {{ $item['category'] }}
              </span>
              <h3 class="text-2xl font-bold text-white mb-2 no-underline">
                {{ $item['title'] }}
              </h3>
              <p class="text-white/90 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                {{ $item['description'] }}
              </p>
            </div>
          </div>
        </a>
      @endforeach
    </div>

    <div class="mt-12 text-center">
      <a href="/inspiratie/" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold text-lg transition-all transform hover:-translate-y-1 bg-brand-600 hover:bg-brand-700 text-white shadow-lg shadow-brand-200">
        Bekijk alle inspiratie
        @svg('icon-arrow-right', 'w-5 h-5')
      </a>
    </div>
  </div>
</section>
