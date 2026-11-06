<div class="table-responsive">
  <table class="table datatable">
      <thead style="display: none;">
          <tr>
              <th>Nombre</th>
          </tr>
      </thead>
      <tbody>
          @foreach ($kam->dealerships as $dealership)
          @if ($dealership->is_active)
                <tr>
                  <td>
                      <a href="{{ route('admin.dealerships.show', $dealership->id) }}" style="color:inherit">
                          {{$dealership->name}}
                      </a>
                  </td>
              </tr>
          @endif    
          @endforeach
      </tbody>
  </table>
</div>