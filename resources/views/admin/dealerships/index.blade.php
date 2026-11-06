@extends('layouts.app')
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-md-9">
            <h2>Lista de Distribuidores</h2>
            {{-- {{ Breadcrumbs::render('admin.dealerships') }} --}}
        </div>
        @can('create', \App\Dealership::class)
            <div class="col-md-3">
                <div class="title-action">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-dealership">
                        Agregar Distribuidor
                    </a>
                </div>
            </div>
        @endcan
    </div>
@endsection
@section('content')
<div class="row" id="app">
    <div class="col-lg-12">
        @if($dealerships->count())
            <div class="ibox">
                <div class="ibox-content">
                    @include('admin.dealerships._table')     
                </div>
            </div>
        @else
        <div class="wrapper wrapper-content">
            <div class="middle-box text-center animated fadeInUp">
                <h3 class="font-bold">No hay ningún distribuidor registrado</h3>
                @can('create', \App\Dealership::class)
                    <div class="error-desc">
                        Registra el primer distribuidor para empezar a utilizar este mantenedor 
                        <br/><a href="#" data-toggle="modal" data-target="#modal-add-dealership" class="btn btn-primary m-t">Agregar distribuidor</a>
                    </div>
                @endcan
            </div>
        </div>
        @endif
    </div>
    @include('admin.dealerships._modal_add_dealership')
</div>
@endsection
@push('scripts')
<script src="{{asset('js/geo/'.session('localization').'.js')}}"></script>
<script src="{{ asset('js/geo/dinamic_geo.js') }}"></script>
<script>
    $(document).ready(function() { 
        // datatables_options.order = [[ 7, "desc" ]];
        $('.datatable').DataTable(datatables_options);
    });
</script>
@endpush
