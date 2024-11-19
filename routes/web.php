<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\UserController;


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

//Route::httpMethod('/isi-path', [NamaController::class, 'namafunc'])->name ('identitas_unique_route');
//httpMethod :
//1.get ->mengambil data/menampilkan halaman
//2.post ->menambahkan data ke db
//3.put/patch -> mengupdate data ke db
//4.delete -> menghapus data dari db



    // Route::get('/', function() {
    //     return view ('user.login');
    // })->name('login');
    Route::middleware(['isGuest'])->group(function(){
        Route::get('/', [Usercontroller::class, 'showLogin'])->name('login.auth');
        Route::post('/login', [UserController::class, 'loginAuth'])->name('login.proses');
    });


//diakses setelah login
Route::middleware(['isLogin'])->group(function() {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/landing', [LandingPageController::class, 'index']) ->name('landing-page');
Route::middleware(['isAdmin'])->group(function() {
    Route::get('/order', [OrderController::class, 'indexAdmin'])->name('pembelian.admin');
    Route::get('/order/export-excel', [OrderController::class, 'exportExcel'])->name('pembelian.admin.export');
    Route::get('/user/export-excel', [UserController::class, 'exportExcel'])->name('user.admin.export');
    Route::get('/medicine/export-excel', [MedicineController::class, 'exportExcel'])->name('medicine.admin.export');

    // Route::get('/home', function () {
        //     return view('pages.welcome');
        // })->name('home');

        // Route::get('/user', function () {
            //     return view('user.index');
            // });


// '/fitur/bagian-fitur'
    route::prefix('/obat')->name('obat.')->group(function(){
        route::get('/tambah-obat', [MedicineController::class, 'create'])->name('tambah_obat');
        Route::get('/data-obat', [MedicineController::class, 'index']) ->name('data-obat');
        route::post('/tambah-obat', [MedicineController::class, 'store'])->name('tambah_obat.formulir');
        route::get('/data', [MedicineController::class, 'index'])->name('data');
        route::delete('/data/obat/{id}', [MedicineController::class, 'destroy'])->name('delete');
        route::get('/edit/{id}', [MedicineController::class, 'edit'])->name('edit');
        route::patch('/obat/update/{id}', [MedicineController::class, 'update'])->name('update');
        route::patch('/edit/stock/{id}', [MedicineController::class, 'updateStock'])->name('edit.stock');
    });

    Route::prefix('/user')->name('user.')->group(function() {
        Route::get('/login', [UserController::class, 'index'])->name('login');
        Route::get('tambah_data', [UserController::class, 'create'])->name('tambah_data');
        route::post('/tambah_data', [UserController::class, 'store'])->name('tambah_data.formulir');
        route::get('/data', [UserController::class, 'index'])->name('data');
        route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
        route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
    });

});
});

Route::middleware('isKasir')->group(function(){
    Route::prefix('/pembelian')->name('pembelian.')->group(function(){
        Route::get('/order', [OrderController::class, 'index'])->name('order');
        Route::get('/formulir', [OrderController::class, 'create'])->name('formulir');
        Route::post('/store-order', [OrderController::class, 'store'])->name('store.order');
        Route::get('/print/{id}', [OrderController::class, 'show'])->name('print');
        Route::get('/download/{id}', [OrderController::class, 'downloadPDF'])->name('download_pdf');
    });
});
