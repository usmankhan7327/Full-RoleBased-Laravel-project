<?php


use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Events\Routing;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\RolesController;

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

// Common Resource Routes
// index - show all listings
// show - show a single listing
// create - show a view to create a new listing
// store - persist the new listing
// edit - show a view to edit an existing listing
// update - persist the edited listing
// destroy - delete the listing





//All listings
Route::get('/', [ListingController::class, 'index']);

//Create a new listing
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

//Store listing Data
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

//edit listing
Route::get('/listings/{listing}/edit ', [ListingController::class, 'edit'])->middleware('auth');

//update listing
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

//delete listing
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

//Manage listings
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth'); 

//Single listing
Route::get('/listings/{listing}', [ListingController::class, 'show']);



//Show register / create user form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

//create new user
Route::post('/users', [UserController::class, 'store']);

//logout user
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

//Show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

//authenticate user
Route::post('/users/authenticate', [UserController::class, 'authenticate']);


//manage users
Route::get('/users/manage', [RolesController::class, 'manage'])->middleware('auth');










// for testing purposes

// Route::get('/hello', function () {
//     return response('<h1>Hello World</h1>', 200)
//         ->header('Content-Type', 'text/html');
// });

// Route::get('/posts/{id}', function ($id) {
//     // ddd($id);
//     return response('Usman ID is: '. $id);
// })-> where('id', '[0-9]+');

// Route::get('/search', function (Request $request) {
//     return $request->name. ' ' .$request->city;
//     // return response('Search Results');
// });