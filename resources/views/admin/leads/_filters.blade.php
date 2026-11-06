<div class="row justify-content-around mb-3">
    @if (Auth::user()->isAdmin())
        <div class="col-md-auto">
            <select data-placeholder="Todos los Kams" id="kam_id" class="chosen-select">
                <option value="">Todos los Kams</option>
                @foreach ($kams as $kam)
                <option value="{{$kam->id}}">{{$kam->name}}</option>
                @endforeach
            </select>
        </div>    
    @endif
    
    <div class="col-md-auto">
        <select data-placeholder="Todos los distribuidores" id="dealership_id" class="chosen-select">
            <option value="">Todos los Distribuidores</option>
            @foreach ($dealerships as $dealer)
            <option value="{{$dealer->id}}">{{$dealer->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-auto">
        <select data-placeholder="Todos los origines" id="origin" class="chosen-select">
            <option value="">Todos los origenes</option>
            @foreach ($origins as $key => $value)
            <option value="{{$key}}">{{$value}}</option>    
            @endforeach
        </select>
    </div>
    <div class="col-md-auto">
        <select data-placeholder="Todas las categorías" id="product_category" class="chosen-select">
            <option value="">Todas las categorias</option>
            @foreach ($product_categories as $key => $value)
                <option value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-auto">
        <select data-placeholder="Todos los estatus" id="status" class="chosen-select">
            <option value="">Todos los Estatus</option>
            @foreach ($states as $key => $value)
            <option value="{{$key}}">{{$value}}</option>    
            @endforeach
        </select>
    </div>
    <div class="col-md-auto">
        <div id="reportrange" class="form-control">
            <i class="fa fa-calendar"></i>
            <span><b class="caret"></b></span>
        </div>
    </div>
    <input type="hidden" id="range_from" name="range_from">
    <input type="hidden" id="range_to" name="range_to">
    <div class="col-md-auto text-right">
        <a href="{{route('admin.leads')}}" class="btn btn-default" title="Limpiar filtros"><i class="fa fa-rotate-left"></i></a>     
    </div>
</div>
