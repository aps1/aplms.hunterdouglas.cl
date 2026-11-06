<div class="row">
    {{-- NAME --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="name">Nombre y apellido *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $lead->name ?? null) }}" required>
        </div>
    </div>
    {{-- EMAIL --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="email">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $lead->email ?? null) }}" required >
        </div>
    </div>
    {{-- PHONE --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="phone">Teléfono </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-addon">{{__('phoneCode')}}</span>
                </div>
                <input 
                    type="tel" 
                    name="phone" 
                    class="form-control" 
                    value="{{ old('phone', $lead->phone ?? null) }}"
                    pattern="[0-9]{9,}"
                    title="Por lo menos 9 dígitos sin espacios"
                >
            </div>
        </div>
    </div>
</div>
<div class="row" id="geo-selects">
    {{-- REGION --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="geo_loc1">{{__('Región')}}</label>
            <select id="regiones" class="form-control" v-model="region" v-on:change="filterGeo2" name="geo_loc1">
                <option value=""></option>
                <option v-for="region in geo1" :value="region">@{{region}}</option>
            </select>
        </div>
    </div>
    {{-- COMMUNE --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="commune">{{__('Comuna')}}</label>
            <select id="comunas" class="form-control" v-model="comuna" name="geo_loc2">
                <option value=""></option>
                <option v-for="comuna in geo2" :value="comuna">@{{comuna}}</option>
            </select>
        </div>
    </div>
    {{-- ADDRESS --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="address">{{__('Dirección')}}</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $lead->address ?? null) }}">
        </div>
    </div>
    {{-- PRODUCT CATEGORY --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="brand">{{__('Categoría de Producto')}}</label>
            <select class="form-control" name="product_category">
                @foreach ($product_categories as $index => $value)
                    <option value="{{$index}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
    {{-- BRAND --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="brand">Marca</label>
            <select class="form-control" name="brand">
                    <option value=""></option>
                @foreach ($brands as $index => $value)
                    <option value="{{$index}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
    {{-- Dealership --}}
    @if (Auth::user()->isAdmin())
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="dealership_id">Distribuidor</label>
                <select class="form-control dealership_id" name="dealership_id">
                    <option value=""></option>
                    @foreach ($dealerships as $dealership)
                        <option value="{{$dealership->id}}">{{$dealership->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    {{-- Para el kam el distribuidor es obligatorio --}}
    @if (Auth::user()->isKam())
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="dealership_id">Distribuidor *</label>
                <select class="form-control dealership_id" name="dealership_id" required>
                    <option value=""></option>
                    @foreach ($dealerships as $dealership)
                        <option value="{{$dealership->id}}">{{$dealership->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    {{-- Sellers --}}
    @if (Auth::user()->isExecutiveLevel())
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="dealership_id">Vendedores</label>
                <select class="form-control" name="user_id">
                    <option value=""></option>
                    @foreach ($sellers as $seller)
                        <option value="{{$seller->id}}">{{$seller->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    {{-- SOURCE --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="source">Fuente *</label>
            <select class="form-control" name="source" required>
                <option value=""></option>
                @foreach ($sources as $index => $value)
                    <option value="{{$index}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
    {{-- Message --}}
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="message">Mensaje</label>
            <textarea class="form-control" rows="5" name="message"></textarea>
        </div>
    </div>
</div>
@include('layouts._required')
