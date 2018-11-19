
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
* User
    * Register ✔️
    * User login ✔️
    * Change password ✖️
* Playlist
    * Get user playlist ✔️
    * Add movie to playlist ✔️
    * Get 10 most popular movies ✔️
    * Select watched / not watched ✔️

http://www.omdbapi.com/

## Authentication to API

www.endumx.com  
endumx / YtSmCmOpSR

With JWT  
`Authorization : Basic ZW5kdW14Oll0U21DbU9wU1I=`  
`JWT-X : Bearer {token}`


## JWT AUTH

### API Routes

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
Route::post('video/imdbLatest', 'SearchController@imdbLatest');

// Playlist functionality
Route::get('movie/getToplist', 'PlaylistController@getToplist');

// User authentication
Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');
```

### Making requests

Request to middleware jwtx.auth:  
`Authorization: Basic ZW5kdW14Oll0U21DbU9wU1I=`  
  
`JWT-X: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvYXBpL3VzZXIvbG9naW4iLCJpYXQiOjE1NDIyMTE4OTQsImV4cCI6MTU0MjIxNTQ5NCwibmJmIjoxNTQyMjExODk0LCJqdGkiOiJkRkxtM0laTE10cHVkbmVZIn0.5B8zpGttm5NTSDcu-Zc-GepOc4jy-r9WKzxjS9N26kw`

![JWT](https://media.discordapp.net/attachments/499833921513586688/512304344461475851/unknown.png)




## API Requests

Send all requests to:
`www.endumx.com/api/[request]`

### User 

| Method | URL | Parameters | Authentication | Description |
|--------|-----|------------|----------------|-------------|
| POST | user/register | name, email, password | Basic | Register user and return JWT-token |
| POST | user/login | email, password | Basic | Login user and return JWT-token |
| GET | user/getInfo | ✖️ | Basic,<br>Bearer {token} | Return signed user information |

### Search

| Method | URL | Parameters | Authentication | Description |
|--------|-----|------------|----------------|-------------|
| POST | movie/search | keyword,<br> type (optional) | Basic | Returns search results by keyword |
| POST | movie/findById | movieId | Basic,<br>Bearer {token} | Returns all details from a single movie |
| POST | video/imdbLatest | ✖️ | Basic | Returns 10 last uploaded videos from IMDb channel |

### Playlist

| Method | URL | Parameters | Authentication | Description |
|--------|-----|------------|----------------|-------------|
| Post | movie/showPlaylist | ✖️ | Basic, <br>Bearer {token} | Returns signed user own playlist |
| POST | movie/addToPlaylist | movieId, movieId | Basic, <br>Bearer {token} | Saves movie to user own playlist |
| GET | movie/getToplist | ✖️ | Basic | Return 10 most popular movies |
| POST | movie/setWatched | movieId | Basic,<br>Bearer {token} | Sets 'watched' to true if false and vice versa |



