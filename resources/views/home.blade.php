@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('home'))
@section('content')
<!-- Content area -->
<div class="content d-flex justify-content-center align-items-center">

  <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
      <img src="{{ asset('img/logo-total.svg') }}" height="350px" class="h-48px" alt="">
  </div>

</div>
<!-- /content area -->
@endsection