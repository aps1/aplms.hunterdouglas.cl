<div class="modal inmodal" id="modal-add-activity" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">Registrar actividad</h4>
            </div>
            <form action="{{route('leads.activities.store', $lead)}}" class="prevent-double-submit" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        {{-- Activities --}}
                        <div class="col-md">
                            <div class="form-group">
                                <label class="form-label" for="brand">Tipo de actividad *</label>
                                <select name="type" id="select-activity" class="form-control">
                                    @foreach ($activities_types as $key => $type)
                                        <option value="{{$key}}">
                                            {{$type}}
                                        </option>    
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="net_value_input" style="display: none">
                                <label class="form-label" for="brand">
                                    Valor neto cotizado
                                    <span class="fa fa-question-circle pull-right" title="Valor sin IVA. Solo números enteros sin puntos o comas"></span>
                                </label>
                                <input type="number" class="form-control" name="net_value">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- OBSERVATION --}}
                        <div class="col-md">
                            <div class="form-group">
                                <label class="form-label" for="observation">Observación *</label>
                                <textarea class="form-control" rows="5" name="observation" required></textarea>
                            </div>
                        </div>
                    </div>
                    {{--Folios--}}
                    <div id="folios_inputs" style="display:none">
                        <label class="form-label" for="brand">
                            {{__('Folios')}} <small><em>({{__("folio_formato")}})</em></small>
                        </label>
                        <div class="row form-group" style="margin-bottom: 0;">
                            <div class="col-md-4" v-for="folio in folios">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    style="margin-bottom: 1rem;" 
                                    :value="folio.number" 
                                    name="folios[]" 
                                    pattern="{{__("folio_regex")}}"
                                    title="{{__("folio_formato")}}"
                                >
                            </div>
                            <div class="col-md-4" v-for="n in folios_extras">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    style="margin-bottom: 1rem;"
                                    name="folios[]" 
                                    pattern="{{__("folio_regex")}}"
                                    title="{{__("folio_formato")}}"
                                >
                            </div>
                        </div>
                        <div class="row form-group" v-for="n in extra_folios_rows">
                            <div class="col-md-4" v-for="n in 3">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="folios[]" 
                                    pattern="{{__("folio_regex")}}" 
                                    title="{{__("folio_formato")}}"
                                >
                            </div>
                        </div>
                        <div class="row m-b">
                            <div class="col-md">
                                <a href="#" title="Más campos de folios" v-on:click="extra_folios_rows += 1">
                                    <span class="label label-default">
                                        +
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- FILES --}}
                    <div class="row">
                        <div class="col" style="margin-bottom: 0.5rem;">Archivos adjuntos <small><em>(max 5MB)</em></small></div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="custom-file">
                                <input id="files" name="files[]" type="file" class="custom-file-input" multiple>
                                <label for="files" class="custom-file-label">Selecciona un archivo...</label>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col"><small></small></div>
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