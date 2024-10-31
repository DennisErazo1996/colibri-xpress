@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Clientes'])
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
                                        <label for="department">Nombres del cliente<span style="color: red">*</span></label>
                                        <input type="text" name="identity" class="form-control" id="inpNombreCliente"
                                            placeholder="Ingresa nombres del cliente" aria-label="nombre" required
                                            autofocus>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="department">Apellidos del cliente<span
                                                style="color: red">*</span></label>
                                        <input type="text" name="identity" class="form-control" id="inpApellidoCliente"
                                            placeholder="Ingresa apellidos del cliente" aria-label="apellido" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button id="btn-registrar-cliente"
                                            type="submit"class="btn  btn-success btn-1  mt-4 mb-0"><i
                                                class="fi fi-sr-add"></i> Agregar cliente</button>
                                    </div>
                                </div>


                                <div class="col-md-12 text-center" id="mensaje" style="color: red; font-size:12px"></div>
                            </div>
                            <div class="text-center d-flex flex-row justify-content-end">

                                {{-- <button id="btn-cancelar-pedido" type="button"
                                    class="btn btn-3 w-30 mt-4 mb-0 ml-50">Cancelar</button> --}}
                            </div>
                        </form>
                        <br>
                        <div class="table-responsive p-0">
                            <table id="tbl-clientes" class="table align-items-center table-striped" style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Nombre cliente</th>
                                        <th>Fecha de registro</th>
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

    <div class="col-md-4">
        <div class="modal fade" id="modal-form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-left">
                                <h3 class="font-weight-bolder text-default">Actualizar información de Cliente</h3>
                                {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                            </div>
                            <div class="card-body">
                                <form role="form text-left">
                                    <input type="text" class="form-control" id="inpIdCliente" style="display: none">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="department">Nombres del cliente<span
                                                        style="color: red">*</span></label>
                                                <input type="text" name="identity" class="form-control"
                                                    id="inpNombreClienteEdit" placeholder="Ingresa nombres del cliente"
                                                    aria-label="nombre" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="department">Apellidos del cliente<span
                                                        style="color: red">*</span></label>
                                                <input type="text" name="identity" class="form-control"
                                                    id="inpApellidoClienteEdit" placeholder="Ingresa apellidos del cliente"
                                                    aria-label="apellido" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center" id="mensaje"
                                            style="color: red; font-size:12px"></div>
                                    </div>
                                    <div class="text-center d-flex flex-row justify-content-end">
                                        <button id="btn-actualizar-cliente" type="submit"
                                            class="btn  bg-gradient-secondary btn-3 mt-4 mb-0">Actualizar</button>
                                        &nbsp;&nbsp;
                                        <button id="btn-cancelar-cliente-edit" type="button"
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
    </div>




    @include('layouts.footers.auth.footer')
@endsection
@push('js')
    <script>
        var urlTableClientes = "{{ route('ver-clientes') }}";

        $('#tbl-clientes').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: urlTableClientes,
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
                    data: 'nombre_completo',
                    name: 'nombre_completo'
                },
                {
                    data: 'fecha_registro',
                    name: 'fecha_registro'
                },
                {
                    data: 'opcion',
                    name: 'opcion',
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

        $('#btn-registrar-cliente').on('click', function() {

            var urlRest = "{{ route('registrar-cliente') }}";
            var vrNombreCliente = $('#inpNombreCliente').val();
            var vrApellidoCliente = $('#inpApellidoCliente').val();

            if (vrNombreCliente != '' && vrApellidoCliente != '') {

                $(document).ajaxSend(function() {
                    $("#overlay").fadeIn(300);
                });

                $('#btn-registrar-cliente').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: urlRest,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "nombreCliente": vrNombreCliente,
                        "apellidoCliente": vrApellidoCliente,
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

                        $('#tbl-clientes').DataTable().ajax.reload();
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                }).done(function() {
                    $('div#mensaje').html('')
                    $('#inpNombreCliente').val('');
                    $('#inpApellidoCliente').val('');
                    $('#btn-registrar-cliente').prop('disabled', false);
                    setTimeout(function() {
                        $("#overlay").fadeOut(300);
                    }, 500);
                });
            } else {
                $('div#mensaje').html('Llena todos los campos, por favor')
            }
        });

        $('#btn-cancelar-cliente-edit').on('click', function() {
            $('div#mensaje').html('')
            $('#inpNombreClienteEdit').val('');
            $('#inpApellidoClienteEdit').val('');
            $('#inpIdCliente').val('');
            $('#modal-form').modal('toggle')

        });

        $('#btn-actualizar-cliente').click(function(e) {
            e.preventDefault();

            var urlRest = "{{ route('editar-cliente') }}";
            var vrNombreCliente = $('#inpNombreClienteEdit').val();
            var vrApellidoCliente = $('#inpApellidoClienteEdit').val();
            var vrIdCliente = $('#inpIdCliente').val();
            //var vrChckPago = $('#chkPago').val();

            if (vrNombreCliente != '' && vrApellidoCliente != '' && vrIdCliente != '') {

                $(document).ajaxSend(function() {
                    $("#overlay").fadeIn(300);
                });

                $('#btn-actualizar-cliente').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: urlRest,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "idCliente": vrIdCliente,
                        "nombreCliente": vrNombreCliente,
                        "apellidoCliente": vrApellidoCliente,
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
                        $('#tbl-clientes').DataTable().ajax.reload();
                    },
                    error: function(request, status, error) {
                        alert(request.responseText);
                    }
                }).done(function() {

                    $('#modal-form').modal('toggle');
                    $('#inpNombreClienteEdit').val('');
                    $('#inpApellidoClienteEdit').val('');
                    $('#inpIdCliente').val('');
                    $('#btn-actualizar-cliente').prop('disabled', false);

                    setTimeout(function() {
                        $("#overlay").fadeOut(300);
                    }, 500);
                });
            } else {
                $.alert('Debes seleccionar un cliente, nombres y apellidos')
            }
        });

        function editarCliente(idCliente, nombreCliente, apellidoCliente) {
            $('#inpNombreClienteEdit').val(nombreCliente);
            $('#inpApellidoClienteEdit').val(apellidoCliente);
            $('#inpIdCliente').val(idCliente);
            $('#modal-form').modal('toggle');
        }

        function eliminarCliente(idCliente) {
            urlRest = "{{ route('eliminar-cliente') }}";

            $.confirm({
                type: 'red',
                animation: 'scale',
                title: 'Eliminar Cliente',
                content: 'Seguro que quiere eliminar este cliente?',
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
                                    "idCliente": idCliente,
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

                                    location.reload();
                                    $('#tbl-clientes').DataTable().ajax.reload();
                                },
                                error: function(request, status, error) {
                                    alert(request.responseText);
                                }
                            }).done(function() {
                                setTimeout(function() {
                                    $("#overlay").fadeOut(300);
                                }, 500);
                            });
                        }
                    },
                    cancelar: function() {
                        //$.alert('Canceled!');
                    },

                }
            });


            //alert(idCliente);
        }
    </script>
@endpush
