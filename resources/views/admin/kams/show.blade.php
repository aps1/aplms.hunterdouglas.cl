@extends('layouts.app')
@push('styles')
    <link href="{{ asset('css/plugins/bootstrap-duallistbox.min.css') }}" rel="stylesheet">
@endpush
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-8">
            <h2>
                {{$kam->name}}
                @if (!$kam->is_active)
                    <span class="small label label-plain ">Inactivo</span>
                @endif
            </h2>
        </div>
    </div>
@endsection
@section('content')
<div id="app" class="row animated fadeInRight">
    <div class="col-md-4">
        <div class="row">
            <div class="col-md">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Datos del Kam</h5>
                        <div class="ibox-tools" style="font-size:17px; top:8px">
                            <button
                            class="btn btn-outline btn-link"  
                            style="padding:5px" 
                            title="{{__('Editar')}}"
                            data-toggle="modal"
                            data-target="#modal-edit-kam"
                            >
                                <span class="fa fa-pencil"></span>
                            </button>
                            @if ($kam->is_active)
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
                    </div>
                    <div class="ibox-content">
                        <h2>{{$kam->name}}
                      
                        </h2>
                        <p>
                            <span class="text-muted">Email</span> <strong>{{$kam->email}}</strong>
                        </p>
                        <p>
                            <span class="text-muted">Celular</span> <strong>{{$kam->cel ?? "----"}}</strong>
                        </p>
                        <p>
                            <span class="text-muted">{{__("Cel. Whatsapp")}}</span> <strong>{{$kam->cel_whatsapp ?? "----"}}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{-- Distribuidores --}}
        <div class="row">
            <div class="col-md">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Distribuidores del Kam</h5>
                        <div class="ibox-tools" style="font-size:17px; top:8px">
                            <button
                            class="btn btn-outline btn-link"  
                            style="padding:5px" 
                            title="{{__('Editar')}}"
                            data-toggle="modal"
                            data-target="#modal-edit-kam-dealers"
                            >
                                <span class="fa fa-pencil"></span>
                            </button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        {{-- Distribuidores --}}
                        @if ($kam->dealerships->count())
                            <div class="scroll_content">
                                @include('admin.kams._dealerships_table')
                            </div>
                        @else
                            <h3 class="text-center">{{$kam->name}} no tiene ningún distribuidor asociado</h3>    
                        @endif  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        {{-- Actividades --}}
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
                            <h3 class="text-center">{{$kam->name}} no tiene ninguna actividad registrada en los últimos 90 días</h3>    
                        @endif  
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Modales --}}
    @include('admin.kams._modal_edit')
    @include('admin.kams._modal_edit_kam_dealers')
    @include('admin.kams._modal_inactivate')
    @include('admin.kams._modal_activate')

    {{-- Campos para el range url--}}
    <input type="hidden" id="range_from" name="range_from">
    <input type="hidden" id="range_to" name="range_to">

</div>
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery.bootstrap-duallistbox.js') }}"></script>
    <script>
        $(document).ready(function(){
            datatables_options.paging = false;
            datatables_options.buttons = [];
            datatables_options.language.sInfo = '';
            $('.datatable').DataTable(datatables_options);
            $('.scroll_content').slimscroll({
                height: '500px'
            })
            $('.dual_select').bootstrapDualListbox({
                selectorMinimalHeight: 160,
                filterPlaceHolder: "Buscar",
                filterTextClear: "Todos",
                selectedListLabel: "Distribuidores Asignados al Kam",
                nonSelectedListLabel: "Distribuidores No asignados al Kam",
                infoText: false,
                infoTextFiltered: false
            });
            $('.moveall').hide();
            $('.removeall').hide();

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
                var user_id = @json($kam).id
                var range_from = $('#range_from').val();
                var range_to = $('#range_to').val();
                var link = "{{route('admin.kams')}}" + "/" + user_id + "?from=" + range_from + "&to=" + range_to;
                window.location.replace(link);
                // console.log(link);
            });
        })

        var app = new Vue({
            el: '#app',
            data: {
                kam_to_edit: @json($kam),
                name: '',
                email: ''
            },
            computed: {
                route_to_edit: function () {
                    return "{{route('admin.kams')}}/" + this.kam_to_edit.id;
                }
            },
        });
    </script>
@endpush