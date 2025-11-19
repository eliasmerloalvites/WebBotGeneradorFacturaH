@extends('layout.appAdminLte')

@section('titulo', 'Marcacion')

@section('contenido')

    <head>
        <style>
            .bg-orange {
                background-color: orange;
            }
        </style>
        <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
        <link href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
        <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    </head>

    @can('gestion.marcacion.create')
        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Nueva Marcación</h5>
                </div>
                <div class="card-body">
                    <form id="marcacion_form" method="POST" action="{{ route('gestion.marcacion.store') }}">
                        @csrf
                        {{-- VEHÍCULO --}}
                        <div class="form-group">
                            <label for="COC_Id">Cochera</label>
                            <select class="form-control select2 select2-primary" id="COC_Id" name="COC_Id"
                                data-dropdown-css-class="select2-primary" style="width: 100%;"
                                {{ $selectedCoc ? '' : 'required' }}>
                                {{-- Solo mostramos el placeholder si NO hay cochera preseleccionada --}}
                                @unless ($selectedCoc)
                                    <option value="">Seleccionar…</option>
                                @endunless

                                @foreach ($cocheras as $coc)
                                    <option value="{{ $coc->id }}"
                                        {{ $coc->id == old('COC_Id', $selectedCoc) ? 'selected' : '' }}>
                                        {{ $coc->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- si lo necesitas para JS u otro fin --}}
                            <input type="hidden" id="idTipoVehiculo">
                        </div>
                        <div class="form-group">
                            <label for="vehiculo_input">Vehículo (Placa)</label>
                            <div class="input-group">
                                <!-- Input readonly en el que se mostrará la placa seleccionada o registrada -->
                                <input type="text" class="form-control" id="vehiculo_input" name="placa" readonly required>
                                <input type="hidden" id="VEH_Id" name="VEH_Id">
                                <div class="input-group-append">
                                    <!-- El botón ahora dice "Ingresar Placa" -->
                                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal"
                                        data-target="#nuevoVehiculoModal">
                                        Ingresar Placa
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- ESPACIO DE PARQUEO (oculto por defecto) --}}
                        <div class="form-group" id="espacio_group" style="display:none;">
                            <label for="espacio_input">Espacio de Parqueo</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="espacio_input" readonly>
                                <input type="hidden" id="espacio_id" name="ESP_Id">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal"
                                        data-target="#nuevoEspacioModal">
                                        Nuevo Espacio
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- NUEVO BLOQUE DE BOTONES PARA TARIFAS -->
                        <div class="form-group">
                            <label>Seleccionar Tarifa</label>
                            <div class="btn-group btn-group-toggle d-flex" role="group" id="tarifaButtons"
                                aria-label="Tarifas">
                                <button type="button" class="btn btn-outline-primary tarifa-btn active" data-tarifa="hora">x
                                    Hora</button>
                                <button type="button" class="btn btn-outline-primary tarifa-btn" data-tarifa="dia">x
                                    Día</button>
                                <button type="button" class="btn btn-outline-primary tarifa-btn" data-tarifa="semana">x
                                    Semana</button>
                                <button type="button" class="btn btn-outline-primary tarifa-btn" data-tarifa="mes">x
                                    Mes</button>
                            </div>
                            <!-- Campo oculto para guardar la tarifa seleccionada (valor por defecto "hora") -->
                            <input type="hidden" name="tarifa" id="tarifa_seleccionada" value="hora">
                        </div>
                        {{-- FECHA Y HORA DE ENTRADA --}}
                        <div class="form-group" hidden>
                            <label for="fecha_entrada">Fecha y Hora de Entrada</label>
                            <input type="datetime-local" class="form-control" id="fecha_entrada" name="fecha_entrada">
                        </div>
                        {{-- FECHA Y HORA DE SALIDA --}}
                        <div class="form-group" hidden>
                            <label for="fecha_salida">Fecha y Hora de Salida</label>
                            <input type="datetime-local" class="form-control" id="fecha_salida" name="fecha_salida">
                        </div>
                        {{-- MONTO TOTAL --}}
                        <div class="form-group" hidden>
                            <label for="monto_total">Monto x Hora</label>
                            <input type="number" step="0.01" class="form-control" id="monto_total" name="monto_total"
                                placeholder="0.00">
                        </div>
                        {{-- ESTADO --}}
                        <div class="form-group" hidden>
                            <label for="estado">Estado</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="activo">Activo</option>
                                <option value="finalizado">Finalizado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        {{-- USU_Id se asigna en el controlador (por ejemplo, del usuario autenticado) --}}
                        <button type="submit" id="btnGuardarMarcacion" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-2"></i> Guardar Marcación
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    {{-- Listado de Marcaciones vía AJAX con DataTable --}}
    @can('gestion.marcacion.index')
        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Lista de Marcaciones</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabla_marcaciones">
                            <thead style="background-color:#123D75;color: #fff;">
                                <tr>
                                    <th>Acciones</th>
                                    <th>#</th>
                                    <th>Vehículo</th>
                                    <th>Espacio</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    {{-- Modal para Nuevo Vehículo --}}
    <div class="modal fade" id="nuevoVehiculoModal" tabindex="-1" role="dialog"
        aria-labelledby="nuevoVehiculoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="vehiculo_form" action="{{ route('gestion.vehiculo.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <!-- Título modificado -->
                        <h5 class="modal-title" id="nuevoVehiculoModalLabel">Ingresar Placa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        {{-- Campo especial para Placa: 6 inputs separados --}}
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Placa</label>
                            <div class="input-group" id="placaInputs">
                                @for ($i = 1; $i <= 6; $i++)
                                    <input type="text" name="placa[]" maxlength="1"
                                        class="form-control text-center placa-box" style="width: 40px;" required>
                                @endfor
                            </div>
                            <!-- Área para alerta (se insertará dinámicamente) -->
                            <small class="form-text text-muted">Ingrese 6 caracteres (cada cuadro una letra o
                                número).</small>
                        </div>
                        {{-- Tipo de Vehículo --}}
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label>Tipo de Vehículo *</label>
                            <select id="idTIV_Id" name="TIV_Id" class="form-control" required>
                                <option value="">-- Seleccione Tipo --</option>
                                @foreach ($tiposVehiculos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Color --}}
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label>Color *</label>
                            <input type="text" name="color" class="form-control">
                        </div>
                        {{-- Marca --}}
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label>Marca</label>
                            <input type="text" name="marca" class="form-control">
                        </div>
                        {{-- Modelo --}}
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label>Modelo</label>
                            <input type="text" name="modelo" class="form-control">
                        </div>
                        {{-- Campo Cliente (se ocultará si la placa ya existe) --}}
                        <div class="form-group" hidden>
                            <label>Cliente (opcional)</label>
                            <select name="CLI_Id" class="form-control">
                                <option value="">-- Sin Cliente --</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- El botón se adaptará: "Registrar Vehículo" o "Añadir" -->
                        <button type="submit" id="btnGuardarVehiculo" class="btn btn-success">Registrar
                            Vehículo</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal para Nuevo Espacio de Parqueo --}}
    <div class="modal fade" id="nuevoEspacioModal" tabindex="-1" role="dialog"
        aria-labelledby="nuevoEspacioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="nuevoEspacioModalLabel">Seleccione un Espacio de Parqueo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Aquí se cargarán dinámicamente las cards de espacios -->
                <div class="modal-body" id="espacios_container">
                    <div class="row">
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Si deseas incluir un botón para cerrar sin seleccionar -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edicionMarcacionModal" tabindex="-1" role="dialog"
        aria-labelledby="edicionMarcacionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="cliente_form" action="{{ route('gestion.cliente.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <!-- Título modificado -->
                        <h5 class="modal-title" id="edicionMarcacionModalLabel">Agregar cliente a la Marcacion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Vehículo -->
                                <div class="card card-outline card-info text-left">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-car"></i> Vehículo</h3>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Tipo:</strong> <span id="ver_idTipoVehiculoEd"
                                                class="text-start"></span></p>
                                        <p><strong>Placa:</strong> <span id="ver_placaEd" class="text-start"></span></p>
                                        <p><strong>Info:</strong> <span id="ver_idInfoEd" class="text-start"></span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Detalle de Tarifa / Marcación -->
                                <div class="card card-outline card-warning text-left">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-clock"></i> Tarifa por <strong> <span
                                                    id="ver_timeEd" class="text-start"></span></strong></h3>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col-md-6">
                                            <p><strong>Espacio: </strong> <span id="ver_espacioEd"
                                                    class="text-start"></span></p>
                                            <p><strong>Entrada: </strong> <span id="ver_fechaentradaEd"
                                                    class="text-start"></span>
                                            </p>
                                            <p><strong>Hora: </strong> <span id="ver_horaentradaEd"
                                                    class="text-start"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Tiempo:</strong> <span id="ver_tiempoEd" class="text-start"></span>
                                            </p>
                                            <p><strong>Pagar:</strong> <span class="text-danger font-weight-bold">S/ <span
                                                        id="ver_totalEd" class="text-start"></span></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <!-- Cliente -->
                            <div class="card card-outline card-success text-left">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-user"></i> Cliente</h3>
                                </div>
                                <div class="card-body">
                                    <div class="input-group input-group-sm mb-2">
                                        <input type="hidden" id="vehiculo_id_edit" name="VEH_Id">
                                        <input type="hidden" id="espacio_parqueo_id_edit" name="ESP_Id">
                                        <input type="hidden" id="marcacion_id_edit" name="MAR_Id">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">DNI *</span>
                                        </div>
                                        <input type="text" name="documento" class="form-control">
                                    </div>
                                    <div class="input-group input-group-sm  mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">NOMBRE *</span>
                                        </div>
                                        <input type="text" name="nombre" class="form-control">
                                    </div>
                                    <div class="input-group input-group-sm  mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">CELULAR</span>
                                        </div>
                                        <input type="text" name="telefono" class="form-control">
                                    </div>
                                    <div class="input-group input-group-sm  mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">CORREO</span>
                                        </div>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer con botones -->
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-times-circle"></i> Cerrar Ticket
                        </button>
                        <button type="button" id="btnGuardarCliente" class="btn btn-info">
                            <i class="fas fa-file-invoice-dollar"></i> Actualizar Marcacion
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="terminarMarcacionModal" tabindex="-1" role="dialog"
        aria-labelledby="terminarMarcacionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="terminar_marcacion_form" action="{{ route('gestion.marcacion.storecerrar') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <!-- Título modificado -->
                        <h5 class="modal-title" id="terminarMarcacionModalLabel">Detalle de la Marcacion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="marcacion_id_cerrar" name="MAR_Id">
                            <input type="hidden" id="monto_total_cerrar" name="monto_total">
                            <!-- Vehículo -->
                            <div class="col-md-6">
                                <div class="card card-outline card-info text-left">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-car"></i> Vehículo</h3>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Tipo:</strong> <span id="ver_idTipoVehiculo" class="text-start"></span>
                                        </p>
                                        <p><strong>Placa:</strong> <span id="ver_placa" class="text-start"></span></p>
                                        <p><strong>Info:</strong> <span id="ver_idInfo" class="text-start"></span></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Cliente -->
                            <div class="col-md-6">
                                <div class="card card-outline card-success text-left">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-user"></i> Cliente</h3>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>DNI:</strong> <span id="ver_idDni" class="text-start"></span></p>
                                        <p><strong>Nombre:</strong> <span id="ver_idNombre" class="text-start"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalle de Tarifa / Marcación -->
                        <div class="card card-outline card-warning mt-3 text-left">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-clock"></i> Detalle de Tarifa por <strong> <span
                                            id="ver_time" class="text-start"></span></strong></h3>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6">
                                    <p><strong>Espacio: </strong> <span id="ver_espacio" class="text-start"></span></p>
                                    <p><strong>Entrada: </strong> <span id="ver_fechaentrada" class="text-start"></span>
                                    </p>
                                    <p><strong>Hora: </strong> <span id="ver_horaentrada" class="text-start"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Tiempo Transcurrido:</strong> <span id="ver_tiempo"
                                            class="text-start"></span></p>
                                    <p><strong>Precio a Pagar:</strong> <span class="text-danger font-weight-bold">S/ <span
                                                id="ver_total" class="text-start"></span></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer con botones -->
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-times-circle"></i> Cerrar Detalle
                        </button>
                        <button type="button" class="btn btn-info" id="btnGuardarMarcacionTerminar"> Cerrar
                            Marcacion</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            function abrirModalAsignarMarcacionTerminar(id){

                $('#terminarMarcacionModal').find('input[name="marcacion_id"]').val(id);
                $('#terminarMarcacionModal').modal('show');

                $.get('{{ route('gestion.marcacion.show', ['marcacion' => ':marcacion']) }}'.replace(
                        ':marcacion',
                        id),
                    function(data) {
                        console.log(data)
                        $('#ver_idTipoVehiculo').text(data.data.TIV_Id);
                        $('#ver_placa').text(data.data.placa);
                        $('#ver_idInfo').text(data.data.color + ' ' + data.data.marca + ' ' +
                            data.data.modelo);
                        $('#ver_idDni').text(data.data.documento);
                        $('#ver_idNombre').text(data.data.NombreCliente);
                        $('#ver_time').text(data.data.tipo_marcacion);
                        $('#ver_espacio').text(data.data.CodigoEspacio);
                        $('#ver_fechaentrada').text(data.data.fechaentrada);
                        $('#ver_horaentrada').text(data.data.horaentrada);
                        $('#ver_tiempo').text(data.data.tiempo);
                        $('#ver_total').text(data.data.total);
                        $('#marcacion_id_cerrar').val(id);
                        $('#monto_total_cerrar').val(data.data.total);

                    })

            }

            function abrirModalAsignarMarcacion(id) {
                $('#edicionMarcacionModal').find('input[name="marcacion_id"]').val(id);
                $('#edicionMarcacionModal').modal('show');

                $.get('{{ route('gestion.marcacion.show1', ['marcacion' => ':marcacion']) }}'.replace(
                        ':marcacion',
                        id),
                    function(data) {
                        console.log(data)
                        $('#ver_idTipoVehiculoEd').text(data.data.TIV_Id);
                        $('#vehiculo_id_edit').val(data.data.VEH_Id);
                        $('#espacio_parqueo_id_edit').val(data.data.ESP_Id);
                        $('#marcacion_id_edit').val(id);
                        $('#ver_placaEd').text(data.data.placa);
                        $('#ver_idInfoEd').text(data.data.color + ' ' + data.data.marca + ' ' +
                            data.data.modelo);
                        $('#ver_timeEd').text(data.data.tipo_marcacion);
                        $('#ver_espacioEd').text(data.data.CodigoEspacio);
                        $('#ver_fechaentradaEd').text(data.data.fechaentrada);
                        $('#ver_horaentradaEd').text(data.data.horaentrada);
                        $('#ver_tiempoEd').text(data.data.tiempo);
                        $('#ver_totalEd').text(data.data.total);
                    })

            }

            $('#tabla_marcaciones').on('click', '.getDataMarcacionTerminar', function() {
                abrirModalAsignarMarcacionTerminar($(this).data('id'));
            });

            $('#tabla_marcaciones').on('click', '.editMarcacion', function() {
                abrirModalAsignarMarcacion($(this).data('id'));
            });

            $('#btnGuardarCliente').on('click', function(e) {
                e.preventDefault();
                const form = document.getElementById('cliente_form');
                if (form.checkValidity()) {
                    $.ajax({
                        data: $('#cliente_form').serialize(),
                        url: "{{ route('gestion.cliente.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $('#tabla_marcaciones').DataTable().ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: data.success,
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });

                            // Reseteo del formulario
                            $('#cliente_form')[0].reset();
                            $('#edicionMarcacionModal').modal('hide');

                            // Mostrar modal con mensaje + opción de abrir el ticket
                            Swal.fire({
                                title: '¡Marcación Actualizada!',
                                text: '¿Deseas terminar la marcación?',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Sí, Terminar Marcación',
                                cancelButtonText: 'No, gracias'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    let urlTemplate =
                                        '{{ route('ticketentrada', ':id') }}';
                                    let ticketUrl = urlTemplate.replace(':id', data.data
                                        .id);
                                    window.open(ticketUrl, '_blank');
                                }
                            });

                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al registrar el vehículo',
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    });
                } else {
                    form.reportValidity();
                }
            });

            $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      'Accept': 'application/json'
    }
  });

  $('#btnGuardarMarcacionTerminar').on('click', function(e) {
    e.preventDefault();

    const form = document.getElementById('terminar_marcacion_form');
    if (!form.checkValidity()) {
      return form.reportValidity();
    }

    $.ajax({
      url: "{{ route('gestion.marcacion.storecerrar') }}",
      method: "POST",
      data: $('#terminar_marcacion_form').serialize(),
      dataType: 'json',
      success: function(data) {
        // 1) Refrescar tabla y notificar
        $('#tabla_marcaciones').DataTable().ajax.reload();
        Swal.fire({
          icon: 'success',
          title: data.success,
          toast: true,
          position: 'top-end',
          timer: 3000,
          showConfirmButton: false
        });

        // 2) Reset y cerrar modal
        $('#terminar_marcacion_form')[0].reset();
        $('#iniciarMarcacionModal').modal('hide');

        // 3) Preguntar acción: ticket o boleta
        Swal.fire({
          title: '¡Marcación guardada!',
          text: '¿Deseas imprimir ticket de cierre o boleta?',
          icon: 'question',
          showCancelButton: true,
          showDenyButton: true,
          confirmButtonText: 'Ticket cierre',
          denyButtonText: 'Boleta',
          cancelButtonText: 'Cancelar',
          buttonsStyling: false,
          customClass: {
            confirmButton: 'btn btn-success',
            denyButton: 'btn btn-primary mx-2',
            cancelButton: 'btn btn-danger'
          }
        }).then((result) => {
          const id = data.data.id;

          if (result.isConfirmed) {
            // Abrir ticket de cierre
            const urlTicket = '{{ route("ticketcierre", ":id") }}'.replace(':id', id);
            window.open(urlTicket, '_blank');

          } else if (result.isDenied) {
            // Llamar al endpoint de emitir boleta
            $.ajax({
              url: `{{ url('gestion/marcacion') }}/${id}/emitir-boleta`,
              method: 'POST',
              dataType: 'json',
              success: function(res) {
                Swal.fire({
                  icon: 'success',
                  title: res.success,
                  toast: true,
                  position: 'top-end',
                  timer: 3000,
                  showConfirmButton: false
                });
                // Si tu API devuelve URL de PDF:
                // if (res.url) window.open(res.url, '_blank');
              },
              error: function(err) {
                Swal.fire({
                  icon: 'error',
                  title: 'Error al emitir boleta',
                  text: err.responseJSON?.detalle || err.responseText,
                  toast: true,
                  position: 'top-end',
                  timer: 4000,
                  showConfirmButton: false
                });
              }
            });
          }
        });
      },
      error: function(xhr) {
        Swal.fire({
          icon: 'error',
          title: 'Error al registrar',
          toast: true,
          position: 'top-end',
          timer: 3000,
          showConfirmButton: false
        });
      }
    });
  });

            // Espacio oculto hasta seleccionar placa
            function toggleEspacio() {
                if ($('#vehiculo_input').val()) {
                    $('#espacio_group').show();
                }
            }
            // Convertir placa a mayúsculas y manejar avanzar/borrar
            $(document).on('keydown', '.placa-box', function(e) {
                if (e.key === 'Backspace' && $(this).val() === '') {
                    var prev = $(this).prevAll('.placa-box:first');
                    if (prev.length) {
                        prev.focus().val('');
                        e.preventDefault();
                    }
                }
            });

            $(document).on('keyup', '.placa-box', function(e) {
                // Convertir a mayúsculas
                var val = $(this).val().toUpperCase();
                $(this).val(val);
                // Avanzar foco
                if (val.length === this.maxLength) $(this).next('.placa-box').focus();

                // Al completar placa, buscar vehículo
                var placaIngresada = '';
                var todosLlenos = true;
                $('.placa-box').each(function() {
                    placaIngresada += $(this).val().trim();
                    if ($(this).val().trim() === '') todosLlenos = false;
                });
                if (todosLlenos) {
                    // AJAX buscar vehículo...
                    // Al éxito, asignar tipo en hidden y toggleEspacio()
                }
            });

            // Manejo de los botones de tarifa
            $(document).on('click', '.tarifa-btn', function() {
                // Remueve la clase de 'active' de todos los botones y marca el actual
                $('.tarifa-btn').removeClass('active');
                $(this).addClass('active');

                // Actualiza el campo oculto con el valor seleccionado
                var tarifaSeleccionada = $(this).data('tarifa');
                $('#tarifa_seleccionada').val(tarifaSeleccionada);
            });

            // Variable global para almacenar los espacios disponibles
            var espaciosDisponibles = [];

            // Al cambiar la cochera: reinicia los campos de espacio y consulta disponibilidad
            $('#COC_Id').on('change', function() {
                let cocheraId = $(this).val();
                // Reinicia el input y el input oculto de espacio
                $('#espacio_input').val('');
                $('#espacio_id').val('');

                if (cocheraId) {
                    $.ajax({
                        url: '{{ route('gestion.vehiculo.filterEspacio') }}',
                        type: 'GET',
                        data: {
                            cocId: cocheraId
                        },
                        success: function(data) {
                            espaciosDisponibles = data; // Se almacena la respuesta globalmente

                            if (data.length === 0) {
                                // Ocultar el grupo de input si no hay espacios disponibles
                                $('#espacio_input').closest('.form-group').hide();
                            } else {
                                // Mostrar el grupo de input si hay espacios disponibles
                                $('#espacio_input').closest('.form-group').show();
                            }
                        },
                        error: function(xhr) {
                            console.log('Error al cargar espacios: ' + xhr.responseText);
                            // Por defecto se muestra el input en caso de error
                            $('#espacio_input').closest('.form-group').show();
                        }
                    });
                } else {
                    // Si no hay cochera seleccionada, se muestra el input
                    $('#espacio_input').closest('.form-group').show();
                }
            });

            // ===> Aquí forzamos la llamada una vez al cargar, con el valor preseleccionado
            if ($('#COC_Id').val()) {
                $('#COC_Id').trigger('change');
            }

            $('#idTIV_Id').on('change', function() {
                const textoSeleccionado = $('#idTIV_Id option:selected').text().trim();
                $('#idTipoVehiculo').val(textoSeleccionado);
            });

            // Al abrir el modal de espacios, utiliza la variable global para renderizar
            $('button[data-target="#nuevoEspacioModal"]').on('click', function() {
                // 1) Leemos el nombre del tipo (p.ej. "Moto", "Auto", etc.)
                const tipoVehiculo = $('#idTipoVehiculo').val();

                const $container = $('#espacios_container .row').empty();
                if (!espaciosDisponibles.length) {
                    return $container.html(
                        '<div class="col-12 text-info">No hay espacios de parqueo disponibles.</div>');
                }

                // 2) Construye los filtros y dropdown
                let $filterRow = $('#espacios_container .filter-row');
                if (!$filterRow.length) {
                    $filterRow = $(
                        '<div class="filter-row d-flex justify-content-between align-items-center mb-3"></div>'
                        );
                    $('#espacios_container').prepend($filterRow);
                } else {
                    $filterRow.empty();
                }

                const $tagContainer = $('<div class="tipo-tags"></div>');
                const $orderContainer = $(`  
                <select id="orderSelect" class="form-control" style="width:200px;">
                    <option value="default">Ordenar por estado</option>
                    <option value="asc">Estado Ascendente</option>
                    <option value="desc">Estado Descendente</option>
                </select>
                `);
                $filterRow.append($tagContainer, $orderContainer);

                // Tag 'Todos'
                $tagContainer.append(
                    `<span class="badge badge-primary mr-1 tag-filter" data-tipo="todos" style="cursor:pointer;">Todos</span>`
                    );

                // Tags dinámicas a partir de los nombres reales (no los IDs)
                const tiposUnicos = [...new Set(espaciosDisponibles.map(e => e.tipo_vehiculo).filter(t =>
                    t))];
                tiposUnicos.forEach(tipo => {
                    $tagContainer.append(
                        `<span class="badge badge-secondary mr-1 tag-filter" data-tipo="${tipo}" style="cursor:pointer;">${tipo}</span>`
                        );
                });

                // 3) Aplicar filtro visual por tipo detectado (al abrir el modal)
                if (tipoVehiculo) {
                    $tagContainer.find('.tag-filter').removeClass('badge-primary').addClass(
                        'badge-secondary');
                    const $match = $tagContainer.find(`.tag-filter[data-tipo="${tipoVehiculo}"]`);
                    if ($match.length) {
                        $match.removeClass('badge-secondary').addClass('badge-primary');
                    } else {
                        $tagContainer.find(`.tag-filter[data-tipo="todos"]`).addClass('badge-primary');
                    }
                }

                // 4) Eventos de filtro y orden
                $tagContainer.find('.tag-filter').off('click').on('click', function() {
                    $tagContainer.find('.tag-filter').removeClass('badge-primary').addClass(
                        'badge-secondary');
                    $(this).removeClass('badge-secondary').addClass('badge-primary');
                    renderCards(espaciosDisponibles);
                });

                $('#orderSelect').off('change').on('change', () => renderCards(espaciosDisponibles));

                // 5) Render inicial
                renderCards(espaciosDisponibles);
            });

            function renderCards(espaciosData) {
                let $container = $('#espacios_container .row');
                $container.find('.espacio-item').remove(); // Limpia las cards sin borrar la fila filtrada

                let filtroTipo = $('.tag-filter.badge-primary').data('tipo') || "todos";
                let order = $('#orderSelect').val();

                let espacios = espaciosData.slice();

                if (filtroTipo && filtroTipo !== "todos") {
                    espacios = espacios.filter(function(espacio) {
                        return espacio.tipo_vehiculo === filtroTipo;
                    });
                }

                if (order === 'asc') {
                    espacios.sort((a, b) => a.estado.toLowerCase().localeCompare(b.estado.toLowerCase()));
                } else if (order === 'desc') {
                    espacios.sort((a, b) => b.estado.toLowerCase().localeCompare(a.estado.toLowerCase()));
                }

                $.each(espacios, function(index, espacio) {
                    let estado = espacio.estado.toLowerCase();
                    let bgColorClass = '';
                    if (estado === 'disponible') {
                        bgColorClass = 'bg-success';
                    } else if (estado === 'ocupado') {
                        bgColorClass = 'bg-danger';
                    } else if (estado === 'mantenimiento') {
                        bgColorClass = 'bg-orange';
                    } else {
                        bgColorClass = 'bg-secondary';
                    }

                    let clickable = (estado === 'disponible');
                    let cursorStyle = clickable ? 'pointer' : 'not-allowed';
                    let pointerEvents = clickable ? 'pointer-events: auto;' : 'pointer-events: none;';
                    let opacityStyle = clickable ? 'opacity: 1;' : 'opacity: 0.6;';
                    let disabledAttr = clickable ? '' : 'data-disabled="true"';

                    // Construir la ruta de la imagen
                    let imagePath = espacio.imagen ?
                        `/storage/archivos/tipoVehiculo/${espacio.imagen}` :
                        `/storage/archivos/tipoVehiculo/default_vehiculo.png`;

                    let cardHtml = `
                    <div class="col-6 col-md-4 mb-3 espacio-item" data-tipo="${espacio.tipo_vehiculo}">
                        <div class="small-box ${bgColorClass} espacio-card" 
                            style="${pointerEvents} ${opacityStyle}; cursor: ${cursorStyle};" 
                            data-id="${espacio.id}" 
                            data-info="${espacio.codigo} - ${espacio.tipo_vehiculo} - ${espacio.estado}"
                            ${disabledAttr}>
                            
                            <div class="inner d-flex justify-content-between align-items-center">
                                <div class="text-left">
                                    <h6 style="font-weight: 600;">Código: ${espacio.codigo}</h6>
                                    <p class="mb-1">Tipo: ${espacio.tipo_vehiculo}</p>
                                    <p class="mb-0">Estado: ${espacio.estado}</p>
                                </div>
                                <div class="ml-3">
                                    <img src="${imagePath}" alt="icono" style="width: 70px; height: 70px; object-fit: contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    $container.append(cardHtml);
                });
            }

            function showLoadingSkeleton($container) {
                $container.empty();
                for (let i = 0; i < 6; i++) {
                    let skeletonHtml = `
                <div class="col-6 col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="placeholder-glow">
                                <span class="placeholder col-12" style="height: 1.2rem;"></span>
                                <span class="placeholder col-8 mt-2" style="height: 1rem;"></span>
                                <span class="placeholder col-6 mt-2" style="height: 1rem;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                    $container.append(skeletonHtml);
                }
            }

            $(document).on('click', '.espacio-card', function() {
                if ($(this).data('disabled')) return false; // Prevenir selección si está deshabilitado

                var espacioId = $(this).data('id');
                var espacioInfo = $(this).data('info');

                // Mostrar los datos en los inputs correspondientes del formulario principal
                $('#espacio_input').val(espacioInfo);
                $('#espacio_id').val(espacioId);

                // Cerrar el modal
                $('#nuevoEspacioModal').modal('hide');
            });

            // Variables globales para determinar si el vehículo ya existe y almacenar la placa (cadena sin formato especial)
            var vehiculoExiste = false;
            var placaIngresada = '';

            // Avanzar el foco en los inputs de placa y detectar cuando estén completos
            $('.placa-box').on('keyup', function() {
                placaIngresada = '';
                var todosLlenos = true;
                $('.placa-box').each(function() {
                    var valor = $(this).val().trim();
                    placaIngresada += valor;
                    if (valor.length === 0) {
                        todosLlenos = false;
                    }
                });

                // Si se han ingresado los 6 caracteres:
                if (todosLlenos && placaIngresada.length === 6) {
                    // Realizar búsqueda AJAX usando la placa directamente, sin guión (según el formato guardado en BD)
                    $.ajax({
                        url: '{{ route('gestion.vehiculo.buscar') }}',
                        method: 'GET',
                        data: {
                            placa: placaIngresada
                        },
                        success: function(response) {
                            if (response.exists) {
                                console.log("response", response);
                                // El vehículo existe: completar los campos con la información recibida
                                $('input[name="color"]').val(response.data.color);
                                $('input[name="marca"]').val(response.data.marca);
                                $('input[name="modelo"]').val(response.data.modelo);
                                $('#idTIV_Id').val(response.data.tipovehiculo).trigger(
                                'change');
                                $('#VEH_Id').val(response.data.id);
                                $('#TIV_Id').val(response.data.id);

                                // Mostrar alerta debajo de los inputs de placa
                                if ($('#alertaPlaca').length === 0) {
                                    $('#placaInputs').after(
                                        '<div id="alertaPlaca" class="text-danger">La placa ya está registrada.</div>'
                                        );
                                }

                                // Cambiar el texto del botón a "Añadir"
                                $('#btnGuardarVehiculo').text('Añadir');
                                vehiculoExiste = true;
                            } else {
                                // Si no existe, limpiar los campos de color, marca y modelo
                                $('input[name="color"]').val('');
                                $('input[name="marca"]').val('');
                                $('input[name="modelo"]').val('');
                                $('#idTIV_Id').val('').trigger('change');

                                // Remover la alerta, si existe
                                $('#alertaPlaca').remove();
                                $('#btnGuardarVehiculo').text('Registrar Vehículo');
                                vehiculoExiste = false;
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });

            // Inicialización del DataTable vía AJAX (el controlador debe enviar la columna 'action')
            var table = $('#tabla_marcaciones').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('gestion.marcacion.index') }}",
                columns: [{
                        data: null,
                        name: '',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // 1) Enlace al ticket (abre en pestaña nueva)
                            let ticketUrl = '/ticketentrada/' + row.id;
                            let html =
                                '<a href="' + ticketUrl + '" target="_blank" ' +
                                'class="btn btn-sm btn-info mr-1" title="Ver ticket">' +
                                '<i class="fas fa-ticket-alt"></i>' +
                                '</a>';

                            @can('gestion.marcacion.edit')
                                html += row.action1 + ' ';
                            @endcan

                            @can('gestion.marcacion.destroy')
                                html += row.action2;
                            @endcan

                            return html;
                        }
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'vehiculo_placa',
                        name: 'vehiculo_placa'
                    },
                    {
                        data: 'espacio_nombre',
                        name: 'espacio_nombre'
                    },
                    {
                        data: 'fecha_entrada',
                        name: 'fecha_entrada'
                    },
                    {
                        data: 'fecha_salida',
                        name: 'fecha_salida'
                    },
                    {
                        data: 'monto_total',
                        name: 'monto_total'
                    },
                    {
                        data: 'estado',
                        name: 'estado'
                    }
                ],
                responsive: true,
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas"
                }
            });

            $('#tabla_marcaciones').on('click', '.deleteMarcacion', function() {
                let id = $(this).data('id');
                cancelarMarcacion(id); // <-- asegúrate que esta función esté definida
            });

            // Envío del formulario de Marcación vía AJAX
            $('#marcacion_form').on('submit', function(e) {
                e.preventDefault();

                // 1) Validación cliente: vehículo siempre obligatorio
                if (!$('#vehiculo_input').val().trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Debe completar el vehículo',
                        toast: true,
                        position: 'top-end',
                        timer: 4000,
                        showConfirmButton: false
                    });
                    return;
                }

                // 2) Si el espacio está visible, también es obligatorio
                if ($('#espacio_group').is(':visible') && !$('#espacio_input').val().trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Debe seleccionar un espacio de parqueo',
                        toast: true,
                        position: 'top-end',
                        timer: 4000,
                        showConfirmButton: false
                    });
                    return;
                }

                // 3) Si pasa las validaciones cliente, enviamos por AJAX
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        // Reseteo del formulario
                        $('#marcacion_form')[0].reset();
                        $('#tarifa_seleccionada').val('hora');
                        $('.tarifa-btn').removeClass('active');
                        $('.tarifa-btn[data-tarifa="hora"]').addClass('active');
                        table.ajax.reload();

                        // Mostrar modal con mensaje + opción de abrir el ticket
                        Swal.fire({
                            title: '¡Marcación guardada!',
                            text: '¿Deseas abrir el ticket de entrada ahora?',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, abrir ticket',
                            cancelButtonText: 'No, gracias'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let urlTemplate =
                                '{{ route('ticketentrada', ':id') }}';
                                let ticketUrl = urlTemplate.replace(':id', data.data
                                .id);
                                window.open(ticketUrl, '_blank');
                            }
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);

                        // Tomamos el mensaje exacto que venga del servidor
                        let mensaje = 'No se pudo registrar';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            mensaje = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Concatenar todos los mensajes de validación
                            let allErrors = [];
                            $.each(xhr.responseJSON.errors, function(_, msgs) {
                                allErrors = allErrors.concat(msgs);
                            });
                            mensaje = allErrors.join(' ');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: mensaje,
                            toast: true,
                            position: 'top-end',
                            timer: 5000,
                            showConfirmButton: false
                        });
                    }
                });
            });

            // Envío del formulario de Nuevo Vehículo vía AJAX
            $('#vehiculo_form').on('submit', function(e) {
                e.preventDefault();

                // Si el vehículo ya existe, simplemente transfiere la placa al input principal y cierra el modal
                if (vehiculoExiste) {
                    $('#vehiculo_input').val(placaIngresada);
                    Swal.fire({
                        icon: 'success',
                        title: 'Vehículo existente añadido',
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    $('#nuevoVehiculoModal').modal('hide');
                    $('#vehiculo_form')[0].reset();
                } else {
                    let formData = new FormData(this);
                    $.ajax({
                        url: $(this).attr('action'),
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            Swal.fire({
                                icon: 'success',
                                title: data.success,
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            $('#vehiculo_input').val(placaIngresada);
                            $('#VEH_Id').val(data.id);
                            $('#vehiculo_form')[0].reset();
                            $('#nuevoVehiculoModal').modal('hide');
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al registrar el vehículo',
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });

            // Avanza el foco al siguiente input de placa cuando el actual alcanza su máximo de caracteres
            $('.placa-box').on('keyup', function() {
                if ($(this).val().length === this.maxLength) {
                    $(this).next('.placa-box').focus();
                }
            });
        });

        function cancelarMarcacion(id) {
            Swal.fire({
                title: '¿Seguro que deseas cancelar esta marcación?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, mantener'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        // usa la ruta resource destroy
                        url: '{{ route('gestion.marcacion.destroy', ':id') }}'.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            $('#tabla_marcaciones').DataTable().ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Marcación cancelada',
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'No se pudo cancelar',
                                toast: true,
                                position: 'top-end',
                                timer: 4000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

    </script>
@endsection
