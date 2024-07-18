@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pedidos'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 text-center">
                        {{-- <h6>Authors table</h6> --}}
                      </div>
                      <div class="align-items-center text-center">
                        <button type="button" class="btn btn-primary btn-default mb-3" data-bs-toggle="modal" data-bs-target="#modal-form">Crear nueva caja</button>
                      </div>
                      <div class="card-body px-2 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="example" class="table align-items-center table-striped" style="width:100%">
                                <thead class="">
                                    <tr>

                                        <th>No.</th>
                                        <th>Nombre</th>
                                        <th>Fecha de envío</th>
                                        <th>Fecha estimada de recepción</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
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
    </div>

    <div class="col-md-4">
        
        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body p-0">
                <div class="card card-plain">
                  <div class="card-header pb-0 text-left">
                    <h3 class="font-weight-bolder text-default">Agregar nuevo pedido</h3>
                    {{-- <p class="mb-0">Enter your email and password to sign in</p> --}}
                  </div>
                  <div class="card-body">
                    <form role="form text-left" method="POST" action="{{route('crear-caja')}}">
                        @csrf
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="ice-cream-choice">Cliente<span style="color: red">*</span></label>
                              <input list="ice-cream-flavors" placeholder="Seleccione el cliente" class="form-control" id="ice-cream-choice" name="ice-cream-choice" />
                          <datalist id="ice-cream-flavors">
                            @foreach ($usuarios as $usr)
                              <option value="{{$usr->id}}">{{'('.$usr->locker_number.') '}}{{$usr->nombre}}</option>                    
                            @endforeach
                          </datalist></div>     
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4">
                          <div class="form-group">
                              <label for="department">Nombre producto<span style="color: red">*</span></label>
                              <input type="text" name="identity" class="form-control" placeholder="Ingresa el nombre del producto" aria-label="Producto" required>
                             
                          </div>
                      </div>
                      <div class="col-md-2 col-4">
                          <div class="form-group">
                              <label for="FormControlSelect1">Cantidad<span style="color: red">*</span></label>
                              <input type="number" name="cantidad" class="form-control" max="8" placeholder="" aria-label="Phone" required>
                               
                          </div>
                      </div>
                      <div class="col-md-2 col-4">
                          <div class="form-group">
                              <label for="FormControlSelect1">Precio<span style="color: red">*</span></label>
                              <input type="number" name="cantidad" class="form-control" max="8" placeholder="" aria-label="Phone" required>
                               
                          </div>
                      </div>
                      <div class="col-md-2 col-4">
                          <div class="form-group">
                              <label for="FormControlSelect1">Ganancia<span style="color: red">*</span></label>
                              <input type="number" name="cantidad" class="form-control"  placeholder="" aria-label="Phone" required>
                               
                          </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                            <label for="department">Enlace del producto<span style="color: red">*</span></label>
                            <textarea type="email" name="email" class="form-control" placeholder="Ingresa el enlace" rows="5" aria-label="Enlace" required></textarea>
                  
                        </div>
                    </div>
                        </div>
                        <div class="text-center d-flex flex-row justify-content-end">
                          <button id="btn-crear-pedido" type="submit" class="btn  bg-gradient-secondary btn-3 w-30 mt-4 mb-0">Crear</button>
                          <button id="btn-cancelar-pedido" type="button" class="btn btn-3 w-30 mt-4 mb-0 ml-50">Cancelar</button>
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
            language: idiomaDatatables,
            
        });

        $('#btn-cancelar-pedido').on('click', function () {
          $('#modal-form').modal('toggle')
        });

    </script>
@endpush
