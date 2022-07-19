<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\pdfController;
/*
|------------------------------------------------------ --------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('product/donhang');
});
// ======================================================TRang route chính ============================
Route::get('/homepage', [PageController::class, 'getIndex'])->name('home');
Route::get('/about', [PageController::class, 'getAbout']);
Route::get('/contact', [PageController::class, 'getContact']);
Route::get('loaisanpham/{type}', [PageController::class, 'getLoaisp']);
Route::get('chitietsp/{id}', [PageController::class, 'getChitietsp']);
Route::get('/signup', [PageController::class, 'getSignUp']);
Route::post('/signup', [PageController::class, 'postSignUp']);
Route::get('/signin', [PageController::class, 'getSignIn']);
Route::post('/login', [PageController::class, 'postSignIn']);
Route::get('/logout', [PageController::class, 'postlogout']);
Route::get('/search', [PageController::class, 'getsearch']);



//=================================================== Giỏ hàng=============================
Route::get('/checkout', [PageController::class, 'getcheckout']);
Route::post('/checkout', [PageController::class, 'postCheckout'])->name('checkout');
Route::get('/add-to-cart/{id}', [PageController::class, 'getAddToCart']);
Route::get('/delete-cart/{id}', [PageController::class, 'getDelItemCart']);
// =============================================CRUD admin============================= 

// Route::get('/adminIndex', [PageController::class, 'getIndexAdmin'])->name('admin');
Route::get('/adminIndex', [PageController::class, 'getAdmin'])->name('admin');
Route::get('/managePayment', [PageController::class, 'getOrderPayment']);
Route::get('/orderUnconfimred', [PageController::class, 'getIndexAdmin']);
Route::get('/orderDelivery', [PageController::class, 'getDelivery']);
Route::get('/orderDeliveried', [PageController::class, 'getDeliveried']);
Route::get('/orderDelete', [PageController::class, 'getDelete']);
Route::get('/postConfirm/{id},{id_status}', [PageController::class, 'postConfirm']);
Route::get('/postDelete/{id},{id_status}', [PageController::class, 'postDelete']);
// Route::get('/adminOrder/{status}', [PageController::class, 'getIndexAdmin'])->name('admin');
Route::get('/adminLogin', [PageController::class, 'loginAdmin'])->name('LoginAdmin');
Route::post('/adminLogin', [PageController::class, 'postLoginAdmin']);

// Route::get('/admin', [PageController::class, 'getAdmin']);

//Logout Admin
Route::get('/logoutAdmin', [PageController::class, 'logoutAdmin']);

// ==============================import, export ===========================
Route::get('/export-form', [pdfController::class, 'index']);
Route::get('/export', [pdfController::class, 'pdf'])->name('export');
Route::get('/importForm', [pdfController::class, 'importForm']);
Route::post('import', [pdfController::class, 'import'])->name('import');

//===========================CỔNG THANH TOÁN===========================
// Route::get('payment', 'PayPalController@payment')->name('payment');
// Route::get('cancel', 'PayPalController@cancel')->name('payment.cancel');
// Route::get('payment/success', 'PayPalController@success')->name('payment.success');

Route::get('returnVNpay', 'App\Http\Controllers\PageController@returnVNpay')->name('returnVNpay');
Route::post('vnpay',  'App\Http\Controllers\PageController@vnpay')->name('vnpay');
Route::get('paypal', 'App\Http\Controllers\PayPalController@getPaymentStatus')->name('status');