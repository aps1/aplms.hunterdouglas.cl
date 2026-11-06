<div class="ibox">
    <div class="ibox-title">
        <h5>Personal del Distribuidor</h5>
        @can('create', \App\User::class)
            <div class="ibox-tools">
                <button data-toggle="modal" data-target="#modal-add-user" class="btn btn-info btn-circle"><i class="fa fa-plus" title="Agregar personal"></i></button>
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        @endcan
    </div>
    <div class="ibox-content">
        @if ($users->count())
        <div class="table-responsive">
            <table class="table table-hover no-margins">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Celular</th>
                        <th>Cargo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('users.show', $user->id) }}" style="color:inherit">
                                    {{$user->name}}
                                </a> <br>
                                @if (!$user->is_active)
                                    <span class="label label-danger">Inactivo</span> 
                                @endif
                            </td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->cel}}</td>
                            <td>{{$user->type_description}}</td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <a href="{{route('users.show', $user->id)}}" class="btn-white btn btn-xs">
                                        Ver
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center">
            <h3 class="font-bold">No hay ningún personal registrado</h3>
            <div class="error-desc">
                <br/><a href="#" class="btn btn-light m-t" 
                    data-toggle="modal" data-target="#modal-add-user"
                >
                    Agregar personal
                </a>
            </div>
        </div>
        @endif
    </div>
</div>