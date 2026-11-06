<div class="modal inmodal" id="modal-derive" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">@lang('Derivar Prospecto') {{$lead->name}}</h4>
            </div>
            <form action="{{route('admin.leads.derive', $lead)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row" id="derive-selects">
                        {{-- REGION --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="geo_loc1">{{__('Región')}}</label>
                                <select id="regiones" class="form-control" name="geo_loc1">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        {{-- COMMUNE --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="commune">{{__('Comuna')}}</label>
                                <select id="comunas" class="form-control"  name="geo_loc2">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        {{-- Dealership --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="dealership_id">
                                    Distribuidor * 
                                    <span class="label label-warning float-right small m-l" id="dealer-count"></span>
                                </label>
                                <select data-placeholder="Seleccione Distribuidor" id="dealership" name="dealership_id" class="chosen-select">
                                    <option value=""></option>
                                </select>    
                            </div>
                        </div>
                        {{-- Observation --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="observation">Observación</label>
                                <textarea class="form-control" rows="5" name="observation"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="url_previus" value="{{$url_previus}}">
                <div class="modal-footer">
                    <a class="btn btn-white" data-dismiss="modal">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
