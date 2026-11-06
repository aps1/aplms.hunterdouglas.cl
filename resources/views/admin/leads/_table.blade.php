<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Marca</th>
                <th>Distribuidor</th>
                <th>Origen</th>
                <th>Región</th>
                <th>Comuna</th>
                <th>Estatus</th>
                <th>Fecha</th>
                {{-- <th>Mensaje</th> --}}
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leads as $lead)
                <tr>
                    <td>
                        <a href="{{ route('admin.leads.show', $lead) }}" style="color:inherit">
                            {{$lead->web_identifier}}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.leads.show', $lead) }}" style="color:inherit">
                            {{$lead->name}}
                        </a>
                    </td>
                    <td>
                        {{$lead->email}}
                    </td>
                    <td>
                        {{$lead->brand}}
                    </td>
                    <td class="text-center">
                        @if ($lead->dealership_id)
                            <a href="{{ route('admin.dealerships.show', $lead->dealership_id) }}" style="color:inherit">
                                {{$lead->dealership->name}}
                            </a>
                        @else
                            @if (!$lead->isDerivable())
                                <i>No requiere</i>
                            @else
                                <div class="btn-group">
                                    <a href="#" 
                                        data-toggle="modal" data-target="#modal-derive" 
                                        data-lead-id="{{$lead->id}}"
                                        v-on:click="setLeadToDerive"
                                    class="btn-warning btn btn-xs">Derivar</a>
                                </div>
                            @endif
                        @endif
                        
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
                    <td data-order="{{$lead->created_at}}">{{$lead->created_at->format('d-m-Y H:i')}}</td>
                    {{-- <td>
                        {{$lead->message}}
                    </td> --}}
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{route('admin.leads.show', $lead)}}" class="btn-white btn btn-xs">Ver</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>