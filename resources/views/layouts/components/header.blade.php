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
                <a href="#" onclick="document.getElementById('logout-form').submit();">Deconnexion</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">                                 @csrf                             </form>
            @else
                <a href="{{ route('login') }}">Connexion</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Inscription</a>
                @endif
            @endauth
        </div>
    @endif
</header>