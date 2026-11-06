<div class="modal inmodal" id="modal-take-lead" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">Confirmar</h4>
            </div>
            <div class="modal-body">
                <p>@lang('messages.leads.confirmar_tomar')</p>
            </div>
            <div class="modal-footer">
                <a  class="btn btn-white" data-dismiss="modal">Cancelar</a>
                <a :href="take_lead_route" class="btn btn-primary">Confirmar</a>
            </div>
        </div>
    </div>
</div>