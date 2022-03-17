@extends('layouts.components.containers._bigContainer')

@section('content')
    <h1>
        {{ $workspace->name }}
    </h1>

    <h2>Tableaux</h2>

    <div>
        @foreach ($workspace->boards as $board)
            <p>
                {{ $board->name }}
            </p>
        @endforeach
    </div>

@endsection