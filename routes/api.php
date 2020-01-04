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
Route::get('film/crawl/longest', 'FilmController@getLongestOpeningCrawl');
Route::get('film/character/top/{number?}', 'FilmController@getPopularCharacter');
Route::get('film/species', 'FilmController@getSpeciesByAppearance');
Route::get('film/planet/pilots', 'FilmController@getPilotsByPlanet');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::fallback(function(){
    return response()->json(['message' => 'Page Not Found.'], 404);
});