<div class="row">
    {{-- NAME --}}
    <div class="col-md">
        <div class="form-group">
            <label class="form-label" for="name">Nombre Web *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $dealership->name ?? null) }}">
        </div>
    </div>
    {{-- Kam --}}
    <div class="col-md">
        <div class="form-group">
            <label class="form-label" for="kam_id">{{__('KAM')}}</label>
            <select class="form-control"  name="kam_id">
                <option value=""></option>
                @foreach ($kams as $kam)
                    <option value="{{ $kam->id }}" {{ $kam->id == $dealership->kam_id ? 'selected' : '' }}>{{$kam->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row" id="geo-selects">
    {{-- REGION --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="region">{{__('Región')}}</label>
            <select id="regiones" class="form-control" v-model="region" v-on:change="filterGeo2" name="region">
                <option value=""></option>
                <option v-for="region in geo1" :value="region">@{{region}}</option>
            </select>
        </div>
    </div>
    {{-- COMMUNE --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="commune">{{__('Comuna')}}</label>
            <select id="comunas" class="form-control" v-model="comuna" name="commune">
                <option value=""></option>
                <option v-for="comuna in geo2" :value="comuna">@{{comuna}}</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    {{-- ADDRESS --}}
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="address">{{__('Dirección')}}</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $dealership->address ?? null) }}">
        </div>
    </div>
</div>

{{-- AP solo tienen HunterDouglas como marca --}}
<input type="hidden" value="1" name="wcp1"/>
<input type="hidden" value="0" name="wcp2"/>

