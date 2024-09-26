<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class VentasController extends Controller
{
    public function verClientes(Request $request){

        if ($request->ajax()) {
            
            $data = DB::select("select 
                        row_number() over(order by id desc) as no,
                        nombre_cliente || ' ' || apellido_cliente as nombre_cliente,
                        to_char(created_At::date, 'DD/MM/YYYY') as fecha_registro
                    from pedidos.cx_clientes where deleted_at is null");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    //$url = "{{url('/caja/$row['id_cliente']/pedidos/cliente/'}}";
                    $actions = "<a class='btn btn-1 m-0' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Paquete' data-container='body' data-animation='true'><i class='fi fi-ss-customize-edit'></i></a>";
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }

    }

    public function registrarCliente(Request $request){
        
        $nombreCliente = $request->nombreCliente;
        $apellidoCliente = $request->apellidoCliente;
        $mensaje = "Cliente registrado correctamente";

        DB::select("Insert into pedidos.cx_clientes(nombre_cliente, apellido_cliente, created_at) values(:nombre_cliente, :apellido_cliente, NOW());",
        ['nombre_cliente' => $nombreCliente, 'apellido_cliente' => $apellidoCliente]); 

        return $mensaje;
    }

    public function verProductos(Request $request){

        if ($request->ajax()) {
            
            $data = DB::select("select 
                            row_number() over(order by id) as no,
                            id,
                            id_caja,
                            nombre,
                            cantidad,
                            precio_normal, precio_compra, precio_venta
                        from pedidos.cx_productos
                        where deleted_at is null");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    
                    $actions = "<a class='btn btn-1 m-0' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Paquete' data-container='body' data-animation='true'><i class='fi fi-ss-customize-edit'></i></a>";
                    return $actions;
                })->addColumn('estadoPago', function($row) {
                    // Si el pago está realizado, marcar el checkbox como checked
                    //$checked = $row->id ? 'checked' : '';
                    return "<div class='form-check form-switch justify-content-center'><input class='form-check-input' onchange='cambiarEstadoVenta($row->id, $row->precio_venta, this.checked)' type='checkbox' id='chkPago' ></div>";
                    //return "<input type='checkbox' class='estado-pago-checkbox' onchange='cambiarEstadoPago($row->id_caja, $row->id_usuario)' $checked />";
                })
                ->rawColumns(['opcion', 'estadoPago'])
                ->make(true);
        }

    }

    public function registrarProducto(Request $request){

        $idCaja = $request->idCaja;
        $nombreProducto = $request->nombreProducto;
        $precioNormal = $request->precioNormal;
        $precioCompra = $request->precioCompra;
        $precioVenta = $request->precioVenta;
        $cantidad = $request->cantidad;
        $mensaje = "Producto registrado correctamente";

        DB::select("insert into pedidos.cx_productos(nombre, precio_normal, precio_compra, precio_venta, created_at, id_caja, cantidad)
                    values(:nombre_producto, :precio_normal, :precio_compra, :precio_venta, now(), :id_caja, :cantidad)", 
                    ['nombre_producto' => $nombreProducto, 'precio_normal' => $precioNormal, 'precio_compra' => $precioCompra, 'precio_venta' => $precioVenta,
                    'id_caja' => $idCaja, 'cantidad' => $cantidad]);


        return $mensaje;
    }

    public function registrarVenta(Request $request){

        $idCliente = $request->idCliente;
        $idProducto = $request->idProducto;
        $metodoPago = $request->metodoPago;
        $precioVenta = $request->precioVenta;
        $cuotas = $request->cuotas;
        $mensaje = "Venta registrada correctamente";

        if($metodoPago == 2){
            DB::select("insert into pedidos.cx_creditos(id_cliente, id_producto, monto_adeudado, cuotas, created_at)
                    values(:id_cliente, :id_producto, :monto_adeudado, :cuotas_credito, now())", 
                    ['id_cliente' => $idCliente, 'id_producto' => $idProducto, 'monto_adeudado' => $precioVenta,
                    'cuotas_credito' => $cuotas]);
        }else{
            DB::select("insert into pedidos.cx_ventas(id_producto, id_metodo_pago, created_at, precio_venta, id_cliente)
            values(:id_producto, :id_metodo_pago, now(), :precio_venta, :id_cliente)", 
            ['id_producto' => $idProducto, 'id_metodo_pago' => $metodoPago, 'precio_venta' => $precioVenta,
            'id_cliente' => $idCliente]);
        }

        return $mensaje;
    }
}
