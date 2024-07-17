@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('direcciones.index'))


@section('barraLateral')
<div class="breadcrumb justify-content-center">
    <a href="{{ route('direcciones.create') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-location-dot mr-1 text-info"></i>
        Nuevo dirección
    </a>
</div> 
@endsection

@section('content')


<div class="card card-body">
    <div class="table-responsive">
        {{$dataTable->table()}}
    </div>
</div>
@push('scripts')
    {{$dataTable->scripts()}}
@endpush
@endsection
