@extends('templates.template')

@section('title', 'Photos de l\'album')

@section('contenu')
    <h2>Photos de l'album</h2>

    @if(count($photos) === 0)
        <p>Aucune photo dans cet album.</p>
    @else
        <ul>
            @foreach ($photos as $photo)
                <li>
                    <strong>{{ $photo->titre }}</strong><br>
                    Note : {{ $photo->note }}<br>
                    <img src="{{ $photo->url }}" alt="{{ $photo->titre }}" width="200"><br>
                </li>
                <hr>
            @endforeach
        </ul>
    @endif
@endsection