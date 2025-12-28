{{--
  Desktop Navigation Component
  Order: Bestellen | Toepassingen | Hoe het werkt | Veiligheid | [cart] [user]
--}}

<nav class="hidden md:flex items-center gap-6">
  {{-- Bestellen button (primary CTA) --}}
  <a href="{{ function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/shop/' }}"
     class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border-2 font-semibold transition-all hover:shadow-md border-brand-600 text-brand-600 hover:bg-brand-50">
    @svg('icon-box', 'w-5 h-5')
    Bestellen
  </a>

  {{-- Navigation links --}}
  <a href="#toepassingen" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
    Toepassingen
  </a>
  <a href="#hoe-het-werkt" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
    Hoe het werkt
  </a>
  <a href="#veiligheid" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
    Veiligheid
  </a>

  {{-- Cart icon with dropdown --}}
  @include('header.cart-dropdown')

  {{-- User icon/dropdown --}}
  @include('header.user-dropdown')
</nav>
