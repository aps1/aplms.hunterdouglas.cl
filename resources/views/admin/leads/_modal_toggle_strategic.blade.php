<div class="modal inmodal" id="modal-toggle-strategic" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">{{__('Prospecto estratégico')}}</h4>
            </div>
            <form action="{{route('leads.priority', $lead)}}" method="POST">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="row">
                        @if ($lead->isStrategic())
                            {{__('messages.leads.confirmar_no_estrategico')}}
                            <input type="hidden" name="priority" value="">
                        @else
                            {{__('messages.leads.confirmar_estrategico')}}
                            <input type="hidden" name="priority" value="4">
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-white" data-dismiss="modal">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>