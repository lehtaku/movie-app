<?php

namespace App\Http\Controllers;

use App\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    public function getPlaylist() {
        $id = auth()->id();

        $playlist = Playlist::where('user_id', $id)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return $playlist;
    }

    public function addToPlaylist(Request $request) {

    }
}
