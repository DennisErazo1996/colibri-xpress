@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Cajas'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal" data-bs-target="#modal-form">Crear nueva caja</button>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="example" class="table table-striped" style="width:100%">
                                <thead class="">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Fecha de envío</th>
                                        <th>Fecha estimada de recepción</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($cajas as $bxs)
                                    <tr>
                                        <td>{{$bxs->numero_caja}}</td>
                                        <td>{{$bxs->fecha_envio}}</td>
                                        <td>{{$bxs->fecha_arribo}}</td>
                                        <td>hi!</td>
                                    </tr>    
                                    @endforeach
                                    
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
    </div>

    <div class="col-md-4">
        
        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
              <div class="modal-body p-0">
                <div class="card card-plain">
                  <div class="card-header pb-0 text-left">
                    <h3 class="font-weight-bolder text-info text-gradient">Ingresa los datos</h3>
                    {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                  </div>
                  <div class="card-body">
                    <form role="form text-left" method="POST" action="{{route('crear-caja')}}">
                        @csrf
                      <label>Fecha de envío</label>
                      <div class="input-group mb-3">
                        <input type="date" name="fechaEnvio" class="form-control" placeholder="Selecciona la fecha" aria-label="Date">
                      </div>
                      <label>Fecha estimada de llegada</label>
                      <div class="input-group mb-3">
                        <input type="date" name="fechaArribo" class="form-control" placeholder="Ingresa la fecha de llegada" aria-label="Date">
                      </div>
                      <div class="text-center">
                        <button id="btn-crear-caja" type="submit" class="btn btn-round bg-gradient-info btn-lg w-100 mt-4 mb-0">Crear</button>
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
@endsection
@push('js')
    <script>
        $('#example').DataTable({
    columnDefs: [
        { className: 'dt-center', targets: '_all' },
    ],
    language: {
        url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/es-MX.json',
    },
    
});
    </script>
@endpush