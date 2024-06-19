@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('direcciones-departamentos.create'))
@section('content')

<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('direcciones-departamentos.guardar') }}" method="POST" autocomplete="off">
            @csrf
            <div class="card">
                
                <div class="card-body">
                    
                    @if ($departamentos->count()>0)
                        <div class="form-group">
                            <label for="departamento_id">Seleccione departamento<i class="text-danger">*</i></label>
                            <select name="departamento_id" id="departamento_id" class="form-control @error('departamento_id') is-invalid @enderror" required>
                                <option value="">--Seleccione--</option>
                                @foreach ($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}" {{ old('departamento_id')==$departamento->id?'selected':'' }}>{{ $departamento->nombre }}</option>
                                @endforeach
                            </select>
                            @error('departamento_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @else
                        @include('layouts.alert',['msg'=>'No existe departamentos.','type'=>'info'])
                    @endif


                    <div class="form-group">
                        <label for="nombre">Nombre<i class="text-danger">*</i></label>
                          
                        <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required autofocus>
        
                        @error('nombre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>  
                      
                </div>
                <div class="card-footer bg-white d-sm-flex justify-content-sm-between align-items-sm-center py-sm-2">
                    <button type="submit" class="btn btn-primary mt-3 mt-sm-0 w-100 w-sm-auto">Guardar</button>
                </div>
            </div>
        </form>
    </div>



</div>


@endsection
