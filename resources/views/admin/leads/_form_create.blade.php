<div class="row">
    {{-- NAME --}}
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
            <label class="form-label" for="name">Nombre y apellido *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $lead->name ?? null) }}">
            @if($errors->has('name'))
                <span class="form-text m-b-none text-danger">{{$errors->first('name')}}</span>
            @endif
        </div>
    </div>
    {{-- RUT --}}
    <div class="col-md-3">
        <div class="form-group {{ $errors->has('rut') ? 'has-error' :'' }}">
            <label class="form-label" for="rut">Rut *</label>
            <input type="text" name="rut" class="form-control" value="{{ old('rut', $lead->rut ?? null) }}">
            @if($errors->has('rut'))
                <span class="form-text m-b-none text-danger">{{$errors->first('rut')}}</span>
            @endif
        </div>
    </div>
    {{-- EMAIL --}}
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('email') ? 'has-error' :'' }}">
            <label class="form-label" for="email">Email</label>
            <input type="text" name="email" class="form-control" value="{{ old('rut', $lead->email ?? null) }}">
            @if($errors->has('email'))
                <span class="form-text m-b-none text-danger">{{$errors->first('email')}}</span>
            @endif
        </div>
    </div>
    
</div>
<div class="row">
    {{-- REGION --}}
    <div class="col-md-3">
        <div class="form-group {{ $errors->has('region') ? 'has-error' :'' }}">
            <label class="form-label" for="region">Region *</label>
            <select id="regiones" class="form-control" name="region"></select>
            @if($errors->has('region'))
                <span class="form-text m-b-none text-danger">{{$errors->first('region')}}</span>
            @endif
        </div>
    </div>
    {{-- COMMUNE --}}
    <div class="col-md-3">
        <div class="form-group {{ $errors->has('commune') ? 'has-error' :'' }}">
            <label class="form-label" for="commune">Comuna *</label>
            <select id="comunas" class="form-control" name="commune"></select>
            @if($errors->has('commune'))
                <span class="form-text m-b-none text-danger">{{$errors->first('commune')}}</span>
            @endif
        </div>
    </div>
    {{-- ADDRESS --}}
    <div class="col-md-3">
        <div class="form-group {{ $errors->has('address') ? 'has-error' :'' }}">
            <label class="form-label" for="address">{{__('Dirección')}} *</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $lead->address ?? null) }}">
            @if($errors->has('address'))
                <span class="form-text m-b-none text-danger">{{$errors->first('address')}}</span>
            @endif
        </div>
    </div>
</div>
<div class="hr-line-dashed"></div>
<div class="row">
    <div class="col-md-12 text-right">
        <a type="button" class="btn btn-white m-r" href="{{route('admin.dealerships')}}">Cancelar</a>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</div>