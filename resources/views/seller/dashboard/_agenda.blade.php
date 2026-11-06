<div class="ibox">
    <div class="ibox-title">
        <h5>Agenda</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    @if ($events->count())
        <div class="ibox-content ibox-heading">
            <h4>{{$events->first()->title}}</h4>
            <small>{{$events->first()->description}}</small>
            <p>{{$events->first()->start->format('d/m/Y h:i:s A')}}</p>
        </div>
        <div class="ibox-content inspinia-timeline">
            @foreach ($events as $event)
                @if ($loop->first)
                    
                @else
                <div class="timeline-item">
                    <div class="row">
                        <div class="col-4 date">
                            <i class="fa fa-calendar"></i>
                            <span>{{$event->start->format('d/m/Y h:i:s A')}}</span>
                            <br>
                        </div>
                        <div class="col content no-top-border">
                            <p class="m-b-xs"><strong>{{$event->title}}</strong></p>
                            <p>{{$event->description}}</p>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>  
    @else
        <div class="ibox-content inspinia-timeline">
            <h4>No hay nada agendado</h4>
        </div>  
    @endif
    
</div>