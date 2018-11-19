
<p align="center">
  <img height="150px" src="https://media.discordapp.net/attachments/499833921513586688/512349750163669004/movieappsmall.png">
   <img height="125px" src="https://media.discordapp.net/attachments/499833921513586688/512350532401233930/movieapptransparent.png">
</p>
<p align="center">
    <img height="40px" src="https://cdn.worldvectorlogo.com/logos/angular-3.svg">
</p>

***

# Movie App

Movie App on käyttäjäpohjainen Angular-sovellus elokuvien hallintaan, joka käyttää OMDB APIa.

## Lähtökohta

Ennen kuin tiesimme minkälaisen sovelluksen toteutamme, lähtökohtana oli toteuttaa backend Laravelilla, koska näimme sen todella tehokkaaksi vaihtoehdoksi luoda oikeasti tuotantokelpoinen sovellus nopeasti ja tehokkaasti.

Tämän lisäksi ajattelimme, että järkevintä on liittää TTMS0900 ja TTMS0500 -opintojaksojen harjoitustyöt yhteen ja näin saada eheämpi kokonaisuus.

## Suunnitelma

## Asetelma

## Middleware

Koska palvelimella on käytössä Basic Auth, jouduimme luomaan oman middlewaren.

```php
<?php

namespace App\Http\Middleware;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
class JWTAuthenticate extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
         //Here we put our client domains
         $trusted_domains = ["http://localhost:4200", "localhost:4200"];
        
         if(isset($request->server()['HTTP_ORIGIN'])) {
             $origin = $request->server()['HTTP_ORIGIN'];
 
             if(in_array($origin, $trusted_domains)) {
                 header('Access-Control-Allow-Origin: ' . $origin);
                 header('Access-Control-Allow-Headers: Origin, Content-Type');
             }
         }
        // Set custom header name for token
        $this->auth->setRequest($request)->parseToken("bearer","JWT-X");
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }
        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }
        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }
        $this->events->fire('tymon.jwt.valid', $user);
        return $response;
    }
```


## Routes
```php
<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Group JWT-Auth routes
Route::middleware(['jwtx.auth'])->group(function () {
    // Show signed user
    Route::get('user/getInfo', 'UserController@getInfo');
    // User playlist
    Route::get('movie/showPlaylist', 'PlaylistController@getPlaylist');
    Route::post('movie/addToPlaylist', 'PlaylistController@addToPlaylist');
    Route::post('movie/setWatched', 'PlaylistController@setWatched');
    Route::post('movie/findById', 'SearchController@findById');
});
// Search from OMDb
Route::post('movie/search', 'SearchController@searchByKeyword');
// Playlist functionality
Route::get('movie/getToplist', 'PlaylistController@getToplist');
// User authentication
Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');
```


## Autentikaatio

### JWT-AUTH

### Making requests

Request to middleware jwtx.auth:  
`Authorization: Basic ZW5kdW14Oll0U21DbU9wU1I=`  
  
`JWT-X: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvYXBpL3VzZXIvbG9naW4iLCJpYXQiOjE1NDIyMTE4OTQsImV4cCI6MTU0MjIxNTQ5NCwibmJmIjoxNTQyMjExODk0LCJqdGkiOiJkRkxtM0laTE10cHVkbmVZIn0.5B8zpGttm5NTSDcu-Zc-GepOc4jy-r9WKzxjS9N26kw`

![JWT](https://media.discordapp.net/attachments/499833921513586688/512304344461475851/unknown.png)

