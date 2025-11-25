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
    $photos = DB::select('SELECT * FROM photos WHERE album_id = ?', [$id]);

    return view('albums', [
        'photos' => $photos,
    ]);
}
}
