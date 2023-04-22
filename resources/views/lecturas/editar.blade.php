@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('lecturaEditar',$lec))

@section('barraLateral')
<div class="breadcrumb justify-content-center">
    <a href="{{ route('vehiculosNuevo') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-car-side mr-1 text-info"></i>
        Nuevo vehículo
    </a>

    <div class="breadcrumb-elements-item dropdown p-0">
        <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
            <i class="fa-solid fa-truck mr-1 text-indigo"></i>
            Tipo de vehículos
        </a>

    </div>
</div> 
@endsection
@section('content')

    <form action="{{ route('lecturaActualizar') }}" method="POST" autocomplete="off">
        @csrf
        <input type="hidden" name="id" value="{{ $lec->id }}">
        <div class="card">
    
            <div class="card-body">
                <div class="form-group">
                    <label for="estado">Estado<i class="text-danger">*</i></label>
                    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                        <option value="{{ $lec->estado }}" {{ old('estado', $lec->estado) == 'DENTRO' ? 'selected' : '' }}>{{ $lec->estado }}</option>
                        <option value="{{ $lec->estado }}" {{ old('estado', $lec->estado) == 'FUERA' ? 'selected' : '' }}>{{ $lec->estado }}</option>
                    </select>

                    @error('estado')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <input id="descripcion" type="text" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" value="{{ old('descripcion', $lec->descripcion) }}" >

                    @error('descripcion')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </form>
@push('scripts')
    
@endpush
@endsection
