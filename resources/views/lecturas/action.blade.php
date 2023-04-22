
<div class="list-icons">
    <div class="dropdown">
        <a href="#" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-left">
            <div class="dropdown-divider"></div>
            <a href="{{ route('lecturaEditar',$lec->id) }}" class="dropdown-item"><i class="fa-solid fa-pen-to-square text-primary"></i> Editar</a>
            <a href="#" onclick="event.preventDefault();eliminar(this);" data-id="{{ $lec->id }}" data-url="{{ route('lecturaEliminar',$lec->id) }}" data-msg="Está seguro de eliminar lectura de {{ $lec->estado }}!" class="dropdown-item"><i class="fa-solid fa-trash text-danger"></i> Eliminar</a>
        </div>
    </div>
    
</div>