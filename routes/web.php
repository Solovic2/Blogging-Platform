<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**  Admin  */
Route::prefix('/admin')->name('admin.')->middleware(['auth','admin'])->group(function(){
    Route::get('/',[App\Http\Controllers\AdminController::class, 'index'])->name('index');
    Route::get('/dashboard',[App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    /**             Users                */

    Route::get('/getUsers',[App\Http\Controllers\AdminController::class, 'getUsers'])->name('getUsers');
    Route::post('/addUser',[App\Http\Controllers\AdminController::class, 'addUser'])->name('addUser');
    Route::post('/editUser',[App\Http\Controllers\AdminController::class, 'editUser'])->name('editUser');
    Route::delete('/deleteUser',[App\Http\Controllers\AdminController::class, 'deleteUser'])->name('deleteUser');

    
    /**             Tags                */
    
    Route::get('/getTags',[App\Http\Controllers\AdminController::class, 'getTags'])->name('getTags');
    Route::post('/addTag',[App\Http\Controllers\AdminController::class, 'addTag'])->name('addTag');
    Route::post('/editTag',[App\Http\Controllers\AdminController::class, 'editTag'])->name('editTag');
    Route::delete('/deleteTag',[App\Http\Controllers\AdminController::class, 'deleteTag'])->name('deleteTag');

    /**             Posts                */

    Route::post('/saveImages',[App\Http\Controllers\AdminController::class, 'saveImages'])->name('saveImages');
    Route::post('/deleteImages',[App\Http\Controllers\AdminController::class, 'deleteImages'])->name('deleteImages');
    
    Route::get('/getPosts',[App\Http\Controllers\AdminController::class, 'getPosts'])->name('getPosts');
    Route::post('/editPost',[App\Http\Controllers\AdminController::class, 'editPost'])->name('editPost');
    Route::post('/addPost',[App\Http\Controllers\AdminController::class, 'addPost'])->name('addPost');
    Route::delete('/deletePost',[App\Http\Controllers\AdminController::class, 'deletePost'])->name('deletePost');
    Route::post('/upload/banner',[App\Http\Controllers\AdminController::class, 'uploadBanner'])->name('uploadBanner');






    Route::any('/{admin}',[App\Http\Controllers\AdminController::class, 'index']);
});

/** Front */

Route::get('/getAllPosts',[App\Http\Controllers\FrontController::class, 'getAllPosts'])->name('getAllPosts');
Route::get('/getAllTags',[App\Http\Controllers\FrontController::class, 'getAllTags'])->name('getAllTags');

Route::get('/getPost/{id}',[App\Http\Controllers\FrontController::class, 'getPost'])->name('getPost');
Route::post('/addComment',[App\Http\Controllers\FrontController::class, 'addComment'])->name('addComment')->middleware('auth');

Route::get('/getAllPostsOfTag/{id}',[App\Http\Controllers\FrontController::class, 'getAllPostsOfTag'])->name('getAllPostsOfTag');
Route::get('/post/{slug}', function () {
    return view('welcome');
});
Route::get('/tag/{slug}', function () {
    return view('welcome');
});
Route::any('{slug}', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
