@extends('layouts.app')

@section('content')
  @include('partials.hero', ['data' => $hero])
  @include('partials.features', ['data' => $features])
  @include('partials.use-cases', ['data' => $useCases])
  @include('partials.cta', ['data' => $cta])
@endsection
