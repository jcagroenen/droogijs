{{--
  Shop Header Component
  Displays the shop page header with brand-specific styling
--}}

@php
  $brand = App\get_current_brand();

  $titles = [
    'thuis' => 'Droogijs voor Thuis',
    'horeca' => 'Droogijs voor Horeca',
    'industrie' => 'Droogijs voor Industrie',
  ];

  $descriptions = [
    'thuis' => 'Bestel eenvoudig droogijs voor je feestje, experiment of speciale gelegenheid. Veilig verpakt en snel geleverd.',
    'horeca' => 'Professionele droogijs oplossingen voor bars, restaurants en catering. Betrouwbare levering en volume kortingen.',
    'industrie' => 'Industriële droogijs oplossingen voor koeling, reiniging en transport. Op maat gemaakte leveringen.',
  ];
@endphp

<div class="bg-gradient-to-br from-brand-50 to-brand-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">
    <div class="flex items-center gap-3 mb-6">
      <div class="w-12 h-12 rounded-2xl bg-brand-600 flex items-center justify-center">
        @svg('icon-box', 'w-6 h-6 text-white')
      </div>
      <div>
        <p class="text-sm font-semibold text-brand-600 uppercase tracking-wide">
          Webshop
        </p>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900">
          {{ $titles[$brand] ?? 'Droogijs Shop' }}
        </h1>
      </div>
    </div>
    <p class="text-xl text-gray-600 max-w-3xl mb-8">
      {{ $descriptions[$brand] ?? '' }}
    </p>

    @include('woocommerce.catalog_view.trust-badges')
  </div>
</div>
