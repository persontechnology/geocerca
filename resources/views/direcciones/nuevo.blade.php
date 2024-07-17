@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('direcciones.create'))
@section('content')

<form action="{{ route('direcciones.store') }}" class="mx-1" autocomplete="off" method="POST">
    <div class="card">
    
        <div class="card-body">
            
                @csrf

                <label for="nombre">Nombre de dirección:</label>

                <div class="input-group">
                    <input id="nombre" placeholder="Ingresar nuevo dirección" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required>
                    
                </div>
                @error('nombre')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                
            
        </div>
        <div class="card-footer ">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('direcciones.index') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </div>
</form>



@endsection
