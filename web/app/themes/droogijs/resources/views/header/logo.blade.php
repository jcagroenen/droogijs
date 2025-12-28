{{--
  Header Logo Component
  Displays the brand logo with link to home
--}}

<a href="{{ home_url('/') }}" class="block shrink-0">
  @include('partials.logo', ['class' => 'h-12'])
</a>
