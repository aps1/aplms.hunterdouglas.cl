<div class="modal inmodal" id="modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                    </button>
                    <h4 class="modal-title">@lang('Eliminar el prospecto')</h4>
                </div>
                <div class="modal-body">
                    <p>
                        <strong>Advertencia: </strong> 
                        @lang('messages.leads.confirmar_eliminar') 
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.leads.destroy', $lead) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>