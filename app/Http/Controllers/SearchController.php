<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SearchController extends Controller
{
    public function searchByKeyword(Request $request) {

        $client = new Client(['base_uri' => 'http://www.omdbapi.com/']);

        $apiKey = env("OMDB_API_KEY");

        $encodedKeyword = $request->query('keyword');

        $response = $client->request('GET', '?apikey=' . $apiKey . '&s=' . $encodedKeyword)->getBody();

        $searchResults = json_decode($response, true)['Search'];

        return $searchResults;
    }
}
