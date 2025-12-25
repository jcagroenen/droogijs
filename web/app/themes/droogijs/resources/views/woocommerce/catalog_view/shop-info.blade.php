{{--
  Shop Info Section
  Info blocks displayed below product grid
--}}

@php
  $brand = App\get_current_brand();

  $infoBlocks = [
    'thuis' => [
      ['icon' => 'truck', 'title' => 'Snelle Levering', 'text' => 'Bestel voor 15:00, morgen in huis. Ook in het weekend leverbaar.'],
      ['icon' => 'shield', 'title' => 'Veilig & Betrouwbaar', 'text' => 'Professionele verpakking en duidelijke veiligheidsinstructies.'],
      ['icon' => 'sparkles', 'title' => 'Kwaliteitsgarantie', 'text' => 'Niet tevreden? Geld terug garantie binnen 24 uur.'],
    ],
    'horeca' => [
      ['icon' => 'clock', 'title' => 'Flexibele Levering', 'text' => 'Dagelijkse leveringen mogelijk, aangepast aan uw openingstijden.'],
      ['icon' => 'award', 'title' => 'Premium Kwaliteit', 'text' => 'HACCP gecertificeerd droogijs voor voedselveilige toepassingen.'],
      ['icon' => 'settings', 'title' => 'Op Maat', 'text' => 'Bespreek uw specifieke wensen met onze horeca specialist.'],
    ],
    'industrie' => [
      ['icon' => 'factory', 'title' => 'Grote Volumes', 'text' => 'Dagelijkse leveringen van 100kg tot meerdere tonnen mogelijk.'],
      ['icon' => 'thermometer', 'title' => 'Koude Keten', 'text' => 'Temperatuur gecontroleerde opslag en transport.'],
      ['icon' => 'shield', 'title' => 'ISO Gecertificeerd', 'text' => 'Voldoet aan de hoogste industriële standaarden.'],
    ],
  ];

  $currentBlocks = $infoBlocks[$brand] ?? $infoBlocks['thuis'];
@endphp

<div class="bg-white py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid md:grid-cols-3 gap-8">
      @foreach($currentBlocks as $block)
        <div class="text-center">
          <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-brand-100 flex items-center justify-center">
            @svg('icon-' . $block['icon'], 'w-8 h-8 text-brand-600')
          </div>
          <h3 class="font-bold text-lg mb-2">{{ $block['title'] }}</h3>
          <p class="text-gray-600 text-sm">
            {{ $block['text'] }}
          </p>
        </div>
      @endforeach
    </div>
  </div>
</div>
