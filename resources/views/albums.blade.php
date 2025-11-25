@extends('templates.template')

@section('contenu')
<h2>Liste des albums</h2>

@if(count($albums) === 0)
    <p>Aucun album trouvé.</p>
@else
    <ul>
        @foreach ($albums as $album)
            <li>
                <strong>{{ $album->titre }}</strong>
                <br>
                <a href="{{ url('/albums/' . $album->id) }}">Voir les photos</a>
            </li>
        @endforeach
    </ul>
@endif
@endsection