<div class="modal inmodal" id="modal-folios" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title">Editar Folios</h4>
            </div>
            <form action="{{route('leads.folios.update', $lead)}}" method="POST">
                @csrf
                @method('put')
                <div class="modal-body">
                    {{--Folios--}}
                    <label class="form-label" for="brand">
                        Folios 
                    </label>
                    <div class="row form-group" style="margin-bottom: 0;">
                        <div class="col-md-4" v-for="folio in folios">
                            <input type="text" class="form-control" style="margin-bottom: 1rem;" :value="folio.number" name="folios[]">
                        </div>
                        <div class="col-md-4" v-for="n in folios_extras">
                            <input type="text" class="form-control" style="margin-bottom: 1rem; "name="folios[]">
                        </div>
                    </div>
                    <div class="row form-group" v-for="n in extra_folios_rows">
                        <div class="col-md-4" v-for="n in 3">
                            <input type="text" class="form-control" name="folios[]">
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
                <div class="modal-footer">
                    <a class="btn btn-white" data-dismiss="modal">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>