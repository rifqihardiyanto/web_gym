<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberReportController;
use App\Http\Controllers\NonMemberReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RegistrasiNonMember;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TestimoniController;
use App\Models\Category;
use App\Models\MemberReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('admin', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
});


Route::group([
    'middleware' => 'api'
], function () {
    Route::resources([
        'sliders' => SliderController::class,
        'categories' => CategoryController::class,
        'members' => MemberController::class,
        'non-member-reports' => NonMemberReportController::class,
        'member-reports' => MemberReportController::class,
    ]);

    // Route untuk mendapatkan harga kategori
    Route::get('/api/get-harga/{id}', [RegistrationController::class, 'getHarga']);

    Route::get('/member/search', [MemberReportController::class, 'searchMember']);

    Route::post('/send-email', function (Request $request) {
        // Ambil data email dan pesan dari request
        $email = $request->input('email');
        $message = $request->input('message');

        // Kirim email menggunakan Mail Facade
        Mail::raw($message, function ($msg) use ($email) {
            $msg->to($email)
                ->subject('Informasi Member');
        });

        return response()->json(['success' => true]);
    });
});
