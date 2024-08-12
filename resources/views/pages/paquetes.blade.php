@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Paquetes'])
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
                                <button id="btn-registrar-paquete" type="submit" class="btn  btn-success btn-1  mt-4 mb-0"><i
                                        class="fi fi-sr-add"></i> Agregar paquete</button>
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
                <div class="card mb-0 p-2">
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
            </div>
        </div>

    </div>
    </div>
    @include('layouts.footers.auth.footer')
@endsection
@push('js')
    <script>
        var urlTable = "{{ route('ver-paquetes', ['id' => $idCaja]) }}";
        var vrIdCaja = "{{ $idCaja }}";


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
                    name: 'descripcion'
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

        $('#btn-cancelar-pedido').on('click', function() {
            $('div#mensaje').html('')
            $('#inpNombreProducto').val('');
            $('#inpCantidad').val('');
            $('#inpPrecio').val('');
            $('#inpGanancia').val('');
            $('#inpEnlaceProducto').val('');
            $('#nombre-cliente').val('');
            $('#modal-form').modal('toggle')

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

        
    </script>
@endpush
