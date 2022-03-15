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
    Route::name('insert')->middleware('ajax')->post('insert', 'Workspace\CreateWorkspaceController@insert_async');

    /**
     * Route: "/workspace"
     * 
     * Display the list of created workspaces by the user.
     */
    Route::name('index')->middleware('auth')->get('/', 'Workspace\ReadWorkspaceController@findAllByUserId');
    
});