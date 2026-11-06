<div class="modal inmodal" id="modal-edit-priority" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">Cambiar interés</h4>
            </div>
            <form action="{{route('leads.priority', $lead)}}" method="POST">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="row">
                        {{-- PRICE --}}
                        <div class="col-md">
                            <div class="form-group">
                                <label class="form-label" for="priority">Interés</label>
                                <select name="priority" class="form-control">
                                @foreach ($priority_types as $key => $type)
                                    @if ($key != $lead->priority)
                                        <option value="{{$key}}">
                                            {{$type}}
                                        </option>   
                                    @endif
                                @endforeach
                                </select>
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