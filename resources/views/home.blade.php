@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('home'))
@section('content')
    <div class="card">
      <div class="card-header">
        {{ config('app.name') }}
      </div>
      <div class="card-body">
        SISTEMA DE PARQUEO VEHICULAR
      </div>
      <div class="card-footer text-muted">
        {{ date('Y') }}
      </div>
    </div>
@endsection