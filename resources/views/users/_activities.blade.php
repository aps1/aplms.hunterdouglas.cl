<div id="vertical-timeline" class="vertical-container dark-timeline">
        @foreach ($activities as $activity)
            <div class="vertical-timeline-block">
                <div class="vertical-timeline-icon {{$activity->type_color}}-bg">
                    <i class="fa {{ $activity->type_icon }}"></i>
                </div>
                <div class="vertical-timeline-content">
                    <strong>
                        {{ $activity->type_description }} 
                        ({{$activity->lead->name}})
                    </strong>
                    <h3>{{$activity->observation}}</h3>
                    <span class="vertical-date">
                        <em> --- Realizada el </em>
                        {{$activity->created_at->format('d-m-Y H:m')}}
                        <em> por </em>
                        {{$activity->user->name ?? __('el Prospecto')}}
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