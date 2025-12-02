<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhotosController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $tag_id = $request->query('tag_id', '');
        $sort   = $request->query('sort', '');

        // Récupérer tous les tags (pour le filtre)
        $tags = DB::select("SELECT * FROM tags");

        // Construction de la requête SQL
        $sql = "SELECT photos.*, albums.titre AS album
                FROM photos
                LEFT JOIN albums ON photos.album_id = albums.id
                LEFT JOIN possede_tag ON photos.id = possede_tag.photo_id
                WHERE 1";

        $params = [];

        // Recherche par titre
        if ($search !== '') {
            $sql .= " AND photos.titre LIKE :search";
            $params['search'] = "%$search%";
        }

        // Filtre par tag
        if ($tag_id !== '') {
            $sql .= " AND possede_tag.tag_id = :tag_id";
            $params['tag_id'] = $tag_id;
        }

        $sql .= " GROUP BY photos.id";

        // Tri
        switch ($sort) {
            case 'titre_asc':
                $sql .= " ORDER BY photos.titre ASC";
                break;

            case 'titre_desc':
                $sql .= " ORDER BY photos.titre DESC";
                break;

            case 'note_asc':
                $sql .= " ORDER BY photos.note ASC";
                break;

            case 'note_desc':
                $sql .= " ORDER BY photos.note DESC";
                break;

            default:
                $sql .= " ORDER BY photos.id DESC";
        }

        $photos = DB::select($sql, $params);

        return view('photos', [
            'photos' => $photos,
            'tags'   => $tags
        ]);
    }
    public function addPhotoForm()
{
    $albums = DB::select("SELECT * FROM albums ORDER BY titre ASC");
    $tags   = DB::select("SELECT * FROM tags ORDER BY nom ASC");

    return view('add_photo_global', [
        'albums' => $albums,
        'tags'   => $tags
    ]);
}

public function addPhoto(Request $request)
{
    $album_id = $request->input('album_id');
    $titre    = $request->input('titre');
    $note     = $request->input('note');
    $tags     = $request->input('tags', []);

    // -----------------------
    // 1️⃣ Ajout via URL
    // -----------------------
    if ($request->filled('url')) {
        $url = $request->input('url');

        DB::insert(
            "INSERT INTO photos (titre, url, album_id, note)
             VALUES (:titre, :url, :album_id, :note)",
            ['titre' => $titre, 'url' => $url, 'album_id' => $album_id, 'note' => $note]
        );

        $photo_id = DB::getPdo()->lastInsertId();

        foreach ($tags as $t) {
            DB::insert("INSERT INTO possede_tag (photo_id, tag_id) VALUES (?, ?)", [$photo_id, $t]);
        }

        return redirect('/photos')->with('success', "Photo ajoutée !");
    }

    // -----------------------
    // 2️⃣ Ajout via upload
    // -----------------------
    if ($request->hasFile('fichier')) {

        $file = $request->file('fichier');
        $filename = time() . "_" . $file->getClientOriginalName();
        $file->move(public_path('images'), $filename);
        $url = "/images/" . $filename;

        DB::insert(
            "INSERT INTO photos (titre, url, album_id, note)
             VALUES (:titre, :url, :album_id, :note)",
            ['titre' => $titre, 'url' => $url, 'album_id' => $album_id, 'note' => $note]
        );

        $photo_id = DB::getPdo()->lastInsertId();

        foreach ($tags as $t) {
            DB::insert("INSERT INTO possede_tag (photo_id, tag_id) VALUES (?, ?)", [$photo_id, $t]);
        }

        return redirect('/photos')->with('success', "Photo uploadée !");
    }

    return redirect()->back()->with('error', "Aucune photo fournie.");
}
}
