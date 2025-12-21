<section class="py-24 bg-white" id="toepassingen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-16">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
        {{ $data['title'] }}
      </h2>
      <p class="text-xl text-gray-600">{{ $data['subtitle'] }}</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
      @foreach($data['items'] as $feature)
        <div class="p-8 rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-brand-200">
          <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6 bg-brand-50 text-brand-600">
            @svg('icon-' . $feature['icon'], 'w-7 h-7')
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">
            {{ $feature['title'] }}
          </h3>
          <p class="text-gray-600 leading-relaxed">
            {{ $feature['description'] }}
          </p>
        </div>
      @endforeach
    </div>
  </div>
</section>
