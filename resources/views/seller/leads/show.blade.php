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
                {{-- <a href="{{route('seller.leads')}}" class="btn btn-primary">@lang('Prospectos')</a> --}}
            </div>
        </div>
    </div>
@endsection
@section('content')
<div id="app" class="row animated fadeInRight">
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>@lang('Datos del Prospecto')</h5>
                        <div class="ibox-tools">
                            @if (!$lead->isTaken())
                                <a href="#" 
                                    data-toggle="modal" 
                                    data-target="#modal-take-lead" 
                                    class="btn btn-primary btn-sm">
                                @lang('Tomar al Prospecto')
                                </a>
                            @endif
                            @if (!$lead->isDiscarded())
                                {{-- Descartar --}}
                                <button
                                    class="btn btn-outline btn-sm "
                                    data-toggle="modal" data-target="#modal-descartar"
                                    title="Descartar"
                                    style="font-size:17px;"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>  
                            @endif
                        </div>    
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
                                <tr>
                                    <td>
                                        <i class="fa fa-envelope text-muted"></i>
                                        <span class="text-muted">Email</span>
                                        <a href="{{'mailto:' . $lead->email}}">{{$lead->email}}</a>
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
                                        {{$lead->created_at->format('d-m-Y')}} {{$lead->created_at->format('H:i')}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-user text-muted"></i> 
                                        @if ($lead->user_id)
                                            <span class="text-muted">Vendedor</span> 
                                            {{$lead->user->name}}
                                        @else
                                            <span class="text-muted">Sin vendedor asignado</span>
                                        @endif
                                        @if (Auth::user()->isExecutiveLevel())
                                            <a href="#" class="pull-right" style="color:inherit" title="Asignar vendedor(a)"
                                                data-toggle="modal" data-target="#modal-assign-seller"
                                            >
                                                <i class="fa fa-pencil"></i>
                                            </a>    
                                        @endif
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
                                        @if ($lead->dealership)
                                            @if (Auth::user()->isAdminLevel())
                                                <a href="{{route('admin.dealerships.show', $lead->dealership->id)}}">
                                                    {{$lead->dealership->name}}

                                                 
                                                </a> 
                                            @else
                                                {{optional($lead->dealership)->name}}
                                            @endif
                                        @else
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
                                        <i class="fa fa-window-maximize text-muted" style="padding-right: 5px;"></i>
                                        <span class="text-muted">Página</span>
                                        <span title="{{$lead->page_title}}">
                                            {{Str::limit($lead->page_title, $limit = 35, $end = '...')}}
                                        </span>
                                    </td>
                                </tr>
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
                        <br>
                        <div class="user-button">
                            <div class="row">
                                <div class="col-md-12">
                                    
                                </div>
                            </div>
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
    @include('seller.dashboard._modal_take_lead')
    @include('owner.leads._modal_assign_seller')
    @include('admin.leads._modal_edit_priority')
    @include('admin.leads._modal_add_activity')
    @include('admin.leads._modal_descartar')
</div>
@endsection
@push('scripts')

<script>
    var app = new Vue({
        el: '#app',
        data: {
            take_lead_route: "{{route('base')}}" + '/seller/leads/take/' + "{{$lead->id}}",
            folios_rows : 1,
            folios: @json($lead->folios),
            extra_folios_rows : 0
        },
        computed: {
            folios_extras: function() {
                // si no viene con folios, agrega 3
                if (this.folios.length == 0) {
                    return 3;
                }
                // Si es multiplo de 3 esta bien, si no agraga los que le faltan para ser multiplo de 3
                if (this.folios.length % 3 == 0) {
                   return 0;
                }else if ((this.folios.length +1) % 3 == 0) {
                    return 1;
                } else {
                    return 2;
                }
            }
        }
    });
    $("#select-activity").change(function(){
        const ActivityTypeCotizar = 4;
        const ActivityTypeFoliar = 14;
        if (this.value == ActivityTypeCotizar){
            $("#net_value_input").show(200)
        }else{
            $("#net_value_input").hide(200)
        }
        if (this.value == ActivityTypeFoliar){
            $("#folios_inputs").show(200)
        }else{
            $("#folios_inputs").hide(200)
        }
    });

    $('#modal-add-activity').on('shown.bs.modal', function () {
        //files input
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
    
@endpush
