<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthentificationController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\CommentController;



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', [AuthentificationController::class, 'test']);

//AUTH
Route::post('/login', [AuthentificationController::class, 'login']);
Route::post('/signup', [AuthentificationController::class, 'signup']);
Route::post('/verifyEmail', [AuthentificationController::class, 'verifyEmail']);

//COMPET
Route::delete('/delete-compte', [CompteController::class, 'deleteCompte']);
Route::post('/edit-compte', [CompteController::class, 'editCompte']);
Route::post('/edit-password', [CompteController::class, 'editPassword']);

//PROFILE
Route::post('/add-profile-image', [ProfileController::class, 'addProfileImage']);
Route::get('/fetch-profile', [ProfileController::class, 'fetchProfile']);

//RESETPASSWORD
Route::post('/check-email', [ResetPasswordController::class, 'checkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
Route::post('/verify-compte', [ResetPasswordController::class, 'verifyCompte']);

//PUBLICATION
Route::post('/add', [PublicationController::class, 'add']);

//COMMENT
Route::post('/comments/add', [CommentController::class, 'addComment']);
Route::delete('/comments/delete', [CommentController::class, 'deleteComment']);
Route::put('/publications/update-number-of-comments', [CommentController::class, 'updateNumberOfComments']);
Route::get('/publications/show-comments', [CommentController::class, 'showComments']);
