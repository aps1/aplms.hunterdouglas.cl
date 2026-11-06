<div class="modal inmodal" id="modal-inactivate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <i class="fa fa-archive modal-icon"></i>
                <h4 class="modal-title">Inactivar Distribuidor</h4>
            </div>
            <div class="modal-body">
                <p>
                    <strong>Advertencia: </strong> 
                    @lang('messages.dealerships.confirmar_inactivar')
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.dealerships.inactivate', $dealership) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-danger">Inactivar</button>
                </form>
            </div>
        </div>
    </div>
</div>