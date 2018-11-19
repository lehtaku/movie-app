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
        try {
            $playlist = Playlist::where('user_id', $this->getUserId())
                ->latest()
                ->get();
            return jsend_success($playlist);
        }
        catch (\Exception $e) {
            return jsend_error('Unable to get playlist: ' . $e->getMessage());
        }
    }

    public function addToPlaylist(Request $request)
    {
        try {
            $movieId = $request->movieId;
            $userId = $this->getUserId();

            $item = Playlist::where([
                'movie_id' => $movieId,
                'user_id' => $userId
            ])->first();

            if ($item !== null) {
                return jsend_error('Movie '. $movieId .' is already on your playlist');
            } else {
                $this->playlist->movie_id = $movieId;
                $this->playlist->user_id = $userId;
                $this->playlist->save();
                return jsend_success($this->playlist);
            }
        }
        catch (\Exception $e) {
            return jsend_error('Unable to save item to playlist: ' . $e->getMessage());
        }
    }

    public function getToplist()
    {
        try {
            $topList = DB::table('playlists')
                ->select('movie_id', DB::raw('COUNT(movie_id) AS `amount`'))
                ->groupBy('movie_id')
                ->latest('amount')
                ->take(10)
                ->get();
            return jsend_success($topList);
        }
        catch (\Exception $e) {
            return jsend_error('Unable to get toplist: ' . $e->getMessage());
        }
    }

    public function setWatched(Request $request)
    {
        try {
            $item = Playlist::where([
                'movie_id' => $request->movieId,
                'user_id' => $this->getUserId()
            ])->first();
            $item->watched = !$item->watched;
            $item->save();
            return jsend_success($item);
        }
        catch (\Exception $e) {
            return jsend_error('Unable to change watched state: ' . $e->getMessage());
        }
    }

    public function getUserId()
    {
        return auth()->id();
    }
}
