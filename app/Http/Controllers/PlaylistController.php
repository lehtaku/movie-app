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
            return $playlist;
        }
        catch (\Exception $e) {
            return 'Unable to get playlist: ' . $e->getMessage();
        }
    }

    public function addToPlaylist(Request $request)
    {
        try {
            $movieId = $request->movieId;
            $movieName = $request->movieName;
            $userId = $this->getUserId();

            $item = Playlist::where([
                'movie_id' => $movieId,
                'name' => $movieName,
                'user_id' => $userId
            ])->first();

            if ($item !== null) {
                return 'Movie '. $movieId .' is already on your playlist';
            } else {
                $this->playlist->movie_id = $movieId;
                $this->playlist->name = $movieName;
                $this->playlist->user_id = $userId;
                $this->playlist->save();
                return $this->playlist;
            }
        }
        catch (\Exception $e) {
            return 'Unable to save item to playlist: ' . $e->getMessage();
        }
    }

    public function removeFromPlaylist(Request $request) {
        $movieId = $request->movieId;
        $userId = $this->getUserId();

        try {
            $item = Playlist::where([
                'movie_id' => $movieId,
                'user_id' => $userId
            ])->first();

            if ($item == null) {
                return "Item is not in your playlist";
            } else {
                $item->delete();
                return auth()->user();
            }
        }
        catch (\Exception $e) {
            return 'Unable to delete item: ' . $e->getMessage();
        }
    }

    public function getToplist()
    {
        try {
            $topList = DB::table('playlists')
                ->select('name', DB::raw('COUNT(name) AS `amount`'))
                ->groupBy('name')
                ->latest('amount')
                ->take(10)
                ->get();
            return $topList;
        }
        catch (\Exception $e) {
            return 'Unable to get toplist: ' . $e->getMessage();
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
            return $item;
        }
        catch (\Exception $e) {
            return 'Unable to change watched state: ' . $e->getMessage();
        }
    }

    public function getUserId()
    {
        return auth()->id();
    }
}
