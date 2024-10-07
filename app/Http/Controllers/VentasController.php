<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Exception;

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
                                row_number() over(order by p.id desc) as no,
                                p.id,
                                id_caja,
                                'BOX-' || LPAD(c.id::TEXT, 4, '0') AS numero_caja,
                                nombre,
                                cantidad,
                                precio_normal, precio_compra, precio_venta
                            from pedidos.cx_productos p
                            join cx_cajas c on p.id_caja = c.id and c.deleted_at is null
                            where p.deleted_at is null and p.id not in(
                                select id_producto from pedidos.cx_ventas where deleted_At is null
                            )
                            AND p.id not in(
                                select id_producto from pedidos.cx_creditos where deleted_At is null
                            )");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    
                    $actions = "<a class='btn btn-1 m-0' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Paquete' data-container='body' data-animation='true'><i class='fi fi-ss-customize-edit'></i></a>";
                    return $actions;
                })->addColumn('estadoPago', function($row) {
                    // Si el pago está realizado, marcar el checkbox como checked
                    //$checked = $row->id ? 'checked' : '';
                    return "<div class='form-check form-switch justify-content-center'><input class='form-check-input' onchange='cambiarEstadoVenta($row->id, $row->precio_venta)' type='checkbox' id='chkPago' ></div>";
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

        try {
            DB::beginTransaction();
    
            DB::select("insert into pedidos.cx_productos(nombre, precio_normal, precio_compra, precio_venta, created_at, id_caja, cantidad)
                    values(:nombre_producto, :precio_normal, :precio_compra, :precio_venta, now(), :id_caja, :cantidad)", 
                    ['nombre_producto' => $nombreProducto, 'precio_normal' => $precioNormal, 'precio_compra' => $precioCompra, 'precio_venta' => $precioVenta,
                    'id_caja' => $idCaja, 'cantidad' => $cantidad]);
    
            DB::commit();

            return $mensaje;

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
       
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

    public function verVentas(Request $request){
        if ($request->ajax()) {
            
            DB::select("SET lc_monetary = 'es_HN';");
            $data = DB::select("select 
                                    row_number() over(order by v.id desc) as no,
                                    p.nombre,
                                    v.precio_venta::numeric::money,
                                    c.nombre_cliente || ' ' || c.apellido_cliente as comprador,
                                    initcap(lower(mp.descripcion)) as metodo_pago,
                                    to_char(v.created_at::date, 'DD/MM/YYYY') as fecha_compra
                                from pedidos.cx_ventas v
                                join pedidos.cx_productos p on p.id = v.id_producto and p.deleted_at is null
                                join pedidos.cx_clientes c on c.id = v.id_cliente and c.deleted_at is null
                                join pedidos.cx_metodos_pago mp on mp.id = v.id_metodo_pago and mp.deleted_At is null
                                where v.deleted_at is null
                                ");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    
                    $actions = "<a class='btn btn-1 m-0' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar venta' data-container='body' data-animation='true'><i class='fi fi-ss-customize-edit'></i></a>";
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }
    }

    public function verCreditos(Request $request){
        if ($request->ajax()) {
            
            DB::select("SET lc_monetary = 'es_HN';");
            $data = DB::select("with cuotas as (select 
                                    id_credito,
                                    count(*) as cuotas_pagadas,
                                    sum(monto_abonado) as monto_abonado
                                from pedidos.cx_cuotas_credito
                                where deleted_at is null
                                group by id_credito)
                                select 
                                    row_number() over(order by cr.id desc) as no,
                                    c.nombre_cliente || ' ' || c.apellido_cliente as comprador,
                                    p.nombre as nombre_producto,
                                    cr.monto_adeudado::numeric::money,
                                    cr.cuotas,
                                    coalesce(cu.cuotas_pagadas, 0) as cuotas_pagadas,
                                    coalesce(cu.monto_abonado, 0)::numeric::money as monto_abonado,
                                    to_char(c.created_at::date, 'DD/MM/YYYY') as fecha_compra
                                from pedidos.cx_creditos cr
                                join pedidos.cx_productos p on p.id = cr.id_producto and p.deleted_at is null
                                join pedidos.cx_clientes c on c.id = cr.id_cliente and c.deleted_at is null
                                left join cuotas cu on cu.id_credito = cr.id	
                                where cr.deleted_at is null
                                ");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('opcion', function($row){
                    
                    $actions = "<a class='btn btn-success btn-1 m-0' data-bs-toggle='tooltip' data-bs-placement='top' title='Agregar pago' data-container='body' data-animation='true'><i class='fi fi-ss-money'></i></a>";
                    return $actions;
                })
                ->rawColumns(['opcion'])
                ->make(true);
        }
    }

    public function datosTotalesProductos(Request $request){

        DB::select("SET lc_monetary = 'es_HN';");
        $data = DB::select("
                 with productos as (
                            select 
                            row_number() over(order by p.id desc) as no,   
                            p.id,
                            id_caja,
                            'BOX-' || LPAD(c.id::TEXT, 4, '0') AS numero_caja,
                            nombre,
                            cantidad,
                            precio_normal, precio_compra, precio_venta,
                            precio_venta*cantidad as total_precio_producto,
                            precio_compra*cantidad as total_precio_inversion
                        from pedidos.cx_productos p
                        join cx_cajas c on p.id_caja = c.id and c.deleted_at is null
                        where p.deleted_at is null and p.id not in(
                            select id_producto from pedidos.cx_ventas where deleted_At is null
                        )
                        AND p.id not in(
                            select id_producto from pedidos.cx_creditos where deleted_At is null
                        )
                    )
                    select 
                        coalesce(sum(cantidad), 0) as total_productos,
                        '$ ' || coalesce(sum(total_precio_inversion), 0) as total_inversion,
                        coalesce(sum(total_precio_producto), 0)::numeric::money as total_venta
                    from productos");
        return response()->json([
            'data' => $data,
        ]);
    }
}
