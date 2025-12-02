@extends('templates.template')

@section('contenu')
<h2>Liste des albums</h2>

@if(empty($albums) || count($albums) === 0)
    <p>Aucun album trouvé.</p>

@else
<section class="albums-liste">
    <ul>
        @foreach ($albums as $album)
            <li>
                <strong>{{ $album->titre }}</strong>
                <br>
                <a id="voirlesphotos" href="{{ url('/albums/' . $album->id) }}">Voir les photos</a>
            </li>
        @endforeach
    </ul>
</section>
@endif
@endsection