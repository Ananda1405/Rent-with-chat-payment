<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BotManController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Landlord\PdfController;
use App\Http\Controllers\Renter\RenPdfController;
use App\Http\Controllers\SslPayment2Controller;

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

Route::get('/', 'HomeController@index')->name('welcome');

Route::get('/descending-order-houses-price', 'HomeController@highToLow')->name('highToLow');
Route::get('/ascending-order-houses-price', 'HomeController@lowToHigh')->name('lowToHigh');

Route::get('/search-result', 'HomeController@search')->name('search');
Route::get('/search-result-by-range', 'HomeController@searchByRange')->name('searchByRange');

Route::get('/houses/details/{id}', 'HomeController@details')->name('house.details');
Route::get('/all-available/houses', 'HomeController@allHouses')->name('house.all');
Route::get('/available-houses/area/{id}', 'HomeController@areaWiseShow')->name('available.area.house');

Route::post('/house-booking/id/{id}', 'HomeController@booking')->name('booking');
Route::post('/house-booking-payment/id/{id}', 'HomeController@bookingPayment')->name('booking.payment');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');

Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');

// ===========For Comment===================
Route::post('/comment/{post}', 'CommentController@store')->name('comment.store')->middleware(['auth', 'verified']);
Route::post('/comment-reply/{comment}', 'CommentReplyController@store')->name('reply.store')->middleware(['auth', 'verified']);


//Chatbot
// Route::get('/botman', [BotManController::class,"handle"]);
// Route::post('/botman', [BotManController::class,"handle"]);
// Route::post('/botman', 'BotManController@boot');

Route::match(['get', 'post'], '/botman', [BotManController::class,"handle"]);

//admin

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin', 'verified']],
    function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::resource('area', 'AreaController');
        Route::resource('house', 'HouseController');
        Route::get('manage-landlord', 'HouseController@manageLandlord')->name('manage.landlord');
        Route::delete('manage-landlord/destroy/{id}', 'HouseController@removeLandlord')->name('remove.landlord');
        Route::get('admin/house/approve/{id}', 'HouseController@switch')->name('house.approve');

        Route::get('manage-renter', 'HouseController@manageRenter')->name('manage.renter');
        Route::delete('manage-renter/destroy/{id}', 'HouseController@removeRenter')->name('remove.renter');

        Route::get('profile-info', 'SettingsController@showProfile')->name('profile.show');
        Route::get('profile-info/edit/{id}', 'SettingsController@editProfile')->name('profile.edit');
        Route::post('profile-info/update/', 'SettingsController@updateProfile')->name('profile.update');

        Route::get('booked-houses-list', 'BookingController@bookedList')->name('booked.list');
        Route::get('booked-houses-history', 'BookingController@historyList')->name('history.list');

        // =========For Comment==============
        Route::get('/comments', 'CommentController@index')->name('comment.index');
        Route::delete('/comment/{id}', 'CommentController@destroy')->name('comment.destroy');
        Route::get('/reply-comments', 'CommentReplyController@index')->name('reply-comment.index');
        Route::delete('/reply-comment/{id}', 'CommentReplyController@destroy')->name('reply-comment.destroy');

    });

//landlord

Route::group(['as' => 'landlord.', 'prefix' => 'landlord', 'namespace' => 'Landlord', 'middleware' => ['auth', 'landlord', 'verified']],
    function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::resource('area', 'AreaController');
        Route::resource('house', 'HouseController');
        Route::get('house/switch-status/{id}', 'HouseController@switch')->name('house.status');
        Route::get('house-pending', 'HouseController@pendingHouse')->name('house.pending');

        Route::get('booking-request-list', 'BookingController@bookingRequestListForLandlord')->name('bookingRequestList'); 
        Route::post('booking-request/accept/{id}', 'BookingController@bookingRequestAccept')->name('request.accept');
        Route::post('booking-request/reject/{id}', 'BookingController@bookingRequestReject')->name('request.reject');
        Route::get('booking/history', 'BookingController@bookingHistory')->name('history');
        Route::get('booked/currently/renter', 'BookingController@currentlyStaying')->name('currently.staying');
        Route::post('renter/leave/{id}', 'BookingController@leaveRenter')->name('leave.renter');

        Route::get('profile-info', 'SettingsController@showProfile')->name('profile.show');
        Route::get('profile-info/edit/{id}', 'SettingsController@editProfile')->name('profile.edit');
        Route::post('profile-info/update/', 'SettingsController@updateProfile')->name('profile.update');


        // =================For Pdf Download=======================
        Route::get('/generate-pdf/{house_id}',[PdfController::class,'generate_pdf'])->name('invoice');
        // Route::get('/download-pdf',[PdfController::class,'download_pdf']);
       
        // Route::get('house/invoice/{house_id}', 'HouseController@viewinvoice')->name('invoice');
        // =================For Pdf Download=======================


    });

//renter

Route::group(['as' => 'renter.', 'prefix' => 'renter', 'namespace' => 'renter', 'middleware' => ['auth', 'renter', 'verified']],
    function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        Route::get('areas', 'DashboardController@areas')->name('areas');

        Route::get('houses', 'DashboardController@allHouses')->name('allHouses');
        Route::get('house/details/{id}', 'DashboardController@housesDetails')->name('houses.details');

        Route::get('profile-info', 'SettingsController@showProfile')->name('profile.show');
        Route::get('profile-info/edit/{id}', 'SettingsController@editProfile')->name('profile.edit');
        Route::post('profile-info/update/', 'SettingsController@updateProfile')->name('profile.update');

        Route::get('booking/history', 'DashboardController@bookingHistory')->name('booking.history');
        Route::get('pending/booking', 'DashboardController@bookingPending')->name('booking.pending');
        Route::post('pending/booking/cancel/{id}', 'DashboardController@cancelBookingRequest')->name('cancel.booking.request');

        Route::post('review', 'DashboardController@review')->name('review');
        Route::get('review-edit/{id}', 'DashboardController@reviewEdit')->name('review.edit');
        Route::post('review-update/{id}', 'DashboardController@reviewUpdate')->name('review.update');


        // ==============For Comment===============
        Route::get('comments', 'CommentController@index')->name('comment.index');
        Route::delete('/comment/{id}', 'CommentController@destroy')->name('comment.destroy');
        Route::get('/reply-comments', 'CommentReplyController@index')->name('reply-comment.index');
        Route::delete('/reply-comment/{id}', 'CommentReplyController@destroy')->name('reply-comment.destroy');


        // ================for wishlist===================
        Route::post('wishlist/{house_id}',[WishlistController::class,'addWsihlist']);
        Route::get('view/wishlist',[WishlistController::class,'index'])->name('view.wishlist');
        Route::delete('cancel/wishlist/{id}',[WishlistController::class,'cancelWishlist'])->name('cancel.wishlist');
        // Route::post('cancel/wishlist/{id}', 'WishlistController@cancelWishlist')->name('cancel.wishlist');

        // =================For Pdf Download=======================
        Route::get('/generate-pdf/{book_id}',[RenPdfController::class,'generate_pdf'])->name('ren.invoice');
    });

        // SSLCOMMERZ Start
        Route::post('/checkout', 'SslCommerzPaymentController@checkout');

        Route::post('/land/success', [SslCommerzPaymentController::class, 'Landsuccess']);
        Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
        Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

        Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
        //SSLCOMMERZ END

        // SSLCOMMERZ Start
        Route::post('/checkout2', [SslPayment2Controller::class, 'checkout2']);

        Route::post('/success', [SslPayment2Controller::class, 'success']);
        // Route::post('/fail2', [SslPayment2Controller::class, 'fail2']);
        // Route::post('/cancel2', [SslPayment2Controller::class, 'cancel2']);

        // Route::post('/ipn2', [SslPayment2Controller::class, 'ipn2']);
        //SSLCOMMERZ END