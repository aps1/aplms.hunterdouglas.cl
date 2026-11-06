<div class="modal inmodal" id="modal-delete-contact" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">{{__('Eliminar el contacto')}}</h4>
            </div>
            <div class="modal-body">
                <p>
                    <strong>Advertencia: </strong> 
                    {{__('messages.contacts.confirmar_eliminar')}} 
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('contacts.destroy', $contact) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>