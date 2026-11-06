@extends('layouts.app')
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-md-9">
            <h2>@lang('Lista de Prospectos')</h2>
        </div>
        <div class="col-md-3">
            <div class="title-action">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-lead">
                    @lang('Agregar Prospecto')
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
        @include('admin.leads._filters') 
        <div class="row">
            <div class="col-lg-12">
                @if($leads_count)
                <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li>
                            <a :class="(selected_status == '') ? ' active nav-link' : 'nav-link'" href="#" v-on:click="setSelectedStatus('')">Todas</a>
                        </li>
                        <li >
                            <a :class="(selected_status == '1') ? ' active nav-link' : 'nav-link'" href="#" v-on:click="setSelectedStatus(1)">
                                Creado
                                <span class="label label-warning" style="font-size: 14px;">@{{leads_count_for_status.Creado}}</span>
                            </a>
                        </li>
                        <!--
                        <li >
                            <a :class="(selected_status == '12') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('12')">
                                Por rederivar
                                <span class="label label-warning" style="font-size: 14px;">@{{leads_count_for_status.Rederivar}}</span>
                            </a>
                        </li>-->
                        <li >
                            <a :class="(selected_status == '2') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('2')">
                            Derivado
                                <span class="label label-teal" style="font-size: 14px;">@{{leads_count_for_status.Derivado}}</span>
                            </a>
                        </li>
                        <!--
                        <li >
                            <a :class="(selected_status == '3') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('3')">
                                Por contactar
                                <span class="label label-purple" style="font-size: 14px;">@{{leads_count_for_status.Asignado}}</span>
                            </a>
                        </li>  -->                          
                        <li >
                            <a :class="(selected_status == '4') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('4')">
                                Por cotizar
                                <span class="label label-indigo" style="font-size: 14px;">@{{leads_count_for_status.Contactado}}</span>
                            </a>
                        </li>                            
                        <li >
                            <a :class="(selected_status == '5') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('5')">
                                Por vender
                                <span class="label label-green" style="font-size: 14px;">@{{leads_count_for_status.Cotizado}}</span>
                            </a>
                        </li>
                      
                        <li >
                            <a :class="(selected_status == '7') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('7')">
                                <span title="Vendido sin folios">Vendido</span>
                                <span class="label label-success" style="font-size: 14px;">@{{leads_count_for_status.Vendido}}</span>
                            </a>
                        </li>
                        <!--
                        <li >
                            <a :class="(selected_status == '11') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('11')">
                                <span title="Vendido con folios">Vendido C/F</span>
                                <span class="label label-cyan" style="font-size: 14px;">@{{leads_count_for_status.Foliado}}</span>
                            </a>
                        </li>-->
                        <li >
                            <a :class="(selected_status == '6') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('6')">
                                No vendido
                                <span class="label label-danger" style="font-size: 14px;">@{{leads_count_for_status.Perdido}}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" role="tabpanel" >
                            <div class="panel-body">   
                                @include('admin.leads._datatable')            
                            </div>
                        </div>
                    </div>    
                </div>
                @else
                <div class="wrapper wrapper-content">
                    <div class="middle-box text-center animated fadeInUp">
                        <h3 class="font-bold">@lang('messages.leads.no_registrado')</h3>
                        <div class="error-desc">
                            Trata de limpiar los filtros para ampliar el alcance 
                            <br/>
                            <a href="{{route('admin.leads')}}" class="btn btn-primary m-t">
                                Limpiar filtros
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div>
    @include('admin.leads._modal_add_lead')
</div>
@endsection

@push('scripts')

<script src="{{asset('js/geo/'.session('localization').'.js')}}"></script>
<script src="{{ asset('js/geo/dinamic_geo.js') }}"></script>
<script>
    var dealership_id = "{{$dealership_id}}";
    var kam_id = "{{$kam_id}}";
    var origin = "{{$origin}}";
    var product_category = "{{$product_category}}";
    var status = "{{$status}}";
    var range_from = "{{$from}}";
    var range_to = "{{$to}}";
    var datatable_route = "{{route('datatables.leads')}}" + 
        "?from=" + range_from + 
        "&to=" + range_to + 
        "&origin=" + origin + 
        "&product_category=" + product_category + 
        "&status=" + status + 
        "&kam_id=" + kam_id + 
        "&dealership_id=" + dealership_id;
    datatables_options.processing = true;
    datatables_options.serverSide = true;
    datatables_options.ajax = datatable_route;
    datatables_options.order = [[ 9, "desc" ]];
    datatables_options.columns = [
        {data: 'id', name: 'id'},
        {data: 'name', name: 'name'},
        {data: 'email', name: 'email'},
        {data: 'dealership_name', name: 'dealership_name'},
        {data: 'origin_description', name: 'origin_description'},
        {data: 'geo_loc1', name: 'geo_loc1'},
        {data: 'geo_loc2', name: 'geo_loc2'},
        {data: 'status_description', name: 'status_description'},
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
        
        //Filtros
        $('#range_from').val("{{$from}}");
        $('#range_to').val("{{$to}}");
        $("#origin" ).val("{{$origin}}");
        $("#product_category" ).val("{{$product_category}}");
        $("#status" ).val("{{$status}}");
        $("#kam_id" ).val("{{$kam_id}}");
        $("#dealership_id" ).val("{{$dealership_id}}");
         
        $('#kam_id').chosen({
            placeholder: 'KAM'
        }).change( (e) => {
            //para que no trate de seleccionar uno de otro kam
            $("#dealership_id" ).val("");
            filtrar();
        });
        $('#dealership_id').chosen({
            placeholder: 'Distribuidores'
        }).change( (e) => {
            filtrar();
        });
        $('#origin').chosen({
            placeholder: 'Origen'
        }).change( (e) => {
            filtrar();
        });
        $('#product_category').chosen({
            placeholder: 'Categoría'
        }).change( (e) => {
            filtrar();
        });
        $('#status').chosen({
            placeholder: 'Status'
        }).change( (e) => {
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
    });

    function exportar() {
        let filters_for_link = getFiltersForLink(); 
        var link = "{{route('leads.export')}}" + filters_for_link;
        window.location.replace(link);
        // console.log(link);
    }

    function filtrar() {
        let filters_for_link = getFiltersForLink(); 
        var link = "{{route('admin.leads')}}" + filters_for_link
        window.location.replace(link);
        // console.log(link);
    }

    function getFiltersForLink(){
        let dealership_id = $( "#dealership_id" ).val() == undefined ? '' : $('#dealership_id').val();;
        let kam_id = $( "#kam_id" ).val() == undefined ? '' : $( "#kam_id" ).val();
        let origin = $( "#origin" ).val();
        let product_category = $( "#product_category" ).val();
        let status = $( "#status" ).val();
        let range_from = $('#range_from').val();
        let range_to = $('#range_to').val();
        
        return "?from=" + range_from + 
            "&to=" + range_to + 
            "&origin=" + origin + 
            "&product_category=" + product_category + 
            "&status=" + status + 
            "&kam_id=" + kam_id + 
            "&dealership_id=" + dealership_id;
    }

    var app = new Vue({
        el: '#app',
        data: {
            origins: @json($origins),
            leads_count_for_status: @json($leads_count_for_status),
            source: '',
            selected_status: @json($status) ? @json($status) : '',
        },
        computed: {
        },
        methods: {
            setSelectedStatus: setSelectedStatus,
            filtrar: filtrar,
        }
    });
    
    function setSelectedStatus(status){
        this.selected_status = status;
        $( "#status" ).val(status)
        filtrar();
    }

</script>
@endpush
