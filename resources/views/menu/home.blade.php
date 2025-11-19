@extends('layout.appAdminLte')
@section('titulo', 'Inicio')
@section('contenido')
    <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="/adminlte/plugins/ekko-lightbox/ekko-lightbox.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">

    <link href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>


    <style>
        .espacio-box {
            border-radius: 16px;
            width: 180px;
            height: 180px;
            position: relative;
            background-color: #ccc;
            color: white;
            font-weight: bold;
            font-size: 32px;
            margin: 10px;
            overflow: hidden;
        }

        .estado-disponible {
            background-color: #A8E6CF;
        }

        .estado-pendiente {
            background-color: #FFD3B6;
        }

        .estado-ocupado {
            background-color: #FF8B94;
        }

        .estado-mantenimiento {
            background-color: #d4cfdd;
        }

        .vehiculo-img {
            position: absolute;
            width: 90%;
            height: auto;
            opacity: 0.2;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .espacio-numero {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            text-align: center;
            font-size: 40px;
        }

        .espacio-placa {
            position: absolute;
            bottom: 8px;
            left: 10px;
            font-size: 12px;
            text-align: left;
            z-index: 2;
        }

        .espacio-derecha {
            position: absolute;
            bottom: 8px;
            right: 10px;
            font-size: 12px;
            text-align: right;
            z-index: 2;
        }
    </style>


    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="montodiario" >S/ {{$montototaldiario}}</h3>

                <p>Monto diario</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">ver mas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="numingresos">{{$numeroIngresos}}<sup style="font-size: 20px"></sup></h3>

                <p>N° Vehiculos Ingresados al dia</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">ver mas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="numestacionados">{{$numeroEstacionados}}</h3>

                <p>N° de vehiculos estacionados</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="numespacioslibres" >65</h3>

                <p>N° Espacios Libre</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-md-12">
        <div class="card shadow-sm mb-3">
            {{-- <div class="card-header">
                <h4 class="card-title">FilterizR Gallery with Ekko Lightbox</h4>
            </div> --}}
            <div class="card-body">
                <div>
                    <div class="row">
                        @if ($admin)
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label class="control-label" style=" text-align: left; display: block;">Empresa:</label>
                                <select class="form-control select2 select2-primary" id="EMP_Id" name="EMP_Id"
                                    data-dropdown-css-class="select2-primary" style="width: 100%; " required>
                                    @foreach ($empresas as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="control-label" style=" text-align: left; display: block;">Cochera:</label>
                            <select class="form-control select2 select2-primary" id="COC_Id" name="COC_Id"
                                data-dropdown-css-class="select2-primary" style="width: 100%; " required>
                            </select>
                        </div>
                    </div>
                    <div class="btn-group w-100 mb-2">
                        <a class="btn btn-info active" href="javascript:void(0)" data-filter="all"> Todos </a>
                        @foreach ($tiposVehiculos as $tv)
                            <a class="btn btn-info" href="javascript:void(0)" data-filter="{{ $tv->id }}">
                                {{ $tv->nombre }} </a>
                        @endforeach
                    </div>
                    <div class="mb-2">
                        {{-- <a class="btn btn-secondary" href="javascript:void(0)" data-shuffle=""> Shuffle items </a> --}}
                        <div class="float-left">
                            <input type="text" class="form-control" placeholder="Search..." data-search=""
                                style="width: 400px;">
                        </div>
                        <div class="float-right">
                            <select class="custom-select" style="width: auto;" data-sortorder="">
                                <option value="index"> Ordenar por Posicion </option>
                                <option value="sortData"> Ordenar por estado </option>
                            </select>
                            <div class="btn-group">
                                <a class="btn btn-default" href="javascript:void(0)" data-sortasc=""> Ascending </a>
                                <a class="btn btn-default" href="javascript:void(0)" data-sortdesc=""> Descending </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-container p-0 row"
                    style="padding: 3px; position: relative; width: 100%; display: flex; flex-wrap: wrap; height: 73px;">
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Iniciar Marcacion --}}
    <div class="modal fade" id="iniciarMarcacionModal" tabindex="-1" role="dialog"
        aria-labelledby="iniciarMarcacionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="iniciar_marcacion_form" action="{{ route('gestion.marcacion.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <!-- Título modificado -->
                        <h5 class="modal-title" id="iniciarMarcacionModalLabel">Detalle de la Marcacion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row">
                        <input type="hidden" id="vehiculo_id_iniciar" name="VEH_Id">
                        <input type="hidden" id="espacio_id_iniciar" name="ESP_Id">
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

                        <!-- NUEVO BLOQUE DE BOTONES PARA TARIFAS -->
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Seleccionar Tarifa</label>
                            <div class="btn-group btn-group-toggle d-flex" role="group" id="tarifaButtons"
                                aria-label="Tarifas">
                                <button type="button" class="btn btn-outline-primary tarifa-btn active"
                                    data-tarifa="hora">x Hora</button>
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

                    </div>

                    <!-- Footer con botones -->
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-times-circle"></i> Cerrar Detalle
                        </button>
                        <button type="button" class="btn btn-success" id="btnGuardarMarcacionIniciar" >
                            <i class="fas fa-save"></i> Iniciar Marcacion
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal para Terminar Marcacion --}}
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
                        <button type="button" class="btn btn-info" id="btnGuardarMarcacionTerminar" > Cerrar Marcacion</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal para Editar Marcacion --}}
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


@endsection

@section('script')

    <!-- Bootstrap -->
    <script src="/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Ekko Lightbox -->
    <script src="/adminlte/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
    <!-- Filterizr-->
    <script src="/adminlte/plugins/filterizr/jquery.filterizr.min.js"></script>
    <script>
        $(document).ready(function() {


            /* $('.filter-container').filterizr({
                gutterPixels: 3
            });
            $('.btn[data-filter]').on('click', function() {
                $('.btn[data-filter]').removeClass('active');
                $(this).addClass('active');
            }); */
            $('.select2').select2();

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
            dataCocheras();
            $('#EMP_Id').on('change', function() {
                dataCocheras();
            });
            $('#COC_Id').on('change', function() {
                dataAllEspacio();
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
            var vehiculoExiste = false;
            var placaIngresada = '';
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
                                $('#idTIV_Id').val(response.data.tipovehiculo).trigger('change');
                                $('#vehiculo_id_iniciar').val(response.data.id);
                                $('#TIV_Id').val(response.data.id);

                                // Mostrar alerta debajo de los inputs de placa
                                if ($('#alertaPlaca').length === 0) {
                                    $('#placaInputs').after(
                                        '<div id="alertaPlaca" class="text-success">La placa ya está registrada.</div>'
                                        );
                                }
                                vehiculoExiste = true;
                            } else {
                                // Si no existe, limpiar los campos de color, marca y modelo
                                $('input[name="color"]').val('');
                                $('input[name="marca"]').val('');
                                $('input[name="modelo"]').val('');
                                $('#idTIV_Id').val('').trigger('change');

                                // Remover la alerta, si existe
                                $('#alertaPlaca').remove();
                                vehiculoExiste = false;
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });

            $('body').on('click', '.getDataMarcacionTerminar', function() {
                var Marcacion_id_ver = $(this).data('id');
                $.get('{{ route('gestion.marcacion.show', ['marcacion' => ':marcacion']) }}'.replace(
                        ':marcacion',
                        Marcacion_id_ver),
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
                        $('#marcacion_id_cerrar').val(Marcacion_id_ver);
                        $('#monto_total_cerrar').val(data.data.total);
                        
                    })
            });
            
            $('body').on('click', '.getDataMarcacionIniciar', function() {
                var Marcacion_id_ver = $(this).data('id');
                $('#espacio_id_iniciar').val(Marcacion_id_ver);
            });

            $('body').on('click', '.getDataMarcacion', function() {
                var Marcacion_id_ver = $(this).data('id');
                $.get('{{ route('gestion.marcacion.show1', ['marcacion' => ':marcacion']) }}'.replace(
                        ':marcacion',
                        Marcacion_id_ver),
                    function(data) {
                        console.log(data)
                        $('#ver_idTipoVehiculoEd').text(data.data.TIV_Id);
                        $('#vehiculo_id_edit').val(data.data.VEH_Id);
                        $('#espacio_parqueo_id_edit').val(data.data.ESP_Id);
                        $('#marcacion_id_edit').val(Marcacion_id_ver);
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
            });
            
            $('#btnGuardarCliente').on('click',function(e) {
                e.preventDefault();
                const form = document.getElementById('cliente_form');
                if (form.checkValidity()) {
                    $.ajax({
                        data: $('#cliente_form').serialize(),
                        url: "{{ route('gestion.cliente.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
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
                            dataAllEspacio();

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
                                    let urlTemplate = '{{ route("ticketentrada", ":id") }}';
                                    let ticketUrl = urlTemplate.replace(':id', data.data.id);
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

            $('#btnGuardarMarcacionIniciar').on('click',function(e) {
                e.preventDefault();         
                const form = document.getElementById('iniciar_marcacion_form');
                if (form.checkValidity()) { 
                    $.ajax({
                        data: $('#iniciar_marcacion_form').serialize(),
                        url: "{{ route('gestion.marcacion.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            console.log(data)
                            Swal.fire({
                                icon: 'success',
                                title: data.success,
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            
                            // Reseteo del formulario
                            $('#iniciar_marcacion_form')[0].reset();
                            $('#iniciarMarcacionModal').modal('hide');
                            $('#tarifa_seleccionada').val('hora');
                            $('.tarifa-btn').removeClass('active');
                            $('.tarifa-btn[data-tarifa="hora"]').addClass('active');
                            dataAllEspacio();

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
                                    let urlTemplate = '{{ route("ticketentrada", ":id") }}';
                                    let ticketUrl = urlTemplate.replace(':id', data.data.id);
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
            
            $('#btnGuardarMarcacionTerminar').on('click',function(e) {
                e.preventDefault();           
                const form = document.getElementById('terminar_marcacion_form');
                if (form.checkValidity()) { 
                    $.ajax({
                        data: $('#terminar_marcacion_form').serialize(),
                        url: "{{ route('gestion.marcacion.storecerrar') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            console.log(data)
                            Swal.fire({
                                icon: 'success',
                                title: data.success,
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            
                            // Reseteo del formulario
                            $('#terminar_marcacion_form')[0].reset();
                            $('#iniciarMarcacionModal').modal('hide');
                            dataAllEspacio();

                            // Mostrar modal con mensaje + opción de abrir el ticket
                            Swal.fire({
                                title: '¡Marcación guardada!',
                                text: '¿Deseas imprimir ticket de cierre?',
                                icon: 'success',
                                showCancelButton: true,
                                showDenyButton: true,
                                confirmButtonText: 'Sí, imprimir',
                                denyButtonText: 'Imprimir Boleta',
                                cancelButtonText: 'Cancelar',
                                buttonsStyling: false, // 👈 Desactiva el estilo por defecto
                                customClass: {
                                    confirmButton: 'btn btn-success',   // verde
                                    denyButton: 'btn btn-primary mx-2',      // azul
                                    cancelButton: 'btn btn-danger'      // rojo
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    //imprimir ticket
                                    let urlTemplate = '{{ route("ticketcierre", ":id") }}';
                                    let ticketUrl = urlTemplate.replace(':id', data.data.id);
                                    window.open(ticketUrl, '_blank');
                                }else if (result.isDenied) {
                                    //imprimir boleta
                                    let urlTemplate = '{{ route("ticketboleta", ":id") }}';
                                    let ticketUrl = urlTemplate.replace(':id', data.data.id);
                                    window.open(ticketUrl, '_blank');
                                }else{

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

        });


        function dataAllEspacio() {
            var cocheraId = $('#COC_Id').val();
            let filterizrInstance;
            $.ajax({
                url: `/gestion/marcacion/list/espacios/${cocheraId}`,
                method: 'GET',
                success: function(response) {
                    document.getElementById('montodiario').innerHTML = response.montodiario;
                    document.getElementById('numingresos').innerHTML = response.numeroIngresos;
                    document.getElementById('numestacionados').innerHTML = response.numeroEstacionados;
                    document.getElementById('numespacioslibres').innerHTML = response.numeroEspaciosLibres;


                    // 1. Destruye la instancia anterior si existe
                    if (filterizrInstance) {
                        filterizrInstance.destroy();
                    }

                    // 2. Vacía el contenedor y genera el HTML dinámico
                    const container = $('.filter-container');
                    container.empty();

                    response.data.forEach(function(espacio) {
                        let estado = espacio.state.toLowerCase();
                        let estadoClass = '';
                        let nameModal = '';
                        let getDataClass = '';


                        switch (estado) {
                            case 'disponible':
                                estadoClass = 'estado-disponible';
                                nameModal = 'iniciarMarcacionModal';
                                getDataClass = 'getDataMarcacionIniciar';
                                break;
                            case 'ocupado':
                                estadoClass = 'estado-ocupado';
                                nameModal = 'terminarMarcacionModal';
                                getDataClass = 'getDataMarcacionTerminar';
                                break;
                            case 'mantenimiento':
                                estadoClass = 'estado-mantenimiento';
                                nameModal = '';
                                getDataClass = '';
                                break;
                            case 'pendiente':
                                estadoClass = 'estado-pendiente';
                                nameModal = 'edicionMarcacionModal';
                                getDataClass = 'getDataMarcacion';
                                break;
                            default:
                                estadoClass = 'estado-pendiente';
                                nameModal = '';
                                getDataClass = '';
                        }

                        const imagenSrc = espacio.Imagen ?
                            `/storage/archivos/tipoVehiculo/${espacio.Imagen}` :
                            `/storage/archivos/tipoVehiculo/default_vehiculo.png`;

                        const placa = espacio.placa ? `<strong>PL: ${espacio.placa}</strong><br>` : '';
                        const horaEntrada = espacio.hora_entrada ?
                            `<span>Ent: ${espacio.hora_entrada}</span>` : '';
                        const tiempoTotal = espacio.tiempo_total ?
                            `<div><strong>${espacio.tiempo_total}</strong></div>` : '';

                        const html = `
                <div class="filtr-item col-sm-2 ${getDataClass}" data-id="${espacio.identificador}" data-toggle="modal" data-target="#${nameModal} "  data-category="${espacio.TIV_Id}" data-sort="${estadoClass}">
                    <div class="espacio-box ${estadoClass}">
                        <img src="${imagenSrc}" alt="Vehículo" class="vehiculo-img">
                        <div class="espacio-numero">${espacio.codigo}</div>
                        <div class="espacio-placa">${placa}${horaEntrada}</div>
                        <div class="espacio-derecha">${tiempoTotal}</div>
                    </div>
                </div>
            `;

                        container.append(html);
                    });

                    // 3. Re-inicializa filterizr ahora que los elementos existen
                    filterizrInstance = new Filterizr('.filter-container', {
                        gutterPixels: 3
                    });

                    // 4. Reactiva los botones de filtrado
                    $('.btn[data-filter]').off('click').on('click', function() {
                        $('.btn[data-filter]').removeClass('active');
                        $(this).addClass('active');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener los espacios:', error);
                }
            });

        }

        function dataCocheras() {
            var empresaId = $('#EMP_Id').val();
            $.ajax({
                url: `/operacion/usuario_cocheras/list/cocheraxempresa/${empresaId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let select = $('#COC_Id');
                    select.empty();
                    $.each(data, function(index, cochera) {
                        select.append(`<option value="${cochera.id}">${cochera.nombre}</option>`);
                    });

                    select.trigger('change');
                },
                error: function() {
                    console.error('Error al obtener cocheras por empresa');
                }
            });
        }
    </script>
@endsection
