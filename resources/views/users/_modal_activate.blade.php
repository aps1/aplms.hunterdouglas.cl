<div class="modal inmodal" id="modal-activate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">Activar {{$user->name}}</h4>
            </div>
            <div class="modal-body">
                <p>
                    <strong>Advertencia: </strong> 
                    Una vez activado, {{$user->name}} podrá iniciar sesión de nuevo en la aplicación, 
                    y se le podrán asignar recursos.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('users.activate', $user) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary">Activar</button>
                </form>
            </div>
        </div>
    </div>
</div>