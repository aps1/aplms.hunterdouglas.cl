<div class="row">
    {{-- NAME --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="name">Nombre y apellido *</label>
            <input type="text" name="name" class="form-control" v-model="lead_to_edit.name" required>
        </div>
    </div>
    {{-- RUT --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="rut">Rut *</label>
            <input 
                type="text" 
                name="rut" 
                class="form-control" 
                v-model="lead_to_edit.rut"
                required
                pattern="\d{3,8}-[\d|kK]{1}"
                title="Debe ser un Rut válido"
            >
        </div>
    </div>
    
    {{-- EMAIL --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="email">Email *</label>
            <input type="email" name="email" class="form-control" v-model="lead_to_edit.email" required>
        </div>
    </div>
    {{-- PHONE --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="phone">Teléfono </label>
            <input 
                type="text" 
                name="phone" 
                class="form-control" 
                v-model="lead_to_edit.phone"
                required pattern="[0-9]{9}"
                title="9 dígitos sin espacios"
            >
        </div>
    </div>
</div>
<div class="row">
    {{-- REGION --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="region">Region</label>
            <select class="form-control" name="region" v-on:change="setCommunesForRegion" v-model="lead_to_edit.region">
                <option v-for="region in regions" :value="region.NombreRegion">@{{region.NombreRegion}}</option>
            </select>
        </div>
    </div>
    {{-- COMMUNE --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="commune">Comuna</label>
            <select class="form-control" name="commune" v-model="lead_to_edit.commune">
                <option v-for="commune in communes" :value="commune">@{{commune}}</option>
            </select>
        </div>
    </div>
    {{-- ADDRESS --}}
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="address">{{__('Dirección')}}</label>
            <input type="text" name="address" class="form-control" v-model="lead_to_edit.address">
        </div>
    </div>
</div>
