@extends('layouts.app')
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-8">
            <h2>
                {{$lead->name}}
            </h2>
            {{-- {{ Breadcrumbs::render('admin.quotations.show', $quotation) }} --}}
        </div>

        <div class="col-sm-4">
            <div class="title-action">
                <a href="{{$url_previus}}" class="btn btn-primary">@lang('Regresar')</a>
            </div>
        </div>
    </div>
@endsection
@section('content')
<div id="app" class="row animated fadeInRight">
    @include('admin.leads._modal_add_activity')
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>
                            @lang('Datos del Prospecto')
                            @can('delete', $lead)
                                <div class="ibox-tools" style="font-size:17px; top:8px">
                                    <div class="btn-group">
                                        <button data-toggle="dropdown" class="btn btn-outline dropdown-toggle" aria-expanded="false">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 32px; left: 0px; will-change: top, left;">
                                            @if (Auth::user()->isAdmin())
                                                <li>
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-delete">
                                                        Eliminar
                                                    </a>
                                                </li>    
                                            @endif
                                            @if (!$lead->isDiscarded())
                                                {{-- Descartar --}}
                                                <li>
                                                    <a class="dropdown-item" href="#"  
                                                        data-toggle="modal" data-target="#modal-descartar" 
                                                        data-lead-id="{{$lead->id}}"
                                                        v-on:click="setLeadToDerive">
                                                        Descartar
                                                    </a>
                                                </li>    
                                            @endif
                                            
                                            {{-- Clusurar --}}
                                            @if ($lead->isCreado())
                                                <li>
                                                    <a class="dropdown-item" href="#"  
                                                    data-toggle="modal" data-target="#modal-clausurar" 
                                                    data-lead-id="{{$lead->id}}"
                                                    v-on:click="setLeadToDerive">
                                                    Clausurar
                                                </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endcan
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <h2>
                            {{$lead->name}}
                            @if ($lead->isStrategic())
                                <a href="#" 
                                    class="pull-right text-warning" 
                                    title="{{__('Prospecto estratégico')}}"
                                    data-toggle="modal"
                                    data-target="#modal-toggle-strategic"
                                    >
                                    <span class="fa fa-star"></span>
                                </a>
                            @else
                                <a href="#" 
                                    class="pull-right" 
                                    style="color:#beb8b8" 
                                    title="{{__('Marcar como estratégico')}}"
                                    data-toggle="modal"
                                    data-target="#modal-toggle-strategic"
                                    >
                                    <span class="fa fa-star-o"></span>
                                </a>
                            @endif
                        </h2>
                        <div class="row">
                            <div class="col-lg-3">
                                <a href="#" style="color:inherit" title="Registrar actividad"
                                    data-toggle="modal" data-target="#modal-add-activity"
                                >
                                    <span class="label label-{{$lead->status_color}}">
                                        {{$lead->status_description}}
                                    </span>
                                </a>
                            </div>
                            @if ($lead->status_description == 'Descartado')
                                <span class="label label-{{$lead->status_color}}">
                                    Motivo: {{$lead->status_reason_description}}
                                </span>
                            @endif
                            @if ($lead->priority)
                                <div class="col-lg-4">
                                    <a href="#" style="color:inherit" title="Cambiar Interés"
                                        data-toggle="modal" data-target="#modal-edit-priority"
                                    >
                                        <span class="label label-{{$lead->priority_color}}">
                                            {{$lead->priority_description}}
                                        </span>
                                    </a>
                                </div>
                            @endif
                        </div> 
                        <div class="m-t">
                            <table class="table m-b-xs">
                                <tbody>
                                <tr>
                                    <td>
                                        <i class="fa fa-barcode text-muted"></i>
                                        <span class="text-muted">ID</span>
                                        {{$lead->web_identifier}}
                                    </td>
                                </tr>
                                @if ($lead->isWon())
                                    <tr>
                                        <td>
                                            <i class="fa fa-tags text-muted" style="padding-right: 5px;"></i>
                                            <span class="text-muted">Folios</span>
                                            @foreach ($lead->folios as $folio)
                                                {{$folio->number}} {{!($loop->last) ? ', ' : ''}}
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <i class="fa fa-envelope text-muted"></i>
                                        <span class="text-muted">Email</span>
                                        <a href="mailto:{{$lead->email}}">{{$lead->email}}</a>
                                    </td>
                                </tr>    
                                <tr>
                                    <td>
                                        <i class="fa fa-phone text-muted"></i>
                                        <span class="text-muted">Teléfono</span>
                                        <a href="tel:{{$lead->phone}}">{{$lead->phone}}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-address-book-o text-muted"></i>
                                        <span class="text-muted">{{__('Método de contacto')}}</span>
                                        {{$lead->contact_method}}
                                    </td>
                                </tr>     
                                <tr>
                                    <td>
                                        <i class="fa fa-map-marker text-muted"></i>
                                        <span class="text-muted">{{__('Región')}}</span>
                                        {{$lead->geo_loc1}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-map-marker text-muted"></i>
                                        <span class="text-muted">{{__('Comuna')}}</span>
                                        {{$lead->geo_loc2}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-map-marker text-muted"></i>
                                        <span class="text-muted">{{__('Dirección')}}</span>
                                        {{$lead->address}}
                                    </td>
                                </tr>        
                                <tr>
                                    <td>
                                        <i class="fa fa-calendar text-muted"></i> 
                                        <span class="text-muted">Registrada el</span> 
                                        {{$lead->created_at->format('d-m-Y H:i')}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-edit text-muted"></i> 
                                        <span class="text-muted">Origen</span> 
                                        {{$lead->origin_description}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-sitemap text-muted"></i> 
                                        <span class="text-muted">Categoría de producto</span> 
                                        {{$lead->product_category_description}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-bookmark text-muted"></i> 
                                        <span class="text-muted">Marca</span> 
                                        {{$lead->brand}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-map text-muted"></i> 
                                        <span class="text-muted">Distribuidor</span>
                                        @if ($lead->dealership_id)
                                            @if (Auth::user()->isAdminLevel())
                                                <a href="{{route('admin.dealerships.show', $lead->dealership->id)}}">
                                                    {{$lead->dealership->name}}
                                                </a> 
                                            @else
                                                {{optional($lead->dealership)->name}}
                                            @endif
                                        @elseif($lead->isDerivable())
                                            <div class="btn-group">
                                                <a href="#" 
                                                    data-toggle="modal" data-target="#modal-derive" 
                                                    data-lead-id="{{$lead->id}}"
                                                    v-on:click="setLeadToDerive"
                                                class="btn-warning btn btn-xs">Derivar</a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-user text-muted"></i> 
                                        <span class="text-muted">Vendedor(a)</span>
                                        @if ($lead->user_id)
                                            @if (Auth::user()->isAdminLevel())
                                                <a href="{{route('users.show', $lead->user_id)}}">
                                                    {{$lead->user->name}}
                                                </a> 
                                            @else
                                                {{optional($lead->user)->name}}
                                            @endif
                                        @else
                                            
                                        @endif
                                    </td>
                                </tr>
                                @if ($lead->page_title)
                                    <tr>
                                        <td>
                                            <i class="fa fa-window-maximize text-muted" style="padding-right: 5px;"></i>
                                            <span class="text-muted">Página</span>
                                            <span title="{{$lead->page_title}}">
                                                {{Str::limit($lead->page_title, $limit = 35, $end = '...')}}
                                            </span>
                                        </td>
                                    </tr>    
                                @endif
                                @if ($lead->source)
                                    <tr>
                                        <td>
                                            <i class="fa fa-window-maximize text-muted" style="padding-right: 5px;"></i>
                                            <span class="text-muted">Fuente</span>
                                            {{$lead->source_description}}
                                        </td>
                                    </tr>    
                                @endif
                                @if ($lead->net_value)
                                    <tr>
                                        <td>
                                            <i class="fa fa-money text-muted" style="padding-right: 5px;"></i>
                                            <span class="text-muted">Valor neto cotizado</span>
                                            {{$lead->net_value}}
                                        </td>
                                    </tr>
                                @endif
                                <tr><td></td><td></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="row">
            <div class="col-md">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Actividades</h5>
                    </div>
                    <div class="ibox-content">
                        {{-- ACTIVITIES --}}
                        @include('admin.leads._activities')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Modales --}}
    @include('admin.leads._modal_toggle_strategic')
    @include('admin.leads._modal_delete')
    @include('admin.leads._modal_derive')
    @include('admin.leads._modal_clausurar')
    @include('admin.leads._modal_descartar')
</div>
@endsection
@push('scripts')

<script src="{{asset('js/geo/'.session('localization').'.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#modal-derive').on('shown.bs.modal', function () {
            $('#dealership', this).chosen();
            $('#regiones', this).chosen();
            $('#comunas', this).chosen();

            // Preselect regiones
            let lead_geo1 = "{{$lead->geo_loc1}}";
            $('#regiones', this).val(lead_geo1);
            $('#regiones').trigger("chosen:updated");
            $('#regiones').change();
            //preselect comuna
            let lead_geo2 = "{{$lead->geo_loc2}}";
            $('#comunas', this).val(lead_geo2);
            $('#comunas').trigger("chosen:updated");
            $('#comunas').change();
        });

        $('#modal-add-activity').on('shown.bs.modal', function () {
            //files input
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });

        //todos las regiones por defecto
        setRegiones();
        //todos los distribuidores por defecto
        setDealerships();

        $('#regiones').change(function(event) {
            let selected = event.target.value;
            //selecciono todas las regiones
            if (selected == '') {
                setDealerships();
                setComunas();
                return;
            }
            let filterGeo1 = geo.filter( (geoItem) => {
                return geoItem.geo1 == selected;
            }).sort();
            let filterGeo2 = filterGeo1[0].geo2.map( (geo2) => {
                return geo2.nombre;
            }).sort();
            setComunas(filterGeo2);
            
            $('#comunas').html('<option value="">Todos las comunas</option>');
                $.each(filterGeo2, function(i, filter_comuna) {
                $('#comunas').append('<option value="' + filter_comuna + '">' + filter_comuna + '</option>');
            });
            $("#comunas").trigger("chosen:updated");

            let filter_dealerships = @json($dealerships).filter( (dealer) => {
                return dealer.region == selected;
            });
            setDealerships(filter_dealerships);
        });

        $('#comunas').change(function(event) {
            let selected = event.target.value; 
            //selecciono todas las comunas, filtra por la region
            if (selected == '') {
                let selected_region = $("#regiones").val();
                // todas las regiones
                if (selected_region == '') {
                    setDealerships();
                    return;
                }
                // hay region
                let filter_dealerships = @json($dealerships).filter( (dealer) => {
                    return dealer.region == selected_region;
                });
                setDealerships(filter_dealerships);
                return;
            }
            let filter_dealerships = @json($dealerships).filter( (dealer) => {
                return dealer.commune == selected;
            });
            setDealerships(filter_dealerships); 
        });
    })

    //Setea el selector de dealerships
    function setDealerships(filter_dealerships = null) {
        if (!filter_dealerships) {
            filter_dealerships = @json($dealerships);
        }

        $('#dealership').html('<option value=""></option>');
        $.each(filter_dealerships, function(i, dealer) {
            //Si viene con usaurio due;o imprime el nombre junto con el dealer name
            let owner_name = dealer.users.length ? ' - ' + dealer.users[0].name : '';
            $('#dealership').append('<option value="' + dealer.id + '">' + dealer.name + owner_name + '</option>');
        });
        $('#dealer-count').text(filter_dealerships.length)
        $("#dealership").trigger("chosen:updated");
    }

    //Setea el selecto de regiones
    function setRegiones() {
        $('#regiones').html('<option value="">Todas las regiones</option>');
        $.each(geo, function(i, geoitem) {
            $('#regiones').append('<option value="' + geoitem.geo1 + '">' + geoitem.geo1 + '</option>');
        }); 
        $("#regiones").trigger("chosen:updated");
    }

    //Setea el selector de comunas
    function setComunas(filter_comunas = null) {
        $('#comunas').html('<option value="">Todos las comunas</option>');
            $.each(filter_comunas, function(i, filter_comuna) {
            $('#comunas').append('<option value="' + filter_comuna + '">' + filter_comuna + '</option>');
        });
        $("#comunas").trigger("chosen:updated");
    }

</script>
    
@endpush
