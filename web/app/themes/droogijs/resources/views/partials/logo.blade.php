@php
  $brand = $brand ?? App\get_current_brand();
  $class = $class ?? 'h-10';
@endphp

@if($brand === 'thuis')
<svg class="{{ $class }}" viewBox="0 0 200 60" fill="none" xmlns="http://www.w3.org/2000/svg">
  {{-- House icon with vapor --}}
  <g>
    {{-- Vapor clouds - playful and soft --}}
    <circle cx="18" cy="45" r="4" fill="#06B6D4" opacity="0.3">
      <animate attributeName="cy" values="45;42;45" dur="3s" repeatCount="indefinite" />
    </circle>
    <circle cx="12" cy="48" r="3" fill="#0EA5E9" opacity="0.25">
      <animate attributeName="cy" values="48;44;48" dur="3.5s" repeatCount="indefinite" />
    </circle>
    <circle cx="24" cy="47" r="3.5" fill="#0EA5E9" opacity="0.25">
      <animate attributeName="cy" values="47;43;47" dur="2.8s" repeatCount="indefinite" />
    </circle>

    {{-- House shape --}}
    <path d="M8 28L18 18L28 28V42C28 43.1046 27.1046 44 26 44H10C8.89543 44 8 43.1046 8 42V28Z" fill="#0EA5E9" stroke="#0EA5E9" stroke-width="1.5" stroke-linejoin="round" />
    <path d="M18 18L18 12L22 12L22 18" stroke="#0EA5E9" stroke-width="1.5" stroke-linecap="round" />
    <rect x="14" y="34" width="8" height="10" rx="1" fill="white" opacity="0.9" />
    <rect x="20" y="28" width="4" height="4" rx="0.5" fill="white" opacity="0.9" />
  </g>

  {{-- Text --}}
  <text x="38" y="32" font-family="system-ui, -apple-system, sans-serif" font-size="20" font-weight="700" fill="#0EA5E9">
    Droogijs
  </text>
  <text x="38" y="48" font-family="system-ui, -apple-system, sans-serif" font-size="14" font-weight="600" fill="#0EA5E9" opacity="0.8">
    Voor Thuis
  </text>
</svg>

@elseif($brand === 'horeca')
<svg class="{{ $class }}" viewBox="0 0 200 60" fill="none" xmlns="http://www.w3.org/2000/svg">
  {{-- Cocktail glass with vapor --}}
  <g>
    {{-- Vapor - elegant wisps --}}
    <path d="M12 46C12 46 14 44 16 44C18 44 20 46 20 46" stroke="#F59E0B" stroke-width="1.5" stroke-linecap="round" opacity="0.4">
      <animate attributeName="d" values="M12 46C12 46 14 44 16 44C18 44 20 46 20 46;M12 44C12 44 14 42 16 42C18 42 20 44 20 44;M12 46C12 46 14 44 16 44C18 44 20 46 20 46" dur="3s" repeatCount="indefinite" />
    </path>
    <path d="M10 48C10 48 12 46 14 46C16 46 18 48 18 48" stroke="#F59E0B" stroke-width="1.5" stroke-linecap="round" opacity="0.3">
      <animate attributeName="d" values="M10 48C10 48 12 46 14 46C16 46 18 48 18 48;M10 46C10 46 12 44 14 44C16 44 18 46 18 46;M10 48C10 48 12 46 14 46C16 46 18 48 18 48" dur="3.5s" repeatCount="indefinite" />
    </path>
    <path d="M22 48C22 48 24 46 26 46C28 46 30 48 30 48" stroke="#F59E0B" stroke-width="1.5" stroke-linecap="round" opacity="0.3">
      <animate attributeName="d" values="M22 48C22 48 24 46 26 46C28 46 30 48 30 48;M22 46C22 46 24 44 26 44C28 44 30 46 30 46;M22 48C22 48 24 46 26 46C28 46 30 48 30 48" dur="2.8s" repeatCount="indefinite" />
    </path>

    {{-- Cocktail glass - martini style --}}
    <path d="M10 20L20 38L20 44L18 44L18 46L22 46L22 44L20 44L20 38L30 20L10 20Z" fill="#F59E0B" stroke="#F59E0B" stroke-width="1.5" stroke-linejoin="round" />
    <circle cx="20" cy="32" r="2" fill="white" opacity="0.6" />
    <line x1="20" y1="20" x2="20" y2="38" stroke="white" stroke-width="0.5" opacity="0.3" />
  </g>

  {{-- Text --}}
  <text x="42" y="32" font-family="system-ui, -apple-system, sans-serif" font-size="20" font-weight="700" fill="#F59E0B">
    Droogijs
  </text>
  <text x="42" y="48" font-family="system-ui, -apple-system, sans-serif" font-size="14" font-weight="600" fill="#F59E0B" opacity="0.8">
    Voor Horeca
  </text>
</svg>

@else
{{-- Industrie --}}
<svg class="{{ $class }}" viewBox="0 0 200 60" fill="none" xmlns="http://www.w3.org/2000/svg">
  {{-- Industrial container with vapor --}}
  <g>
    {{-- Vapor - industrial steam --}}
    <rect x="10" y="44" width="3" height="6" rx="1.5" fill="#475569" opacity="0.2">
      <animate attributeName="height" values="6;4;6" dur="2s" repeatCount="indefinite" />
      <animate attributeName="y" values="44;46;44" dur="2s" repeatCount="indefinite" />
    </rect>
    <rect x="15" y="46" width="2.5" height="4" rx="1.25" fill="#475569" opacity="0.15">
      <animate attributeName="height" values="4;2;4" dur="2.5s" repeatCount="indefinite" />
      <animate attributeName="y" values="46;48;46" dur="2.5s" repeatCount="indefinite" />
    </rect>
    <rect x="20" y="45" width="3" height="5" rx="1.5" fill="#475569" opacity="0.2">
      <animate attributeName="height" values="5;3;5" dur="2.2s" repeatCount="indefinite" />
      <animate attributeName="y" values="45;47;45" dur="2.2s" repeatCount="indefinite" />
    </rect>
    <rect x="26" y="46" width="2.5" height="4" rx="1.25" fill="#475569" opacity="0.15">
      <animate attributeName="height" values="4;2;4" dur="2.7s" repeatCount="indefinite" />
      <animate attributeName="y" values="46;48;46" dur="2.7s" repeatCount="indefinite" />
    </rect>

    {{-- Industrial container/dewar --}}
    <rect x="8" y="26" width="24" height="18" rx="2" fill="#1E40AF" stroke="#1E40AF" stroke-width="2" />
    <rect x="12" y="22" width="16" height="4" rx="1" fill="#1E40AF" stroke="#1E40AF" stroke-width="1.5" />
    <line x1="14" y1="30" x2="14" y2="40" stroke="white" stroke-width="1.5" opacity="0.3" />
    <line x1="20" y1="30" x2="20" y2="40" stroke="white" stroke-width="1.5" opacity="0.3" />
    <line x1="26" y1="30" x2="26" y2="40" stroke="white" stroke-width="1.5" opacity="0.3" />

    {{-- Handle --}}
    <path d="M8 30C6 30 6 28 6 28C6 28 6 26 8 26" stroke="#1E40AF" stroke-width="2" fill="none" stroke-linecap="round" />
    <path d="M32 30C34 30 34 28 34 28C34 28 34 26 32 26" stroke="#1E40AF" stroke-width="2" fill="none" stroke-linecap="round" />
  </g>

  {{-- Text --}}
  <text x="42" y="32" font-family="system-ui, -apple-system, sans-serif" font-size="20" font-weight="700" fill="#1E40AF">
    Droogijs
  </text>
  <text x="42" y="48" font-family="system-ui, -apple-system, sans-serif" font-size="14" font-weight="600" fill="#475569" opacity="0.9">
    Voor Industrie
  </text>
</svg>
@endif
