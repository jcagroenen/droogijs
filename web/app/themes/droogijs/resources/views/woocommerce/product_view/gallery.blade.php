{{--
  Product Gallery Component
  Main image with thumbnail navigation
--}}

@php
  global $product;

  $mainImageId = $product->get_image_id();
  $galleryIds = $product->get_gallery_image_ids();

  $images = [];
  if ($mainImageId) {
    $images[] = wp_get_attachment_image_url($mainImageId, 'large');
  }
  foreach ($galleryIds as $id) {
    $images[] = wp_get_attachment_image_url($id, 'large');
  }

  if (empty($images)) {
    $images[] = wc_placeholder_img_src('large');
  }
@endphp

<div x-data="{
  images: @js($images),
  selectedIndex: 0,
  get selectedImage() {
    return this.images[this.selectedIndex];
  },
  prevImage() {
    this.selectedIndex = (this.selectedIndex - 1 + this.images.length) % this.images.length;
  },
  nextImage() {
    this.selectedIndex = (this.selectedIndex + 1) % this.images.length;
  }
}" class="mb-8">
  {{-- Main Image --}}
  <div class="relative bg-white rounded-2xl overflow-hidden mb-4">
    <div class="relative h-80 md:h-[500px] flex items-center justify-center p-8">
      <img
        :src="selectedImage"
        :alt="'{{ esc_attr($product->get_name()) }}'"
        class="max-h-full w-auto object-contain transition-transform duration-300 hover:scale-105"
      >

      {{-- Navigation Arrows --}}
      <template x-if="images.length > 1">
        <div>
          <button
            @click="prevImage()"
            class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors"
          >
            <span class="sr-only">Vorige</span>
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button
            @click="nextImage()"
            class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors"
          >
            <span class="sr-only">Volgende</span>
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </template>
    </div>
  </div>

  {{-- Thumbnails --}}
  <template x-if="images.length > 1">
    <div class="flex gap-3 overflow-x-auto pb-2">
      <template x-for="(image, index) in images" :key="index">
        <button
          @click="selectedIndex = index"
          class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition-colors"
          :class="selectedIndex === index ? 'border-brand-500' : 'border-gray-200 hover:border-gray-300'"
        >
          <img :src="image" alt="" class="w-full h-full object-cover">
        </button>
      </template>
    </div>
  </template>
</div>
