{{--
  Trust Badges Component
  Shows trust indicators below shop header
--}}

@php
  $brand = App\get_current_brand();

  $badges = [
    'thuis' => [
      ['icon' => 'truck', 'text' => 'Gratis verzending vanaf €50'],
      ['icon' => 'shield', 'text' => 'Veilig verpakt'],
      ['icon' => 'sparkles', 'text' => 'Inclusief instructies'],
    ],
    'horeca' => [
      ['icon' => 'truck', 'text' => 'Levering binnen 24 uur'],
      ['icon' => 'shield', 'text' => 'HACCP gecertificeerd'],
      ['icon' => 'award', 'text' => 'Volume kortingen'],
    ],
    'industrie' => [
      ['icon' => 'truck', 'text' => 'Op maat levering'],
      ['icon' => 'shield', 'text' => 'ISO gecertificeerd'],
      ['icon' => 'factory', 'text' => 'Industriële kwaliteit'],
    ],
  ];

  $currentBadges = $badges[$brand] ?? $badges['thuis'];
@endphp

<div class="flex flex-wrap gap-6 text-sm">
  @foreach($currentBadges as $badge)
    <div class="flex items-center gap-2 text-gray-700">
      @svg('icon-' . $badge['icon'], 'w-5 h-5 text-brand-600')
      <span class="font-medium">{{ $badge['text'] }}</span>
    </div>
  @endforeach
</div>
