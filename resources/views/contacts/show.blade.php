@extends('layouts.app')
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-8">
            <h2>
                {{$contact->name}}
            </h2>
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
                        <h5>
                            Datos del contacto
                            @can('update', $contact)
                                <div class="ibox-tools" style="font-size:17px; top:8px">
                                    <button
                                    class="btn btn-outline btn-link"  
                                    style="padding:5px" 
                                    title="{{__('Editar')}}"
                                    data-toggle="modal"
                                    data-target="#modal-edit-contact"
                                    >
                                        <span class="fa fa-pencil"></span>
                                    </button>
                                    <button
                                        class="btn btn-outline btn-link"   
                                        title="{{__('Eliminar')}}"
                                        data-toggle="modal"
                                        data-target="#modal-delete-contact"
                                        >
                                            <span class="fa fa-trash"></span>
                                    </button>
                                </div>
                            @endcan
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-9">
                                <h2>{{$contact->name}}</h2>
                            </div>
                            <div class="col-sm-3">
                                
                            </div>
                        </div>

                        <div class="m-t-sm">
                            <table class="table m-b-xs">
                                <tbody>
                                @if (Auth::user()->isAdminLevel())
                                    <tr>
                                        <td>
                                            <i class="fa fa-map text-muted"></i>
                                            <span class="text-muted">Distribuidor</span>
                                            <a href="{{route('admin.dealerships.show', $contact->dealership->id)}}">{{$contact->dealership->name}}</a>
                                        </td>
                                    </tr>    
                                @endif    
                                <tr>
                                    <td>
                                        <i class="fa fa-envelope text-muted"></i>
                                        <span class="text-muted">Email</span>
                                        <a href="mailto:{{$contact->email}}">{{$contact->email}}</a>
                                    </td>
                                </tr>    
                                <tr>
                                    <td>
                                        <i class="fa fa-phone text-muted"></i>
                                        <span class="text-muted">Teléfono</span>
                                        <a href="tel:{{$contact->phone}}">{{$contact->phone}}</a>
                                    </td>
                                </tr>    
                                <tr>
                                    <td>
                                        <i class="fa fa-phone text-muted"></i>
                                        <span class="text-muted">Teléfono</span>
                                        <a href="tel:{{$contact->phone2}}">{{$contact->phone2}}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-address-book-o text-muted"></i>
                                        <span class="text-muted">{{__('Método de contacto')}}</span>
                                        {{$contact->contact_method}}
                                    </td>
                                </tr>   
                                <tr>
                                    <td>
                                        <i class="fa fa-handshake-o text-muted"></i>
                                        <span class="text-muted">Tipo</span>
                                        {{$contact->type_description}}
                                    </td>
                                </tr>    
                                <tr>
                                    <td>
                                        <i class="fa fa-pencil-square-o text-muted"></i>
                                        <span class="text-muted">Fuente</span>
                                        {{$contact->source_description}}
                                    </td>
                                </tr>    
                                <tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-map-marker text-muted"></i>
                                        <span class="text-muted">{{__('Región')}}</span>
                                        {{$contact->geo_loc1}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-map-marker text-muted"></i>
                                        <span class="text-muted">{{__('Comuna')}}</span>
                                        {{$contact->geo_loc2}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-map-marker text-muted"></i>
                                        <span class="text-muted">{{__('Dirección')}}</span>
                                        {{$contact->address}}
                                    </td>
                                </tr>        
                                <tr>
                                    <td>
                                        <i class="fa fa-calendar text-muted"></i> 
                                        <span class="text-muted">Registrada el</span> 
                                        {{$contact->created_at->format('d-m-Y H:i')}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa fa-file-text-o text-muted"></i> 
                                        <span class="text-muted">Descripción</span> 
                                        {{$contact->description}}
                                    </td>
                                </tr>
                                
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
            <div class="col-lg-12">
                {{-- Leads --}}
                @include('contacts._leads')    
            </div>
        </div>
    </div>
    
    {{-- Modales --}}
    @include('contacts._modal_edit_contact')
    @include('contacts._modal_delete_contact')
</div>
@endsection
@push('scripts')
    <script src="{{asset('js/geo/'.session('localization').'.js')}}"></script>
    <script>
       var geo_loc1 = "{{$contact->geo_loc1}}";
       var geo_loc2 = "{{$contact->geo_loc2}}";
       $(document).ready(function(){
            setRegiones();
            
            $('#modal-edit-contact').on('shown.bs.modal', function () {
                $('#regiones', this).chosen();
                $('#comunas', this).chosen();
                $("#regiones").val(geo_loc1);
                $("#comunas").val(geo_loc2);
            });

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
       })
       //Setea el selecto de regiones
        function setRegiones() {
            $('#regiones').html('<option value=""></option>');
            $.each(geo, function(i, geoitem) {
                $('#regiones').append('<option value="' + geoitem.geo1 + '">' + geoitem.geo1 + '</option>');
            }); 
            $("#regiones").val(geo_loc1);
            $("#regiones").trigger("chosen:updated");
            let filterGeo1 = geo.filter( (geoItem) => {
                return geoItem.geo1 == geo_loc1;
            });
            if (filterGeo1.lenght) {
                var filterGeo2 = filterGeo1[0].geo2.map( (geo2) => {
                    return geo2.nombre;
                });
            } else {
                var filterGeo2 = null;
            }
            setComunas(filterGeo2);
        }

        //Setea el selecto de comunas
        function setComunas(filter_comunas = null) {
            $('#comunas').html('<option value=""></option>');
                $.each(filter_comunas, function(i, filter_comuna) {
                $('#comunas').append('<option value="' + filter_comuna + '">' + filter_comuna + '</option>');
            });
            $("#comunas").val(geo_loc2);
            $("#comunas").trigger("chosen:updated");
        }
    </script>
@endpush