<?php

namespace App\Http\Controllers;

use App\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    public function getPlaylist() {

        $user_id = $this->getUserId();

        $playlist = Playlist::where('user_id', $user_id)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return $playlist;
    }

    public function addToPlaylist(Request $request) {

        $user_id = $this->getUserId();
        $movie_id = $request->query('movieId');

        $playlist = New Playlist;
        $playlist->movie_id = $movie_id;
        $playlist->user_id = $user_id;
        $playlist->save();

        return "Saved!";
    }

    public function getMostPopular() {
        //
    }

    public function getUserId() {
        return auth()->id();
    }
}
