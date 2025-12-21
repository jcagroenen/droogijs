{{--
  Template Name: Inspiratie
--}}

@extends('layouts.app')

@section('content')
  {{-- Header --}}
  <div class="pt-32 pb-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-brand-50 to-brand-100">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-brand-600 flex items-center justify-center">
          @svg('icon-sparkles', 'w-6 h-6 text-white')
        </div>
        <div>
          <p class="text-sm font-semibold text-brand-600 uppercase tracking-wide">
            Blog
          </p>
          <h1 class="text-4xl md:text-5xl font-bold text-gray-900">
            {{ $title }}
          </h1>
        </div>
      </div>
      <p class="text-xl text-gray-600 max-w-3xl">
        {{ $subtitle }}
      </p>
    </div>
  </div>

  {{-- Blog Grid --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-auto">
      @foreach($posts as $post)
        @include('partials.blog-card', ['post' => $post])
      @endforeach
    </div>

    @if($posts->isEmpty())
      <div class="text-center py-16">
        <p class="text-gray-500 text-lg">Nog geen inspiratie artikelen gevonden.</p>
      </div>
    @endif
  </div>
@endsection
