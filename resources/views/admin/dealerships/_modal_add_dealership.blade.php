<div class="modal inmodal" id="modal-add-dealership" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">Agregar Distribuidor</h4>
            </div>
            <form action="{{route('admin.dealerships.store')}}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('admin.dealerships._form_create')
                </div>
                <div class="modal-footer">
                    <a class="btn btn-white" data-dismiss="modal">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>