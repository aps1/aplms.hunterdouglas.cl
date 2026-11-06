<div class="row vertical-container" style="padding-bottom: 45px">
    <div class="col">
        <a href="#" title="Registrar actividad"
            data-toggle="modal" 
            data-target="#modal-add-activity"
        >
            <div class="vertical-timeline-icon bg-primary">
                <i class="fa fa-plus"></i>
            </div>
        </a>
    </div>
</div>
<div id="vertical-timeline" class="vertical-container dark-timeline">
    
    <div class="vertical-timeline-block">
        <div class="vertical-timeline-icon yellow-bg">
            <i class="fa fa-envelope-open"></i>
        </div>
        <div class="vertical-timeline-content">
            <strong>{{__('Registro')}}</strong>
            <p>
                <em>@lang('messages.leads.registro', ['origen' => $lead->origin_description])</em>
            </p>
            <h3>{{$lead->message}}</h3>
            @if ($lead->product)
                <p style="font-size: 17px;">
                    <em>{{__('Productos')}}</em>: <span class="text-danger">{{$lead->product}}</span>
                </p>
            @endif
            <span class="vertical-date">
                <em> ---  Realizada el </em>
                {{$lead->created_at->format('d-m-Y H:i')}}
                {{-- El nombre del user de la actividad de creacion si es manual --}}
                @if ($lead->isManual() && $lead->activities->first() )
                    <em>por {{$lead->activities->first()->user ? $lead->activities->first()->user->name : ''}}</em>
                @elseif(!Auth::user()->isKam())
                    <em>por el Prospecto</em>
                @endif
                
            </span>
        </div>
    </div>
    @foreach ($lead->activities as $activity)
        @if ($activity->type == 0)
            @continue
        @endif
            
        <div class="vertical-timeline-block">
            <div class="vertical-timeline-icon {{$activity->type_color}}-bg">
                <i class="fa {{ $activity->type_icon }}"></i>
            </div>
            <div class="vertical-timeline-content">
                <strong>{{ $activity->type_description }}</strong>
                <h3>{!!$activity->observation!!}</h3>
                @if ($activity->reason)
                    <p>
                        <strong>Motivo:</strong>
                        {{ $activity->reason_description }}
                    </p>
                @endif
                @if ($activity->getMedia()->count())
                    <p style="font-size: 17px;">
                        <span class="fa fa-file" title="Adjuntos"></span>
                        @foreach ($activity->media as $media)
                            @can('view', $media)
                                <a href="{{route('media.download', $media)}}">
                                    {{$media->name}}
                                </a>         
                            @else
                                {{$media->name}}    
                            @endcan
                        @endforeach
                    </p>
                @endif
                
                    
                <span class="vertical-date">
                    <em> ---  Realizada el </em>
                    {{$activity->created_at->format('d-m-Y H:i')}}
                    @if ($activity->user)
                        <em> por </em> {{$activity->user->name }}    
                    @endif
                </span>
                @if ($activity->previous_at)
                    <span class="pull-right">
                        <em> Tiempo respuesta</em>
                        <span class="text-danger">{{$activity->created_at->diffForHumans($activity->previous_at, false, false, 1)}}</span>
                    </span> 
                @endif
                
            </div>
        </div>
    @endforeach
        
</div>