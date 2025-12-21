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
        <div class="{{ $colSpan }} relative group overflow-hidden rounded-2xl h-80 bg-brand-900">
          {{-- Gradient overlay --}}
          <div class="absolute inset-0 opacity-40 mix-blend-overlay transition-transform duration-700 group-hover:scale-105 bg-gradient-to-br from-brand-400 to-brand-700"></div>

          <div class="absolute inset-0 p-8 flex flex-col justify-end">
            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
              <span class="text-xs font-bold tracking-wider uppercase text-white/80 mb-2 block">
                {{ $item['category'] }}
              </span>
              <h3 class="text-2xl font-bold text-white mb-2">
                {{ $item['title'] }}
              </h3>
              <p class="text-white/90 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                {{ $item['description'] }}
              </p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
