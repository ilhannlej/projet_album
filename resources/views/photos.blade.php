@extends('templates.template')

@section('contenu')

<h2>Toutes les photos</h2>

<!-- 🔎 FILTRES -->
<form method="GET" action="{{ url('/photos') }}" style="margin-bottom:20px;">

    <input type="text" name="search" placeholder="Rechercher une photo..."
           value="{{ request('search') }}">

    <select class="deroule" name="tag_id">
        <option value="">Filtrer par étiquette</option>
        @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                {{ $tag->nom }}
            </option>
        @endforeach
    </select>

    <select class="deroule" name="sort">
        <option value="">Trier par</option>

        <option value="titre_asc"  {{ request('sort') == 'titre_asc' ? 'selected' : '' }}>
            Titre (A → Z)
        </option>

        <option value="titre_desc" {{ request('sort') == 'titre_desc' ? 'selected' : '' }}>
            Titre (Z → A)
        </option>

        <option value="note_asc"   {{ request('sort') == 'note_asc' ? 'selected' : '' }}>
            Note (faible → forte)
        </option>

        <option value="note_desc"  {{ request('sort') == 'note_desc' ? 'selected' : '' }}>
            Note (forte → faible)
        </option>
    </select>

    <button class="bouton" type="submit">Filtrer</button>
</form>


<!-- 🖼️ AFFICHAGE DES PHOTOS -->
@if(count($photos) === 0)
    <p>Aucune photo trouvée.</p>
@else
    <div style="display:flex;flex-wrap:wrap;gap:20px;justify-content:center">
        @foreach ($photos as $photo)
            <div>
                <img src="{{ $photo->url }}"
                     alt="{{ $photo->titre }}"
                     width="200"
                     style="cursor:pointer;"
                     onclick="openLightbox('{{ $photo->url }}', {{ $photo->id }})">

                <p class="description-photos">
                    <strong>{{ $photo->titre }}</strong><br>
                    <small>Album : {{ $photo->album }}</small><br>
                    <small>Note : {{ $photo->note }}/5</small>
                </p>
            </div>
        @endforeach
    </div>
@endif



<!-- 🔦 LIGHTBOX -->
<div id="lightbox" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background:rgba(0,0,0,0.8); justify-content:center; align-items:center; z-index:9999;">
    
    <div style="position: relative;">

        <img id="lightbox-img" src=""
             style="max-width:90vw; max-height:90vh; border-radius:10px;">

        <form id="delete-form" method="POST" style="position:absolute; top:10px; right:10px;">
            @csrf
            <button type="submit"
               onclick="return confirm('Supprimer cette photo ?')"
               style="background:red; color:white; padding:10px 15px; border:none; border-radius:6px; cursor:pointer;">
               Supprimer
            </button>
        </form>

        <button onclick="closeLightbox()"
                style="position:absolute; top:10px; left:10px;
                       background:#444; color:white; padding:10px 15px; border:none;
                       border-radius:6px; cursor:pointer;">
            Fermer
        </button>
    </div>
</div>
<a class="add-photo" href="{{ url('/photos/add') }}">Ajouter une photo</a>

@endsection


@push('scripts')
<script>
function openLightbox(url, id) {
    document.getElementById('lightbox-img').src = url;
    document.getElementById('lightbox').style.display = 'flex';

    document.getElementById('delete-form').action = '/photos/' + id + '/delete';
}

function closeLightbox() {
    document.getElementById('lightbox-img').src = '';
    document.getElementById('lightbox').style.display = 'none';
}
</script>
@endpush
