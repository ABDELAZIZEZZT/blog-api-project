<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\blogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/', function (Request $request) {
//     return response()->json(User::all());
// });

Route::post('/forgetPassword', [AuthController::class, 'forgetPassword']);
Route::post('/confirmToken', [AuthController::class, 'confirmToken'])->name('confirmToken');
Route::post('/resetPassword', [AuthController::class, 'resetPassword'])->name('resetPassword');



Route::middleware(['guest'])->group(function () {
    Route::get('register/{provider}', [AuthController::class, 'registrationProvider']);
    Route::get('register/{provider}/redirection', [AuthController::class, 'redirectProvider']);
    Route::get('viewSetPassword/{id}', [AuthController::class, 'viewSetPassword'])->name('viewSetPassword');
    Route::post('setPassword/{id}', [AuthController::class, 'setPassword'])->name('setPassword');
    Route::post('register',[AuthController::class,'register']);
});
Route::post('login',[AuthController::class,'login'])->name('login');


Route::get('/blogs', [BlogController::class, 'index'])->name('blogs');


Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::resource('blogs', BlogController::class);
    Route::get('blog/{blog}',[BlogController::class,'show']);
    Route::post('react/blog/{blog}', [blogController::class, 'react']);
    Route::Post('/blog/search', [BlogController::class, 'search']);

    Route::get('/user', function (Request $request) { return $request->user();});
    Route::put('/user/{id}', [AuthController::class, 'update']);

    Route::post("follow", [FollowController::class, 'follow']);
    Route::post("unfollow", [FollowController::class, 'unfollow']);
    Route::get("followers", [FollowController::class, 'followers']);
    Route::get("following", [FollowController::class, 'following']);

    Route::resource('comments', CommentController::class);
    Route::get('/comment/{blog_id}', [CommentController::class, 'getAllCommentsInBlog']);
    Route::get('/comment/replies/{comment}', [CommentController::class, 'getReplies']);
    Route::post('react/comment/{comment}', [CommentController::class, 'react']);

});

Route::get('/', function () {
    return response()->json(['error' => 'please login'], 401);
})->name('unauth');


