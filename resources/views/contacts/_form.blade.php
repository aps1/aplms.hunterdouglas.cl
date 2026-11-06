<div class="row">
    @isset($dealerships)
        {{-- Dealership --}}
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="dealership_id">Distribuidor * </label>
                <select data-placeholder="Seleccione Distribuidor" id="dealership" name="dealership_id" class="chosen-select">
                    <option value=""></option>
                    @foreach ($dealerships as $dealership)
                        <option value="{{$dealership->id}}">{{$dealership->name}}</option> 
                    @endforeach
                </select>    
            </div>
        </div>    
    @endisset
    {{-- Type --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="type">Tipo * </label>
            <select name="type" class="form-control">
                @foreach ($contact_types as $index => $value) 
                    <option value="{{$index}}" {{(isset($contact) && $contact->type == $index) ? 'selected' : ''}}>
                        {{$value}}
                    </option> 
                @endforeach
            </select>    
        </div>
    </div>
    {{-- Source --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="source">Fuente </label>
            <select name="source" class="form-control">
                <option value=""></option>
                @foreach ($contact_sources as $index => $value) 
                    <option value="{{$index}}" {{(isset($contact) && $contact->source == $index) ? 'selected' : ''}}>{{$value}}</option>    
                @endforeach
            </select>    
        </div>
    </div>
</div>
<hr>
<div class="row">
    {{-- NAME --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="name">Nombre y apellido *</label>
            <input type="text" name="name" value="{{ old('name', $contact->name ?? null) }}" class="form-control" required>
        </div>
    </div>
    {{-- EMAIL --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="email">Email *</label>
            <input type="text" name="email" value="{{ old('email', $contact->email ?? null) }}" class="form-control" required>
        </div>
    </div>
    {{-- PHONE1 --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="name">Teléfono 1</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-addon">{{__('phoneCode')}}</span>
                </div>
                <input 
                    type="tel" 
                    name="phone" 
                    class="form-control" 
                    value="{{ old('phone', $contact->phone_to_edit ?? null) }}"
                    pattern="[0-9]{9,}"
                    title="Por lo menos 9 dígitos sin espacios"
                >
            </div>
            
        </div>
    </div>
</div>
<div class="row">
    {{-- PHONE2 --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="name">Teléfono 2</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-addon">{{__('phoneCode')}}</span>
                </div>
                <input 
                    type="tel" 
                    name="phone2" 
                    class="form-control" 
                    value="{{ old('phone2', $contact->phone2_to_edit ?? null) }}"
                    pattern="[0-9]{9,}"
                    title="Por lo menos 9 dígitos sin espacios"
                >
            </div>
        </div>
    </div>
    {{-- DESCRIPTION --}}
    <div class="col-md-8">
        <div class="form-group">
            <label class="form-label" for="description">Descripción</label>
            <input type="text" name="description" value="{{ old('description', $contact->description ?? null) }}" class="form-control">
        </div>
    </div>
</div>
<hr>
<div class="row">
    {{-- REGION --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="geo_loc1">{{__('Región')}}</label>
            <select data-placeholder="Seleccione {{__('Región')}}" id="regiones" class="form-control" name="geo_loc1"></select>
        </div>
    </div>
    {{-- COMMUNE --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="geo_loc2">{{__('Comuna')}}</label>
            <select data-placeholder="Seleccione {{__('Comuna')}}" id="comunas" class="form-control" name="geo_loc2"></select>
        </div>
    </div>
    {{-- ADDRESS --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="address">{{__('Dirección')}}</label>
            <input type="text" value="{{ old('address', $contact->address ?? null) }}" name="address" class="form-control">
        </div>
    </div>
</div>
@include('layouts._required')