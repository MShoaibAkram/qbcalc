<?php

use App\Http\Controllers\Backend\AssignPermissionController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ResetPasswordUserController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return redirect()->route('login');
})->name('home.index');

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::group(['prefix' => 'backend', 'as' => 'backend.', 'middleware' => 'auth'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('permission:lihat dasbor');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update/{id}', [ProfileController::class, 'updateGeneralInformation'])->name('profile.update.information');
    Route::put('/profile/update/password/{id}', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::post('/profile/update/image', [ProfileController::class, 'updateImage'])->name('profile.update.image');

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:lihat role');
        Route::get('/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:tambah role');
        Route::post('/', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:tambah role');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:ubah role');
        Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:ubah role');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:hapus role');
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:lihat permission');
        Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:tambah permission');
        Route::post('/', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:tambah permission');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:ubah permission');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:ubah permission');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:hapus permission');
    });

    Route::group(['prefix' => 'assignpermission'], function () {
        Route::get('/', [AssignPermissionController::class, 'index'])->name('assignpermission.index')->middleware('permission:lihat assign permission');
        Route::get('/{role}/edit', [AssignPermissionController::class, 'editRolePermission'])->name('assignpermission.edit')->middleware('permission:ubah assign permission');
        Route::post('/updaterolepermission', [AssignPermissionController::class, 'updateRolePermission'])->name('assignpermission.update')->middleware('permission:ubah assign permission');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index')->middleware('permission:lihat pengguna');
        Route::get('/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:tambah pengguna');
        Route::post('/', [UserController::class, 'store'])->name('users.store')->middleware('permission:tambah pengguna');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:ubah pengguna');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:ubah pengguna');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:hapus pengguna');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:lihat pengguna');

        Route::put('/users/{user}/resetpassword', [ResetPasswordUserController::class, 'resetPassword'])->name('users.reset.password')->middleware('permission:ubah pengguna');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/index', [SettingController::class, 'index'])->name('setting.index')->middleware('permission:lihat pengaturan');
        Route::put('/updateinformation/{setting}/', [SettingController::class, 'updateInformation'])->name('setting.update.information')->middleware('permission:ubah pengaturan');
        Route::put('/updatelogo/{setting}/', [SettingController::class, 'updateLogo'])->name('setting.update.logo')->middleware('permission:ubah pengaturan');
        Route::put('/updatefrontimage/{setting}/', [SettingController::class, 'updateFrontImage'])->name('setting.update.front.image')->middleware('permission:ubah pengaturan');
    });

    Route::post('/invoice/get_invoice', [InvoiceController::class, 'getInvoices'])->name('invoice.getInvoice');
});

Route::group(["middleware" => "auth"], function() {
    Route::get("/invoice", [InvoiceController::class, "getInvoice"])->name("invoice.get");
    Route::get("/invoice/view", [InvoiceController::class, "viewInvoice"])->name("invoice.view");
    Route::post("/invoice/details", [InvoiceController::class, "getInvoiceDetails"]);

    Route::get("/report/unpaidInvoice/", [ReportController::class, "unpaidInvoice"])->name("report.unpaidInvoice");
    Route::get("/report/current/", [ReportController::class, "current"])->name("report.current");
    Route::get("/report/history/", [ReportController::class, "history"])->name("report.history");
    Route::get("/report/other/", [ReportController::class, "other"])->name("report.other");
    Route::get("/bill/view", [BillController::class, "view"])->name("bill.view");
    Route::get("/bill/sendToQB/", [BillController::class, "sendToQB"])->name("bill.sendToQB");
    Route::get("/history/update/", [HistoryController::class, "update"])->name("history.update");
    Route::get("/commission/plan/", [CommissionController::class, "plan"])->name("commission.plan");
    Route::get("/custom/", [CustomController::class, "index"])->name("custom.index");
    Route::get("/system/option/", [SystemController::class, "option"])->name("system.option");
    Route::get("/system/utility/", [SystemController::class, "utility"])->name("system.utility");
    Route::post("/system/getSalesrep/", [SystemController::class, "getSalesrep"])->name("system.getSalesrep");


    Route::group(['prefix' => 'report'], function(){
        Route::get("commRptBySalesRep", [ReportController::class, "commReportBySalesRep"])->name("report.commRptSalesRep");
        Route::post("commRptBySalesRep", [ReportController::class, "postCommReportBySalesRep"]);
    });
});
