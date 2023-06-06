@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('ingresoCombustible.index'))

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
