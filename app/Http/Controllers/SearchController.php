<?php
namespace App\Http\Controllers;
use App\Playlist;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use PhpParser\Node\Expr\Cast\Object_;

class SearchController extends Controller
{
    private $ytClient;
    private $ytApiKey;

    private $omdbClient;
    private $omdbApiKey;

    public function __construct()
    {
        // Clients
        $this->omdbClient = new Client(['base_uri' => 'http://www.omdbapi.com/']);
        $this->ytClient = new Client(['base_uri' => 'https://www.googleapis.com/youtube/v3/search']);

        // API Keys
        $this->omdbApiKey = config('app.omdb_key');
        $this->ytApiKey = config('app.yt_key');
    }

    public function searchByKeyword(Request $request)
    {
        $keyword = $request->keyword;

        if ($request->type !== null) {
            $movieType = $request->type;
        } else {
            $movieType = '';
        }

        try {
            $response = $this->omdbClient->request('GET', '?apikey=' . $this->omdbApiKey . '&s=' . $keyword . '&type=' . $movieType)->getBody();
            $json = json_decode($response, true);

            if ($json['Response'] === 'False') return $json['Error'];

            return jsend_success($json['Search']);
        }
        catch (\Exception $e) {
            return jsend_error('Unable to make search: ' . $e->getMessage());
        }
    }

    public function findById(Request $request)
    {
        $movieId = $request->movieId;
        $userId = $this->getUserId();

        try {
            $response = $this->omdbClient->request('GET', '?apikey=' . $this->omdbApiKey . '&i=' . $movieId . '&plot=full')->getBody();
            $json = json_decode($response, true);

            if ($json['Response'] === 'False') return $json['Error'];
            $json['InPlaylist'] = $this->checkIfWatched($userId, $movieId);

            return jsend_success($json);
        }
        catch (\Exception $e) {
            return jsend_error('Unable to find that item: ' . $e->getMessage());
        }
    }

    public function imdbLatest()
    {
        try {
            $response = $this->ytClient->request('GET', '?part=snippet&channelId=UC_vz6SvmIkYs1_H3Wv2SKlg&maxResults=10&order=date&type=video&key=' . $this->ytApiKey)->getBody();
            $json = json_decode($response, true);

            foreach ($json['items'] as $item) {
                $videoUrls[] = 'https://www.youtube.com/watch?v=' . $item['id']['videoId'];
            }

            return jsend_success($videoUrls);
        }
        catch (\Exception $e) {
            return jsend_error('Unable to search videos: ' . $e->getMessage());
        }
    }

    public function getUserId()
    {
        return auth()->id();
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