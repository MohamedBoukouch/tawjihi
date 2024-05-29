<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthentificationController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SliderController;







Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', [AuthentificationController::class, 'test']);

//AUTH
Route::post('/login', [AuthentificationController::class, 'login']);
Route::post('/signup', [AuthentificationController::class, 'signup']);
Route::post('/verifyEmail', [AuthentificationController::class, 'verifyEmail']);

//COMPET
Route::delete('/compte/delete-compte', [CompteController::class, 'deleteCompte']);
Route::post('/compte/edit-compte', [CompteController::class, 'editCompte']);
Route::post('/compte/edit-password', [CompteController::class, 'editPassword']);

//PROFILE
Route::post('/profile/add-profile-image', [ProfileController::class, 'addProfileImage']);
Route::post('/profile/fetch-profile', [ProfileController::class, 'fetchProfile']);

//RESETPASSWORD
Route::post('/check-email', [ResetPasswordController::class, 'checkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
Route::post('/verify-compte', [ResetPasswordController::class, 'verifyCompte']);

//PUBLICATION
Route::post('/publications/addpublications', [PublicationController::class, 'add']);
Route::post('/publications/getPublications', [PublicationController::class, 'getPublications']);
Route::get('/publications/search', [PublicationController::class, 'search']);


//COMMENT
Route::post('/comments/add', [CommentController::class, 'addComment']);
Route::post('/comments/delete', [CommentController::class, 'deleteComment']);
Route::post('/publications/update-number-of-comments', [CommentController::class, 'updateNumberOfComments']);
Route::post('/publications/show-comments', [CommentController::class, 'showComments']);

//LIKE
Route::post('/likes/add', [LikeController::class, 'addLike']);
Route::post('/likes/drop', [LikeController::class, 'dropLike']);
Route::post('/publications/update-number-of-likes', [LikeController::class, 'updateNumberOfLikes']);

//FAVORITE
Route::post('/favorites/add', [FavoriteController::class, 'addFavorite']);
Route::post('/favorites/delete', [FavoriteController::class, 'deleteFavorite']);
Route::post('/favorites/select', [FavoriteController::class, 'selectFavorite']);

//SLIDERS
Route::post('/sliders/add', [SliderController::class, 'addSlider']);
Route::delete('/sliders/delete', [SliderController::class, 'deleteSlider']);
Route::post('/sliders', [SliderController::class, 'fetchSliders']);
Route::put('/sliders/update', [SliderController::class, 'updateSlider']);
