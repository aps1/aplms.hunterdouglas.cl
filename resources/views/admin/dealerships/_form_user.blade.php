<div class="row">
    {{-- NAME --}}
    <div class="col-md">
        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
            <label class="form-label" for="name">Nombre y apellido *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? null) }}">
        </div>
    </div>
    {{-- EMAIL --}}
    <div class="col-md">
        <div class="form-group {{ $errors->has('email') ? 'has-error' :'' }}">
            <label class="form-label" for="email">Email *</label>
            <input type="text" name="email" class="form-control" value="{{ old('email', $user->email ?? null) }}">
        </div>
    </div>
</div>
<div class="row">
    {{-- Rut --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="rut">Rut *</label>
            <input type="text" name="rut" class="form-control">
        </div>
    </div>
    {{-- PASSWORD --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="password">Contraseña *</label>
            <input type="password" name="password" class="form-control">
        </div>
    </div>
</div>
<input type="hidden" name="dealership_id", value="{{$dealership->id}}">
<input type="hidden" name="branch_id", value="">