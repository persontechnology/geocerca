@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('vehiculosReportePdf'))

@section('barraLateral')

@endsection
@section('content')

    @livewire('vehiculos.reporte-pdf')
    
@push('scripts')
  
@endpush
@endsection

