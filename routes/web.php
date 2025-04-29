<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Backoffice\Cliente\ClienteComponent;
use App\Livewire\Backoffice\Cliente\ClienteFormComponent;
use App\Livewire\Backoffice\Pedidos\Detalle;
use App\Livewire\Backoffice\Pedidos\PedidosForm;
use App\Livewire\Backoffice\Pedidos\PedidosIndex;
use App\Livewire\Backoffice\Product\ProductComponent;
use App\Livewire\Backoffice\Product\ProductFormComponent;
use App\Livewire\Backoffice\Users\UsersComponent;
use App\Livewire\Backoffice\Users\UsersFormComponent;
use App\Livewire\GaleryFood;
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
// Rutas de login y logout para invitados/autenticados
Route::middleware('guest')->group(function () {
    Route::get('login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/store', function () {
          return view('store'); // esta vista va a tener el HTML completo
     })->name('store');

// Rutas protegidas por autenticación
Route::middleware(['auth', 'check.admin'])->group(function () {

    // Productos
    Route::get('/productos', ProductComponent::class)
         ->name('productos.index');
    Route::get('/productos/create', ProductFormComponent::class)
         ->name('productos.create');
    Route::get('/productos/{productoId}/edit', ProductFormComponent::class)
         ->name('productos.edit');

    // Usuarios
    Route::get('/usuarios', UsersComponent::class)
         ->name('usuarios.index');
    Route::get('/usuarios/create', UsersFormComponent::class)
         ->name('usuarios.create');
    Route::get('/usuarios/{userId}/edit', UsersFormComponent::class)
         ->name('usuarios.edit');

    // Clientes
    Route::get('/clientes', ClienteComponent::class)
         ->name('clientes.index');
    Route::get('/clientes/create', ClienteFormComponent::class)
         ->name('clientes.create');
    Route::get('/clientes/{clienteId}/edit', ClienteFormComponent::class)
         ->name('clientes.edit');

    // Pedidos
    Route::get('/pedidos', PedidosIndex::class)
         ->name('pedidos.index');
    Route::get('/pedidos/create', PedidosForm::class)
         ->name('pedidos.create');
    Route::get('/pedidos/detalle', Detalle::class)
         ->name('pedidos.detalle');

});


