<?php

use App\Http\Controllers\AdminController;
use App\Models\Addon;
use App\Models\Appliance;
use App\Models\Appsetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

Route::get('/', function () {
    $cost = (float) @Appsetting::first()->cost;
    $peakload = (float) @Appsetting::first()->peakload;
    $col = ['id', 'name as item', 'watts'];
    $items = Appliance::orderBy('id', 'desc')->get($col);
    $tab = [];
    foreach ($items as $item) {
        $tab[] = (object) ['id' => $item->id, 'item' => $item->item, 'watts' => $item->watts];
    }
    $items = json_encode($tab);
    $addons = Addon::orderBy('id', 'desc')->get();
    return view('index', compact('cost', 'peakload', 'items', 'addons'));
})->name('home');

Route::get('/admin/login', function () {
    if (auth()->check()) {
        return redirect(route('admin.home'));
    }
    return view('login');
})->name('login');

Route::post('auth/login', [AdminController::class, 'login'])->name('admin.login');
Route::get('mail/verify/{token}', [AdminController::class, 'mail_verify'])->name('mail.verify');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('logout', function () {
        Auth::logout();
        return redirect(route('login'));
    })->name('logout');
    Route::get('home', [AdminController::class, 'home'])->name('admin.home');
    Route::get('contact', [AdminController::class, 'contact'])->name('admin.contact');
    Route::get('addons', [AdminController::class, 'addons'])->name('admin.addons');
    Route::get('contact/edit/{id}', [AdminController::class, 'contact_edit'])->name('admin.contact.edit');
});

Route::any('make-pdf', [AdminController::class, 'make_pdf'])->name('makepdf');
Route::get('get-pdf/{item?}', [AdminController::class, 'getpdf'])->name('getpdf');

Route::get('embeded/power-calc', [AdminController::class, 'embeded_power_calc']);
