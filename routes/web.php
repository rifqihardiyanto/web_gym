<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberReportController;
use App\Http\Controllers\NonMemberReportController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TentangController;

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
    return redirect()->route('public');
});

// auth
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);

Route::get('login_member', [AuthController::class, 'login_member']);
Route::post('login_member', [AuthController::class, 'login_member_action']);
Route::get('logout_member', [AuthController::class, 'logout_member']);

Route::post('register_member', [AuthController::class, 'register_member_action']);

// dashboard
Route::get('dashboard', [DashboardController::class, 'index']);
Route::get('slider', [SliderController::class, 'list']);
Route::get('kategori', [CategoryController::class, 'list']);
Route::get('member', [MemberController::class, 'list']);
Route::get('non-member-reports', [NonMemberReportController::class, 'list']);
Route::get('member-reports', [MemberReportController::class, 'list']);

// regis member
Route::get('regis/member', [RegistrationController::class, 'indexMember']);
Route::get('/search-member', [RegistrationController::class, 'searchMember']);


Route::get('regis/member', [RegistrationController::class, 'indexMember']);

Route::get('regis/non_member', [RegistrationController::class, 'index']);

Route::get('tentang', [TentangController::class, 'index']);
Route::post('tentang/{about}', [TentangController::class, 'update']);

// Laporan
Route::get('report', [ReportController::class, 'index']);
Route::get('payment', [PaymentController::class, 'list']);


// public
Route::get('home', [HomeController::class, 'index'])->name('public');
Route::get('home2', [HomeController::class, 'index2'])->name('public2');
Route::get('shop', [HomeController::class, 'shop']);
Route::get('about', [HomeController::class, 'about']);
Route::get('produks/{category}', [HomeController::class, 'produks']);
Route::get('produk/{id}', [HomeController::class, 'produk']);
Route::get('cart', [HomeController::class, 'cart']);
Route::get('checkout', [HomeController::class, 'checkout']);
Route::get('orders', [HomeController::class, 'orders']);
Route::get('contact', [HomeController::class, 'kontak']);
Route::get('faq', [HomeController::class, 'faq']);
Route::get('catalog', [HomeController::class, 'catalog']);

Route::post('addtocart', [HomeController::class, 'add_to_cart']);
Route::get('deletecart/{cart}', [HomeController::class, 'delete_from_cart']);

Route::get('get_kota/{id}', [HomeController::class, 'get_kota']);
Route::get('get_ongkir/{destination}', [HomeController::class, 'get_ongkir']);


