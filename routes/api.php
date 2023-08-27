<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\RetweetController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) { */
/*     return $request->user(); */
/* }); */


Route::controller(UserController::class)->group(function(){
    Route::post('/login', 'loginUser');
    Route::post('/signup', 'store');
    Route::get('/logout', 'logOutUser');
    });


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('tweets', TweetController::class);
    Route::resource('retweets', RetweetController::class);

    Route::prefix('user/')->group(function(){
        Route::get('{user_id}/tweets/', [TweetController::class, 'getUserTweets']);

        Route::get('{user_id}/followers' ,[FollowController::class, 'getFollowers']);
        Route::get('{user_id}/following' ,[FollowController::class, 'getFollowings']);

        // Check if follower_user_id is the same as the authenticated user
        Route::middleware(EnsureUserAuthorizedFollowActions::class)->group(function(){

            Route::post('{follower_user_id}/follow/{following_user_id}',
                [FollowController::class, 'followUser']);

            Route::delete('{follower_user_id}/follow/{following_user_id}',
                [FollowController::class, 'unfollowUser']);
        });
    });
});

