<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover datatable">
      <thead>
          <tr>
                <th>Nombre Web</th>
                <th>{{__('Región')}}</th>
                <th>{{__('Comuna')}}</th>
                <th>{{__('Dirección')}}</th>
                <th>KAM</th>
                <th></th>
          </tr>
      </thead>
      <tbody>
            @foreach ($dealerships as $dealership)
                <tr>
                    <td>
                        <a href="{{ route('admin.dealerships.show', $dealership->id) }}" style="color:inherit">
                            {{$dealership->name}}
                            <br>
                            @if (! $dealership->is_active)
                                <span class="small label label-danger ">Inactivo</span>
                            @endif
                        </a>
                    </td>
                    <td>{{$dealership->region}}</td>
                    <td>{{$dealership->commune}}</td>
                    <td>{{$dealership->address}}</td>
                    <td>{{optional($dealership->kam)->name}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{route('admin.dealerships.show', $dealership->id)}}" class="btn-white btn btn-xs">Ver</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
