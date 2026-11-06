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
        <div class="row justify-content-end mb-3">
            @if (Auth::user()->isExecutiveLevel())
                <div class="col-md-2">
                    <select data-placeholder="Todos los origines" id="seller" class="chosen-select">
                        <option value="">Todos los vendedores</option>
                        @foreach ($sellers as $seller)
                            <option value="{{$seller->id}}">{{$seller->name}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-md-2">
                <select data-placeholder="Todos los origines" id="origin" class="chosen-select">
                    <option value="">Todos los origenes</option>
                    @foreach ($origins as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select data-placeholder="Todas las categorías" id="product_category" class="chosen-select">
                    <option value="">Todas las categorias de productos</option>
                    @foreach ($product_categories as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select data-placeholder="Todos los estatus" id="status" class="chosen-select">
                    <option value="">Todos los Estatus</option>
                    @foreach ($states as $key => $value)
                    <option value="{{$key}}">{{$value}}</option>    
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
                <a href="{{route('seller.leads')}}" class="btn btn-default" title="Limpiar filtros"><i class="fa fa-rotate-left"></i></a>     
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        {{-- Por asignar --}}
                        <li>
                            <a :class="(status_todos) ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('')">Todas</a>
                        </li>
                        <li >
                            <a :class="(selected_status == '2') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('2')">
                                Por asignar
                                <span class="label label-teal" style="font-size: 14px;">@{{leads_count_for_status.Derivado}}</span>
                            </a>
                        </li> 
                        <li >
                            <a :class="(selected_status == '3') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('3')">
                                Por contactar
                                <span class="label label-purple" style="font-size: 14px;">@{{leads_count_for_status.Asignado}}</span>
                            </a>
                        </li>                            
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
                                Vendido sin folios
                                <span class="label label-success" style="font-size: 14px;">@{{leads_count_for_status.Vendido}}</span>
                            </a>
                        </li>
                        <li >
                            <a :class="(selected_status == '11') ? ' active nav-link' : 'nav-link'" href="#" v-on:click.prevent="setSelectedStatus('11')">
                                Vendido con folios
                                <span class="label label-cyan" style="font-size: 14px;">@{{leads_count_for_status.Foliado}}</span>
                            </a>
                        </li>
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
                                @if($leads->count())
                                    @include('seller.leads._table')            
                                @else
                                <div class="wrapper wrapper-content">
                                    <div class="text-center animated fadeInUp">
                                        <h3 class="font-bold">@lang('messages.leads.no_registrado')</h3>
                                        <div class="error-desc">
                                            Trata de limpiar los filtros para ampliar el alcance 
                                            <br/>
                                            <a href="{{route('seller.leads')}}" class="btn btn-primary m-t">
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
            </div>
        </div>
    </div>
</div>
<div>
    {{-- Modales --}}
    @include('admin.leads._modal_add_lead')
</div>
@endsection

@push('scripts')
<script src="{{asset('js/geo/'.session('localization').'.js')}}"></script>
<script src="{{ asset('js/geo/dinamic_geo.js') }}"></script>
<script>
     var datatables_options = {
        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        language: {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }      
        },
        columnDefs: [
            {
                "targets": [ 9 ],
                "visible": true,
                "searchable": false,
            }
        ],
        buttons: [
            {
                extend: 'excel', 
                title: 'excel',
                text: '<i class="fa fa-file-excel-o"></i> Excel',
                action: function() {
                    exportar();
                }
            }
        ]
    };


    $(document).ready(function() {
        //Loading
        $('#contenido').css({visibility: "visible"});
        $('.sk-loading').fadeOut(50);
        datatables_options.order = [[ 9, "desc" ]];
        $('.datatable').DataTable(datatables_options);
        //Filtros
        $('#range_from').val("{{$from}}");
        $('#range_to').val("{{$to}}");
        $("#origin" ).val("{{$origin}}");
        $("#status" ).val("{{$status}}");
        $("#product_category" ).val("{{$product_category}}");
        $("#seller" ).val("{{$selected_seller}}");

        $('#origin').chosen({
            placeholder: 'Origen'
        }).change( (e) => filtrar());;

        $('#seller').chosen({
            placeholder: 'Vendedor'
        }).change( (e) => filtrar());;

        $('#status').chosen({
            placeholder: 'Status'
        }).change( (e) => filtrar());

        $('#product_category').chosen({
            placeholder: 'Categoría'
        }).change( (e) => filtrar());

        $('#reportrange span').html("{{$from}}" + ' / ' + "{{$to}}");
        report_range_options.startDate = moment("{{$from}}", "DD-MM-YYYY");
        report_range_options.endDate = moment("{{$to}}", "DD-MM-YYYY");
        
        $('#reportrange').daterangepicker(report_range_options, function(start, end, label) {
            $('#range_from').val(start.format('DD-MM-YYYY'));
            $('#range_to').val(end.format('DD-MM-YYYY'));
            $('#reportrange span').html(start.format('DD-MM-YYYY') + ' al ' + end.format('DD-MM-YYYY'));
            filtrar();
        });
        
        $('#filtrar').click(function( event ) {
            event.preventDefault();
            filtrar();
            // console.log(link);
        });
    });

    var app = new Vue({
        el: '#app',
        data: {
            leads_count_for_status: @json($leads_count_for_status),
            selected_status: @json($status) ? @json($status) : '',
        },
        computed: {
            status_todos: function() {
                let not_todos = ['2', '3', '4', '5'];
                return !(not_todos.includes(this.selected_status));
            }
        },
        methods: {
            filtrar: filtrar,
            setSelectedStatus: setSelectedStatus
        }
    });

    function setSelectedStatus(status){
        this.selected_status = status;
        this.filtrar(status);
    }

    function filtrar(status = null) {
        var origin = $( "#origin" ).val();
        var product_category = $( "#product_category" ).val();
        var seller = $( "#seller" ).val() == undefined ? '' : $( "#seller" ).val();
        var status = status === null ? $( "#status" ).val() : status;
        range_from = $('#range_from').val();
        range_to = $('#range_to').val();
        var link = "{{route('seller.leads')}}" + 
            "?from=" + range_from + 
            "&to=" + range_to + 
            "&origin=" + origin + 
            "&product_category=" + product_category + 
            "&seller=" + seller + 
            "&status=" + status;
        window.location.replace(link);
    }

    function exportar() {
        var origin = $( "#origin" ).val();
        var product_category = $( "#product_category" ).val();
        var seller = $( "#seller" ).val() == undefined ? '' : $( "#seller" ).val();
        var status = $( "#status" ).val();
        range_from = $('#range_from').val();
        range_to = $('#range_to').val();
        var link = "{{route('leads.export')}}" + 
            "?from=" + range_from + 
            "&to=" + range_to + 
            "&origin=" + origin + 
            "&product_category=" + product_category + 
            "&seller=" + seller + 
            "&status=" + status; 
        window.location.replace(link);
        // console.log(link);
    }
</script>
@endpush
