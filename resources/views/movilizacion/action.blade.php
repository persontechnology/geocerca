
<div class="list-icons">
    <div class="dropdown">
        <a href="#" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-left">
            <a href="{{ route('odernMovilizacionPdf',$om->id) }}" target="_blank" class="dropdown-item"><i class="fa-solid fa-file-pdf"></i> PDF</a>
            <a href="{{ route('odernMovilizacionLecturas',$om->id) }}" class="dropdown-item"><i class="fa-solid fa-file-pdf text-warning"></i> Lecturas</a>
            <div class="dropdown-divider"></div>
            <a href="{{ route('odernMovilizacionEditar',$om->id) }}" class="dropdown-item"><i class="fa-solid fa-pen-to-square text-primary"></i> Editar</a>
            <a href="#" onclick="event.preventDefault();eliminar(this);" data-id="{{ $om->id }}" data-url="{{ route('odernMovilizacionEliminar',$om->id) }}" data-msg="Está seguro de eliminar Orden de movilización {{ $om->numero }}!" class="dropdown-item"><i class="fa-solid fa-trash text-danger"></i> Eliminar</a>
            
        </div>
    </div>
    
</div>