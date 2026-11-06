@extends('layouts.app')

@section('content')
        
    {{-- FILTROS --}}
    <div class="row m-b-md">
        <div class="col-md-3">
            <select data-placeholder="Todos los vendedores" id="seller_id" class="chosen-select">
                <option value="">Todos los vendedores</option>
                @foreach ($sellers as $seller)
                    <option value="{{$seller->id}}">{{Str::title($seller->name)}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <div id="reportrange" class="form-control">
                <i class="fa fa-calendar"></i>
                <span></span> <b class="caret"></b>
            </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3 text-right">
            <a href="#" id="filtrar" class="btn btn-default" title="Filtrar"><i class="fa fa-filter"></i></a>
            <a href="{{route('admin.dashboard')}}" class="btn btn-default" title="Limpiar filtros"><i class="fa fa-rotate-left"></i></a> 
            <a href="{{route('admin.dashboard')}}" class="btn btn-default" title="Imprimir reporte" onclick="printPage()"><i class="fa fa-print"></i></a>    
        </div>
    </div>
    {{-- KPI --}}
    @include('admin.dashboard._kpis')
    
    {{-- GRAFICO --}}
    {{-- <div class="row">
        <div class="col">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col">
                            <canvas id="barChart" height="200" width="762" style="display: block; width: 762px; height: 200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
<input type="hidden" id="range_from" name="range_from">
<input type="hidden" id="range_to" name="range_to">
@endsection

@push('scripts')
<script>
    var months = @json($last_months);
    var leads_count_by_status = @json($leads_count_by_status);
    var leads_all = Object.values(leads_count_by_status.todos.by_months);
    var leads_won = Object.values(leads_count_by_status.vendidos.by_months);
    var leads_lost = Object.values(leads_count_by_status.perdidos.by_months);

    $(document).ready(function() {
        $('#range_from').val("{{$from}}");
        $('#range_to').val("{{$to}}");
        $("#seller_id" ).val("{{$seller_id}}");
        $("#seller_id").chosen({placeholder: 'Todos los vendedores'});


        $('#reportrange span').html("{{$from}}" + ' / ' + "{{$to}}");

        $('#reportrange').daterangepicker(report_range_options, function(start, end, label) {
            $('#range_from').val(start.format('DD-MM-YYYY'));
            $('#range_to').val(end.format('DD-MM-YYYY'));
            $('#reportrange span').html(start.format('DD-MM-YYYY') + ' al ' + end.format('DD-MM-YYYY'));
        });
    });

    var barData = {
        labels: months,
        datasets: [
            {
                label: "Prospectos generados",
                backgroundColor: 'rgba(220, 220, 220, 0.5)',
                pointBorderColor: "#fff",
                data: leads_all
            },
            {
                label: "Prospectos Vendidos",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: leads_won
            },
            {
                label: "Prospectos Perdidos",
                backgroundColor: 'rgba(237,85,101,0.5)',
                borderColor: "rgba(237,85,101,0.7)",
                pointBackgroundColor: "rgba(237,85,101,1)",
                pointBorderColor: "#fff",
                data: leads_lost
            }
        ]
    };

    var barOptions = {
        responsive: true
    };

    // var ctx2 = document.getElementById("barChart").getContext("2d");
    // new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});

    

    $('#filtrar').click(function( event ) {
        event.preventDefault();
        var seller_id = $( "#seller_id" ).val();
        var range_from = $('#range_from').val();
        var range_to = $('#range_to').val();
        
        var link = "{{route('owner.dashboard')}}" + 
            "?from=" + range_from + 
            "&to=" + range_to + 
            "&seller_id=" + seller_id;
            // console.log(link);
        window.location.replace(link);
        
    });
    
</script>
@endpush

