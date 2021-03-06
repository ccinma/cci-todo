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

Route::prefix('user')->as('user.')->middleware(['auth'])->group(function() {

    // READ
    Route::name('show')->middleware('ajax')->get('/', 'User\ReadUserController@show');

    // UPDATE
    Route::name('updateImage')->middleware('ajax')->post('{user}/updateImage', 'User\UpdateUserController@updateImage');
    Route::name('updateInfos')->middleware(['ajax', 'xxs.sanitize'])->put('{user}/updateInfos', 'User\UpdateUserController@updateInfos');

});

/**
 * Route: "/workspace"
 * 
 * The Workspace controllers for Workspace CRUD.
 */
Route::prefix('workspace')->as('workspace.')->middleware(['auth'])->group(function() {

    // CREATE
    Route::name('store')->middleware(['ajax', 'xxs.sanitize'])->post('/', 'Workspace\CreateWorkspaceController@store');

    // READ
    Route::name('index')->middleware('ajax')->get('/', 'Workspace\ReadWorkspaceController@index');
    Route::name('show')->middleware('ajax')->get('{workspace}', 'Workspace\ReadWorkspaceController@show');

    // UPDATE
    Route::name('update')->middleware('ajax')->put('{workspace}', 'Workspace\UpdateWorkspaceController@update');
    Route::name('addMember')->middleware('ajax')->put('{workspace}/addMember', 'Workspace\UpdateWorkspaceController@addMember');
    Route::name('removeMember')->middleware('ajax')->put('{workspace}/removeMember', 'Workspace\UpdateWorkspaceController@removeMember');

    // DELETE
    Route::name('delete')->middleware('ajax')->delete('{workspace}', 'Workspace\DeleteWorkspaceController@delete');
    
});


Route::prefix('board')->as('board.')->middleware(['auth'])->group(function() {

    // CREATE
    Route::name('store')->middleware(['ajax', 'xxs.sanitize'])->post('/', 'Board\CreateBoardController@store');

    // READ
    Route::name('show')->middleware('ajax')->get('{board}', 'Board\ReadBoardController@show');
    
    // UPDATE
    Route::name('update')->middleware(['ajax', 'xxs.sanitize'])->put('{board}', 'Board\UpdateBoardController@update');

    // DELETE
    Route::name('delete')->middleware('ajax')->delete('{board}', 'Board\DeleteBoardController@delete');
});



Route::prefix('lane')->as('lane.')->middleware(['auth'])->group(function() {

    // CREATE
    Route::name('store')->middleware(['ajax', 'xxs.sanitize'])->post('/', 'Lane\CreateLaneController@store');

    // READ
    Route::name('show')->middleware('ajax')->get('{lane}', 'Lane\ReadLaneController@show');
    
    // UPDATE
    Route::name('update')->middleware(['ajax'])->put('{lane}', 'Lane\UpdateLaneController@update');
    Route::name('move')->middleware(['ajax'])->put('{lane}/move', 'Lane\UpdateLaneController@move');

    // DELETE
    Route::name('delete')->middleware('ajax')->delete('{lane}', 'Lane\DeleteLaneController@delete');

});


Route::prefix('card')->as('card.')->middleware(['auth'])->group(function() {

    // CREATE
    Route::name('store')->middleware(['ajax'])->post('/', 'Card\CreateCardController@store');

    // READ
    Route::name('show')->middleware('ajax')->get('{card}', 'Card\ReadCardController@show');

    // UPDATE
    Route::name('update')->middleware(['ajax'])->put('{card}', 'Card\UpdateCardController@update');
    Route::name('move')->middleware(['ajax'])->put('{card}/move', 'Card\UpdateCardController@move');

    // DELETE
    Route::name('delete')->middleware('ajax')->delete('{card}', 'Card\DeleteCardController@delete');

});


Route::prefix('label')->as('label.')->middleware(['auth'])->group(function() {

    // CREATE
    Route::name('store')->middleware(['ajax', 'xxs.sanitize'])->post('/', 'Label\CreateLabelController@store');

    // READ
    Route::name('show')->middleware('ajax')->get('{label}', 'Label\ReadLabelController@show');

    // UPDATE
    Route::name('update')->middleware(['ajax', 'xxs.sanitize'])->put('{label}', 'Label\UpdateLabelController@update');
    Route::name('attach')->middleware(['ajax', 'xxs.sanitize'])->put('{label}/attach', 'Label\UpdateLabelController@attach');

    // DELETE
    Route::name('delete')->middleware('ajax')->delete('{label}', 'Label\DeleteLabelController@delete');

});