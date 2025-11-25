@extends('template')

@section('contenu')

<h2>Photos de l'album</h2>

<!-- Formulaire de filtrage -->
<form method="GET" action="{{ url('/albums/' . $album_id) }}" style="margin-bottom:20px;">

    <input 
        type="text"
        name="search"
        placeholder="Rechercher une photo..."
        value="{{ request('search') }}"
    >

    <select name="tag_id">
        <option value="">-- Filtrer par étiquette --</option>
        @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" 
                {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                {{ $tag->nom }}
            </option>
        @endforeach
    </select>

    <button type="submit">Filtrer</button>
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
                    onclick="openLightbox('{{ $photo->url }}')"
                    style="cursor:pointer;"
                >
                <p>{{ $photo->titre }}</p>
            </div>
        @endforeach
    </div>
@endif

<!-- Lightbox -->
<div id="lightbox" onclick="closeLightbox()" 
     style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);justify-content:center;align-items:center;">
    <img id="lightbox-img" style="max-width:90%;max-height:90%;">
</div>

@endsection

@push('scripts')
<script>
    function openLightbox(url) {
        document.getElementById('lightbox-img').src = url;
        document.getElementById('lightbox').style.display = 'flex';
    }
    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
    }
</script>
@endpush