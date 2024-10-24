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
                    
                    $actions = "<a class='btn btn-1 m-0' data-bs-toggle='tooltip' onclick='editarProducto($row->id, \"".htmlspecialchars($row->nombre, ENT_QUOTES, 'UTF-8')."\", $row->cantidad, $row->precio_normal, $row->precio_compra, $row->precio_venta)' data-bs-placement='top' title='Editar Paquete' data-container='body' data-animation='true'><i class='fi fi-ss-customize-edit'></i></a>
                    <a class='btn btn-danger btn-1 m-0' data-bs-toggle='tooltip' onclick='eliminarProducto($row->id)' data-bs-placement='top' title='Eliminar Producto' data-container='body' data-animation='true'><i class='fi fi-sr-trash'></i></a>";
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
                                    v.id,
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
                    
                    $actions = "<a class='btn btn-danger btn-1 m-0' data-bs-toggle='tooltip' onclick='eliminarVenta($row->id)' data-bs-placement='top' title='Eliminar venta' data-container='body' data-animation='true'><i class='fi fi-sr-trash'></i></a>";
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
                                    cr.id,
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
                    
                    $actions = "<a class='btn btn-success btn-1 m-0' data-bs-toggle='tooltip' onclick='agregarCuota($row->id)' data-bs-placement='top' title='Agregar pago' data-container='body' data-animation='true'><i class='fi fi-ss-money'></i></a>
                    <a class='btn btn-danger btn-1 m-0' data-bs-toggle='tooltip' onclick='eliminarCredito($row->id)' data-bs-placement='top' title='Eliminar Credito' data-container='body' data-animation='true'><i class='fi fi-sr-trash'></i></a>";
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

    public function editarProducto(Request $request){

        $idProducto = $request->idProducto;
        $nombreProducto = $request->nombreProducto;
        $cantidadProducto = $request->cantidadProducto;
        $precioNormal = $request->precioNormal;
        $precioCompra = $request->precioCompra;
        $precioVenta = $request->precioVenta;
        $mensaje = "Producto editado correctamente";

       try {
            DB::beginTransaction();

            DB::select("update pedidos.cx_productos set nombre = :nombre_producto,
                cantidad = :cantidad_producto,
                precio_normal = :precio_normal,
                precio_compra = :precio_compra,
                precio_venta = :precio_venta
                where id = :id_producto
                ", ['nombre_producto' => $request->nombreProducto, 'cantidad_producto' => $request->cantidadProducto,
                'precio_normal' => $request->precioNormal, 'precio_compra' => $request->precioCompra,
                'precio_venta' => $request->precioVenta, 'id_producto' => $request->idProducto]);

            DB::commit();

            return $mensaje;

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }       
    }

    public function datosTotalesVentas(){

        DB::select("SET lc_monetary = 'es_HN';");
        $data = DB::select("
                 select
                    coalesce(sum(case when v.id_metodo_pago = 1 then v.precio_venta else 0 end), 0)::numeric::money as total_efectivo,
                    coalesce(sum(case when v.id_metodo_pago = 3 then v.precio_venta else 0 end), 0)::numeric::money as total_bac,
                    coalesce(sum(case when v.id_metodo_pago = 4 then v.precio_venta else 0 end), 0)::numeric::money as total_ficohsa,
                    coalesce(sum(case when v.id_metodo_pago = 5 then v.precio_venta else 0 end), 0)::numeric::money as total_atlantida,
                    coalesce(sum(case when v.id_metodo_pago = 6 then v.precio_venta else 0 end), 0)::numeric::money as total_banpais,
                    coalesce(sum(case when v.id_metodo_pago = 7 then v.precio_venta else 0 end), 0)::numeric::money as total_occidente,
                    coalesce(sum(case when v.id_producto is not null then v.precio_venta end), 0)::numeric::money as total_venta,
                    '$ ' || coalesce(sum(p.precio_compra), 0) as total_inversion
                from pedidos.cx_ventas v
                join pedidos.cx_productos p on p.id = v.id_producto and p.deleted_at is null
                    where v.deleted_at is null");
        return response()->json([
            'data' => $data,
        ]);      
    }

    public function eliminarVenta(Request $request){

        $idVenta = $request->idVenta;
        $mensaje = "Venta eliminada correctamente";

        try {
            DB::beginTransaction();
            DB::select("update pedidos.cx_ventas set deleted_at = now() where id = :idVenta", ['idVenta' => $idVenta]);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }

        return $mensaje;
    }

    public function eliminarProducto(Request $request){

        $idProducto = $request->idProducto;
        $mensaje = "Producto eliminado correctamente";

        try {
            DB::beginTransaction();
            DB::select("update pedidos.cx_productos set deleted_at = now() where id = :idProducto", ['idProducto' => $idProducto]);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }

        return $mensaje;
    }

    public function eliminarCredito(Request $request){

        $idCredito = $request->idCredito;
        $mensaje = "Credito eliminado correctamente";

        try {
            DB::beginTransaction();
            DB::select("update pedidos.cx_creditos set deleted_at = now() where id = :idCredito", ['idCredito' => $idCredito]);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }

        return $mensaje;
    }

    public function datosTotalesCreditos(){

        DB::select("SET lc_monetary = 'es_HN';");
        
        $data = DB::select("
                select
                    '$ ' || coalesce(sum(p.precio_compra), 0) as total_inversion, 
                    coalesce(sum(cr.monto_adeudado), 0)::numeric::money as total_monto_adeudado,
                    coalesce(sum(cu.monto_abonado), 0)::numeric::money as total_monto_abonado
                from pedidos.cx_creditos cr
                join pedidos.cx_productos p on p.id = cr.id_producto and p.deleted_at is null
                left join pedidos.cx_cuotas_credito cu on cu.id_credito = cr.id and cu.deleted_at is null
                    where cr.deleted_at is null");
        
        return response()->json([
            'data' => $data,
        ]);
                
    }

    public function registrarCuota(Request $request){
        
        $idCredito = $request->idCredito;
        $idMetodoPago = $request->idMetodoPago;
        $montoAbonado = $request->montoAbonado;
        $mensaje = "Cuota registrada exitosamente";

        try {
            DB::beginTransaction();
            DB::select("insert into pedidos.cx_cuotas_credito (id_credito, id_metodo_pago, monto_abonado, created_at) values(:idCredito, :idMetodoPago, :montoAbonado, now())",
            ['idCredito' => $idCredito, 'idMetodoPago' => $idMetodoPago, 'montoAbonado' => $montoAbonado]
            );
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['mensaje' => $e->getMessage()], 500);
        }

        return $mensaje;

    }


    public function verCuotasCredito(Request $request){

        $idCredito = $request->idCredito;

        if ($request->ajax()) {
            
            $data = DB::select("select 
                                row_number() over(order by cc.id desc) as no,
                                cc.id_credito,
                                cl.nombre_cliente || ' ' || cl.apellido_cliente as nombre_cliente,
                                initcap(lower(mp.descripcion)) as metodo_pago,
                                cc.monto_abonado,
                                to_char(cc.created_at::date, 'DD/MM/YYYY') as fecha_pago
                                from pedidos.cx_cuotas_credito cc
                                join pedidos.cx_creditos c on c.id = cc.id_credito and c.deleted_at is null
                                join pedidos.cx_clientes cl on cl.id = c.id_cliente and cl.deleted_At is null
                                join pedidos.cx_metodos_pago mp on mp.id = cc.id_metodo_pago and mp.deleted_at is null
                                where cc.deleted_at is null and cc.id_credito = :idCredito
                                ", ['idCredito' => $idCredito]);

            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }


    }
        

}
