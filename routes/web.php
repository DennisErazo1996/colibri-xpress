<?php

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
    return view('welcome');
});

Route::get('/bienvenido', function () {
    return view('/bienvenido');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;            
use App\Http\Controllers\CajasController;            
use App\Http\Controllers\VentasController;            
            

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');

	Route::get('/crear-casillero', [RegisterController::class, 'create'])->middleware('guest')->name('crear-casillero');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static'); 

	//Endpoints pageController
	Route::get('/caja/{idCaja}/paquetes', [PageController::class, 'paquetes']); 
	Route::get('/cajas', [PageController::class, 'cajas'])->name('cajas')->middleware('checkUser');
	Route::post('/crear-caja', [CajasController::class, 'crearCaja'])->name('crear-caja')->middleware('checkUser');
	Route::get('/caja/{id}/pedidos', [PageController::class, 'pedidos'])->middleware('checkUser');
	Route::get('/pedidos', [PageController::class, 'verPedidos'])->name('pedidos')->middleware('checkUser');
	Route::post('/ver-pedidos/caja/{id}', [CajasController::class, 'pedidos'])->name('ver-pedidos')->middleware('checkUser');
	Route::get('/ver-pedidos/caja/{id}/cliente/{idCliente}', [PageController::class, 'pedidosCliente'])->name('ver-pedidos-cliente')->middleware('checkUser');
	Route::get('/productos', [PageController::class, 'verProductos'])->name('productos')->middleware('checkUser');;
	Route::get('/clientes', [PageController::class, 'clientes'])->name('clientes')->middleware('checkUser');;
	Route::get('/{page}', [PageController::class, 'index'])->name('page')->middleware('checkUser');
	
	//Endpoints cajasController
	Route::post('/pedidos-cliente/caja/{id}/cliente/{idCliente}', [CajasController::class, 'verPedidosCliente'])->name('pedidos-cliente')->middleware('checkUser');
	Route::post('/eliminar-pedido/caja/{idCaja}/cliente/{idCliente}', [CajasController::class, 'eliminarPedido'])->name('eliminar-pedido')->middleware('checkUser');
	Route::post('/editar-pedido', [CajasController::class, 'editarPedido'])->name('editar-pedido')->middleware('checkUser');
	Route::post('/crear-pedido', [CajasController::class, 'agregarPedido'])->middleware('checkUser');
	Route::post('/eliminar-caja/{idCaja}', [CajasController::class, 'eliminarCaja'])->name('eliminar-caja')->middleware('checkUser');
	Route::post('/editar-caja', [CajasController::class, 'editarCaja'])->name('editar-caja')->middleware('checkUser');
	Route::post('/ver-paquetes/caja/{id}', [CajasController::class, 'verPaquetes'])->name('ver-paquetes')->middleware('checkUser');
	Route::post('/registrar-paquete', [CajasController::class, 'registrarPaquete'])->middleware('checkUser');;
	Route::post('/editar-paquete', [CajasController::class, 'editarPaquete'])->name('editar-paquete')->middleware('checkUser');
	Route::post('/enviar-paquetes', [CajasController::class, 'enviarPaquetes'])->name('enviar-paquetes')->middleware('checkUser');
	Route::post('/ver-paquetes-enviados/caja/{id}', [CajasController::class, 'verPaquetesEnviados'])->name('ver-paquetes-enviados')->middleware('checkUser');
	Route::post('/guardar-envio', [CajasController::class, 'guardarDatosEnvios'])->name('guardar-envio')->middleware('checkUser');
	Route::post('/datos-envio', [CajasController::class, 'datosTotalesEnvio'])->name('datos-envio')->middleware('checkUser');
	Route::post('/actualizar-estado-pago', [CajasController::class, 'actualizarEstadoPago'])->name('estado-pago')->middleware('checkUser');
	
	//Endpoints ventasController
	Route::post('/ver-clientes', [VentasController::class, 'verClientes'])->name('ver-clientes')->middleware('checkUser');
	Route::post('/registrar-cliente', [VentasController::class, 'registrarCliente'])->name('registrar-cliente')->middleware('checkUser');
	Route::post('/ver-productos', [VentasController::class, 'verProductos'])->name('ver-productos')->middleware('checkUser');
	Route::post('/registrar-producto', [VentasController::class, 'registrarProducto'])->name('registrar-producto')->middleware('checkUser');
	Route::post('/registrar-venta', [VentasController::class, 'registrarVenta'])->name('registrar-venta')->middleware('checkUser');
	Route::post('/ver-ventas', [VentasController::class, 'verVentas'])->name('ver-ventas')->middleware('checkUser');
	Route::post('/ver-creditos', [VentasController::class, 'verCreditos'])->name('ver-creditos')->middleware('checkUser');
	Route::post('/ver-total-productos', [VentasController::class, 'datosTotalesProductos'])->name('total-productos')->middleware('checkUser');
	Route::post('/ver-total-ventas', [VentasController::class, 'datosTotalesVentas'])->name('total-ventas')->middleware('checkUser');
	Route::post('/editar-producto', [VentasController::class, 'editarProducto'])->name('editar-producto')->middleware('checkUser');
	Route::post('/eliminar-venta', [VentasController::class, 'eliminarVenta'])->name('eliminar-venta')->middleware('checkUser');
	Route::post('/eliminar-producto', [VentasController::class, 'eliminarProducto'])->name('eliminar-producto')->middleware('checkUser');
	Route::post('/eliminar-credito', [VentasController::class, 'eliminarCredito'])->name('eliminar-credito')->middleware('checkUser');
	Route::post('/ver-total-creditos', [VentasController::class, 'datosTotalesCreditos'])->name('total-creditos')->middleware('checkUser');
	Route::post('/registrar-cuota', [VentasController::class, 'registrarCuota'])->name('registrar-cuota')->middleware('checkUser');
	Route::post('/ver-cuotas-credito', [VentasController::class, 'verCuotasCredito'])->name('ver-cuotas-credito')->middleware('checkUser');
	Route::post('/eliminar-cuota', [VentasController::class, 'eliminarCuota'])->name('eliminar-cuota')->middleware('checkUser');
	Route::post('/editar-cliente', [VentasController::class, 'editarCliente'])->name('editar-cliente')->middleware('checkUser');
	Route::post('/eliminar-cliente', [VentasController::class, 'eliminarCliente'])->name('eliminar-cliente')->middleware('checkUser');
	Route::post('/liquidar-ventas', [VentasController::class, 'liquidarVentas'])->name('liquidar-ventas')->middleware('checkUser');
	Route::post('/liquidar-creditos', [VentasController::class, 'liquidarCreditos'])->name('liquidar-creditos')->middleware('checkUser');

	
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});