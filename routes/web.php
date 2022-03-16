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

use App\Workspace;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * Route: "/workspace"
 * 
 * The Workspace controllers for Workspace CRUD.
 */
Route::prefix('workspace')->as('workspace.')->group(function() {
    /**
     * Route: "/workspace/create"
     * 
     * Display the new workspace form.
     */
    Route::name('create')->get('create', 'Workspace\CreateWorkspaceController@create');
    /**
     * Route: "/workspace/insert"
     * 
     * Validate AJAX Request body, try insert Workspace then returns JSON.
     * A Http request will result a 403 Forbidden response.
     */
    Route::name('store')->middleware('ajax')->post('store', 'Workspace\CreateWorkspaceController@store');

    /**
     * Route: "/workspace"
     * 
     * Display the list of created workspaces by the user.
     */
    Route::name('index')->get('/', 'Workspace\ReadWorkspaceController@index');

    Route::name('show')->get('{id}', 'Workspace\ReadWorkspaceController@show');
    
});