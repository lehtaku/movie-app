
<p align="center">
  <img height="150px" src="https://media.discordapp.net/attachments/499833921513586688/512349750163669004/movieappsmall.png">
   <img height="125px" src="https://media.discordapp.net/attachments/499833921513586688/512350532401233930/movieapptransparent.png">
</p>
<p align="center">
    <img height="40px" src="https://cdn.worldvectorlogo.com/logos/angular-3.svg">
</p>

***


## To-Do List

* Movie search
    * By keyword(s) ✔️	
    * By IMDb-ID ✔️
* User register ✔️
* User login ✔️
* "Playlist"
    * Get user playlist ✔️
    * Add movie to playlist ✖️

http://www.omdbapi.com/

## Authentication to API

www.endumx.com  
endumx / YtSmCmOpSR

With JWT
`Authorization: Basic ZW5kdW14Oll0U21DbU9wU1I=`  
`JWT-X: Bearer {token}`


## JWT AUTH

### API Routes

```php
<?php
// User authentication
Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');

// Auth Routes
Route::group(['middleware' => ['jwtx.auth']], function () {
    Route::get('users', function(Request $request) {
        return auth()->user();
    });
});
```

### Making requests

Request to middleware jwtx.auth:  
`Authorization: Basic ZW5kdW14Oll0U21DbU9wU1I=`  
  
`JWT-X: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvYXBpL3VzZXIvbG9naW4iLCJpYXQiOjE1NDIyMTE4OTQsImV4cCI6MTU0MjIxNTQ5NCwibmJmIjoxNTQyMjExODk0LCJqdGkiOiJkRkxtM0laTE10cHVkbmVZIn0.5B8zpGttm5NTSDcu-Zc-GepOc4jy-r9WKzxjS9N26kw`

![JWT](https://media.discordapp.net/attachments/499833921513586688/512304344461475851/unknown.png)




## API Requests

| Method | URL | Parameters | Authentication | Description |
|--------|-----|------------|----------------|-------------|
| POST | api/movie/search | keyword<br> type (optional) | Basic | Returns search results by keyword |
| POST | api/movie/findById | id (IMDb) | Basic | Returns all details from a single movie |
| POST | api/movie/showPlaylist | ✖️ | Basic, <br> Bearer {token} | Returns signed user own playlist |

