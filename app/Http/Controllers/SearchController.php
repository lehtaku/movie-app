<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SearchController extends Controller
{
    public function searchMovies(Request $request) {

        $encodedParam = $request->input('search');
        $apiKey = '39d58ddb';

        $client = new Client();

        $res = $client->request('GET', 'http://www.omdbapi.com/?apikey=' . $apiKey . '&s=' . $encodedParam);

        return $res->getBody();
    }
}
