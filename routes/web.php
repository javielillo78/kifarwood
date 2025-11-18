<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Frontend\HomePublicController;
use App\Http\Controllers\Frontend\ProductoPublicController;
use App\Http\Controllers\Frontend\ServicioPublicController;
use App\Http\Controllers\Frontend\ContactoController;
use App\Http\Controllers\Frontend\CestaController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ImagenController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Admin\CompraController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::get('/', [HomePublicController::class, 'index'])->name('public.index');
Route::get('/productos', [ProductoPublicController::class, 'index'])->name('public.productos.index');
Route::get('/productos/{producto}', [ProductoPublicController::class, 'show'])->name('public.productos.show');
Route::get('/servicios', [ServicioPublicController::class, 'index'])->name('public.servicios.index');
Route::get('/servicios/{slug}', [ServicioPublicController::class, 'show'])->name('public.servicios.show');
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::get('/contacto', [ContactoController::class, 'index'])
    ->name('public.contacto.index');
Route::post('/contacto', [ContactoController::class, 'send'])
    ->name('public.contacto.send');

Route::prefix('cesta')->name('public.cesta.')->group(function () {
    Route::get('/',                     [CestaController::class, 'index'])->name('index');
    Route::post('/agregar/{producto}',  [CestaController::class, 'add'])->name('add');
    Route::patch('/cantidad/{id}',      [CestaController::class, 'updateQty'])->name('qty');
    Route::delete('/item/{id}',         [CestaController::class, 'remove'])->name('remove');
    Route::delete('/vaciar',            [CestaController::class, 'clear'])->name('clear');
    Route::get('/tramitar',             [CestaController::class, 'checkout'])->name('checkout');
});
Route::get('/cesta/checkout', [CestaController::class, 'checkout'])
    ->name('public.cesta.checkout');
Route::post('/cesta/confirmar', [CestaController::class, 'confirmar'])
    ->middleware('auth')
    ->name('public.cesta.confirmar');
Route::get('/cesta/factura/{pedido}', [CestaController::class, 'factura'])
    ->middleware('auth')
    ->name('public.cesta.factura');
Route::get('/cesta/result/{pedido}', function (\App\Models\Pedido $pedido) {
    return view('public.cesta.result', compact('pedido'));
    })->middleware('auth')->name('public.cesta.result');

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
    Route::resource('/admin/productos', ProductoController::class)->names('admin.productos');
    Route::resource('/admin/categorias', CategoriaController::class)->names('admin.categorias');
    Route::resource('/admin/usuarios', UsuarioController::class)->names('admin.usuarios')->except(['show']);
    Route::resource('/admin/servicios', ServicioController::class)->names('admin.servicios');
    Route::delete('/admin/imagenes/{imagen}', [ImagenController::class, 'destroy'])->name('admin.imagenes.destroy');
    Route::delete('/admin/servicios/imagenes/{imagen}', [ServicioController::class, 'destroyImagen'])->name('admin.servicios.imagenes.destroy');
    Route::get('/admin/ventas', [VentaController::class, 'index'])->name('admin.ventas.index');
    Route::delete('/admin/ventas/{pedido}', [VentaController::class, 'destroy'])->name('admin.ventas.destroy');
    Route::resource('/admin/compras', CompraController::class)->names('admin.compras')->only(['index','store','destroy']);

});