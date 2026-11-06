@extends('layouts.app')
@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-md-9">
            <h2>Lista de usuarios</h2>
            {{-- {{ Breadcrumbs::render('admin.managers') }} --}}
        </div>
    </div>
@endsection
@section('content')
<div id="app">
    <div class="row">
        <div class="col-lg-12">
            @if($users->count())
                <div class="ibox">
                    <div class="ibox-content">
                        @include('users._table')     
                    </div>
                </div>
            @else
            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInUp">
                    <h3 class="font-bold">No hay ningún usuario registrado</h3>
                </div>
            </div>
            @endif
        </div>
    </div>  
</div>
@endsection
@push('scripts')
<script>
  $(document).ready(function() { 
    // datatables_options.order = [[ 7, "desc" ]];
    $('.datatable').DataTable(datatables_options);
  });
</script>
@endpush