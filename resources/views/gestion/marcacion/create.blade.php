@extends('layout.appAdminLte')

@section('titulo', 'Marcación')

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
    <div class="col-md-12">
        <div class="card shadow-sm mb-3">
            <div class="card-header">
                <h4 class="card-title">FilterizR Gallery with Ekko Lightbox</h4>
            </div>
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
                {{-- <div class="filter-container p-0 row"
                    style="padding: 3px; position: relative; width: 100%; display: flex; flex-wrap: wrap; height: 73px;">
                    @foreach ($espacios_parqueo as $espacio)
                        @php
                            // Convertimos el estado en clase CSS
                            $estadoClass = match (strtolower($espacio->state)) {
                                'disponible' => 'estado-disponible',
                                'ocupado' => 'estado-ocupado',
                                'mantenimiento' => 'estado-mantenimiento',
                                'pendiente' => 'estado-pendiente',
                                default => 'estado-pendiente',
                            };
                        @endphp

                        <div class="filtr-item col-sm-2" data-category="{{ $espacio->TIV_Id }}"
                            data-sort="{{ $estadoClass }}">
                            <div class="espacio-box {{ $estadoClass }}">
                                <img src="{{ $espacio->Imagen ? asset('storage/archivos/tipoVehiculo/' . $espacio->Imagen) : asset('storage/archivos/tipoVehiculo/default_vehiculo.png') }}"
                                    alt="Vehículo" class="vehiculo-img">

                                <div class="espacio-numero">
                                    {{ $espacio->codigo }}
                                </div>

                                <div class="espacio-placa">
                                    <strong> {{ !empty($espacio->placa) ? 'PL: ' . $espacio->placa : '' }}</strong><br>
                                    <span>{{ !empty($espacio->hora_entrada) ? 'Ent: ' . $espacio->hora_entrada : '' }}</span>
                                </div>

                                <div class="espacio-derecha">
                                    <div><strong> {{ !empty($espacio->tiempo_total) ? $espacio->tiempo_total : '' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div> --}}
            </div>
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
        });


        function dataAllEspacio() {
            var cocheraId = $('#COC_Id').val();
            let filterizrInstance;
            $.ajax({
                url: `/gestion/marcacion/list/espacios/${cocheraId}`,
                method: 'GET',
                success: function(response) {
                    // 1. Destruye la instancia anterior si existe
                    if (filterizrInstance) {
                        filterizrInstance.destroy();
                    }

                    // 2. Vacía el contenedor y genera el HTML dinámico
                    const container = $('.filter-container');
                    container.empty();

                    response.forEach(function(espacio) {
                        let estado = espacio.state.toLowerCase();
                        let estadoClass = '';

                        switch (estado) {
                            case 'disponible':
                                estadoClass = 'estado-disponible';
                                break;
                            case 'ocupado':
                                estadoClass = 'estado-ocupado';
                                break;
                            case 'mantenimiento':
                                estadoClass = 'estado-mantenimiento';
                                break;
                            case 'pendiente':
                                estadoClass = 'estado-pendiente';
                                break;
                            default:
                                estadoClass = 'estado-pendiente';
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
                <div class="filtr-item col-sm-2" data-category="${espacio.TIV_Id}" data-sort="${estadoClass}">
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
