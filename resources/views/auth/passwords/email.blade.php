@extends('layouts.components.containers._smallContainer')

@section('content')
<div id="email">
    <h3>Mot de passe oublié</h3>
    <div class="hr"></div>

    <div class='svg-container'>
        @include("layouts.components.svg.small_logo")
    </div>
    
    <div class="todo-form-container">
        @if (session('status'))
            <div role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form class="todo-form" method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="todo-form-group first">
                <input class="todo-form-text" placeholder="Email" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="todo-form-group">
                <button type="submit" class="todo-btn-round">
                    Envoyer le mail
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
