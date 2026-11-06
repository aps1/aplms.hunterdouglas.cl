<div class="row">
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title">
                {{-- <span class="label label-default float-right" style="margin-right: -75px">Monthly</span> --}}
                <h5>Todos</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">
                    {{$leads_count_by_status['todos']['actual']}}
                </h1>
                @if ($leads_count_by_status['todos']['change'] > 0)
                    <span>
                        <div class="stat-percent font-bold text-success">
                            {{$leads_count_by_status['todos']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @elseif ($leads_count_by_status['todos']['change'] < 0)
                    <span>
                        <div class="stat-percent font-bold text-danger">
                            {{$leads_count_by_status['todos']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @endif
                <small>
                    Periodo anterior {{$leads_count_by_status['todos']['previus']}} <br>
                </small>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title">
                {{-- <span class="label label-default float-right" style="margin-right: -75px">Monthly</span> --}}
                <h5>Creados</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">
                    {{$leads_count_by_status['creados']['actual']}}
                </h1>
                @if ($leads_count_by_status['creados']['change'] > 0)
                    <span>
                        <div class="stat-percent font-bold text-success">
                            {{$leads_count_by_status['creados']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @elseif ($leads_count_by_status['creados']['change'] < 0)
                    <span>
                        <div class="stat-percent font-bold text-danger">
                            {{$leads_count_by_status['creados']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @endif
                <small>
                    Periodo anterior {{$leads_count_by_status['creados']['previus']}} <br>
                </small>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title">
                {{-- <span class="label label-default float-right" style="margin-right: -75px">Monthly</span> --}}
                <h5>Derivados</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">
                    {{$leads_count_by_status['derivados']['actual']}}
                </h1>
                @if ($leads_count_by_status['derivados']['change'] > 0)
                    <span>
                        <div class="stat-percent font-bold text-success">
                            {{$leads_count_by_status['derivados']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @elseif ($leads_count_by_status['derivados']['change'] < 0)
                    <span>
                        <div class="stat-percent font-bold text-danger">
                            {{$leads_count_by_status['derivados']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @endif
                <small>
                    Periodo anterior {{$leads_count_by_status['derivados']['previus']}} <br>
                </small>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title">
                {{-- <span class="label label-default float-right" style="margin-right: -75px">Monthly</span> --}}
                <h5>Asignados</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">
                    {{$leads_count_by_status['asignados']['actual']}}
                </h1>
                @if ($leads_count_by_status['asignados']['change'] > 0)
                    <span>
                        <div class="stat-percent font-bold text-success">
                            {{$leads_count_by_status['asignados']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @elseif ($leads_count_by_status['asignados']['change'] < 0)
                    <span>
                        <div class="stat-percent font-bold text-danger">
                            {{$leads_count_by_status['asignados']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @endif
                <small>
                    Periodo anterior {{$leads_count_by_status['asignados']['previus']}} <br>
                </small>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title">
                {{-- <span class="label label-default float-right" style="margin-right: -75px">Monthly</span> --}}
                <h5>Contactados</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">
                    {{$leads_count_by_status['contactados']['actual']}}
                </h1>
                @if ($leads_count_by_status['contactados']['change'] > 0)
                    <span>
                        <div class="stat-percent font-bold text-success">
                            {{$leads_count_by_status['contactados']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @elseif ($leads_count_by_status['contactados']['change'] < 0)
                    <span>
                        <div class="stat-percent font-bold text-danger">
                            {{$leads_count_by_status['contactados']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @endif
                <small>
                    Periodo anterior {{$leads_count_by_status['contactados']['previus']}} <br>
                </small>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title">
                {{-- <span class="label label-default float-right" style="margin-right: -75px">Monthly</span> --}}
                <h5>Cotizados</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">
                    {{$leads_count_by_status['cotizados']['actual']}}
                </h1>
                @if ($leads_count_by_status['cotizados']['change'] > 0)
                    <span>
                        <div class="stat-percent font-bold text-success">
                            {{$leads_count_by_status['cotizados']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @elseif ($leads_count_by_status['cotizados']['change'] < 0)
                    <span>
                        <div class="stat-percent font-bold text-danger">
                            {{$leads_count_by_status['cotizados']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @endif
                <small>
                    Periodo anterior {{$leads_count_by_status['cotizados']['previus']}} <br>
                </small>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title">
                {{-- <span class="label label-default float-right" style="margin-right: -75px">Monthly</span> --}}
                <h5>Vendidos</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">
                    {{$leads_count_by_status['vendidos']['actual']}}
                </h1>
                @if ($leads_count_by_status['vendidos']['change'] > 0)
                    <span>
                        <div class="stat-percent font-bold text-success">
                            {{$leads_count_by_status['vendidos']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @elseif ($leads_count_by_status['vendidos']['change'] < 0)
                    <span>
                        <div class="stat-percent font-bold text-danger">
                            {{$leads_count_by_status['vendidos']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @endif
                <small>
                    Periodo anterior {{$leads_count_by_status['vendidos']['previus']}} <br>
                </small>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="ibox ">
            <div class="ibox-title">
                {{-- <span class="label label-default float-right" style="margin-right: -75px">Monthly</span> --}}
                <h5>Perdidos</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">
                    {{$leads_count_by_status['perdidos']['actual']}}
                </h1>
                @if ($leads_count_by_status['perdidos']['change'] > 0)
                    <span>
                        <div class="stat-percent font-bold text-success">
                            {{$leads_count_by_status['perdidos']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @elseif ($leads_count_by_status['perdidos']['change'] < 0)
                    <span>
                        <div class="stat-percent font-bold text-danger">
                            {{$leads_count_by_status['perdidos']['change']}} %<i class="fa fa-level-up"></i>
                        </div>
                    </span>
                @endif
                <small>
                    Periodo anterior {{$leads_count_by_status['perdidos']['previus']}} <br>
                </small>
            </div>
        </div>
    </div>
</div>