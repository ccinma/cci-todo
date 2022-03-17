@extends("layouts.components.containers._bigContainer")

@section("content")
  <div id="welcome">
    <div class="content">
      <div class="description">
        <h3>Avec ToDo, gardez toujours un oeil sur l'avancéé de vos projets !</h3>
        <p>Rome ne s’est pas faite en un jour... Mais un ToDo aurait bien aidé ! Pensez vos projets en amont avec ToDo et collaborez seul ou en équipe, le tout, gratuitement !</p>
      </div>
      <a href="{{ route('register') }}">
        <div class="todo-btn">
          <p>Inscrivez-vous gratuitement</p>
        </div>
      </a>
    </div>

    <div class="image">
      <img src="{{asset("assets/images/dazzle-teamwork-two-men.png")}}" alt="Illustration">
    </div>
  </div>
@endsection