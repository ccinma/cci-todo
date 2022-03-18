@extends('layouts.components.containers._smallContainer')

@section('content')
<div id="register">
    <h3>Ravis de vous accueillir parmis nous</h3>
    <div class="hr"></div>
    @include("layouts.components.svg.small_logo")

    <div class="form">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="email">
                <input class="todo-form" placeholder="Identifiant" id="name" type="text" @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                    <span role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div>
                <input class="todo-form" placeholder="Email" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                    <span role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div>
                <input class="todo-form" placeholder="Mot de passe" id="password" type="password" name="password" required autocomplete="new-password">

                @error('password')
                    <span role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div>
                <input class="todo-form" placeholder="Confirmer le mot de passe" id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div>
                <button class="todo-btn-round" type="submit">
                    S'inscrire
                </button>
            </div>
        </form>
    </div>
    <div class="hr"></div>
    <a class="login" href="{{ route('login') }}">Vous avez déjà un compte ? C'est par ici !</a>
</div>
@endsection
