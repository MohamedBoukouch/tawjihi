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
use App\Http\Controllers\EcoleController;
use App\Http\Controllers\EcolevilleController;
use App\Http\Controllers\PdfConcoursController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;





Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('contact', 
  ['as' => 'contact', 'uses' => 'AboutController@create']);

Route::get('/test', [AuthentificationController::class, 'test']);

//AUTH
Route::post('/login', [AuthentificationController::class, 'login']);
Route::post('/signup', [AuthentificationController::class, 'signup']);
Route::post('/verifyEmail', [AuthentificationController::class, 'verifyEmail']);
Route::post('/store', [AuthentificationController::class, 'store']);
Route::post('/mohamed', [AuthentificationController::class, 'mohamed']);

//COMPET
Route::post('/compte/delete-compte', [CompteController::class, 'deleteCompte']);
Route::post('/compte/edit-compte', [CompteController::class, 'editCompte']);
Route::post('/compte/edit-password', [CompteController::class, 'editPassword']);

//PROFILE
Route::post('/profile/add-profile-image', [ProfileController::class, 'addProfileImage']);
Route::post('/profile/fetch-profile', [ProfileController::class, 'fetchProfile']);

//RESETPASSWORD
Route::post('/reset_password/check-email', [ResetPasswordController::class, 'checkEmail']);
Route::post('/reset-password/reset', [ResetPasswordController::class, 'resetPassword']);
Route::post('/reset_password/verify-compte', [ResetPasswordController::class, 'verifyCompte']);

//PUBLICATION
Route::post('/publications/addpublications', [PublicationController::class, 'add']);
Route::post('/publications/getPublications', [PublicationController::class, 'getPublications']);
Route::post('/publications/search', [PublicationController::class, 'search']);


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

//ECOLE
Route::post('/ecoles/add', [EcoleController::class, 'addEcole']);
Route::post('/ecoles/select', [EcoleController::class, 'showEcoleByType']);

//ECOLE VILLE
Route::post('/ecolevilles/add', [EcolevilleController::class, 'addEcoleville']);
Route::post('/ecolevilles/select', [EcolevilleController::class, 'getEcolevillesByTypeAndEcole']);

//CONCOURS
Route::post('/pdfconcours/add', [PdfConcoursController::class, 'addConcours']);
Route::post('/pdfconcours/select', [PdfConcoursController::class, 'getConcoursByTypeAndEcole']);

//NOTIFICATION
Route::get('/notifications/select', [NotificationController::class, 'selectNotifications']);
Route::post('/notifications/delete', [NotificationController::class, 'deletNotification']);
Route::get('/notifications/check_is_active', [NotificationController::class, 'checkActiveNotifications']);
Route::post('/notifications/update-status', [NotificationController::class, 'updateStatus']);

// Route to send a message

// Route::post('/chat', [ChatController::class, 'sendMessage']);
// Route::delete('/chat/{id}', [ChatController::class, 'deleteMessage']);
// Route::get('/chat/active', [ChatController::class, 'activeMessages']);
// Route::get('/chat/{id_user}', [ChatController::class, 'selectMessages']);
// Route::put('/chat/{id_user}', [ChatController::class, 'updateMessages']);
