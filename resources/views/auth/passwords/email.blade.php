@extends('layouts.auth')

@section('content')
<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>
            <img src="{{asset('img/logo-hunter.png')}}" alt="Logo HunterDouglas"  class="w-75">
        </div>
        @if (session('status'))
            <div class="alert alert-warning" style="margin-top: 15px;">
                {{ session('status') }}
            </div>
        @else
        <form class="m-t" role="form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Enviar correo de recuperación</button>
        </form>
        @endif
        <a style="color:white" href="{{ route('login') }}">Regresar a iniciar sesión</a>
    </div>
</div>
@endsection

    
