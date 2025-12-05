@extends('templates.template')

@section('contenu')
<h2>Liste des albums</h2>

<!-- 🔽 Formulaire de tri des albums -->
<form method="GET" action="{{ url('/albums') }}" style="margin-bottom:20px;">

    <select name="sort">
        <option value="">-- Trier par --</option>

        <option value="titre_asc"  {{ request('sort') == 'titre_asc' ? 'selected' : '' }}>
            Titre (A → Z)
        </option>

        <option value="titre_desc" {{ request('sort') == 'titre_desc' ? 'selected' : '' }}>
            Titre (Z → A)
        </option>

        <option value="date_asc"  {{ request('sort') == 'date_asc' ? 'selected' : '' }}>
            Date (ancien → récent)
        </option>

        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>
            Date (récent → ancien)
        </option>
    </select>

    <button type="submit">Trier</button>
</form>
<!-- 🔼 Fin formulaire de tri -->


@if(empty($albums) || count($albums) === 0)
    <p>Aucun album trouvé.</p>

@else
<section class="albums-liste">
    <ul>
        @foreach ($albums as $album)
            <li>
                <strong>{{ $album->titre }}</strong><br>
                <a id="voirlesphotos" href="{{ url('/albums/' . $album->id) }}">
                    Voir les photos
                </a>
            </li>
        @endforeach
    </ul>
</section>
@endif

@endsection