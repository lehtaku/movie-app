
<p align="center">
  <img height="150px" src="https://media.discordapp.net/attachments/499833921513586688/512349750163669004/movieappsmall.png">
   <img height="125px" src="https://media.discordapp.net/attachments/499833921513586688/512350532401233930/movieapptransparent.png">
</p>
<p align="center">
    <img height="40px" src="https://cdn.worldvectorlogo.com/logos/angular-3.svg">
</p>

***

# MovieApp

MovieApp on käyttäjäpohjainen Angular-sovellus elokuvien hallintaan, joka käyttää OMDB APIa http://www.omdbapi.com/.

## Lähtökohta

Ennen kuin tiesimme minkälaisen sovelluksen toteutamme, lähtökohtana oli toteuttaa backend [Laravelilla](https://laravel.com/), koska näimme sen todella tehokkaaksi vaihtoehdoksi luoda oikeasti tuotantokelpoinen sovellus nopeasti ja tehokkaasti.

Tämän lisäksi ajattelimme, että järkevintä on liittää TTMS0900 ja TTMS0500 -opintojaksojen harjoitustyöt yhteen ja näin saada eheämpi kokonaisuus.

## Asetelma

### Paketit & työkalut:

#### JWT-Auth
[JWT](https://github.com/tymondesigns/jwt-auth) eli JSON Web Token kompakti mutta turvallinen tapa varmentaa tiedonsiirtoa osapuolten välillä. JWT on nimensä mukaisesti JSON objekti, joka sisältää automaattisesti generoidun salausavaimen. Tässä tapauksessa käyttäjän kirjautuessa luodaan token, joka lähetetään vastauksena onnistuneesta kirjautumisesta ja tallennetaan muuttujaan. Aina käyttäjän lähettäessä pyyntöjä rajapintaan jotka hakevat/välittävät jotain käyttäjään liittyvää tietoa, vaaditaan pyynnön mukana token joka varmentaa käyttäjän. Se helpottaa käytettävyyttä ja lisää turvallisuutta.

`JWT-X: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvYXBpL3VzZXIvbG9naW4iLCJpYXQiOjE1NDIyMTE4OTQsImV4cCI6MTU0MjIxNTQ5NCwibmJmIjoxNTQyMjExODk0LCJqdGkiOiJkRkxtM0laTE10cHVkbmVZIn0.5B8zpGttm5NTSDcu-Zc-GepOc4jy-r9WKzxjS9N26kw`

![JWT](https://media.discordapp.net/attachments/499833921513586688/512304344461475851/unknown.png)

#### Laravel-CORS
[Laravel-CORS](https://github.com/barryvdh/laravel-cors) eli Cross-Origin Resource Sharing on mekanismi jonka avulla voidaan sallia suojatun/rajoitetun pääsyn takana olevan tiedon lähettäminen valituille verkkopalveluille. Eli kutsuja lähettävän palvelun (tässä tapauksessa käyttöliittymän) toimiessa kokonaan eri osoitteessa, saadaan sallittua tiedon lähettäminen.

#### Guzzle
[Guzzle](http://docs.guzzlephp.org/en/stable/) on PHP-pohjainen ohjelma, joka on tarkoitettu HTTP pyyntöjen lähettämiseen. Guzzlella voi luoda helposti kutsuja, hallita evästeitä, lähettää JSON dataa ja paljon muuta. Guzzlella voi luoda synkronisia (blocking) ja asynkronisia (non-blocking) pyyntöjä. Perus hakupyynnön lähettäminen Guzzlella on vaivatonta:
```php
$client = new GuzzleHttp\Client();
$res = $client->request('GET', 'https://api.github.com/user');
echo $res->getStatusCode();
// "200"
echo $res->getHeader('content-type');
// 'application/json; charset=utf8'
echo $res->getBody();
// {"type":"User"...'
```

### Kehitysympäristö

#### Homestead
[Homestead](https://laravel.com/docs/5.7/homestead) on Laravel-kehitykseen optimoitu virtuaalikone. Se asennetaan yhtenä pakettina käyttäen [Vagranttia](https://www.vagrantup.com/), joka on erilaisten kehitysympäristöjen hallintaan ja asentamiseen käytetty työkalu. Homesteadin asentaminen ja käyttöönotto on vaivatonta eikä vaadi asennettavaksi erikseen PHP:tä, web-palvelinta tai muuta vastaavaa. Se sisältää valmiina ominaisuuksia kuten Linux (Ubuntu 18.04), Git, PHPn, Nginxin, MySQL, Composer ja paljon muuta hyödyllistä.

#### Postman
[Postman](https://www.getpostman.com/) on rajapintojen kehittämiseen tarkoitettu työkalu. Se on täysin ilmainen ja tekee kehittämisestä helpompaa ja tehokkaampaa. Postmanilla pystyy lähettämään API requesteja eli kutsuja rajapintaan haluamallaan HTTP metodilla ja ohjelma palauttaa vastauksen joko JSON, raaka tai HTML muodossa. Postmanissa pystyy luomaan kokoelmia pyynnöistä eli samaa pyyntöä voi käyttää myöhemmin uudelleen eikä sitä tarvitse laatia joka kerta uudelleen. Pyyntöön pystyy liittämään haluamiaan parametrejä ja autentikointimenetelmiä vaivattomasti. Postmanilla on mahdollista luoda myös erilaisia testejä ja monitoroimaan APIn tilaa. Postmanista on saatavilla selaimeen asennettava versio sekä työpyötäsovellus.

### Tuotantopalvelin

#### CentOS 7 & Apache



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
// Group JWT-Auth routes
Route::middleware(['jwtx.auth'])->group(function () {

    // Show signed user
    Route::get('user/getInfo', 'UserController@getInfo');

    // User playlist
    Route::post('movie/showPlaylist', 'PlaylistController@getPlaylist');
    Route::post('movie/addToPlaylist', 'PlaylistController@addToPlaylist');
    Route::post('movie/setWatched', 'PlaylistController@setWatched');
    Route::post('movie/findById', 'SearchController@findById');
});

// Search from OMDb
Route::post('movie/search', 'SearchController@searchByKeyword');

// IMDb's Youtube channel 10 latest videos
Route::get('video/imdbLatest', 'SearchController@imdbLatest');

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

