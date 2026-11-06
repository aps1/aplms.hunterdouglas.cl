<div class="modal inmodal" id="modal-add-kam" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">Agregar Kam</h4>
            </div>
            <form action="{{route('admin.kams.store')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        {{-- NAME --}}
                        <div class="col-md">
                            <div class="form-group">
                                <label class="form-label" for="name">Nombre y apellido *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        {{-- EMAIL --}}
                        <div class="col-md">
                            <div class="form-group">
                                <label class="form-label" for="email">Email *</label>
                                <input type="email" name="email" class="form-control" required>
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
                                        title="Por lo menos 9 dígitos sin espacios"
                                    >
                                </div>
                            </div>
                        </div>
                        {{-- CEL Whatsapp --}}
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
                                        title="Por lo menos 9 dígitos sin espacios"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- PASSWORD --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="password">Contraseña *</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    @include('layouts._required')
                </div>
                <div class="modal-footer">
                    <a class="btn btn-white" data-dismiss="modal">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>