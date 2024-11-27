<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomersReportsController;
use App\Http\Controllers\InvoicesAttachmentsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesReportsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use App\Models\Invoices;
use App\Models\InvoicesAttachments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
})->name('login');

//'AdminController@index'


// Auth::routes();
Auth::routes(['register' => false]);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('/invoices',InvoicesController::class)->middleware('checkuser');
Route::post('/Test',[InvoicesController::class,'Test'])->name('Test');
Route::resource('/sections',SectionsController::class);
Route::resource('/products',ProductController::class);
Route::get('/section',[InvoicesController::class,'getSections'])->name('getsection');
Route::post('Status_Update',[InvoicesController::class,'Status_Update'])->name('Status_Update');
Route::get('invoices_Archive',[InvoicesController::class,'ShowInvoicesArchive'])->name('invoices.ShowInvoicesArchive');
Route::get('invoices_Paid',[InvoicesController::class,'ShowInvoicesPaid'])->name('invoices.ShowInvoicesPaid');
Route::get('invoices_Unpaid',[InvoicesController::class,'ShowInvoicesUnpaid'])->name('invoices.ShowInvoicesUnpaid');
Route::get('invoices_Partial',[InvoicesController::class,'ShowInvoicesPartial'])->name('invoices.ShowInvoicesPartial');
Route::delete('Archive_Invoices',[InvoicesController::class,'Archive_Invoices'])->name('Archive_Invoices');
Route::get('print_invoices/{id}',[InvoicesController::class,'Print_invoices'])->name('print_invoices');
Route::resource('/invoiceDetails',InvoicesDetailsController::class);
// ->middleware('checkuser');
Route::post('delete_file',[InvoicesDetailsController::class,'destroy'])->name('delete_file');
Route::post('/{$file}')->name('fileshow');
Route::get('get_file/{invoicenum}/{fileName}',[InvoicesDetailsController::class,'get_file'])->name('download');
Route::resource('/invoiceAttachments',InvoicesAttachmentsController::class);

Route::middleware('auth')->group(function () {
     // Our resource routes
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    // Route::resource('products', ProductController::class);
});

Route::get('invoices_reports',[InvoicesReportsController::class,'index'])->name('invoices_reports')->middleware('checkuser');;
Route::post('Search_invoices',[InvoicesReportsController::class,'Search_Invoices'])->name('Search_invoices');
Route::get('/customers_reports',[CustomersReportsController::class,'index'])->name('customers_reports');
Route::post('Search_customers',[CustomersReportsController::class,'SearchCustomers'])->name('Search_customers');

// =========================================================================
 // Notification Routes

 Route::name('notification.')->group(function () {
    Route::get('/readnotification/{notification_id}', [NotificationController::class, 'ReadNotifcation'])->name('read');
    Route::get('/readallnotification', [NotificationController::class, 'ReadAllNotifcation'])->name('readall');
    Route::get('fetchNotification',[NotificationController::class,'FetchNotification'])->name('fetch');
});
//  ===========================================================================
Route::get('/{page}', [AdminController::class,'index']);
