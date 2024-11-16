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
    return redirect()->route('login');
});

// auth
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('profile', [AuthController::class, 'profile'])->middleware('auth');
Route::put('profile/update', [AuthController::class, 'update'])->name('user.update');
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

Route::get('daftar-member', [MemberReportController::class, 'daftarMember']);

// regis member
Route::get('regis/member', [RegistrationController::class, 'indexMember']);
Route::get('/search-member', [RegistrationController::class, 'searchMember']);


Route::get('regis/member', [RegistrationController::class, 'indexMember']);

Route::get('regis/non_member', [RegistrationController::class, 'index']);

Route::get('export-daftar-member', [MemberReportController::class, 'exportDaftarMember'])->name('export.daftar-member');
Route::get('export-regis-member', [MemberReportController::class, 'exportRegisMember'])->name('export.regis-member');
Route::get('export-regis-nonmember', [MemberReportController::class, 'exportRegisNonMember'])->name('export.regis-nonmember');


