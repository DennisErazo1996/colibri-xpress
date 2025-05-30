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
	
	//Route::get('/envios', [PageController::class, 'verEnvios'])->name('envios');
	Route::get('/ver-envios', [CajasController::class, 'indexEnvios'])->name('envios.index');
	Route::post('/ver-paquetes-cliente', [CajasController::class, 'verPaquetesCliente'])->name('ver-paquetes-cliente');

	Route::middleware('checkUser')->group(function () {

		Route::post('/casilleros', [PageController::class, 'casilleros'])->name('casilleros');


		//Endpoints pageController
		Route::get('/caja/{idCaja}/paquetes', [PageController::class, 'paquetes']); 
		Route::get('/cajas', [PageController::class, 'cajas'])->name('cajas');
		Route::post('/crear-caja', [CajasController::class, 'crearCaja'])->name('crear-caja');
		Route::get('/caja/{id}/pedidos', [PageController::class, 'pedidos']);
		Route::get('/pedidos', [PageController::class, 'verPedidos'])->name('pedidos');
		Route::post('/ver-pedidos/caja/{id}', [CajasController::class, 'pedidos'])->name('ver-pedidos');
		Route::get('/ver-pedidos/caja/{id}/cliente/{idCliente}', [PageController::class, 'pedidosCliente'])->name('ver-pedidos-cliente');
		Route::get('/productos', [PageController::class, 'verProductos'])->name('productos');
		Route::get('/clientes', [PageController::class, 'clientes'])->name('clientes');
		Route::get('/{page}', [PageController::class, 'index'])->name('page');
		Route::get('/liquidaciones', [PageController::class, 'liquidaciones'])->name('liquidaciones');
		
		//Endpoints cajasController
		Route::post('/pedidos-cliente/caja/{id}/cliente/{idCliente}', [CajasController::class, 'verPedidosCliente'])->name('pedidos-cliente');
		Route::post('/eliminar-pedido/caja/{idCaja}/cliente/{idCliente}', [CajasController::class, 'eliminarPedido'])->name('eliminar-pedido');
		Route::post('/editar-pedido', [CajasController::class, 'editarPedido'])->name('editar-pedido');
		Route::post('/crear-pedido', [CajasController::class, 'agregarPedido']);
		Route::post('/eliminar-caja/{idCaja}', [CajasController::class, 'eliminarCaja'])->name('eliminar-caja');
		Route::post('/editar-caja', [CajasController::class, 'editarCaja'])->name('editar-caja')->middleware('checkUser');
		Route::post('/ver-paquetes/caja/{id}', [CajasController::class, 'verPaquetes'])->name('ver-paquetes');
		Route::post('/registrar-paquete', [CajasController::class, 'registrarPaquete'])->middleware('checkUser');;
		Route::post('/editar-paquete', [CajasController::class, 'editarPaquete'])->name('editar-paquete');
		Route::post('/enviar-paquetes', [CajasController::class, 'enviarPaquetes'])->name('enviar-paquetes');
		Route::post('/ver-paquetes-enviados/caja/{id}', [CajasController::class, 'verPaquetesEnviados'])->name('ver-paquetes-enviados');
		Route::post('/guardar-envio', [CajasController::class, 'guardarDatosEnvios'])->name('guardar-envio');
		Route::post('/datos-envio', [CajasController::class, 'datosTotalesEnvio'])->name('datos-envio');
		Route::post('/actualizar-estado-pago', [CajasController::class, 'actualizarEstadoPago'])->name('estado-pago');
		Route::post('/liquidar-caja', [CajasController::class, 'liquidarCaja'])->name('liquidar-caja');
		Route::post('/ver-liquidaciones-caja', [CajasController::class, 'verLiquidacionesCaja'])->name('ver-cajas-liquidadas');
		
		
		
		//Endpoints ventasController
		Route::post('/ver-clientes', [VentasController::class, 'verClientes'])->name('ver-clientes');
		Route::post('/registrar-cliente', [VentasController::class, 'registrarCliente'])->name('registrar-cliente');
		Route::post('/ver-productos', [VentasController::class, 'verProductos'])->name('ver-productos');
		Route::post('/registrar-producto', [VentasController::class, 'registrarProducto'])->name('registrar-producto');
		Route::post('/registrar-venta', [VentasController::class, 'registrarVenta'])->name('registrar-venta');
		Route::post('/ver-ventas', [VentasController::class, 'verVentas'])->name('ver-ventas');
		Route::post('/ver-creditos', [VentasController::class, 'verCreditos'])->name('ver-creditos');
		Route::post('/ver-total-productos', [VentasController::class, 'datosTotalesProductos'])->name('total-productos');
		Route::post('/ver-total-ventas', [VentasController::class, 'datosTotalesVentas'])->name('total-ventas');
		Route::post('/editar-producto', [VentasController::class, 'editarProducto'])->name('editar-producto');
		Route::post('/eliminar-venta', [VentasController::class, 'eliminarVenta'])->name('eliminar-venta');
		Route::post('/eliminar-producto', [VentasController::class, 'eliminarProducto'])->name('eliminar-producto');
		Route::post('/eliminar-credito', [VentasController::class, 'eliminarCredito'])->name('eliminar-credito');
		Route::post('/ver-total-creditos', [VentasController::class, 'datosTotalesCreditos'])->name('total-creditos');
		Route::post('/registrar-cuota', [VentasController::class, 'registrarCuota'])->name('registrar-cuota');
		Route::post('/ver-cuotas-credito', [VentasController::class, 'verCuotasCredito'])->name('ver-cuotas-credito')->middleware('checkUser');
		Route::post('/eliminar-cuota', [VentasController::class, 'eliminarCuota'])->name('eliminar-cuota');
		Route::post('/editar-cliente', [VentasController::class, 'editarCliente'])->name('editar-cliente');
		Route::post('/eliminar-cliente', [VentasController::class, 'eliminarCliente'])->name('eliminar-cliente');
		Route::post('/liquidar-ventas', [VentasController::class, 'liquidarVentas'])->name('liquidar-ventas');
		Route::post('/liquidar-creditos', [VentasController::class, 'liquidarCreditos'])->name('liquidar-creditos');
		Route::post('/ver-liquidaciones', [VentasController::class, 'verLiquidaciones'])->name('ver-liquidaciones');
		Route::post('/ver-montos-liquidados', [VentasController::class, 'montosLiquidados'])->name('ver-montos-liquidados');
		Route::post('/cambiar-inversores', [VentasController::class, 'cambiarInversores'])->name('cambiar-inversores');
		
	});
	
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});