<?php

namespace App\Http\Controllers;

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
        $this->apiKey = env("OMDB_API_KEY");
    }

    public function searchByKeyword(Request $request) {

        $encodedKeyword = urlencode($request->query('keyword'));

        $response = $this->client->request('GET', '?apikey=' . $this->apiKey . '&s=' . $encodedKeyword)->getBody();

        $searchResults = json_decode($response, true)['Search'];

        return $searchResults;
    }

    public function findById(Request $request) {

        $imdbId = $request->query('id');

        $response = $this->client->request('GET', '?apikey=' . $this->apiKey . '&i=' . $imdbId)->getBody();

        $searchResults = json_decode($response, true)['Search'];

        return $searchResults;
    }
}
