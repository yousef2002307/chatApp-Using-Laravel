<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Functions;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/test', [Functions::class, 'RightSide']);
Route::any('/test2/{id}', function ($id) {
    $messages = new Functions;
    $messages2 = $messages->RightSide($id);
    return $messages2;
})->name("test2");
Route::post('/addM', [App\Http\Controllers\HomeController::class, 'addM'])->name('addM');
Route::post('/update', [App\Http\Controllers\HomeController::class, 'update'])->name('update');
Route::get("/editpro",function(){
    $user = User::find(Auth::user()->id);
   
return view("editpro",compact("user"));
})->name("editpro");
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
