@extends('layouts.auth')

@section('content')

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>
            <img src="{{asset('img/logo-hunter.png')}}" alt="Logo HunterDouglas"  class="w-75">
        </div>
        <form class="m-t" role="form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required="" autofocus>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" style="display: block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <input name="password" type="password" class="form-control" placeholder="Contraseña" required="">
            
                @if ($errors->has('password'))
                    <span class="invalid-feedback" style="display: block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            {{-- <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                    </label>
                </div>
            </div> --}}

            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

            <a style="color:white" href="{{ route('password.request') }}">Recuperar contraseña</a>
            
        </form>
    </div>
</div>


@endsection
