<?php

namespace App\Http\Controllers;

use App\Playlist;
use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SearchController extends Controller
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://www.omdbapi.com/']);
        $this->apiKey = config('app.omdb_key');
    }

    public function searchByKeyword(Request $request)
    {
        $keyword = $request->keyword;
        if ($request->type !== null){
            $movieType = $request->type;
        } else {
            $movieType = '';
        }

        $response = $this->client->request('GET', '?apikey=' . $this->apiKey . '&s=' . $keyword . '&type=' . $movieType)->getBody();
        $json = json_decode($response, true);

        if ($json['Response'] === 'False') return $json['Error'];

        return $json['Search'];
    }

    public function findById(Request $request)
    {
        $movieId = $request->query('movieId');
        $userId = $this->getUserId();

        $response = $this->client->request('GET', '?apikey=' . $this->apiKey . '&i=' . $movieId . '&plot=full')->getBody();
        $json = json_decode($response, true);

        if ($json['Response'] === 'False') return $json['Error'];

        $json['InPlaylist'] = $this->checkIfWatched($userId, $movieId);

        return $json;
    }

    public function getUserId()
    {
        if(auth()->id() !== null) {
            return auth()->id();
        } else {
            return null;
        }
    }

    public function checkIfWatched($userId, $movieId)
    {
        $item = Playlist::where([
            'movie_id' => $movieId,
            'user_id' => $userId
            ])->first();

        return ($item == null ? false : true);
    }
}
