<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('anonymous.welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * Route: "/workspace"
 * 
 * The Workspace controllers for Workspace CRUD.
 */
Route::prefix('workspace')->as('workspace.')->middleware('auth')->group(function() {

    // CREATE
    Route::name('store')->middleware('ajax')->post('/', 'Workspace\CreateWorkspaceController@store');

    // READ
    Route::name('index')->get('/', 'Workspace\ReadWorkspaceController@index');
    Route::name('show')->get('{workspace}', 'Workspace\ReadWorkspaceController@show');

    // UPDATE
    Route::name('update')->middleware('ajax')->put('{workspace}', 'Workspace\UpdateWorkspaceController@update');

    // DELETE
    Route::name('delete')->middleware('ajax')->delete('{workspace}', 'Workspace\DeleteWorkspaceController@delete');
    
});


Route::prefix('board')->as('board.')->middleware('auth')->group(function() {

    // CREATE
    Route::name('store')->middleware('ajax')->post('/', 'Board\CreateBoardController@store');

    // READ
    Route::name('show')->get('{board}', 'Board\ReadBoardController@show');
    
    // UPDATE
    Route::name('update')->middleware('ajax')->put('{board}', 'Board\UpdateBoardController@update');

    // DELETE
    Route::name('delete')->middleware('ajax')->delete('{board}', 'Board\DeleteBoardController@delete');
});



Route::prefix('lane')->as('lane.')->middleware('auth')->group(function() {

    // CREATE
    Route::name('store')->middleware('ajax')->post('/', 'Lane\CreateLaneController@store');

    // READ
    Route::name('show')->middleware('ajax')->get('{lane}', 'Lane\ReadLaneController@show');
    
    // UPDATE
    Route::name('update')->middleware('ajax')->put('{lane}', 'Lane\UpdateLaneController@update');

    // DELETE
    Route::name('delete')->middleware('ajax')->delete('{lane}', 'Lane\DeleteLaneController@delete');

});


Route::prefix('card')->as('card.')->middleware('auth')->group(function() {

    // CREATE
    Route::name('store')->middleware('ajax')->post('/', 'Card\CreateCardController@store');

    // READ
    Route::name('show')->middleware('ajax')->get('{card}', 'Card\ReadCardController@show');

    // UPDATE
    Route::name('update')->middleware('ajax')->put('{card}', 'Card\UpdateCardController@update');

    // DELETE
    Route::name('delete')->middleware('ajax')->delete('{card}', 'Card\DeleteCardController@delete');

});


Route::prefix('label')->as('label.')->middleware('auth')->group(function() {

    // CREATE
    Route::name('store')->middleware('ajax')->post('/', 'Label\CreateLabelController@store');

    // READ
    Route::name('show')->middleware('ajax')->get('{label}', 'Label\ReadLabelController@show');

    // UPDATE
    Route::name('update')->middleware('ajax')->put('{label}', 'Label\UpdateLabelController@update');

    // DELETE

});