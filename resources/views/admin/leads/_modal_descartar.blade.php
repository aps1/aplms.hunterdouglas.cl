<div class="modal inmodal" id="modal-descartar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">@lang('Descartar Prospecto') {{$lead->name}}</h4>
            </div>
            <form action="{{route('leads.discard', $lead)}}" method="POST">
                @method('PUT')
                @csrf
                <div class="modal-body">
                    <div class="row">
                        {{-- Observation --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="observation">Motivo</label>
                                <select class="form-control" name="reason" id="reason">
                                    @foreach ($discard_reasons as $key => $reason)
                                        <option value="{{$key}}">
                                            {{$reason}}
                                        </option>    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- Observation --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="observation">Observación</label>
                                <textarea class="form-control" rows="5" name="observation">@lang('messages.leads.descartar')</textarea>
                            </div>
                        </div>
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