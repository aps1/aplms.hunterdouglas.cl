<div class="ibox">
    <div class="ibox-title">
        <h5>@lang('Prospectos del') contacto</h5>
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
                                <a 
                                    href="{{ Auth::user()->isAdminLevel() ? route('admin.leads.show', $lead) : route('seller.leads.show', $lead) }}" 
                                    style="color:inherit">
                                    {{$lead->web_identifier}}
                                </a>
                            </td>
                            <td>
                                <a 
                                    href="{{ Auth::user()->isAdminLevel() ? route('admin.leads.show', $lead) : route('seller.leads.show', $lead) }}" 
                                    style="color:inherit">
                                    {{$lead->name}}
                                </a>
                            </td>
                            <td>{{$lead->email}}</td>
                            <td>{{$lead->brand}}</td>
                            <td>{{$lead->origin_description}}</td>
                            <td>
                                <span class="label label-{{$lead->status_color}}">
                                    {{$lead->status_description}}
                                </span>
                            </td>
                            
                            <td>{{$lead->created_at}}</td>
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
        <h3 class="font-bold">No hay ningún prospecto para este contacto</h3>
    </div>
    @endif
    </div>
</div>