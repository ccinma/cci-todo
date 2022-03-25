@extends('layouts.app')

@section('main-container')
  <main id='main'>
      @yield("container")

      @include("layouts.components.mobile_nav")
  </main>
@endsection