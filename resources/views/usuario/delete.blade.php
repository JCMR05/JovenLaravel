<div class="modal" id="modal-eliminar-{{  $reg->id }}" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <form action="{{ route('usuarios.destroy', $reg->id) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h4>Eliminar registro</h4>
                </div>

                <div class="modal-body">
                    ¿Usted desea eliminar el registro de {{$reg->name}} ?
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-light">Eliminar</button>
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </form>
        </div>
    </div>
</div>
