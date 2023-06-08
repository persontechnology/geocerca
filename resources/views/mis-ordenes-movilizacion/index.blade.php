@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('odernMovilizacionReportePdf'))

@section('barraLateral')

@endsection
@section('content')

    @livewire('mis-ordenes-movilizacion.index')
    
@push('scripts')
  
@endpush
@endsection
