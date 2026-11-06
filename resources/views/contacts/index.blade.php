@extends('layouts.app')
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-md-9">
            <h2>@lang('Lista de Contactos')</h2>
        </div>
        <div class="col-md-3">
            <div class="title-action">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-contact">
                    @lang('Agregar Contacto')
                </a>
            </div>
        </div>
    </div>
@endsection
@section('content')
<div id="app">
    {{-- Loading --}}
    @include('layouts._loading')
    <div id="contenido" style="visibility: hidden">
        {{-- FILTROS --}}
        <div class="row justify-content-end mb-3">
            @if (Auth::user()->isAdmin())
                <div class="col-md-2">
                    <select data-placeholder="Todos los KAM" id="kam-filter" class="chosen-select">
                        <option value="">Todos los KAM</option>
                        @foreach ($kams as $kam)
                            <option value="{{$kam->id}}">{{$kam->name}}</option>
                        @endforeach
                    </select>
                </div>  
            @endif
            @if (Auth::user()->isAdminLevel())
                <div class="col-md-2">
                    <select data-placeholder="Todos los distribuidores" id="dealership-filter" class="chosen-select">
                        <option value="">Todos los distribuidores</option>
                        @foreach ($dealerships as $dealership)
                            <option value="{{$dealership->id}}">{{$dealership->name}}</option>
                        @endforeach
                    </select>
                </div>    
            @endif
            <div class="col-md-2">
                <select data-placeholder="Todos los tipos" id="type-filter" class="chosen-select">
                    <option value="">Todos los tipos</option>
                    @foreach ($contact_types as $index => $value) 
                        <option value="{{$index}}">{{$value}}</option>    
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select data-placeholder="Todos las fuentes" id="source-filter" class="chosen-select">
                    <option value="">Todos las fuentes</option>
                    @foreach ($contact_sources as $index => $value) 
                        <option value="{{$index}}">{{$value}}</option>    
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-auto">
                <div id="reportrange" class="form-control">
                    <i class="fa fa-calendar"></i>
                    <span><b class="caret"></b></span>
                </div>
            </div>
            <input type="hidden" id="range_from" name="range_from">
            <input type="hidden" id="range_to" name="range_to"> 
           
            <div class="col-md-auto text-right">
                <a href="{{route('contacts')}}" class="btn btn-default" title="Limpiar filtros"><i class="fa fa-rotate-left"></i></a>     
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        @include('contacts._datatable')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    @include('contacts._modal_add_contact')
</div>
@endsection

@push('scripts')

<script src="{{asset('js/geo/'.session('localization').'.js')}}"></script>
<script>
    var dealership = "{{$selected_dealership}}";
    var kam = "{{$selected_kam}}";
    var type = "{{$selected_type}}";
    var source = "{{$selected_source}}";
    var range_from = "{{$from}}";
    var range_to = "{{$to}}";
    var datatable_route = "{{route('datatables.contacts')}}" + 
        "?from=" + range_from + 
        "&to=" + range_to + 
        "&dealership=" + dealership + 
        "&kam=" + kam + 
        "&type=" + type + 
        "&source=" + source; 
    datatables_options.processing = true;
    datatables_options.serverSide = true;
    datatables_options.ajax = datatable_route;
    datatables_options.order = [[ 7, "desc" ]];
    datatables_options.columns = [
        {data: 'name', name: 'name'},
        {data: 'email', name: 'email'},
        {data: 'dealership', name: 'dealership'},
        {data: 'type_description', name: 'type_description'},
        {data: 'source_description', name: 'source_description'},
        {data: 'geo_loc1', name: 'geo_loc1'},
        {data: 'geo_loc2', name: 'geo_loc2'},
        {data: 'created_at', name: 'created_at'},
        {data: 'action', name: 'action'}
    ];
    datatables_options.buttons = [
        {
            extend: 'excel', 
            title: 'excel',
            text: '<i class="fa fa-file-excel-o"></i> Excel',
            action: function() {
                exportar();
            }
        }
    ];

    $(document).ready(function() {
        //LOading
        $('#contenido').css({visibility: "visible"});
        $('.sk-loading').fadeOut(50);

        $('#modal-add-contact').on('shown.bs.modal', function () {
            $('#dealership', this).chosen();
            $('#regiones', this).chosen();
            $('#comunas', this).chosen();
        });
        //Filtros
        $("#kam-filter" ).val("{{$selected_kam}}");
        $("#dealership-filter" ).val("{{$selected_dealership}}");
        $("#type-filter" ).val("{{$selected_type}}");
        $("#source-filter" ).val("{{$selected_source}}");
        $('#range_from').val("{{$from}}");
        $('#range_to').val("{{$to}}");
        
        $('#kam-filter', this).chosen().change( (e) => {
            $("#dealership-filter" ).val("");
            filtrar();
        });
        $('#dealership-filter', this).chosen().change( (e) => {
            filtrar();
        });
        $('#type-filter', this).chosen().change( (e) => {
            filtrar();
        });
        $('#source-filter', this).chosen().change( (e) => {
            filtrar();
        });
        $('#reportrange span').html("{{$from}}" + ' / ' + "{{$to}}");
        report_range_options.startDate = moment("{{$from}}", "DD-MM-YYYY");
        report_range_options.endDate = moment("{{$to}}", "DD-MM-YYYY");
        
        $('#reportrange').daterangepicker(report_range_options, function(start, end, label) {
            $('#range_from').val(start.format('DD-MM-YYYY'));
            $('#range_to').val(end.format('DD-MM-YYYY'));
            $('#reportrange span').html(start.format('DD-MM-YYYY') + ' al ' + end.format('DD-MM-YYYY'));
            filtrar();
        });       
        
        $('.datatable').DataTable(datatables_options);

        $('#filtrar').click(function( event ) {
            event.preventDefault();
            filtrar();            
        });

        //todos las regiones por defecto
        setRegiones();
        $('#regiones').change(function(event) {
            let selected = event.target.value;
            //selecciono todas las regiones
            if (selected == '') {
                setComunas();
                return;
            }
            let filterGeo1 = geo.filter( (geoItem) => {
                return geoItem.geo1 == selected;
            });
            let filterGeo2 = filterGeo1[0].geo2.map( (geo2) => {
                return geo2.nombre;
            });
            setComunas(filterGeo2);
            
            $('#comunas').html('<option value=""></option>');
                $.each(filterGeo2, function(i, filter_comuna) {
                $('#comunas').append('<option value="' + filter_comuna + '">' + filter_comuna + '</option>');
            });
            $("#comunas").trigger("chosen:updated");
        });
    });

    //Setea el selecto de regiones
    function setRegiones() {
        $('#regiones').html('<option value=""></option>');
        $.each(geo, function(i, geoitem) {
            $('#regiones').append('<option value="' + geoitem.geo1 + '">' + geoitem.geo1 + '</option>');
        }); 
        $("#regiones").trigger("chosen:updated");
    }

    //Setea el selecto de comunas
    function setComunas(filter_comunas = null) {
        $('#comunas').html('<option value=""></option>');
            $.each(filter_comunas, function(i, filter_comuna) {
            $('#comunas').append('<option value="' + filter_comuna + '">' + filter_comuna + '</option>');
        });
        $("#comunas").trigger("chosen:updated");
    }

    function filtrar() {
        let filters_for_link = getFiltersForLink(); 
        var link = "{{route('contacts')}}" + filters_for_link;
        window.location.replace(link);
        // console.log(link);
    }

    function exportar() {
        let filters_for_link = getFiltersForLink(); 
        var link = "{{route('contacts.export')}}" + filters_for_link;
        window.location.replace(link);
        // console.log(link);
    }

    function getFiltersForLink(){
        let dealership = $('#dealership-filter').val() == undefined ? '' : $('#dealership-filter').val();
        let kam = $('#kam-filter').val() == undefined ? '' : $('#kam-filter').val();
        let type = $('#type-filter').val();
        let source = $('#source-filter').val();
        let range_from = $('#range_from').val();
        let range_to = $('#range_to').val();
        return "?dealership=" + dealership + 
            "&kam=" + kam + 
            "&from=" + range_from + 
            "&to=" + range_to +
            "&type=" + type + 
            "&source=" + source;
    }
</script>
@endpush
