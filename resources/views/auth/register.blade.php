@extends('layouts.components.containers._smallContainer')

@section('content')
<div id="register">
    <h3>Ravis de vous accueillir parmis nous</h3>
    <div class="hr"></div>

    <div class='svg-container'>
        @include("layouts.components.svg.small_logo")
    </div>

    <div class="todo-form-container">
        <form class="todo-form" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="todo-form-group first">
                <input class="todo-form-text" placeholder="Identifiant" id="name" type="text" @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                    <span role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="todo-form-group">
                <input class="todo-form-text" placeholder="Email" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                    <span role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="todo-form-group">
                <input class="todo-form-text" placeholder="Mot de passe" id="password" type="password" name="password" required autocomplete="new-password">

                @error('password')
                    <span role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="todo-form-group">
                <input class="todo-form-text" placeholder="Confirmer le mot de passe" id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="todo-form-group">
                <button class="todo-btn-round" type="submit">
                    S'inscrire
                </button>
            </div>
        </form>
    </div>
    <div class="hr"></div>

    <div class="link-container">
        <a class="login" href="{{ route('login') }}">Vous avez déjà un compte ? C'est par ici !</a>
    </div>
</div>
@endsection
