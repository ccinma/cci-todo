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
Route::prefix('workspace')->as('workspace.')->middleware('auth')->group(function() {

    /**
     * Route: POST "/workspace"
     * 
     * Validate AJAX Request body, try insert Workspace then returns JSON.
     * A Http request will result a 403 Forbidden response.
     */
    Route::name('store')->middleware('ajax')->post('/', 'Workspace\CreateWorkspaceController@store');

    /**
     * Route: GET "/workspace"
     * 
     * Display the list of created workspaces by the user.
     */
    Route::name('index')->get('/', 'Workspace\ReadWorkspaceController@index');

    /**
     * Route: GET "/workspace/{workspace}"
     * 
     * Display a specific workspace with all its informations.
     */
    Route::name('show')->get('{workspace}', 'Workspace\ReadWorkspaceController@show');

    /**
     * Route: PUT "/workspace/{workspace}"
     * 
     * Update a workspace.
     */
    Route::name('update')->middleware('ajax')->put('{workspace}', 'Workspace\UpdateWorkspaceController@update');

    /**
     * Route: DELETE "/workspace/{workspace}"
     * 
     * Delete a workspace
     */
    Route::name('delete')->middleware('ajax')->delete('{workspace}', 'Workspace\DeleteWorkspaceController@delete');
    
});