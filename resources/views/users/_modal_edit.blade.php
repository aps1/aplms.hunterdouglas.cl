<div class="modal inmodal" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">Editar datos de {{$user->name}}</h4>
            </div>
            <form action="{{route('users.update', $user)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        {{-- NAME --}}
                        <div class="col-md">
                            <div class="form-group">
                                <label class="form-label" for="name">Nombre y apellido *</label>
                                <input type="text" name="name" value="{{$user->name}}" class="form-control" required>
                            </div>
                        </div>
                        {{-- EMAIL --}}
                        <div class="col-md">
                            <div class="form-group">
                                <label class="form-label" for="email">Email *</label>
                                <input type="email" name="email" value="{{$user->email}}" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- CEL --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="cel">Celular</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon">{{__('phoneCode')}}</span>
                                    </div>
                                    <input 
                                        type="tel" 
                                        name="cel" 
                                        class="form-control" 
                                        pattern="[0-9]{9,}"
                                        value="{{$user->cel_to_edit}}"
                                        title="Por lo menos 9 dígitos sin espacios"
                                    >
                                </div>
                            </div>
                        </div>
                        {{-- CEL --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="cel">Cel Whatsapp</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon">{{__('phoneCode')}}</span>
                                    </div>
                                    <input 
                                        type="tel" 
                                        name="cel_whatsapp" 
                                        class="form-control" 
                                        pattern="[0-9]{9,}"
                                        value="{{$user->cel_whatsapp_to_edit}}"
                                        title="Por lo menos 9 dígitos sin espacios"
                                    >
                                </div>
                            </div>
                        </div>
                        {{-- TYPE --}}
                        <div class="col-md-6">
                            <label class="form-label" for="brand">Rol</label>
                            <select class="form-control" name="type">
                                @foreach ($user_types as $key => $type)
                                    <option value="{{$key}}" {{ $user->type == $key ? 'selected' : '' }}>
                                        {{$type}}
                                    </option>
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