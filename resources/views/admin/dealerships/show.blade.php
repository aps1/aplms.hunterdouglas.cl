@extends('layouts.app')
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-8">
            <h2>
                Distribuidor {{Str::title($dealership->name)}}
                @if (! $dealership->is_active)
                    <span class="small label label-danger ">Inactivo</span>
                @endif
            </h2>
            {{-- {{ Breadcrumbs::render('admin.dealerships.show', $dealership) }} --}}
        </div>
    </div>
@endsection
@section('content')
<div class="row animated fadeInRight">
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Datos del distribuidor</h5>
                        @can('update', $dealership)
                            <div class="ibox-tools" style="font-size:17px; top:8px">
                                <button
                                class="btn btn-outline btn-link"  
                                style="padding:5px" 
                                title="{{__('Editar')}}"
                                data-toggle="modal"
                                data-target="#modal-edit"
                                >
                                    <span class="fa fa-pencil"></span>
                                </button>
                                @if ($dealership->is_active)
                                    <button
                                        class="btn btn-outline btn-link"   
                                        title="{{__('Inactivar')}}"
                                        data-toggle="modal"
                                        data-target="#modal-inactivate"
                                        >
                                            <span class="fa fa-archive"></span>
                                    </button>
                                @else
                                    <button
                                        class="btn btn-outline btn-link"   
                                        title="{{__('Activar')}}"
                                        data-toggle="modal"
                                        data-target="#modal-activate"
                                        >
                                            <span class="fa fa-archive"></span>
                                    </button>
                                @endif
                            </div>
                        @endcan
                    </div>
                    <div class="ibox-content">
                        <h4><strong>{{$dealership->name}}</strong></h4>
                        <p><i class="fa fa-map-marker"></i> 
                            {{$dealership->region}},
                            {{$dealership->commune}},
                            {{$dealership->address}}
                        </p>
                        <p>
                            <strong>Kam</strong> {{ optional($dealership->kam)->name }} <br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        {{-- Kpis --}}
        {{-- @include('admin.partials._kpis') --}}
        <div class="row">
            <div class="col-lg-12">
                {{-- SELLERS --}}
                @include('admin.dealerships._users_table')
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        {{-- Leads --}}
        @include('admin.dealerships._leads_table')    
    </div>
</div>
{{-- Modales --}}
@include('admin.dealerships._modal_inactivate')
@include('admin.dealerships._modal_activate')
@include('admin.dealerships._modal_edit')
@include('admin.dealerships._modal_user_add')

{{-- Campos para el range url--}}
<input type="hidden" id="range_from" name="range_from">
<input type="hidden" id="range_to" name="range_to">

@endsection
@push('scripts')
<script src="{{asset('js/geo/'.session('localization').'.js')}}"></script>
<script src="{{ asset('js/geo/dinamic_geo.js') }}"></script>
    <script>
        $( document ).ready(function() {
            //range
            $('#range_from').val("{{$from}}");
            $('#range_to').val("{{$to}}");
            $('#reportrange span').html("{{$from}}" + ' / ' + "{{$to}}");

            $('#reportrange').daterangepicker(report_range_options, function(start, end, label) {
                $('#range_from').val(start.format('DD-MM-YYYY'));
                $('#range_to').val(end.format('DD-MM-YYYY'));
                $('#reportrange span').html(start.format('DD-MM-YYYY') + ' al ' + end.format('DD-MM-YYYY'));
            });

            var dealership =  @json($dealership->toArray());
            // console.log(dealership.commune);
            $("#regiones").val(dealership.region);
            $("#comunas").val(dealership.commune);

            $('#filtrar').click(function( event ) {
                event.preventDefault();
                range_from = $('#range_from').val();
                range_to = $('#range_to').val();
                var link = "{{route('admin.dealerships')}}" + "/" + dealership.id + "?from=" + range_from + "&to=" + range_to;
                window.location.replace(link);
                // console.log(link);
            });
        });
        
        
    </script>
@endpush
