@extends('templates.template')

@section('contenu')

<h2>Photos de l'album</h2>

<!-- Formulaire de filtrage -->
<form method="GET" action="{{ url('/albums/' . $album_id) }}" style="margin-bottom:20px;">

    <input type="text" name="search" placeholder="Rechercher une photo..." value="{{ request('search') }}">

    <select class="deroule" name="tag_id">
        <option value="">Filtrer par étiquette</option>
        @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" 
                {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                {{ $tag->nom }}
            </option>
        @endforeach
    </select>

    <!-- ▼ NOUVEAU ▼ -->
    <select class="deroule" name="sort">
        <option value="">Trier par</option>
        <option value="titre_asc"  {{ request('sort') == 'titre_asc' ? 'selected' : '' }}>Titre (A → Z)</option>
        <option value="titre_desc" {{ request('sort') == 'titre_desc' ? 'selected' : '' }}>Titre (Z → A)</option>
        <option value="note_asc"   {{ request('sort') == 'note_asc' ? 'selected' : '' }}>Note (faible → forte)</option>
        <option value="note_desc"  {{ request('sort') == 'note_desc' ? 'selected' : '' }}>Note (forte → faible)</option>
    </select>
    <!-- ▲ NOUVEAU ▲ -->

    <button class="bouton" type="submit">Filtrer</button>
</form>

<!-- Affichage des photos -->
@if(count($photos) === 0)
    <p>Aucune photo ne correspond au filtre.</p>
@else
    <div style="display:flex;flex-wrap:wrap;gap:20px;">
        @foreach ($photos as $photo)
            <div>
                <img 
                    src="{{ $photo->url }}" 
                    alt="{{ $photo->titre }}"
                    width="200"
                    onclick="openLightbox('{{ $photo->url }}', {{ $photo->id }})"
                    style="cursor:pointer;"
                >
                <p>{{ $photo->titre }}</p>
            </div>
        @endforeach
    </div>
@endif

<!-- Lightbox améliorée avec formulaire de suppression -->
<div id="lightbox" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
     background:rgba(0,0,0,0.8); justify-content:center; align-items:center; z-index:9999;">
    
    <div style="position: relative;">

        <!-- IMAGE -->
        <img id="lightbox-img" src="" style="max-width:90vw; max-height:90vh; border-radius:10px;">

        <!-- FORMULAIRE SUPPRIMER (POST + CSRF) -->
        <form id="delete-form" method="POST" style="position:absolute; top:10px; right:10px;">
            @csrf
            <button id="delete-button" type="submit"
               onclick="return confirm('Supprimer cette photo ?')"
               style="background:red; color:white; padding:10px 15px; text-decoration:none; border-radius:6px; border:none; cursor:pointer;">
               Supprimer
            </button>
        </form>

        <!-- BOUTON FERMER -->
        <button onclick="closeLightbox()"
                style="position:absolute; top:10px; left:10px;
                       background:#444; color:white; padding:10px 15px; border:none;
                       border-radius:6px; cursor:pointer;">
            Fermer
        </button>
    </div>
</div>

<a class="bouton" href="{{ url('/albums/' . $album_id . '/add') }}">Ajouter une photo</a>

@endsection

@push('scripts')
<script>
function openLightbox(url, id) {
    document.getElementById('lightbox-img').src = url;
    document.getElementById('lightbox').style.display = 'flex';

    // Met à jour l'action du formulaire de suppression
    var form = document.getElementById('delete-form');
    form.action = '/photos/' + id + '/delete';  // <--- indispensable
}

function closeLightbox() {
    document.getElementById('lightbox-img').src = '';
    document.getElementById('lightbox').style.display = 'none';
}
</script>
@endpush