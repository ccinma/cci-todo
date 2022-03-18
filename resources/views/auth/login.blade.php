@extends('layouts.components.containers._smallContainer')

@section('content')
<div id="login">
    <h3>Bon retour parmis nous !</h3>
    <div class="hr"></div>

    @include("layouts.components.svg.small_logo")

    <div class="form">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="email">
                <input class="todo-form" placeholder="Email" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="password">
                <input class="todo-form" placeholder="Mot de passe" id="password" type="password" name="password" required autocomplete="current-password">

                @error('password')
                    <span role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                @if (Route::has('password.request'))
                    <a class="forget" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié ?') }}
                    </a>
                @endif
            </div>

            <div class="remember">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label for="remember">
                    {{ __('Se souvenir de moi') }}
                </label>
            </div>

            <div>
                <button type="submit" class="todo-btn-round">
                    Se connecter
                </button>
            </div>
        </form>
    </div>
    <div class="hr"></div>
    <a class="register" href="{{ route('register') }}">Pas encore inscrit ? C'est par ici !</a>
</div>
@endsection
