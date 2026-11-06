<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Origen</th>
                <th>{{__('Región')}}</th>
                <th>{{__('Comuna')}}</th>
                <th>Estatus</th>
                <th>Vendedor</th>
                <th>Fecha</th>
                {{-- <th>Mensaje</th> --}}
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leads as $lead)
                <tr>
                    <td>
                        <a href="{{ route('seller.leads.show', $lead) }}" style="color:inherit">
                            {{$lead->id}}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('seller.leads.show', $lead) }}" style="color:inherit">
                            @if ($lead->isStrategic())
                                <span title="{{__('Prospecto estratégico')}}" class="text-warning fa fa-star"></span>
                            @endif
                            {{$lead->name}}
                        </a>
                    </td>
                    <td>
                        <a href="{{'mailto:' . $lead->email}}" style="color:inherit">{{$lead->email}}</a>
                    </td>
                    <td>
                        {{$lead->origin_description}}
                    </td>
                    <td>
                        {{$lead->geo_loc1}}
                    </td>
                    <td>
                        {{$lead->geo_loc2}}
                    </td>
                    <td>
                        <span class="label label-{{$lead->status_color}}">
                            {{$lead->status_description}}
                        </span>
                    </td>
                    <td>{{optional($lead->user)->name}}</td>
                    <td data-order="{{$lead->created_at}}">{{$lead->created_at->format('d-m-Y H:i')}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{route('seller.leads.show', $lead)}}" class="btn-white btn btn-xs">Ver</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
