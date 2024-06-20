@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('odernMovilizacionReportePdf'))

@section('barraLateral')

@endsection
@section('content')

    @livewire('orden-movilizacion.listado')
    

@endsection


