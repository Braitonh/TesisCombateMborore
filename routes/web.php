<?php

use App\Events\EventoDePrueba;
use App\Livewire\Backoffice\Cliente\ClienteComponent;
use App\Livewire\Backoffice\Cliente\ClienteFormComponent;
use App\Livewire\Backoffice\Pedidos\Detalle;
use App\Livewire\Backoffice\Pedidos\PedidosForm;
use App\Livewire\Backoffice\Pedidos\PedidosIndex;
use App\Livewire\Backoffice\Product\ProductComponent;
use App\Livewire\Backoffice\Product\ProductFormComponent;
use App\Livewire\Backoffice\Users\UsersComponent;
use App\Livewire\Backoffice\Users\UsersFormComponent;
use App\Livewire\OrderList;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
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

Route::get('/', function () { return view('welcome');})->name('welcome');

Route::get('/productos', ProductComponent::class)->name('productos.index');
Route::get('/productos/create', ProductFormComponent::class)->name('productos.create');
Route::get('/productos/{productoId}/edit', ProductFormComponent::class)->name('productos.edit');

Route::get('/usuarios', UsersComponent::class)->name('usuarios.index');
Route::get('/usuarios/create', UsersFormComponent::class)->name('usuarios.create');
Route::get('/usuarios/{userId}/edit', UsersFormComponent::class)->name('usuarios.edit');

Route::get('/clientes', ClienteComponent::class)->name('clientes.index');
Route::get('/clientes/create', ClienteFormComponent::class)->name('clientes.create');
Route::get('/clientes/{clienteId}/edit', ClienteFormComponent::class)->name('clientes.edit');

Route::get('/pedidos', PedidosIndex::class)->name('pedidos.index');
Route::get('/pedidos/create', PedidosForm::class)->name('pedidos.create');
Route::get('/pedidos/detalle', Detalle::class)->name('pedidos.detalle');



