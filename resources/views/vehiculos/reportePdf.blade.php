@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('vehiculosReportePdf'))

@section('barraLateral')

@endsection
@section('content')

    @livewire('vehiculos.reposrte-pdf')
    
@push('scripts')
  
@endpush
@endsection
