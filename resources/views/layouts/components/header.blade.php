<header id="header">
    @if (Route::has('login'))

        <a href="{{ url('/home') }}">
            <div class="logo">
                @include("layouts.components.svg.logo")
            </div>
        </a>

        <div class="nav">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif
</header>