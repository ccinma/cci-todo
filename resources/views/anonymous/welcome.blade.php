@extends("layouts.components.containers._bigCenteredContainer")

@section("content")
  <div id="welcome">
    <div class="content">
      <div class="description">
        <h3>Avec ToDo, gardez toujours un oeil sur l'avancé de vos projets&nbsp;!</h3>
        <p>Rome ne s’est pas faite en un jour... Mais un ToDo aurait bien aidé&nbsp;! Pensez vos projets en amont avec ToDo et collaborez seul ou en équipe, le tout, gratuitement&nbsp;!</p>
      </div>
      <div class="register-btn">
        <a href="{{ route('register') }}">
          <button class="todo-btn">
            Inscrivez-vous gratuitement
          </button>
        </a>
      </div>
    </div>

    <div class="image">
      <img src="{{asset("assets/images/dazzle-teamwork-two-men.png")}}" alt="Illustration">
    </div>
  </div>
@endsection