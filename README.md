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



With JWT

`Authorization: Basic ZW5kdW14Oll0U21DbU9wU1I=`  
`JWT-X: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvYXBpL3VzZXIvbG9naW4iLCJpYXQiOjE1NDIyMTE4OTQsImV4cCI6MTU0MjIxNTQ5NCwibmJmIjoxNTQyMjExODk0LCJqdGkiOiJkRkxtM0laTE10cHVkbmVZIn0.5B8zpGttm5NTSDcu-Zc-GepOc4jy-r9WKzxjS9N26kw`



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
| POST | /api/search | keyword | Returns JSON from all movies found by keyword |

