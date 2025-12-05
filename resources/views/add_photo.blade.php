@extends('templates.template')

@section('contenu')

<link rel="stylesheet" href="{{ asset('css/add_photo.css') }}">

<div class="form-container">

<h2>Ajouter une photo à l'album</h2>

@if(session('success'))
    <p class="success-message">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p class="error-message">{{ session('error') }}</p>
@endif

<form action="{{ url('/albums/' . $album_id . '/add') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Titre :</label>
    <input type="text" name="titre" required>

    <h3>Ajouter via URL :</h3>
    <input type="text" name="url" placeholder="https://...">

    <h3>Ou téléverser une image :</h3>
    <input type="file" name="fichier" accept="image/*">

    <label>Étiquettes :</label>
    <select name="tags[]" multiple>
        @foreach ($tags as $tag)
            <option value="{{ $tag->id }}">{{ $tag->nom }}</option>
        @endforeach
    </select>

    <label>Note (0 à 5) :</label>
    <select name="note" required>
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>

    <button type="submit">Ajouter</button>

</form>

</div>
@endsection
