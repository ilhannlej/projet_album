@extends('templates.template')

@section('contenu')

<h2>Ajouter une photo</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<form action="{{ url('/photos/add') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Album :</label><br>
    <select name="album_id" required>
        @foreach ($albums as $album)
            <option value="{{ $album->id }}">{{ $album->titre }}</option>
        @endforeach
    </select>
    <br><br>

    <label>Titre :</label><br>
    <input type="text" name="titre" required><br><br>

    <h3>Ajouter via URL :</h3>
    <input type="text" name="url" placeholder="https://..."><br><br>

    <h3>Ou téléverser une image :</h3>
    <input type="file" name="fichier" accept="image/*"><br><br>

    <label>Étiquettes :</label><br>
    <select name="tags[]" multiple>
        @foreach ($tags as $tag)
            <option value="{{ $tag->id }}">{{ $tag->nom }}</option>
        @endforeach
    </select>
    <br><br>

    <label>Note (0 à 5) :</label><br>
    <select name="note" required>
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    <br><br>

    <button type="submit">Ajouter</button>
</form>

@endsection
