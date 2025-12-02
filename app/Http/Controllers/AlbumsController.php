<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlbumsController extends Controller
{
    public function index()
    {
        // Récupération des albums triés par date
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
        $sort = request()->query('sort', '');

        // Construction de la requête SQL
        $sql = "SELECT photos.*
                FROM photos
                LEFT JOIN possede_tag ON photos.id = possede_tag.photo_id
                WHERE photos.album_id = :album";

        $params = ['album' => $id];

        // 🔎 Filtre par recherche
        if ($search !== '') {
            $sql .= " AND photos.titre LIKE :search";
            $params['search'] = "%$search%";
        }

        // 🏷 Filtre par tag
        if ($tag_id !== '') {
            $sql .= " AND possede_tag.tag_id = :tag_id";
            $params['tag_id'] = $tag_id;
        }

        // GROUP BY obligatoire
        $sql .= " GROUP BY photos.id";

        // 🔽 TRI selon la valeur choisie
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
                // ordre par défaut → rien
                break;
        }

        // Exécution de la requête finale
        $photos = DB::select($sql, $params);

        return view('album', [
            'photos'   => $photos,
            'tags'     => $tags,
            'album_id' => $id
        ]);
    }

    public function addPhotoForm($album_id)
{
    // Renvoyer la liste des tags pour le formulaire
    $tags = DB::select('SELECT * FROM tags');

    return view('add_photo', [
        'album_id' => $album_id,
        'tags'     => $tags
    ]);
}

public function addPhoto(Request $request, $album_id)
{
    $titre = $request->input('titre');

    // Récupérer les tags sélectionnés (peut être absent)
    $tags = $request->input('tags', []); // tableau d'IDs ou empty array

    // 1️⃣ Ajout via URL (priorité)
    if ($request->filled('url')) {
        $url = $request->input('url');

        DB::insert(
            "INSERT INTO photos (titre, url, album_id) VALUES (:titre, :url, :album)",
            ['titre' => $titre, 'url' => $url, 'album' => $album_id]
        );

        // Récupérer l'id inséré
        $lastId = DB::getPdo()->lastInsertId();

        // Insérer les tags dans la table de liaison si fournis
        if (is_array($tags) && count($tags) > 0) {
            foreach ($tags as $tagId) {
                DB::insert(
                    "INSERT INTO possede_tag (photo_id, tag_id) VALUES (:photo_id, :tag_id)",
                    ['photo_id' => $lastId, 'tag_id' => $tagId]
                );
            }
        }

        return redirect('/albums/' . $album_id)->with('success', "Photo ajoutée via URL !");
    }

    // 2️⃣ Ajout via upload
    if ($request->hasFile('fichier')) {
        $file = $request->file('fichier');
        $filename = time() . '_' . $file->getClientOriginalName();

        // Stockage dans public/images
        $file->move(public_path('images'), $filename);

        $url = '/images/' . $filename;

        DB::insert(
            "INSERT INTO photos (titre, url, album_id) VALUES (:titre, :url, :album)",
            ['titre' => $titre, 'url' => $url, 'album' => $album_id]
        );

        // Récupérer l'id inséré
        $lastId = DB::getPdo()->lastInsertId();

        // Insérer les tags dans la table de liaison si fournis
        if (is_array($tags) && count($tags) > 0) {
            foreach ($tags as $tagId) {
                DB::insert(
                    "INSERT INTO possede_tag (photo_id, tag_id) VALUES (:photo_id, :tag_id)",
                    ['photo_id' => $lastId, 'tag_id' => $tagId]
                );
            }
        }

        return redirect('/albums/' . $album_id)->with('success', "Photo uploadée !");
    }

    return redirect()->back()->with('error', 'Aucune photo fournie.');
}

    public function deletePhoto(Request $request, $photo_id)
    {
        $rows = DB::select("SELECT * FROM photos WHERE id = ?", [$photo_id]);

        if (!$rows || count($rows) === 0) {
            return redirect('/albums')->with('error', 'Photo introuvable.');
        }

        $photo = $rows[0];
        $albumId = $photo->album_id ?? null;

        // Suppression du fichier uploaded
        if (!empty($photo->url) && str_starts_with($photo->url, '/images/')) {
            $filePath = public_path(ltrim($photo->url, '/'));
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        // Suppression des tags
        DB::delete("DELETE FROM possede_tag WHERE photo_id = ?", [$photo_id]);

        // Suppression de la photo
        DB::delete("DELETE FROM photos WHERE id = ?", [$photo_id]);

        return $albumId
            ? redirect('/albums/' . $albumId)->with('success', 'Photo supprimée avec succès.')
            : redirect('/albums')->with('success', 'Photo supprimée avec succès.');
    }
}
