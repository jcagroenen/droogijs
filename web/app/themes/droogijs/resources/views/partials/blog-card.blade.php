@php
  $sizeClasses = match($post['size'] ?? 'medium') {
    'large' => 'md:col-span-2 md:row-span-2 min-h-[500px]',
    'medium' => 'md:col-span-1 md:row-span-1 min-h-[320px]',
    'small' => 'md:col-span-1 md:row-span-1 min-h-[280px]',
  };
  $titleSize = ($post['size'] ?? 'medium') === 'large' ? 'text-4xl md:text-5xl' : 'text-2xl md:text-3xl';
  $descSize = ($post['size'] ?? 'medium') === 'large' ? 'text-lg max-w-2xl' : 'text-base';
@endphp

<a href="{{ $post['url'] }}" class="group relative overflow-hidden rounded-3xl {{ $sizeClasses }} transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl no-underline">
  {{-- Background Image or Gradient --}}
  <div class="absolute inset-0 bg-brand-800">
    @if($post['image'])
      <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
    @endif
  </div>

  {{-- Overlay Gradient --}}
  <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent opacity-70 group-hover:opacity-80 transition-opacity duration-500"></div>

  {{-- Vapor Effect Overlay --}}
  <div class="absolute inset-0 opacity-0 group-hover:opacity-30 transition-opacity duration-700">
    <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-white/40 to-transparent blur-2xl"></div>
  </div>

  {{-- Content --}}
  <div class="relative h-full p-8 flex flex-col justify-end">
    <div class="transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
      <span class="inline-block text-xs font-bold tracking-widest uppercase text-white/80 mb-3 bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full">
        {{ $post['category'] }}
      </span>

      <h3 class="font-bold text-white mb-3 no-underline {{ $titleSize }}">
        {{ $post['title'] }}
      </h3>

      <p class="text-white/90 leading-relaxed mb-4 {{ $descSize }}">
        {{ $post['excerpt'] }}
      </p>

      <div class="flex items-center text-white font-semibold">
        <span class="mr-2">Lees meer</span>
        @svg('icon-arrow-right', 'w-5 h-5 transform group-hover:translate-x-1 transition-transform')
      </div>
    </div>
  </div>
</a>
