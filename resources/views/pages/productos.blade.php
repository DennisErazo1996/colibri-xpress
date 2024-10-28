@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Productos'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0 p-4">
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                    </div>
                    {{-- <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Agregar nuevo pedido</button>
                    </div> --}}
                    <div class="card-body px-2 pt-0 pb-2">
                        <form role="form text-left">


                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="caja-envio">Caja de envío<span style="color: red">*</span></label>
                                        <input list="cajas-list" placeholder="Seleccione la caja de envío"
                                            class="form-control" id="inpCaja" name="caja-envio" />
                                        <datalist id="cajas-list">
                                            @foreach ($cajas as $bx)
                                                <option value="{{ $bx->numero_caja }}{{ ' (' . $bx->fecha_envio . ')' }}"
                                                    data-idCaja="{{ $bx->id }}"></option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="producto">Nombre del producto<span style="color: red">*</span></label>
                                        <input type="text" name="nombre-producto" class="form-control"
                                            id="inpNombreProducto" placeholder="Ingresa el nombre del producto"
                                            aria-label="producto" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cantidad">Cantidad<span style="color: red">*</span></label>
                                        <input type="number" name="precio-normal" class="form-control" id="inpCantidad"
                                            placeholder="0" aria-label="cantidad" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="precio-normal">Precio normal<span style="color: red">*</span></label>
                                        <input type="number" name="precio-normal" class="form-control" id="inpPrecioNormal"
                                            placeholder="$ 0.00" aria-label="precio-normal" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="precio-compra">Precio Compra<span style="color: red">*</span></label>
                                        <input type="number" name="precio-compre" class="form-control" id="inpPrecioCompra"
                                            placeholder="$ 0.00" aria-label="precio-compra" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="precio-venta">Precio Venta<span style="color: red">*</span></label>
                                        <input type="number" name="precio-compre" class="form-control" id="inpPrecioVenta"
                                            placeholder="L 0.00" aria-label="precio-venta" required>
                                    </div>
                                </div>


                                <div class="col-md-12 text-center" id="mensaje" style="color: red; font-size:12px"></div>
                            </div>
                            <div class="text-center d-flex flex-row justify-content-center">
                                <button id="btn-registrar-producto" type="submit"
                                    class="btn  btn-success btn-1  mt-4 mb-0"><i class="fi fi-sr-add"></i> Agregar
                                    producto</button>
                                {{-- <button id="btn-cancelar-pedido" type="button"
                                    class="btn btn-3 w-30 mt-4 mb-0 ml-50">Cancelar</button> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="container-fluid py-4 mt-0">

        <div class="row">
            <div class="col-12">

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" id="lnk-list-productos">Lista de productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" id="lnk-list-ventas">Productos vendidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" id="lnk-list-creditos">Lista de crédito</a>
                    </li>
                </ul>

                <div class="card mb-0 p-2" id="card-list-productos">
                    <div class="text-center d-flex flex-row justify-content-center">


                    </div>
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                    </div>
                    {{-- <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Agregar nuevo pedido</button>
                    </div> --}}
                    <div class="card-body px-2 pt-0 pb-2">
                        <div class="container">
                            <div id="total-productos" class="row text-center justify-content-center">

                            </div>
                        </div>
                        <div class="table-responsive p-0">
                            <table id="tbl-productos" class="table align-items-center table-striped" style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Caja</th>
                                        <th>Nombre Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Normal ($)</th>
                                        <th>Precio Compra ($)</th>
                                        <th>Precio de Venta (L)</th>
                                        <th>Vender</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-0 p-2" id="card-list-ventas">
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                    </div>
                    {{-- <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Agregar nuevo pedido</button>
                    </div> --}}
                    <div class="card-body px-2 pt-0 pb-2">
                        <div class="container">
                            <div id="total-ventas" class="row text-center justify-content-center">

                            </div>
                        </div>
                        <br>
                        <div class="table-responsive p-0">
                            <table id="tbl-ventas" class="table align-items-center table-striped" style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Nombre producto</th>
                                        <th>Precio de venta</th>
                                        <th>Comprador</th>
                                        <th>Método de pago</th>
                                        <th>Fecha de compra</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mb-0 p-2" id="card-list-creditos">
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                    </div>
                    {{-- <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Agregar nuevo pedido</button>
                    </div> --}}
                    <div class="card-body px-2 pt-0 pb-2">
                        <div class="container">
                            <div id="total-creditos" class="row text-center justify-content-center">

                            </div>
                        </div>
                        <br>
                        <div class="table-responsive p-0">
                            <table id="tbl-creditos" class="table align-items-center table-striped" style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Cliente</th>
                                        <th>Nombre del producto</th>
                                        <th>Monto adeudado</th>
                                        <th>Cuotas</th>
                                        <th>Monto abonado</th>
                                        <th>Cuotas pagadas</th>
                                        <th>Fecha de compra</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    <div class="col-md-4">

        <div class="modal fade" id="modal-form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-left">
                                <h3 class="font-weight-bolder text-default">Actualizar información de Producto</h3>
                                {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                            </div>
                            <div class="card-body">
                                <form role="form text-left">
                                    <input type="text" class="form-control" id="inpIdProducto" style="display: none">
                                    {{-- <input type="text" class="form-control" id="inpIdCliente" style="display: none"> --}}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="producto">Nombre del producto<span
                                                        style="color: red">*</span></label>
                                                <input type="text" name="nombre-producto" class="form-control"
                                                    id="inpNombreProductoEdit"
                                                    placeholder="Ingresa el nombre del producto" aria-label="producto"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="cantidad">Cantidad<span style="color: red">*</span></label>
                                                <input type="number" name="precio-normal" class="form-control"
                                                    id="inpCantidadEdit" placeholder="0" aria-label="cantidad" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="precio-normal">Precio normal<span
                                                        style="color: red">*</span></label>
                                                <input type="number" name="precio-normal" class="form-control"
                                                    id="inpPrecioNormalEdit" placeholder="$ 0.00"
                                                    aria-label="precio-normal" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="precio-compra">Precio Compra<span
                                                        style="color: red">*</span></label>
                                                <input type="number" name="precio-compre" class="form-control"
                                                    id="inpPrecioCompraEdit" placeholder="$ 0.00"
                                                    aria-label="precio-compra" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="precio-venta">Precio Venta<span
                                                        style="color: red">*</span></label>
                                                <input type="number" name="precio-compre" class="form-control"
                                                    id="inpPrecioVentaEdit" placeholder="L 0.00"
                                                    aria-label="precio-venta" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center" id="mensaje"
                                            style="color: red; font-size:12px"></div>
                                    </div>
                                    <div class="text-center d-flex flex-row justify-content-end">
                                        <button id="btn-actualizar-producto" type="submit"
                                            class="btn  bg-gradient-secondary btn-3 mt-4 mb-0">Actualizar</button>
                                        &nbsp;&nbsp;
                                        <button id="btn-cancelar-producto-edit" type="button"
                                            class="btn btn-3 mt-4 mb-0 ml-50">Cancelar</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">

        <div class="modal fade" id="modal-form-cuotas" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-left">
                                <h3 class="font-weight-bolder text-default">Cuotas pagadas</h3>
                                {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <input type="text" class="form-control" id="inpIdCredito" style="display: none">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="caja-envio">Metodo de pago<span
                                                    style="color: red">*</span></label>
                                            <select class="form-control" id="metodos-pago">
                                                <option selected disabled>Seleccione el metodo de pago</option>
                                                @foreach ($metodosPago as $mp)
                                                    @if ($mp->id != 2)
                                                        <option value="{{ $mp->id }}"
                                                            data-idCaja="{{ $mp->id }}">{{ $mp->descripcion }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="monto-abono">Monto abonado<span
                                                    style="color: red">*</span></label>
                                            <input type="number" name="monto-abono" class="form-control"
                                                id="inpMontoAbono" placeholder="L 0.00" aria-label="monto-abono"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button id="btn-registrar-cuota" type="button"
                                            class="btn btn-success mt-4 mb-0"><i class="fi fi-sr-add"></i></button>
                                    </div>
                                </div>
                                <br>
                                <div class="table-responsive p-0">
                                    <table id="tbl-cuotas" class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    No.</th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Cliente</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Metodo de pago</th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Monto abonado</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Fecha de pago</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center d-flex flex-row justify-content-end">

                                    <button id="btn-cerrar-cuotas" type="button"
                                        class="btn btn-3 mt-4 mb-0 ml-50">Cerrar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    @include('layouts.footers.auth.footer')
@endsection
@push('js')
    <script>
        var stateCookieEnviado = getCookie("stateListEnviados");
        var urlTableProductos = "{{ route('ver-productos') }}";


        //alert(stateCookieEnviado)


        if (stateCookieEnviado == null) {
            $('#card-list-ventas').hide();
            $('#card-list-creditos').hide();
            $('li.nav-item a#lnk-list-productos').addClass('active');
            $('li.nav-item a#lnk-list-productos').attr('aria-current', 'page');
        } else {
            inicializarTablaVentas()
            $('li.nav-item a#lnk-list-ventas').addClass('active');
            $('li.nav-item a#lnk-list-ventas').attr('aria-current', 'page');
            $('#card-list-ventas').show();
            $('#card-list-productos').hide();
            $('#card-list-creditos').hide();

        }

        $(document).ready(function() {

            inicializarTablaProductos()
            inicializarTotalesProductos()
            inicializarTotalesVentas()
            inicializarTotalesCreditos()
            $("#overlay").fadeOut(300);

        });

        $('#btn-cancelar-producto-edit').on('click', function() {
            $('div#mensaje').html('')
            $('#inpIdPaquete').val('');
            $('#inpNombreProductoEdit').val('');
            $('#inpCantidadEdit').val('');
            $('#inpPrecioNormalEdit').val('');
            $('#inpPrecioCompraEdit').val('');
            $('#inpPrecioVentaEdit').val('');
            $('#modal-form').modal('toggle');

        });


        $('#lnk-list-productos').click(function(e) {
            e.preventDefault();

            document.cookie = "stateListEnviados=active; expires=Thu, 01 Jan 1970 00:00:00 UTC";
            var cookieState = getCookie('stateListEnviados')
            //console.log(cookieState)

            $('li.nav-item a#lnk-list-productos').addClass('active');
            $('li.nav-item a#lnk-list-ventas').removeClass('active');
            $('li.nav-item a#lnk-list-creditos').removeClass('active');
            $('li.nav-item a#lnk-list-productos').attr('aria-current', 'page');
            $('#card-list-productos').show().fadeIn();
            $('#card-list-ventas').hide().fadeOut();
            $('#card-list-creditos').hide().fadeOut();
            inicializarTablaProductos()
            inicializarTotalesProductos()
        });

        $('#lnk-list-creditos').click(function(e) {
            e.preventDefault();

            $('li.nav-item a#lnk-list-creditos').addClass('active');
            $('li.nav-item a#lnk-list-creditos').attr('aria-current', 'page');
            $('li.nav-item a#lnk-list-ventas').removeClass('active');
            $('li.nav-item a#lnk-list-productos').removeClass('active');
            $('#card-list-creditos').show().fadeIn();
            $('#card-list-ventas').hide().fadeOut();
            $('#card-list-productos').hide().fadeOut();
            inicializarTablaCreditos()
            inicializarTotalesCreditos()
        });

        $('#lnk-list-ventas').click(function(e) {
            e.preventDefault();

            document.cookie = "stateListEnviados=active";
            var cookieState = getCookie('stateListEnviados')
            //console.log(cookieState)

            $('li.nav-item a#lnk-list-productos').removeClass('active');
            $('li.nav-item a#lnk-list-creditos').removeClass('active');
            $('li.nav-item a#lnk-list-ventas').addClass('active');
            $('li.nav-item a#lnk-list-ventas').attr('aria-current', 'page');
            $('#card-list-productos').hide().fadeOut();
            $('#card-list-creditos').hide().fadeOut();
            $('#card-list-ventas').show().fadeIn();
            inicializarTablaVentas()
            inicializarTotalesVentas()
        });

        $('#btn-registrar-producto').on('click', function() {

            var urlRest = "{{ route('registrar-producto') }}";
            var vrNombreProducto = $('#inpNombreProducto').val();
            var vrCantidad = $('#inpCantidad').val();
            var vrPrecioNormal = $('#inpPrecioNormal').val();
            var vrPrecioCompra = $('#inpPrecioCompra').val();
            var vrPrecioVenta = $('#inpPrecioVenta').val();

            var input = document.getElementById('inpCaja');
            var selectedValue = input.value;

            var selectedOption = $('#cajas-list option[value="' + selectedValue + '"]');
            if (selectedOption.length > 0) {
                var dataId = selectedOption.attr('data-idCaja');
                //console.log('Valor de data-idCaja:', dataId);
                //$('#inpNombreProducto').focus();
            } else {
                console.log('No se encontró una opción seleccionada con ese valor.');
            }

            //console.log(dataId);

            if (typeof dataId != "undefined" && vrNombreProducto != '' && vrCantidad != '' && vrPrecioNormal !=
                '' && vrPrecioCompra != '') {

                $(document).ajaxSend(function() {
                    $("#overlay").fadeIn(300);
                });

                $('#btn-registrar-producto').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: urlRest,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "idCaja": dataId,
                        "nombreProducto": vrNombreProducto,
                        "cantidad": vrCantidad,
                        "precioNormal": vrPrecioNormal,
                        "precioCompra": vrPrecioCompra,
                        "precioVenta": vrPrecioVenta,
                    },
                    success: function(response) {
                        //alert(response)
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: response
                        });

                        $('#tbl-productos').DataTable().ajax.reload();
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                }).done(function() {

                    inicializarTotalesProductos()

                    $('div#mensaje').html('')
                    $('#inpCaja').val('');
                    $('#inpNombreProducto').val('');
                    $('#inpCantidad').val('');
                    $('#inpPrecioNormal').val('');
                    $('#inpPrecioCompra').val('');
                    $('#inpPrecioVenta').val('');
                    $('#btn-registrar-producto').prop('disabled', false);
                    setTimeout(function() {
                        $("#overlay").fadeOut(300);
                    }, 500);
                });
            } else {
                $('div#mensaje').html('Llena todos los campos, por favor')
            }
        });

        $('#btn-actualizar-producto').on('click', function() {

            //alert('hola')
            var urlRest = "{{ route('editar-producto') }}";

            var vrIdProducto = $('#inpIdProducto').val();
            var vrNombreProducto = $('#inpNombreProductoEdit').val();
            var vrCantidad = $('#inpCantidadEdit').val();
            var vrPrecioNormal = $('#inpPrecioNormalEdit').val();
            var vrPrecioCompra = $('#inpPrecioCompraEdit').val();
            var vrPrecioVenta = $('#inpPrecioVentaEdit').val();
            //var vrChckPago = $('#chkPago').val();
            if (vrIdProducto != '' && vrNombreProducto != '' && vrCantidad != '' && vrPrecioNormal != '' &&
                vrPrecioCompra != '' && vrPrecioVenta != '') {

                $(document).ajaxSend(function() {
                    $("#overlay").fadeIn(300);
                });

                $('#btn-actualizar-producto').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: urlRest,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "idProducto": vrIdProducto,
                        "nombreProducto": vrNombreProducto,
                        "cantidadProducto": vrCantidad,
                        "precioNormal": vrPrecioNormal,
                        "precioCompra": vrPrecioCompra,
                        "precioVenta": vrPrecioVenta,
                    },
                    success: function(response) {
                        //alert(response)
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: response
                        });
                        $('#tbl-productos').DataTable().ajax.reload();
                        inicializarTotalesProductos()
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                }).done(function() {
                    $('#modal-form').modal('toggle');
                    $('#btn-actualizar-producto').prop('disabled', false);
                    $('div#mensaje').html('')
                    $('#inpIdProducto').val('');
                    $('#inpNombreProductoEdit').val('');
                    $('#inpCantidadEdit').val('');
                    $('#inpPrecioNormalEdit').val('');
                    $('#inpPrecioCompraEdit').val('');
                    $('#inpPrecioVentaEdit').val('');

                    setTimeout(function() {
                        $("#overlay").fadeOut(300);
                    }, 500);
                });
            } else {
                $('div#mensaje').html('Llena todos los campos')
            }
        });

        $('#btn-cerrar-cuotas').on('click', function() {

            $('#modal-form-cuotas').modal('toggle');
            $('#inpMontoAbono').val('');
            $('#metodos-pago').val('');
            $('#inpIdCredito').val('');

        })

        $('#btn-registrar-cuota').on('click', function() {

            var urlRest = "{{ route('registrar-cuota') }}";
            var vrIdCredito = $('#inpIdCredito').val();
            var vrMontoAbono = $('#inpMontoAbono').val();
            var vrIdMetodoPago = $('#metodos-pago').val();

            if (vrIdCredito != '' && vrMontoAbono != '' && vrIdMetodoPago != '') {

                $(document).ajaxSend(function() {
                    $("#overlay").fadeIn(300);
                });


                $.ajax({
                    type: "POST",
                    url: urlRest,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "idCredito": vrIdCredito,
                        "montoAbonado": vrMontoAbono,
                        "idMetodoPago": vrIdMetodoPago,
                    },
                    success: function(response) {
                        //alert(response)                        
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: response
                        });

                        $('#tbl-cuotas').DataTable().ajax.reload();
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                }).done(function() {

                    $('#tbl-creditos').DataTable().ajax.reload();
                    inicializarTotalesCreditos()
                    $('#inpMontoAbono').val('');
                    $('#metodos-pago').val('');
                    setTimeout(function() {
                        $("#overlay").fadeOut(300);
                    }, 500);
                });
            } else {
                $('div#mensaje').html('Llena todos los campos')
            }
        });



        function cambiarEstadoVenta(idProducto, precioVenta) {

            var clientes = {!! json_encode($clientes) !!};
            var metodosPago = {!! json_encode($metodosPago) !!};
            var urlRest = "{{ route('registrar-venta') }}";

            $.confirm({
                title: 'Registrar venta',
                content: '' +
                    '<form action="" class="formName">' +
                    '<div class="form-group">' +
                    '<label>Ingrese la cantidad del producto</label>' +
                    '<input type="number" id="cantidad" class="form-control" placeholder="Cantidad de producto">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Selecciona el comprador</label>' +
                    '<input list="clientes" class="form-control cliente" placeholder="Buscar cliente" required>' +
                    '<datalist id="clientes">' +
                    clientes.map(cliente =>
                        `<option value="${cliente.nombre_cliente+' '+cliente.apellido_cliente}" data-id="${cliente.id}">`
                    )
                    .join('') +
                    '</datalist>' +
                    '<input type="hidden" id="clienteId">' + // Input oculto para guardar el ID del producto
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Selecciona el método de pago</label>' +
                    '<select class="metodo-pago form-control" required>' +
                    '<option value="" selected disabled>Metodo de pago</option>' +
                    metodosPago.map(metodo => `<option value="${metodo.id}">${metodo.descripcion}</option>`).join(
                        '') +
                    '</select>' +
                    '</div>' +
                    '<div class="form-group" id="cuotas-container" style="display:none;">' +
                    // Input de cuotas inicialmente oculto
                    '<label>Ingrese el número de cuotas</label>' +
                    '<input type="number" id="cuotas" class="form-control" placeholder="Número de cuotas">' +
                    '</div>' +
                    '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Vender',
                        btnClass: 'btn-green',
                        action: function() {
                            var nombreCliente = this.$content.find('.cliente').val();
                            var idCliente = this.$content.find('#clienteId').val();
                            var metodoPago = this.$content.find('.metodo-pago').val();
                            var cuotas = this.$content.find('#cuotas').val();
                            var cantidad = this.$content.find('#cantidad').val();

                            $(document).ajaxSend(function() {
                                $("#overlay").fadeIn(300);
                            });

                            if (idCliente != '' && metodoPago != '' && cantidad != '') {
                                $.ajax({
                                    type: "POST",
                                    url: urlRest,
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        "idCliente": idCliente,
                                        "cuotas": cuotas,
                                        "metodoPago": metodoPago,
                                        "precioVenta": precioVenta,
                                        "idProducto": idProducto,
                                        "cantidad": cantidad,

                                    },
                                    success: function(response) {
                                        //alert(response)
                                        const Toast = Swal.mixin({
                                            toast: true,
                                            position: "top-end",
                                            showConfirmButton: false,
                                            timer: 3000,
                                            timerProgressBar: true,
                                            didOpen: (toast) => {
                                                toast.onmouseenter = Swal.stopTimer;
                                                toast.onmouseleave = Swal.resumeTimer;
                                            }
                                        });
                                        Toast.fire({
                                            icon: "success",
                                            title: response
                                        });

                                        $('#tbl-productos').DataTable().ajax.reload();
                                    },
                                    error: function(request, status, error) {
                                        alert(request.responseText);
                                    }
                                }).done(function() {

                                    inicializarTotalesProductos()

                                    setTimeout(function() {
                                        $("#overlay").fadeOut(300);
                                    }, 500);

                                });
                            }else{
                                $.alert('Debes seleccionar un cliente, metodo de pago y cantidad')
                            }

                            /*if (!idCliente) {
                                $.alert('Debes seleccionar un cliente');
                                return false;
                            }
                            $.alert('Has seleccionado el cliente con ID ' + idCliente + ', nombre ' + nombreCliente +
                                ' y método de pago con ID ' + meto
                                doPago + '. Cuotas: ' + cuotas + ' El precio venta: '+precioVenta+ ' el id producto: '+ idProducto);*/

                        }
                    },
                    cancelar: function() {
                        $('#chkPago').prop('checked', false);
                    },
                },
                onContentReady: function() {
                    var jc = this;

                    // Detectar cambios en el input de productos
                    this.$content.find('.cliente').on('input', function() {
                        var inputVal = $(this).val();
                        var option = $('#clientes').find(
                            `option[value="${inputVal}"]`);

                        if (option.length) {
                            var idCliente = option.data('id');
                            jc.$content.find('#clienteId').val(idCliente);
                        } else {
                            jc.$content.find('#clienteId').val('');
                        }
                    });

                    // Mostrar/ocultar input de cuotas basado en el método de pago seleccionado
                    this.$content.find('.metodo-pago').on('change', function() {
                        var metodoSeleccionado = $(this).val();
                        if (metodoSeleccionado == "2") {
                            jc.$content.find('#cuotas-container').show();
                        } else {
                            jc.$content.find('#cuotas-container').hide();
                            jc.$content.find('#cuotas').val('');
                        }
                    });

                    // Bind to form submit
                    this.$content.find('form').on('submit', function(e) {
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click');
                    });
                }
            });
        }

        function inicializarTablaProductos() {

            $(document).ajaxSend(function() {
                $("#overlay").hide();
            })

            $('#tbl-productos').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                ajax: {
                    url: urlTableProductos,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Agrega el token al encabezado
                    },
                    complete: function() {

                        $("#overlay").fadeOut(300);
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'numero_caja',
                        name: 'numero_caja'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'cantidad',
                        name: 'cantidad'
                    },
                    {
                        data: 'precio_normal',
                        name: 'precio_normal'
                    },
                    {
                        data: 'precio_compra',
                        name: 'precio_compra'
                    },
                    {
                        data: 'precio_venta',
                        name: 'precio_venta',
                        className: 'column-color'
                    },
                    {
                        data: 'estadoPago',
                        name: 'estadoPago'
                    },
                    {
                        data: 'opcion',
                        name: 'Opciones',
                        orderable: true,
                        searchable: true
                    }

                ],
                columnDefs: [{
                    className: 'dt-center',
                    targets: '_all'
                }, ],
                language: idiomaDatatables,

            });
        }


        function inicializarTablaVentas() {

            var urlTableVentas = "{{ route('ver-ventas') }}";

            $(document).ajaxSend(function() {
                $("#overlay").hide();
            })

            $('#tbl-ventas').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                ajax: {
                    url: urlTableVentas,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Agrega el token al encabezado
                    },
                    error: function(xhr, status, error) {
                        alert("Ocurrió un error: " + error);
                    },
                    complete: function() {
                        $("#overlay").fadeOut(300);
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'precio_venta',
                        name: 'precio_venta',
                        className: 'column-color'
                    },
                    {
                        data: 'comprador',
                        name: 'comprador'
                    },
                    {
                        data: 'metodo_pago',
                        name: 'metodo_pago'
                    },
                    {
                        data: 'fecha_compra',
                        name: 'fecha_compra'
                    },

                    {
                        data: 'opcion',
                        name: 'Opciones',
                        orderable: true,
                        searchable: true
                    }

                ],
                columnDefs: [{
                    className: 'dt-center',
                    targets: '_all'
                }, ],
                language: idiomaDatatables,

            });

        }


        function inicializarTablaCreditos() {

            var urlTableVentas = "{{ route('ver-creditos') }}";

            $(document).ajaxSend(function() {
                $("#overlay").hide();
            })

            $('#tbl-creditos').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                ajax: {
                    url: urlTableVentas,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Agrega el token al encabezado
                    },
                    error: function(xhr, status, error) {
                        alert("Ocurrió un error: " + error);
                    },
                    complete: function() {
                        //$("#overlay").hide();
                        $("#overlay").fadeOut(300);
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'comprador',
                        name: 'comprador'
                    },
                    {
                        data: 'nombre_producto',
                        name: 'nombre_producto'
                    },
                    {
                        data: 'monto_adeudado',
                        name: 'monto_adeudado'
                    },
                    {
                        data: 'cuotas',
                        name: 'cuotas'
                    },
                    {
                        data: 'monto_abonado',
                        name: 'monto_abonado',
                        className: 'column-color'
                    },
                    {
                        data: 'cuotas_pagadas',
                        name: 'cuotas_pagadas',
                        className: 'column-color'
                    },
                    {
                        data: 'fecha_compra',
                        name: 'fecha_compra'
                    },

                    {
                        data: 'opcion',
                        name: 'Opciones',
                        orderable: true,
                        searchable: true
                    }

                ],
                columnDefs: [{
                    className: 'dt-center',
                    targets: '_all'
                }, ],
                language: idiomaDatatables,

            });

        }

        function inicializarTotalesProductos() {

            var urlRest = "{{ route('total-productos') }}";

            $.ajax({
                type: "POST",
                url: urlRest,
                data: {
                    "_token": "{{ csrf_token() }}",

                },
                success: function(response) {

                    if (response.data && response.data.length > 0) {

                        var total_productos = response.data[0].total_productos;
                        var total_inversion = response.data[0].total_inversion;
                        var total_venta = response.data[0].total_venta;
                        //var total_pagado = response.data[0].total_pagado;


                        $('div#total-productos').html(
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total productos: <strong style="color:#3ed06a">' +
                            total_productos + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total inversión: <strong style="color:#3ed06a">' +
                            total_inversion + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total venta: <strong style="color:#3ed06a">' +
                            total_venta + '</strong></div>' +
                            '</div>'
                        );
                    } else {
                        alert("No se encontraron datos.");
                    }


                    //$('#totales-envio').append(response.data);

                },
                error: function(request, status, error) {
                    alert(request.responseText);
                }
            });
        }

        function inicializarTotalesVentas() {

            var urlRest = "{{ route('total-ventas') }}";

            $.ajax({
                type: "POST",
                url: urlRest,
                data: {
                    "_token": "{{ csrf_token() }}",

                },
                success: function(response) {

                    if (response.data && response.data.length > 0) {

                        var total_efectivo = response.data[0].total_efectivo;
                        var total_bac = response.data[0].total_bac;
                        var total_ficohsa = response.data[0].total_ficohsa;
                        var total_atlantida = response.data[0].total_atlantida;
                        var total_banpais = response.data[0].total_banpais;
                        var total_occidente = response.data[0].total_occidente;
                        var total_venta = response.data[0].total_venta;
                        var total_inversion = response.data[0].total_inversion;


                        $('div#total-ventas').html(
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total efectivo: <strong style="color:#3ed06a">' +
                            total_efectivo + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total BAC: <strong style="color:#3ed06a">' +
                            total_bac + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total Ficohsa: <strong style="color:#3ed06a">' +
                            total_ficohsa + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total Atlantida: <strong style="color:#3ed06a">' +
                            total_atlantida + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total Banpais: <strong style="color:#3ed06a">' +
                            total_banpais + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total Ocidente: <strong style="color:#3ed06a">' +
                            total_occidente + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total-focus mt-2" style="color:#fff">Total Venta: <strong style="color:#fff">' +
                            total_venta + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total-focus mt-2" style="color:#fff">Total Inversión: <strong style="color:#fff">' +
                            total_inversion + '</strong></div>' +
                            '</div>')


                    } else {
                        alert("No se encontraron datos.");
                    }


                    //$('#totales-envio').append(response.data);

                },
                error: function(request, status, error) {
                    alert(request.responseText);
                }
            });
        }

        function inicializarTotalesCreditos() {

            var urlRest = "{{ route('total-creditos') }}";

            $.ajax({
                type: "POST",
                url: urlRest,
                data: {
                    "_token": "{{ csrf_token() }}",

                },
                success: function(response) {

                    if (response.data && response.data.length > 0) {

                        var total_inversion = response.data[0].total_inversion;
                        var total_monto_adeudado = response.data[0].total_monto_adeudado;
                        var total_monto_abonado = response.data[0].total_monto_abonado;


                        $('div#total-creditos').html(
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total inversión: <strong style="color:#3ed06a">' +
                            total_inversion + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total Adeudado: <strong style="color:#3ed06a">' +
                            total_monto_adeudado + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total Abonado: <strong style="color:#3ed06a">' +
                            total_monto_abonado + '</strong></div>' +
                            '</div>'
                        );
                    } else {
                        alert("No se encontraron datos.");
                    }

                },
                error: function(request, status, error) {
                    alert(request.responseText);
                }
            });
        }

        function eliminarProducto(idProducto) {

            var urlRest = "{{ route('eliminar-producto') }}";
            var vrIdProducto = idProducto;

            $.confirm({
                type: 'red',
                animation: 'scale',
                title: 'Eliminar Producto',
                content: 'Seguro que quiere eliminar este producto?',
                buttons: {
                    confirm: {
                        text: 'Confirmar',
                        btnClass: 'btn-red',
                        action: function() {

                            $(document).ajaxSend(function() {
                                $("#overlay").fadeIn(300);
                            });

                            $.ajax({
                                type: "POST",
                                url: urlRest,
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "idProducto": vrIdProducto,
                                },
                                success: function(response) {
                                    //alert(response)
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: "top-end",
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.onmouseenter = Swal.stopTimer;
                                            toast.onmouseleave = Swal.resumeTimer;
                                        }
                                    });
                                    Toast.fire({
                                        icon: "success",
                                        title: response
                                    });

                                    $('#tbl-productos').DataTable().ajax.reload();
                                    inicializarTotalesProductos()
                                },
                                error: function(request, status, error) {
                                    alert(request.responseText);
                                }
                            })
                        }
                    },
                    cancelar: function() {
                        //$.alert('Canceled!');
                    },

                }
            });

        }

        function editarProducto(idProducto, nombreProducto, cantidad, precioNormal, precioCompra, precioVenta) {

            $('#inpIdProducto').val(idProducto);
            $('#inpNombreProductoEdit').val(nombreProducto);
            $('#inpCantidadEdit').val(cantidad);
            $('#inpPrecioNormalEdit').val(precioNormal);
            $('#inpPrecioCompraEdit').val(precioCompra);
            $('#inpPrecioVentaEdit').val(precioVenta);

            $('#modal-form').modal('toggle');
        }

        function eliminarVenta(idVenta) {

            var urlRest = "{{ route('eliminar-venta') }}";
            var vrIdVenta = idVenta;

            $.confirm({
                type: 'red',
                animation: 'scale',
                title: 'Eliminar Venta',
                content: 'Seguro que quiere eliminar esta venta?',
                buttons: {
                    confirm: {
                        text: 'Confirmar',
                        btnClass: 'btn-red',
                        action: function() {

                            $(document).ajaxSend(function() {
                                $("#overlay").fadeIn(300);
                            });

                            $.ajax({
                                type: "POST",
                                url: urlRest,
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "idVenta": vrIdVenta,
                                },
                                success: function(response) {
                                    //alert(response)
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: "top-end",
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.onmouseenter = Swal.stopTimer;
                                            toast.onmouseleave = Swal.resumeTimer;
                                        }
                                    });
                                    Toast.fire({
                                        icon: "success",
                                        title: response
                                    });

                                    $('#tbl-ventas').DataTable().ajax.reload();
                                    $('#tbl-productos').DataTable().ajax.reload();
                                    inicializarTotalesVentas()
                                },
                                error: function(request, status, error) {
                                    alert(request.responseText);
                                }
                            })
                        }
                    },
                    cancelar: function() {
                        //$.alert('Canceled!');
                    },

                }
            });


        }

        function eliminarCredito(idCredito) {

            var urlRest = "{{ route('eliminar-credito') }}";
            var vrIdCredito = idCredito;

            $.confirm({
                type: 'red',
                animation: 'scale',
                title: 'Eliminar Credito',
                content: 'Seguro que quiere eliminar este credito?',
                buttons: {
                    confirm: {
                        text: 'Confirmar',
                        btnClass: 'btn-red',
                        action: function() {
                            $(document).ajaxSend(function() {
                                $("#overlay").fadeIn(300);
                            });
                            $.ajax({
                                type: "POST",
                                url: urlRest,
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "idCredito": vrIdCredito,
                                },
                                success: function(response) {
                                    //alert(response)
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: "top-end",
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.onmouseenter = Swal.stopTimer;
                                            toast.onmouseleave = Swal.resumeTimer;
                                        }
                                    });
                                    Toast.fire({
                                        icon: "success",
                                        title: response
                                    });
                                    $('#tbl-creditos').DataTable().ajax.reload();
                                    inicializarTotalesCreditos()
                                },
                                error: function(request, status, error) {
                                    alert(request.responseText);
                                }
                            })
                        }
                    },
                    cancelar: function() {
                        //$.alert('Canceled!');
                    },
                }
            });
        }

        function agregarCuota(idCredito) {

            $('#inpIdCredito').val(idCredito);

            $('#modal-form-cuotas').modal('show');

            var urlTableCuotas = "{{ route('ver-cuotas-credito') }}";

            $('#tbl-cuotas').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                dom: 'l',
                ajax: {
                    url: urlTableCuotas,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Agrega el token al encabezado
                    },
                    data: {
                        'idCredito': idCredito
                    },
                    error: function(xhr, status, error) {
                        alert("Ocurrió un error: " + error);
                    },
                    complete: function() {
                        $("#overlay").fadeOut(300);
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'nombre_cliente',
                        name: 'nombre_cliente'
                    },
                    {
                        data: 'metodo_pago',
                        name: 'metodo_pago'
                    },
                    {
                        data: 'monto_abonado',
                        name: 'monto_abonado'
                    },
                    {
                        data: 'fecha_pago',
                        name: 'fecha_pago'
                    }
                ],
                columnDefs: [{
                        className: 'dt-center',
                        targets: '_all'
                    },
                    {
                        searchable: false,
                        targets: '_all'
                    } // Desactiva el filtrado para todas las columnas
                ],
                language: idiomaDatatables,
            });

        }


        function getCookie(name) {
            let cookieArr = document.cookie.split(";");
            for (let i = 0; i < cookieArr.length; i++) {
                let cookiePair = cookieArr[i].split("=");
                if (name == cookiePair[0].trim()) {
                    return decodeURIComponent(cookiePair[1]);
                }
            }
            return null;
        }
    </script>
@endpush
