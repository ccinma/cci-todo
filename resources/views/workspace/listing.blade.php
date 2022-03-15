@extends('layouts.components.containers._bigContainer')

<div>
    <ul>
        @if (count($workspaces) > 0)
            @foreach ($workspaces as $workspace)
                <li>
                    {{ $workspace->name }}
                </li>
            @endforeach
        @else
            <li>
                Aucune data disponible
            </li>
        @endif
    </ul>
</div>