<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AlbumsController extends Controller
{
    public function index()
    {
        // Récupération des albums triés par date (plus récent en premier)
        $albums = DB::select('SELECT * FROM albums ORDER BY creation DESC');

        return view('albums', [
            'albums' => $albums,
        ]);
    }

public function show($id)
{
    // Récupérer tous les tags pour l'affichage du filtre
    $tags = DB::select('SELECT * FROM tags');

    // Récupérer les filtres si présents
    $search = request()->query('search', '');
    $tag_id = request()->query('tag_id', '');

    // Construire la requête SQL
    $sql = "SELECT photos.* 
            FROM photos
            LEFT JOIN possede_tag ON photos.id = possede_tag.photo_id
            WHERE photos.album_id = :album";

    $params = ['album' => $id];

    // Filtre titre
    if ($search !== '') {
        $sql .= " AND photos.titre LIKE :search";
        $params['search'] = "%$search%";
    }

    // Filtre tag
    if ($tag_id !== '') {
        $sql .= " AND possede_tag.tag_id = :tag_id";
        $params['tag_id'] = $tag_id;
    }

    $sql .= " GROUP BY photos.id";

    $photos = DB::select($sql, $params);

        return view('album', [
    'photos' => $photos,
    'tags'   => $tags,
    'album_id' => $id
    ]);
}
}
