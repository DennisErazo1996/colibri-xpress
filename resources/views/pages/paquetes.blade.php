@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Paquetes'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-0 p-4" >
                    <div class="card-header pb-0 text-start justify-content-between row">
                        <div class="col-md-10">

                            @foreach ($infoCaja as $box)
                                <h1 class="m-0 p-0">{{ $box->locker_number }}</h1>
                                <p>Fecha de envio: <strong>{{ $box->fecha_envio}}</strong> <br> Costo de envío: <strong>{{ $box->costo ?? 'Por definir'}}</strong>
                                    <br> Estado: <strong>{{ $box->liquidado == 1 ? 'Liquidado' : 'No liquidado' }}</strong></p> 
                                <br>
                            @endforeach

                        </div>
                        <div class="col-md-2">
                            <img style="width: 150px" src="{{ asset('img/no-envios.png') }}" alt="">
                        </div>

                        {{-- <h6>Authors table</h6> --}}
                    </div>
                    {{-- <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Agregar nuevo pedido</button>
                    </div> --}}
                    <div class="card-body px-2 pt-0 pb-2">
                        <form id="form-paquete" role="form text-left">


                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre-cliente">Cliente<span style="color: red">*</span></label>
                                        <input list="clientes-list" placeholder="Seleccione el cliente" class="form-control"
                                            id="nombre-cliente" name="nombre-cliente" />
                                        <datalist id="clientes-list">
                                            @foreach ($usuarios as $usr)
                                                <option value="{{ '(' . $usr->locker_number . ') ' }}{{ $usr->nombre }}"
                                                    data-idUsuario="{{ $usr->id }}"></option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department">Número de tracking<span style="color: red">*</span></label>
                                        <input type="text" name="identity" class="form-control" id="inpNumeroTracking"
                                            placeholder="Escanea el tracking" aria-label="Producto" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="department">Descripción del paquete<span
                                                style="color: red">*</span></label>
                                        <textarea id="inpDescripcionPaquete" type="text" name="descripcion" class="form-control"
                                            placeholder="Ingresa la descripción del paquete" rows="5" aria-label="Enlace" required></textarea>

                                    </div>
                                </div>
                                <div class="col-md-12 text-center" id="mensaje" style="color: red; font-size:12px"></div>
                            </div>
                            <div class="text-center d-flex flex-row justify-content-end">
                                <button id="btn-registrar-paquete" type="submit" <?php if ($statusEnvio != null) {
                                    echo 'disabled';
                                } ?>
                                    class="btn  btn-success btn-1  mt-4 mb-0"><i class="fi fi-sr-add"></i> Agregar
                                    paquete</button>
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
                        <a class="nav-link" aria-current="page" id="lnk-list-paquetes">Lista de paquetes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" id="lnk-list-enviados">Paquetes enviados</a>
                    </li>
                </ul>

                <div class="card mb-0 p-2" id="card-list-paquetes">
                    <div class="text-center d-flex flex-row justify-content-center">
                        <button id="btn-enviar-paquetes" type="button" class="btn  btn-secondary btn-1  mt-4 mb-0"
                            <?php if ($statusEnvio != null) {
                                echo 'disabled';
                            } ?>>
                            <?php if ($statusEnvio != null) {
                                echo '<i class="fi fi-ss-paper-plane"></i> Paquetes Enviados';
                            } else {
                                echo '<i class="fi fi-ss-paper-plane"></i> Enviar Paquetes';
                            } ?></button>

                    </div>
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                    </div>
                    {{-- <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Agregar nuevo pedido</button>
                    </div> --}}
                    <div class="card-body px-2 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="tbl-paquetes" class="table align-items-center table-striped" style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Casillero</th>
                                        <th>Nombre del Cliente</th>
                                        <th>Número de tracking</th>
                                        <th>Descripción del paquete</th>
                                        <th>Fecha de registro</th>
                                        <th>Hora de registro</th>
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

                <div class="card mb-0 p-2" id="card-list-enviados">
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                    </div>
                    {{-- <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal"
                            data-bs-target="#modal-form">Agregar nuevo pedido</button>
                    </div> --}}
                    <div class="card-body px-2 pt-0 pb-2">
                        <div class="d-flex  justify-content-end ">
                            <button id="btn-liquidar-caja" onclick="liquidarCaja()"
                                class="btn btn-warning btn-1 mb-5"
                                @php
                                    if($estadoLiquidadoCaja == 1){
                                        echo 'disabled';
                                    }
                                @endphp><i class="fi fi-ss-money"></i> 
                                &nbsp;
                                @php
                                    if($estadoLiquidadoCaja == 1){
                                        echo 'Caja Liquidada';
                                    }else{
                                        echo 'Liquidar Caja';
                                    }
                                @endphp
                            </button>
                        </div>
                        <div class="container">
                            <div id="total-envio" class="row text-center">
                                {{-- <div class="col">
                                    Total libras: <strong>50</strong>
                                </div>
                                <div class="col">
                                    Total Pagado: <strong>500</strong>
                                </div>
                                <div class="col">
                                    Mitad Ganancia: <strong>5000</strong>
                                </div> --}}
                            </div>
                            
                        </div>
                        <br>
                        <div class="table-responsive p-0">
                            <table id="tbl-paquetes-enviados" class="table align-items-center table-striped"
                                style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Casillero</th>
                                        <th>Nombre del Cliente</th>
                                        <th>Número de paquetes</th>
                                        <th>Peso de envío (lb)</th>
                                        <th>Precio de envío (L)</th>
                                        <th>Estado de pago</th>
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
                                <h3 class="font-weight-bolder text-default">Actualizar información de paquete</h3>
                                {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                            </div>
                            <div class="card-body">
                                <form role="form text-left">
                                    <input type="text" class="form-control" id="inpIdPaquete" style="display: none">
                                    <input type="text" class="form-control" id="inpIdCliente" style="display: none">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="department">Número de Tracking<span
                                                        style="color: red">*</span></label>
                                                <input type="text" name="identity" class="form-control"
                                                    id="inpTracking" placeholder="Ingresa el numero de tracking"
                                                    aria-label="Producto" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="department">Descripción del paquete<span
                                                        style="color: red">*</span></label>
                                                <textarea id="inpDescripcion" type="email" name="email" class="form-control"
                                                    placeholder="Ingresa la descripcion del paquete" rows="5" aria-label="Enlace" required></textarea>

                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center" id="mensaje"
                                            style="color: red; font-size:12px"></div>
                                    </div>
                                    <div class="text-center d-flex flex-row justify-content-end">
                                        <button id="btn-actualizar-paquete" type="submit"
                                            class="btn  bg-gradient-secondary btn-3 mt-4 mb-0">Actualizar</button>
                                        &nbsp;&nbsp;
                                        <button id="btn-cancelar-paquete" type="button"
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

        <div class="modal fade" id="modal-form-envio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-left">
                                <h3 class="font-weight-bolder text-default">Agregar datos del pedido</h3>
                                {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                            </div>
                            <div class="card-body">
                                <form role="form text-left">
                                    <input type="text" class="form-control" id="inpIdClienteEnvio"
                                        style="display: none">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="department">Nombre del Cliente</label>
                                                <input type="text" name="name" class="form-control"
                                                    id="inpNombreClienteEnvio" placeholder="Nombre del cliente"
                                                    aria-label="Producto" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-4">
                                            <div class="form-group">
                                                <label for="FormControlSelect1">Peso del envío (lb)<span
                                                        style="color: red">*</span></label>
                                                <input type="number" name="cantidad" class="form-control"
                                                    id="inpPesoEnvio" placeholder="0 lb" aria-label="Phone">

                                            </div>
                                        </div>
                                        <div class="col-md-3 col-4">
                                            <div class="form-group">
                                                <label for="FormControlSelect1">Precio del envío (L)<span
                                                        style="color: red">*</span></label>
                                                <input type="number" name="precio" class="form-control"
                                                    id="inpPrecioEnvio" placeholder="L 0.00" aria-label="Phone">

                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6 col-6 align-middle">
                                            <div class="form-group">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="chkPago">
                                                    <label class="form-check-label" for="chkPago">Estado de Pago</label>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-12 text-center" id="mensaje"
                                            style="color: red; font-size:12px"></div>
                                    </div>
                                    <div class="text-center d-flex flex-row justify-content-end">
                                        <button id="btn-guardar-envio" type="button"
                                            class="btn  bg-gradient-secondary btn-3 mt-4 mb-0">Guardar</button>
                                        &nbsp;&nbsp;
                                        <button id="btn-cancelar-envio" type="button"
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



    @include('layouts.footers.auth.footer')
@endsection
@push('js')
    <script>
        var urlTable = "{{ route('ver-paquetes', ['id' => $idCaja]) }}";
        var urlTableEnviados = "{{ route('ver-paquetes-enviados', ['id' => $idCaja]) }}";
        var vrIdCaja = "{{ $idCaja }}";
        var vrCostoEnvioCaja = "{{ $costoEnvioCaja }}";
        var stateCookieEnviado = getCookie("stateListEnviados");

        //alert(stateCookieEnviado)


        if (stateCookieEnviado == null) {
            $('#card-list-enviados').hide();
            $('li.nav-item a#lnk-list-paquetes').addClass('active');
            $('li.nav-item a#lnk-list-paquetes').attr('aria-current', 'page');
        } else {
            inicializarTablaEnvios()
            $('li.nav-item a#lnk-list-enviados').addClass('active');
            $('li.nav-item a#lnk-list-enviados').attr('aria-current', 'page');
            $('#card-list-enviados').show();
            $('#card-list-paquetes').hide();

        }

        $(document).ready(function() {

            document.getElementById('form-paquete').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevenir el envío automático del formulario
            });

            document.getElementById('inpNumeroTracking').addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Evita el submit cuando se presiona Enter
                }
            });

            inicializarTotales();
            //$('#card-list-paquetes').show();
            // $('#tbl-paquetes-enviados').hide();

        });

        $('#tbl-paquetes').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: urlTable,
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
                    data: 'casillero',
                    name: 'casillero'
                },
                {
                    data: 'nombre_cliente',
                    name: 'nombre_cliente'
                },
                {
                    data: 'numero_tracking',
                    name: 'numero_tracking'
                },
                {
                    data: 'descripcion',
                    name: 'descripcion',
                    render: function(data, type, row) {
                        // Aquí se ajusta el texto largo para que no se desborde
                        return '<div style="max-width: 300px !important;  white-space: normal !important; word-wrap: break-word !important;">' +
                            data + '</div>';
                    }
                },
                {
                    data: 'fecha_registro',
                    name: 'fecha_registro'
                },
                {
                    data: 'hora_registro',
                    name: 'hora_registro'
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



        $('#lnk-list-paquetes').click(function(e) {
            e.preventDefault();

            document.cookie = "stateListEnviados=active; expires=Thu, 01 Jan 1970 00:00:00 UTC";
            var cookieState = getCookie('stateListEnviados')
            //console.log(cookieState)

            $('li.nav-item a#lnk-list-paquetes').addClass('active');
            $('li.nav-item a#lnk-list-enviados').removeClass('active');
            $('li.nav-item a#lnk-list-paquetes').attr('aria-current', 'page');
            $('#card-list-paquetes').show().fadeIn();
            $('#card-list-enviados').hide().fadeOut();
        });


        $('#lnk-list-enviados').click(function(e) {
            e.preventDefault();

            document.cookie = "stateListEnviados=active";
            var cookieState = getCookie('stateListEnviados')
            //console.log(cookieState)

            $('li.nav-item a#lnk-list-paquetes').removeClass('active');
            $('li.nav-item a#lnk-list-enviados').addClass('active');
            $('li.nav-item a#lnk-list-enviados').attr('aria-current', 'page');
            $('#card-list-paquetes').hide().fadeOut();
            $('#card-list-enviados').show().fadeIn();
            inicializarTablaEnvios()
        });



        $('#btn-cancelar-paquete').on('click', function() {
            $('div#mensaje').html('')
            $('#inpTracking').val('');
            $('#inpDescripcion').val('');
            $('#inpIdPaquete').val('');
            $('#inpIdCliente').val('');
            $('#modal-form').modal('toggle')

        });

        $('#btn-cancelar-envio').on('click', function() {
            $('div#mensaje').html('')
            $('#inpNombreClienteEnvio').val('');
            $('#modal-form-envio').modal('toggle')

        });


        $('#btn-registrar-paquete').click(function(e) {
            e.preventDefault();

            var urlRest = "{{ url('/registrar-paquete') }}";
            var vrNumeroTracking = $('#inpNumeroTracking').val();
            var vrDescripcionPaquete = $('#inpDescripcionPaquete').val();

            var input = document.getElementById('nombre-cliente'); // Obtén el elemento input
            var selectedValue = input.value; // Obtiene el valor seleccionado
            // Busca la opción correspondiente en el datalist
            var selectedOption = $('#clientes-list option[value="' + selectedValue + '"]');
            if (selectedOption.length > 0) {
                var dataId = selectedOption.attr('data-idUsuario'); // Obtiene el valor del atributo data-idUsuario
                //console.log('Valor de data-idUsuario:', dataId);
                $('#inpNumeroTracking').focus();
            } else {
                console.log('No se encontró una opción seleccionada con ese valor.');
            }

            //console.log(dataId);

            if (typeof dataId != "undefined" && vrNumeroTracking != '' && vrDescripcionPaquete != '') {

                $(document).ajaxSend(function() {
                    $("#overlay").fadeIn(300);
                });

                $('#btn-crear-pedido').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: urlRest,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "idCaja": vrIdCaja,
                        "idCliente": dataId,
                        "numeroTracking": vrNumeroTracking,
                        "descripcionPaquete": vrDescripcionPaquete,
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

                        $('#tbl-paquetes').DataTable().ajax.reload();
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                }).done(function() {
                    $('div#mensaje').html('')
                    $('#nombre-cliente').val('');
                    $('#inpNumeroTracking').val('');
                    $('#inpDescripcionPaquete').val('');
                    setTimeout(function() {
                        $("#overlay").fadeOut(300);
                    }, 500);
                });
            } else {
                $('div#mensaje').html('Llena todos los campos, por favor')
            }
        });


        $('#btn-actualizar-paquete').click(function(e) {
            e.preventDefault();

            var urlRest = "{{ route('editar-paquete') }}";
            var vrNumeroTracking = $('#inpTracking').val();
            var vrDescripcionPaquete = $('#inpDescripcion').val();
            var vrIdPaquete = $('#inpIdPaquete').val();
            var vrIdCliente = $('#inpIdCliente').val();
            //var vrIdCaja = "{{ $idCaja }}";

            //alert(vrIdPedido)
            //console.log(dataId);

            if (vrNumeroTracking != '' && vrDescripcionPaquete != '') {

                $(document).ajaxSend(function() {
                    $("#overlay").fadeIn(300);
                });

                $('#btn-actualizar-paquete').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: urlRest,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "numeroTracking": vrNumeroTracking,
                        "descripcionPaquete": vrDescripcionPaquete,
                        "idPaquete": vrIdPaquete,
                        "idCliente": vrIdCliente,
                        "idCaja": vrIdCaja,
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

                        //window.location.reload();
                        $('#tbl-paquetes').DataTable().ajax.reload();
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                }).done(function() {

                    $('#modal-form').modal('toggle');
                    $('div#mensaje').html('')
                    $('#inpTracking').val('');
                    $('#inpDescripcion').val('');
                    $('#inpIdPaquete').val('');
                    $('#inpIdCliente').val('');
                    $('#btn-actualizar-paquete').prop('disabled', false);

                    setTimeout(function() {
                        $("#overlay").fadeOut(300);
                    }, 500);
                });
            } else {
                $('div#mensaje').html('Llena todos los campos')
            }
        });


        $('#btn-enviar-paquetes').on('click', function() {

            var urlRest = "{{ route('enviar-paquetes') }}";

            $.confirm({
                title: 'Confirmar envio',
                type: 'green',
                content: 'Seguro que quiere cambiar el estado de los paquetes?',
                buttons: {
                    Confirmar: function() {

                        $(document).ajaxSend(function() {
                            $("#overlay").fadeIn(300);
                        });

                        $('#btn-enviar-paquetes').prop('disabled', true);

                        $.ajax({
                            type: "POST",
                            url: urlRest,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "idCaja": vrIdCaja,
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

                                //window.location.reload();
                                //$('#tbl-paquetes').DataTable().ajax.reload();
                            },
                            error: function(request, status, error) {
                                alert(request.responseText);
                            }
                        }).done(function() {

                            $('#btn-enviar-paquetes').prop('disabled', true);
                            $('#btn-registrar-paquete').prop('disabled', true);
                            $('#btn-enviar-paquetes').html('')
                            $('#btn-enviar-paquetes').html(
                                '<i class="fi fi-ss-paper-plane"></i> Paquetes Enviados')

                            setTimeout(function() {
                                $("#overlay").fadeOut(300);
                            }, 500);
                        });
                    },
                    cancelar: function() {
                        // $.alert('Canceled!');
                    }

                }
            });

        });

        $('#chkPago').on('change', function() {
            console.log($(this).is(':checked'));
        });

        $('#btn-guardar-envio').on('click', function(e) {
            //e.preventDefault();
            //alert('hola')

            var urlRest = "{{ route('guardar-envio') }}";
            var vrIdCliente = $('#inpIdClienteEnvio').val();
            var vrPrecioEnvio = $('#inpPrecioEnvio').val();
            var vrPesoEnvio = $('#inpPesoEnvio').val();
            // var vrChckPago = $('#chkPago').val();


            if (vrIdCliente != '' && vrIdCaja != '') {

                $(document).ajaxSend(function() {
                    $("#overlay").fadeIn(300);
                });

                $('#btn-guardar-envio').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: urlRest,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "idCliente": vrIdCliente,
                        "precioEnvio": vrPrecioEnvio,
                        "pesoEnvio": vrPesoEnvio,
                        "idCaja": vrIdCaja,
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

                        //window.location.reload();
                        $('#tbl-paquetes-enviados').DataTable().ajax.reload();
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                }).done(function() {

                    inicializarTotales()

                    $('#inpNombreClienteEnvio').val('');
                    $('#modal-form-envio').modal('toggle')
                    $('div#mensaje').html('')
                    $('#inpPrecioEnvio').val('');
                    $('#inpPesoEnvio').val('');
                    $('#inpIdClienteEnvio').val('');
                    $('#btn-guardar-envio').prop('disabled', false);

                    setTimeout(function() {
                        $("#overlay").fadeOut(300);
                    }, 500);
                });
            } else {
                $('div#mensaje').html('Llena todos los campos')
            }
        });

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

        function editarPaquete(idUsuario, idPaquete, numeroTracking, descripcionPaquete) {

            $('#inpTracking').val(numeroTracking);
            $('#inpDescripcion').val(descripcionPaquete);
            $('#inpIdPaquete').val(idPaquete);
            $('#inpIdCliente').val(idUsuario);

            $('#modal-form').modal('toggle');
        }

        function pesoPaquete(idCaja, idCliente, nombreCliente, pesoEnvio, precioEnvio) {

            $('#inpIdClienteEnvio').val(idCliente);
            $('#inpNombreClienteEnvio').val(nombreCliente);
            $('#inpPrecioEnvio').val(precioEnvio);
            $('#inpPesoEnvio').val(pesoEnvio);

            $('#modal-form-envio').modal('toggle');
        }


        function inicializarTablaEnvios() {

            $(document).ajaxSend(function() {
                $("#overlay").hide();
            })

            $('#tbl-paquetes-enviados').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                ajax: {
                    url: urlTableEnviados,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Agrega el token al encabezado
                    },
                    complete: function() {
                        //$("#overlay").fadeOut(300);
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'casillero',
                        name: 'casillero'
                    },
                    {
                        data: 'nombre_cliente',
                        name: 'nombre_cliente'
                    },
                    {
                        data: 'numero_paquetes',
                        name: 'numero_paquetes'
                    },
                    {
                        data: 'peso_envio',
                        name: 'peso_envio'
                    },
                    {
                        data: 'precio_envio',
                        name: 'precio_envio'
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

        function inicializarTotales() {
            //$('#total-envio').html('<p>Contenido de prueba</p>');
            var urlRest = "{{ route('datos-envio') }}";

            $.ajax({
                type: "POST",
                url: urlRest,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "idCaja": vrIdCaja,
                },
                success: function(response) {

                    if (response.data && response.data.length > 0) {

                        var total_libras = response.data[0].total_libras;
                        var total_precio_envio = response.data[0].total_precio_envio;
                        var total_precio_envio_format = response.data[0].total_precio_envio_format;
                        var mitad_ganancia = response.data[0].mitad_ganancia;
                        var mitad_ganancia_format = response.data[0].mitad_ganancia_format;
                        var total_pagado = response.data[0].total_pagado;
                        

                        //$('#inpMitadTotal').val(mitad_ganancia);


                        $('div#total-envio').html(
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total libras: <strong style="color:#3ed06a">' +
                            total_libras + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total Precio Envio: <strong style="color:#3ed06a">' +
                            '<input type="text" id="inpTotalPrecioEnvio" value = "'+total_precio_envio_format+'" style="display: none">'+
                            total_precio_envio + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Mitad Total: <strong style="color:#3ed06a">' +
                            '<input type="text" id="inpMitadTotal" value = "'+mitad_ganancia_format+'" style="display: none">'+
                            mitad_ganancia + '</strong></div>' +
                            '</div>' +
                            '<div class="col-12 col-sm-3">' +
                            '<div class="item-total mt-2">Total Pagado: <strong style="color:#3ed06a">' +
                            total_pagado + '</strong></div>' +
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

        function cambiarEstadoPago(idUsuario, estadoPago) {
            //alert(idUsuario+' '+estadoPago)

            var urlRest = "{{ route('estado-pago') }}";
            $.ajax({
                type: "POST",
                url: urlRest,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "estadoPago": estadoPago ? 1 : 0,
                    "idCliente": idUsuario,
                    "idCaja": vrIdCaja,
                },
                success: function(response) {

                    inicializarTotales()

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

                    //$('#totales-envio').append(response.data);

                },
                error: function(request, status, error) {
                    alert(request.responseText);
                }
            });
        }

        function liquidarCaja(){
            
            var urlRest = "{{ route('liquidar-caja') }}";

            var mitadGanancia = $('#inpMitadTotal').val();
            var totalPrecioEnvio = $('#inpTotalPrecioEnvio').val();

            //alert(totalPrecioEnvio)

            vrCostoEnvioCaja == 0 ? $.alert('No se puede liquidar la caja sin costo de envío') :
            $.confirm({
                title: 'Confirmar liquidación',
                type: 'green',
                content: 'Seguro que quiere liquidar la caja?',
                buttons: {
                    Confirmar: function() {

                        $(document).ajaxSend(function() {
                            $("#overlay").fadeIn(300);
                        });

                        $('#btn-liquidar-caja').prop('disabled', true);

                        $.ajax({
                            type: "POST",
                            url: urlRest,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "idCaja": vrIdCaja,
                                "mitadGanancia": mitadGanancia,
                                "totalPrecioEnvio": totalPrecioEnvio,
                                "costoEnvio": vrCostoEnvioCaja,
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

                                //window.location.reload();
                                //$('#tbl-paquetes').DataTable().ajax.reload();
                            },
                            error: function(request, status, error) {
                                alert(request.responseText);
                            }
                        }).done(function() {

                            $('#btn-liquidar-caja').prop('disabled', true);
                            $('#btn-liquidar-caja').html('')
                            $('#btn-liquidar-caja').html(
                                '<i class="fi fi-ss-paper-plane"></i> Caja Liquidada')

                            setTimeout(function() {
                                $("#overlay").fadeOut(300);
                            }, 500);
                        });
                    },
                    cancelar: function() {
                        // $.alert('Canceled!');
                    }

                }
            });

            
            
        }

        
    </script>
@endpush
