@extends('layout.appAdminLte')

@section('titulo', 'Tarifa')

@section('contenido')
<head>
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
</head>
@can('gestion.tarifa.create')
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">CREAR TARIFA</h5>
                <p class="card-text"></p>
                <form method="POST" id="tarifa_form" action="{{ route('gestion.tarifa.store') }}">
                    @csrf
                    <input type="hidden" id="_method" name="_method" value="" style="display: none;">
                    <input type="text" id="tarifa_id_edit" hidden>
                    <div class="form-group row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" style=" text-align: left; display: block;">Cochera:</label>
                            <select class="form-control select2 select2-primary" id="COC_Id" name="COC_Id"
                                data-dropdown-css-class="select2-primary"  style="width: 100%; " required>
                                <option value="">Seleccionar ...</option>
                                @foreach ($cocheras as $coc)
                                    <option value="{{ $coc->id }}">{{ $coc->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" style=" text-align: left; display: block;">Tipo Vehiculo:</label>
                            <select class="form-control select2 select2-primary" id="TIV_Id" name="TIV_Id"
                                data-dropdown-css-class="select2-primary"  style="width: 100%; " required>
                                <option value="">Seleccionar ...</option>
                                @foreach ($tipos_vehiculos as $tiv)
                                    <option value="{{ $tiv->id }}">{{ $tiv->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Precio Hora:</label>
                            <input type="text" id="idprecio_hora" name="precio_hora" class="form-control "
                                placeholder="Ingrese Dia" required>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Precio Día:</label>
                            <input type="text" id="idprecio_dia" name="precio_dia" class="form-control "
                                placeholder="Ingrese Mes" required>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Precio Mes:</label>
                            <input type="text" id="idprecio_mes" name="precio_mes" class="form-control "
                                placeholder="Ingrese Año" required>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label" style=" text-align: left; display: block;">Cochera:</label>
                            <select class="form-control " id="moneda" name="moneda"  style="width: 100%; " required>
                                <option value="PEN">PEN</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>

                    </div>
                    <p></p>
                    <button id="saveBtn" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Guardar</button>
                    <button id="updateBtn" class="btn btn-info" style="display: none;"><i
                            class="fas fa-save mr-2"></i>Actualizar</button>
                    <button type="reset" id="btncancelar" class="btn btn-danger"> <i
                            class="fas fa-ban mr-2"></i>Cancelar </button>
                </form>
            </div>
        </div>
    </div>
@endcan

@can('gestion.tarifa.index')
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">LISTA DE TARIFAS</h5>
                <p class="card-text">
                <div class="table-responsive" style="background:#FFF;">
                    <table class="table" id="tabla_tarifa">
                        <thead style="background-color:#123D75;color: #fff;">
                            <tr>
                                <th scope="col">N°</th>
                                <th scope="col">Cochera</th>
                                <th scope="col">T. Vehiculo</th>
                                <th scope="col">P. Hora</th>
                                <th scope="col">P. Día</th>
                                <th scope="col">P. Mes</th>
                                <th scope="col">Moneda</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endcan


@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.select2').select2();

            $('.select2bs4').select2({
            theme: 'bootstrap4'
            })

            $("#fileLogo").change(function() {
                $nombre = document.getElementById('fileLogo').files[0].name;
                document.querySelector('#idFileLogo').innerText = $nombre;
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                icon: 'info'
            });

            var table = $('#tabla_tarifa').DataTable({
                responsive: true, // Habilitar la opción responsive
                autoWidth: false,
                searchDelay: 2000,
                processing: true,
                serverSide: true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ ",
                    "zeroRecords": "Nada encontrado - disculpa",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },

                order: [
                    [0, "asc"]
                ],
                ajax: "{{ route('gestion.tarifa.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'Cochera',
                        name: 'Cochera'
                    },
                    {
                        data: 'TipoVehiculo',
                        name: 'TipoVehiculo'
                    },
                    {
                        data: 'precio_hora',
                        name: 'precio_hora',
                    },
                    {
                        data: 'precio_dia',
                        name: 'precio_dia',
                    },
                    {
                        data: 'precio_mes',
                        name: 'precio_mes',
                    },
                    {
                        data: 'moneda',
                        name: 'moneda',
                    },
                    {
                        data: null,
                        name: '',
                        'render': function(data, type, row) {
                            return  @can('gestion.tarifa.show')
                                    data.action3 + ' ' +
                                @endcan
                            ''
                            @can('gestion.tarifa.edit')
                                +data.action1 + ' ' +
                            @endcan
                            ''
                            @can('gestion.tarifa.destroy')
                                +data.action2
                            @endcan ;
                        }
                    }
                ]
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                const form = document.getElementById('tarifa_form');
                if (form.checkValidity()) {
                    let formData = new FormData($('#tarifa_form')[0]);
                    $.ajax({
                        data: $('#tarifa_form').serialize(),
                        url: "{{ route('gestion.tarifa.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            console.log('Success:', data);
                            Toast.fire({
                                type: 'success',
                                title: data.success
                            })
                            cancelarUpdate();
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            if(data.responseText){
                                Toast.fire({
                                    type: 'error',
                                    title: data.responseText
                                })
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'tarifa fallo al Registrarse.'
                                })
                            }
                        }
                    });
                } else {
                    form.reportValidity();
                }

            });

            $('body').on('click', '.editTarifa', function() {
                var Tarifa_id_edit = $(this).data('identificador');
                $.get('{{ route('gestion.tarifa.edit', ['tarifa' => ':tarifa']) }}'.replace(':tarifa',
                        Tarifa_id_edit),
                    function(result) {
                        console.log(result);
                        $('#tarifa_id_edit').val(result.data.id);
                        $('#COC_Id').val(result.data.COC_Id);
                        $('#COC_Id').change();
                        $('#TIV_Id').val(result.data.TIV_Id);
                        $('#TIV_Id').change();
                        $('#idprecio_hora').val(result.data.precio_hora);
                        $('#idprecio_dia').val(result.data.precio_dia);
                        $('#idprecio_mes').val(result.data.precio_mes);
                        $('#idmoneda').val(result.data.moneda);


                        // Mostrar botón Actualizar y ocultar botón Guardar
                        $('#_method').val('PUT').show();
                        $("#saveBtn").hide();
                        $("#updateBtn").show();
                    })
            });

            $('#updateBtn').click(function(e) {
                e.preventDefault();
                const form = document.getElementById('tarifa_form');
                if (form.checkValidity()) {
                    Tarifa_id_update = $('#tarifa_id_edit').val();
                    $.ajax({
                        data: $('#tarifa_form').serialize(),
                        url: '{{ route('gestion.tarifa.update', ['tarifa' => ':tarifa']) }}'.replace(':tarifa', Tarifa_id_update),
                        type: "PUT",
                        dataType: 'json',
                        success: function(data) {
                            console.log('Success:', data);
                            Toast.fire({
                                type: 'success',
                                title: data.success
                            });
                            cancelarUpdate();
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            if(data.responseText){
                                Toast.fire({
                                    type: 'error',
                                    title: data.responseText
                                })
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'tarifa fallo al Registrarse.'
                                })
                            }
                        }
                    });
                } else {
                    form.reportValidity();
                }
            });

            $('body').on('click', '.eyeTarifa', function() {
                var Tarifa_id_ver = $(this).data('id');
                $('#modalVerDetalle').modal('show');
                $.get('{{ route('gestion.tarifa.show', ['tarifa' => ':tarifa']) }}'.replace(':tarifa',
                        Tarifa_id_ver),
                    function(data) {
                        console.log(data)
                        $('#ver_id').text(data.data.id);
                        $('#ver_empresa').text(data.data.empresa);
                        $('#ver_nombre').text(data.data.nombre);
                        $('#ver_direccion').text(data.data.direccion);
                        $('#ver_latitud').text(data.data.latitud);
                        $('#ver_longitud').text(data.data.longitud);
                        $('#ver_fecha_registro').text(moment(data.data.created_at).format(
                            'YYYY-MM-DD HH:mm:ss'));
                        $('#ver_fecha_update').text(moment(data.data.updated_at).format(
                            'YYYY-MM-DD HH:mm:ss'));
                            
                    })
            });

            $('#btncancelar').click(function(e) {
                cancelarUpdate();
                Swal.fire({
                    icon: 'info',
                    title: 'Registro cancelado',
                    text: 'El formulario se ha reiniciado correctamente.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            });

            $('body').on('click', '.deleteTarifa', function() {

                var Tarifa_id_delete = $(this).data("id");
                $confirm = confirm("¿Estás seguro de que quieres eliminarlo?");
                if ($confirm == true) {
                    $.ajax({
                        type: "DELETE",

                        url: '{{ route('gestion.tarifa.destroy', ['tarifa' => ':tarifa']) }}'
                            .replace(
                                ':tarifa', Tarifa_id_delete),
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            table.draw();
                            console.log('success:', data);
                            Toast.fire({
                                type: 'success',
                                title: String(data.success),
                                icon: 'info'
                            });

                        },
                        error: function(data) {
                            console.log('Error:', data);
                            Toast.fire({
                                type: 'error',
                                title: 'Tarifa fallo al Eliminarlo.',
                                icon: 'info'
                            })
                        }
                    });
                } else {
                    Toast.fire({
                        title: 'Acción cancelada',
                        text: 'La tarifa no ha sido eliminada.',
                        icon: 'info'
                    });
                }
            });
        });

        function cancelarUpdate() {
            $('#tarifa_form').trigger("reset");
            $('#COC_Id').val('');
            $('#COC_Id').change();   
            $('#TIV_Id').val('');
            $('#TIV_Id').change();   
            $("#tarifa_id_edit").val('');
            $('#_method').val('').hide();
            $("#saveBtn").show(); // Mostrar botón Guardar
            $("#updateBtn").hide();
        }

        function ocultar() {
            document.getElementById('Buscar_Tarifa').style.display = 'none';
            document.getElementById('cargando').style.display = 'block';
            setInterval('mostrar()', 1000);
        }

        function mostrar() {
            $valorcito = $('#idnombre').val();
            $valor = $valorcito.length;
            if ($valor > 0) {
                document.getElementById('Buscar_Tarifa').style.display = 'block';
                document.getElementById('cargando').style.display = 'none';
            }
        }
    </script>
@endsection
