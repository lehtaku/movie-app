# Movie App

## ToDo

* Elokuvien haku
* Käyttäjän rekisteröityminen
* Käyttäjän kirjautuminen
* Oma Playlist
    * user_id, ?imdb_id?

http://www.omdbapi.com/

## Authentication to API

www.endumx.com  
endumx / YtSmCmOpSR

Authorization: Basic ZW5kdW14Oll0U21DbU9wU1I=

With JWT (at least for now)
http://endumx.com/api/users?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9lbmR1bXguY29tL2FwaS91c2VyL3JlZ2lzdGVyIiwiaWF0IjoxNTQyMjEwMDg5LCJleHAiOjE1NDIyMTM2ODksIm5iZiI6MTU0MjIxMDA4OSwianRpIjoidkt2aENpMmsxWW9TcGEzeCJ9.TGsZA0THBc-riU8llpcGxC5_CXKfexDwGTxps17fn2w

## JWT AUTH

### API Routes

```php
<?php
Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');

Route::middleware('jwt.auth')->get('users', function(Request $request) {
    return auth()->user();
});
```

### Making requests

Request to middleware jwt.auth:  
Header: `Authorization` Value: `Bearer {token}`  
`Authorization: Bearer {token}`

![JWT](https://media.discordapp.net/attachments/499833921513586688/512266191067283462/unknown.png)

| Method | URL | Parameters | Description |
|--------|-----|------------|-------------|
| POST | endumx.com/api/search | keyword | Returns JSON from all movies found by keyword |

