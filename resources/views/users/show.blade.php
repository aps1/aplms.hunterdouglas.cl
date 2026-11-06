@extends('layouts.app')
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-8">
            <h2>
                {{$user->name}}
                @if (! $user->is_active)
                    <span class="small label label-danger ">Inactivo</span>
                @endif
            </h2>
            {{-- {{ Breadcrumbs::render('admin.users.show', $user) }} --}}
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
                        <h5>Datos del {{$user->type_description}}</h5>
                        @can('update', $user)
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
                                @if ($user->is_active)
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
                        <h2>{{$user->name}}
                        @if ($user->isExecutiveLevel())
                            <span class="small label label-success pull-right">{{$user->type_description}}</span>
                        @endif
                        </h2>
                        <p>
                            <span class="text-muted">Email</span> <strong>{{$user->email}}</strong>
                        </p>
                        <p>
                            <span class="text-muted">Celular</span> <strong>{{$user->cel ?? "----"}}</strong>
                        </p>
                        <p>
                            <span class="text-muted">{{__("Cel. Whatsapp")}}</span> <strong>{{$user->cel_whatsapp ?? "----"}}</strong>
                        </p>
                        <p>
                            <span class="text-muted">Distribuidor</span> 
                            @if (Auth::user()->isAdminLevel() && $user->dealership_id)
                                <a href="{{route('admin.dealerships.show', $user->dealership->id)}}">
                                    {{$user->dealership->name}}
                                </a> 
                            @else    
                                {{optional($user->dealership)->name}}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-12">
                {{-- @include('admin.partials._kpis') --}}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                {{-- Leads --}}
                @include('users._leads')    
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Actividades</h5>
                    </div>
                    <div class="ibox-content">
                        {{-- ACTIVITIES --}}
                        @if ($activities->count())
                            <div class="scroll_content">
                                @include('users._activities')
                            </div>
                        @else
                            <h3 class="text-center">El {{$user->type_description}} no tiene ninguna actividad registrada en el rango de fecha</h3>    
                        @endif  
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Modales --}}
    @include('users._modal_inactivate')
    @include('users._modal_activate')
    @include('users._modal_edit')

    {{-- Campos para el range url--}}
    <input type="hidden" id="range_from" name="range_from">
    <input type="hidden" id="range_to" name="range_to">

</div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            $('.scroll_content').slimscroll({
                height: '500px'
            })

            //range
            $('#range_from').val("{{$from}}");
            $('#range_to').val("{{$to}}");
            $('#reportrange span').html("{{$from}}" + ' / ' + "{{$to}}");

            $('#reportrange').daterangepicker(report_range_options, function(start, end, label) {
                $('#range_from').val(start.format('DD-MM-YYYY'));
                $('#range_to').val(end.format('DD-MM-YYYY'));
                $('#reportrange span').html(start.format('DD-MM-YYYY') + ' al ' + end.format('DD-MM-YYYY'));
            });

            $('#filtrar').click(function( event ) {
                event.preventDefault();
                var user_id = @json($user).id
                var range_from = $('#range_from').val();
                var range_to = $('#range_to').val();
                var link = "{{route('users.index')}}" + "/" + user_id + "?from=" + range_from + "&to=" + range_to;
                window.location.replace(link);
                // console.log(link);
            });
        })

    </script>
@endpush