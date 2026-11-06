<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover datatable">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>{{__('Celular')}}</th>
                <th>{{__('Cel. Whatsapp')}}</th>
                @if (Auth::user()->isAdminLevel())
                    <th>Distribuidor</th>
                @endif
                <th>Rol</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" style="color:inherit">
                            {{$user->name}}
                        </a>
                        <br>
                        @if (!$user->is_active)
                           <span class="label label-danger">Inactivo</span> 
                        @endif
                    </td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->cel}}</td>
                    <td>{{$user->cel_whatsapp}}</td>
                    @if (Auth::user()->isAdminLevel())
                        <td>
                            @if ($user->dealership)
                                <a href="{{ route('admin.dealerships.show', $user->dealership->id) }}" style="color:inherit">
                                    {{$user->dealership->name}}
                                </a>
                            @else
                                No Tiene
                            @endif
                            
                        </td>    
                    @endif
                    <td>{{$user->type_description}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{route('users.show', $user->id)}}" class="btn-white btn btn-xs">Ver</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>