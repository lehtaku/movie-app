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

Request to search:
