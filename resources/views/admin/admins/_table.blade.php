<div class="table-responsive">
  <table class="table table-striped table-bordered table-hover datatable">
      <thead>
          <tr>
              <th>Nombre</th>
              <th>Email</th>
              <th>{{__('Celular')}}</th>
              <th>{{__('Cel. Whatsapp')}}</th>
              <th>Fecha de Registro</th>
              <th></th>
          </tr>
      </thead>
      <tbody>
          @foreach ($admins as $admin)
              <tr>
                  <td>
                      <a href="{{ route('admin.admins.show', $admin->id) }}" style="color:inherit">
                          {{$admin->name}}
                      </a>
                      <br>
                      @if (!$admin->is_active)
                         <span class="label label-plain">Inactivo</span> 
                      @endif
                  </td>
                  <td>{{$admin->email}}</td>
                  <td>{{$admin->cel}}</td>
                  <td>{{$admin->cel_whatsapp}}</td>
                  <td>{{$admin->created_at->format('d/m/Y')}}</td>
                  <td class="text-center">
                      <div class="btn-group">
                          <a href="{{route('admin.admins.show', $admin->id)}}" class="btn-white btn btn-xs">Ver</a>
                          <button class="btn-white btn btn-xs"
                              data-toggle="modal" data-target="#modal-edit-admin" 
                              data-admin-id="{{$admin->id}}"
                              v-on:click="setAdminToEdit">
                              Editar
                          </button>
                      </div>
                  </td>
              </tr>
          @endforeach
      </tbody>
  </table>
</div>