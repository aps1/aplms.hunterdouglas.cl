{{-- KPI --}}
<div class="row">
    <div class="col-md-6">
        <div class="ibox">
            <div class="ibox-title">
                <h5>@lang('Prospectos totales')</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{$quotations_metrics['general']['proper']['count']}}</h1>
                <div class="stat-percent font-bold text-default">
                    {{$quotations_metrics['general']['percent']}} % 
                </div>
                <small>
                    De {{$quotations_metrics['general']['all']}} total <br>
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-4 offset-md-1">
        <div id="reportrange" class="form-control">
            <i class="fa fa-calendar"></i>
            <span></span> <b class="caret"></b>
        </div>
    </div>
    <div class="col-md-1">
        <a href="#" id="filtrar" class="btn btn-default">Filtrar</a>        
    </div>
</div>
<div class="row">
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title" style="border-color:#ffc107">
                <h5>Pendientes</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{$quotations_metrics['open']['proper']['count']}}</h1>
                <div class="stat-percent font-bold text-primary">
                    {{$quotations_metrics['open']['percent']}} % 
                </div>
                <small>
                    De {{$quotations_metrics['open']['all']}} total <br>
                </small>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title" style="border-color:#17a2b8">
                <h5>Tomadas</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{$quotations_metrics['taken']['proper']['count']}}</h1>
                <div class="stat-percent font-bold text-primary">
                    {{$quotations_metrics['taken']['percent']}} % 
                </div>
                <small>
                    De {{$quotations_metrics['taken']['all']}} total <br>
                </small>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title" style="border-color:#1ab394">
                <h5>Ganadas</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{$quotations_metrics['won']['proper']['count']}}</h1>
                <div class="stat-percent font-bold text-primary">
                    {{$quotations_metrics['won']['percent']}} %
                </div>
                <small>
                    De {{$quotations_metrics['won']['all']}} total <br>
                </small>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title" style="border-color:#ed5565">
                <h5>Perdidas</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{$quotations_metrics['lost']['proper']['count']}}</h1>
                <div class="stat-percent font-bold text-primary">
                    {{$quotations_metrics['lost']['percent']}} %
                </div>
                <small>
                    De {{$quotations_metrics['lost']['all']}} total <br>
                    Monto {{number_format(($quotations_metrics['lost']['proper']['sum']) / 1000000, 2, ",", ".")}} $MM
                </small>
            </div>
        </div>
    </div>
</div>