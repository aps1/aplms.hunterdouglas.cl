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
          @foreach ($kams as $kam)
              <tr>
                  <td>
                      <a href="{{ route('admin.kams.show', $kam->id) }}" style="color:inherit">
                          {{$kam->name}}
                      </a>
                      @if (!$kam->is_active)
                         <span class="label label-plain">Inactivo</span> 
                      @endif
                  </td>
                  <td>{{$kam->email}}</td>
                  <td>{{$kam->cel}}</td>
                  <td>{{$kam->cel_whatsapp}}</td>
                  <td>{{$kam->created_at->format('d/m/Y')}}</td>
                  <td class="text-center">
                      <div class="btn-group">
                          <a href="{{route('admin.kams.show', $kam->id)}}" class="btn-white btn btn-xs">Ver</a>
                          <button class="btn-white btn btn-xs"
                              data-toggle="modal" data-target="#modal-edit-kam" 
                              data-kam-id="{{$kam->id}}"
                              v-on:click="setKamToEdit">
                              Editar
                          </button>
                      </div>
                  </td>
              </tr>
          @endforeach
      </tbody>
  </table>
</div>