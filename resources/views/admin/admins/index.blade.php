@extends('layouts.app')
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-md-9">
            <h2>Lista de administradores</h2>
            {{-- {{ Breadcrumbs::render('admin.admins') }} --}}
        </div>
        <div class="col-md-3">
            <div class="title-action">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-admin">
                    Agregar admin
                </a>
            </div>
        </div>
    </div>
@endsection
@section('content')
<div id="app">
    <div class="row">
        <div class="col-lg-12">
            @if($admins->count())
                <div class="ibox">
                    <div class="ibox-content">
                        @include('admin.admins._table')     
                    </div>
                </div>
            @else
            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInUp">
                    <h3 class="font-bold">No hay ningún administrador registrado</h3>
                    <div class="error-desc">
                        Registra el primer administrador para empezar a utilizar este mantenedor 
                        <br/><a href="#" data-toggle="modal" data-target="#modal-add-admin" class="btn btn-primary m-t">Agregar Admin</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    {{-- Modales --}}
    @include('admin.admins._modal_add')    
    @include('admin.admins._modal_edit')    
</div>
@endsection

@push('scripts')
    <script>
        $('.datatable').DataTable(datatables_options);
        var app = new Vue({
            el: '#app',
            data: {
                admins: @json($admins),
                admin_to_edit: {id: ''},
                name: '',
                email: ''
            },
            computed: {
                route_to_edit: function () {
                    return "{{route('admin.admins')}}/" + this.admin_to_edit.id;
                }
            },
            methods: {
                setAdminToEdit: setAdminToEdit,
            }
        });

        function setAdminToEdit(event){
            var id = event.target.getAttribute('data-admin-id');
            this.admin_to_edit = {};
            vm = this;
            $.each(vm.admins, function(i, admin) {
                if (admin.id == id) {
                  vm.admin_to_edit = admin;
                  return;
                }
            });
        }
    </script>
@endpush
