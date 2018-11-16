<?php

namespace App\Http\Controllers;

use App\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function getToplist() {

        $topList = DB::table('playlists')
                        ->select('movie_id', DB::raw('COUNT(movie_id) AS `amount`'))
                        ->groupBy('movie_id')
                        ->orderBy('amount', 'desc')
                        ->take(10)
                        ->get();

        return response()->json($topList);
    }

    public function getUserId() {
        return auth()->id();
    }
}
