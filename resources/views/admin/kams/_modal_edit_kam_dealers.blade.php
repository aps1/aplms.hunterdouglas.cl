<div class="modal inmodal" id="modal-edit-kam-dealers" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">Editar los distribuidores de @{{kam_to_edit.name}}</h4>
            </div>
            <form action="{{ route('admin.kams.dealerships', $kam) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        {{-- dealerships --}}
                        <div class="col-md">
                            <select class="form-control dual_select" name="dealerships[]" multiple>
                                @foreach ($dealerships as $dealer)
                                    @if (!$dealer->kam_id)
                                        <option class="text-danger" value="{{ $dealer->id }}" {{ $dealer->kam_id == $kam->id ? 'selected' : '' }}>
                                            {{ $dealer->name }}
                                        </option>
                                    @else {{-- Tiene kam--}}
                                        <option value="{{ $dealer->id }}" {{ $dealer->kam_id == $kam->id ? 'selected' : '' }}>
                                            
                                            {{ $dealer->name }}
                                                {{-- es un kam distinto al que se esta revisando--}}
                                                @if ($dealer->kam_id != $kam->id)
                                                    (Kam actual: {{ $dealer->kam->name }})
                                                @endif
                                        </option>
                                    @endif
                                @endforeach
                            </select>
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