<div class="ibox">
    <div class="ibox-title">
        <h5>@lang('Prospectos del') {{$user->type_description}}</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
    @if ($leads->count())
        <div class="table-responsive">
            <table class="table table-hover no-margins">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Marca</th>
                        <th>Origen</th>
                        <th>Estatus</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr>
                            <td>
                                @if (Auth::user()->isAdminLevel())
                                    <a href="{{ route('admin.leads.show', $lead) }}" style="color:inherit">
                                        {{$lead->web_identifier}}
                                    </a>
                                @else
                                    <a href="{{ route('seller.leads.show', $lead) }}" style="color:inherit">
                                        {{$lead->web_identifier}}
                                    </a>
                                @endif
                                
                            </td>
                            <td>
                                {{$lead->name}}
                            </td>
                            <td>{{$lead->email}}</td>
                            <td>{{$lead->brand}}</td>
                            <td>{{$lead->origin_description}}</td>
                            <td>
                                <span class="label label-{{$lead->status_color}}">
                                    {{$lead->status_description}}
                                </span>
                            </td>
                            
                            <td>{{$lead->date}}</td>
                            <td class="text-right">
                                <div class="btn-group">
                                    @if (Auth::user()->isAdminLevel())
                                        <a href="{{route('admin.leads.show', $lead)}}" class="btn-white btn btn-xs">Ver</a>
                                    @else
                                        <a href="{{route('seller.leads.show', $lead)}}" class="btn-white btn btn-xs">Ver</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach 
                </tbody>
            </table>
        </div>    
    @else
    <div class="text-center">
        <h3 class="font-bold">No hay ningúna prospecto para este {{$user->type_description}}</h3>
    </div>
    @endif
    </div>
</div>