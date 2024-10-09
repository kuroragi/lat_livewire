<?php

use App\Livewire\Master\Role;
use App\Livewire\Master\User;
use App\Livewire\Post\Post;
use App\Livewire\Post\PostCategory;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/role', Role::class);

Route::get('/user', User::class);

Route::get('/post_category', PostCategory::class);

Route::get('/post', Post::class);
