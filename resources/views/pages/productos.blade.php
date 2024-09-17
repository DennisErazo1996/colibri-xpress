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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre-cliente">Cliente<span style="color: red">*</span></label>
                                        <input list="clientes-list" placeholder="Seleccione el cliente" class="form-control"
                                            id="nombre-cliente" name="nombre-cliente" />
                                        <datalist id="clientes-list">
                                            {{-- @foreach ($usuarios as $usr)
                                                <option value="{{ '(' . $usr->locker_number . ') ' }}{{ $usr->nombre }}"
                                                    data-idUsuario="{{ $usr->id }}"></option>
                                            @endforeach --}}
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
                                <button id="btn-registrar-paquete" type="submit"
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

    




    @include('layouts.footers.auth.footer')
@endsection
@push('js')
    <script>
      
        var stateCookieEnviado = getCookie("stateListEnviados");

        //alert(stateCookieEnviado)



        if (stateCookieEnviado == null) {
            $('#card-list-enviados').hide();
            $('li.nav-item a#lnk-list-paquetes').addClass('active');
            $('li.nav-item a#lnk-list-paquetes').attr('aria-current', 'page');
        } else {
            //inicializarTablaEnvios()
            $('li.nav-item a#lnk-list-enviados').addClass('active');
            $('li.nav-item a#lnk-list-enviados').attr('aria-current', 'page');
            $('#card-list-enviados').show();
            $('#card-list-paquetes').hide();

        }

        $(document).ready(function() {

            
            //$('#card-list-paquetes').show();
            // $('#tbl-paquetes-enviados').hide();

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
