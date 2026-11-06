@extends('layouts.app')
@push('styles')
    <link href="{{ asset('css/plugins/bootstrap-duallistbox.min.css') }}" rel="stylesheet">
@endpush
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-md-9">
            <h2>Lista de Kams</h2>
            {{-- {{ Breadcrumbs::render('admin.admins') }} --}}
        </div>
        <div class="col-md-3">
            <div class="title-action">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-kam">
                    Agregar Kam
                </a>
            </div>
        </div>
    </div>
@endsection
@section('content')
<div id="app">
    <div class="row">
        <div class="col-lg-12">
            @if($kams->count())
                <div class="ibox">
                    <div class="ibox-content">
                        @include('admin.kams._table')     
                    </div>
                </div>
            @else
            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInUp">
                    <h3 class="font-bold">No hay ningún kam registrado</h3>
                    <div class="error-desc">
                        Registra el primer kam para empezar a utilizar este mantenedor 
                        <br/><a href="#" data-toggle="modal" data-target="#modal-add-kam" class="btn btn-primary m-t">Agregar kam</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    {{-- Modales --}}
    @include('admin.kams._modal_add')    
    @include('admin.kams._modal_edit')    
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.bootstrap-duallistbox.js') }}"></script>
    <script>
        $('.datatable').DataTable(datatables_options);
        $('.dual_select').bootstrapDualListbox({
            selectorMinimalHeight: 160,
            filterPlaceHolder: "Buscar",
            filterTextClear: "Todos",
            moveAllLabel: "Asigna todos",
            removeAllLabel: "Remueve todos",
            selectedListLabel: "Distribuidores Asignados al Kam",
            nonSelectedListLabel: "Distribuidores No asignados al Kam",
            infoText: false,
            infoTextFiltered: false
        });
        var app = new Vue({
            el: '#app',
            data: {
                kams: @json($kams),
                kam_to_edit: {id: ''},
                name: '',
                email: ''
            },
            computed: {
                route_to_edit: function () {
                    return "{{route('admin.kams')}}/" + this.kam_to_edit.id;
                }
            },
            methods: {
                setKamToEdit: setKamToEdit,
            }
        });

        function setKamToEdit(event){
            var id = event.target.getAttribute('data-kam-id');
            this.kam_to_edit = {};
            vm = this;
            $.each(vm.kams, function(i, kam) {
                if (kam.id == id) {
                  vm.kam_to_edit = kam;
                  return;
                }
            });
        }
    </script>
@endpush
