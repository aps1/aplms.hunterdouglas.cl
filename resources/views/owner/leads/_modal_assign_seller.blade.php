<div class="modal inmodal" id="modal-assign-seller" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">Asignar vendedor(a)</h4>
            </div>
            <form action="{{route('owner.leads.update', $lead)}}" method="POST">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="row">
                        {{-- User id --}}
                        <div class="col-md">
                            <div class="form-group">
                                <label class="form-label" for="price">Vendedor(a)</label>
                                <select class="form-control" name="user_id">
                                    <option value="">
                                        Sin vendedor(a)
                                    </option>
                                    @foreach ($sellers as $seller)
                                    <option value="{{$seller->id}}">
                                        {{$seller->name}}
                                    </option>
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