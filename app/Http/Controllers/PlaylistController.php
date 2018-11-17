<?php

namespace App\Http\Controllers;

use App\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    private $playlist;

    public function __construct(Playlist $playlist)
    {
        $this->playlist = $playlist;
    }

    public function getPlaylist()
    {
        $playlist = Playlist::where('user_id', $this->getUserId())
            ->latest()
            ->get();

        return $playlist;
    }

    public function addToPlaylist(Request $request)
    {
        $user_id = $this->getUserId();
        $movie_id = $request->movieId;

        $this->playlist->movie_id = $movie_id;
        $this->playlist->user_id = $user_id;
        $this->playlist->save();

        return "Saved!";
    }

    public function getToplist()
    {
        $topList = DB::table('playlists')
                        ->select('movie_id', DB::raw('COUNT(movie_id) AS `amount`'))
                        ->groupBy('movie_id')
                        ->latest('amount')
                        ->take(10)
                        ->get();

        return response()->json($topList);
    }

    public function setWatched(Request $request)
    {
        $movieId = $request->movieId;
    }

    public function getUserId()
    {
        return auth()->id();
    }
}
