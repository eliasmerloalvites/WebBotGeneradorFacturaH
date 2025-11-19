@extends('layout.appAdminLte')

@section('titulo', 'Espacios Parqueo')

@section('contenido')
<head>
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
</head>
@can('operacion.espacios_parqueo.create')
    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">CREAR ESPACIOS PARQUEO</h5>
                <p class="card-text"></p>
                <form method="POST" id="espacios_parqueo_form" action="{{ route('operacion.espacios_parqueo.store') }}">
                    @csrf
                    <input type="hidden" id="_method" name="_method" value="" style="display: none;">
                    <input type="text" id="espacios_parqueo_id_edit" hidden>
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
                            <label class="control-label" style="text-align: left; display: block;">Codigo:</label>
                            <input type="text" id="idcodigo" name="codigo" class="form-control "
                                placeholder="Ingrese Codigo" required>
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" style=" text-align: left; display: block;">Tipo Vehiculo:</label>
                            <select class="form-control select2 select2-primary" id="TIV_Id" name="TIV_Id"
                                data-dropdown-css-class="select2-primary"  style="width: 100%; ">
                                <option value="">Seleccionar ...</option>
                                @foreach ($tipos_vehiculos as $tiv)
                                    <option value="{{ $tiv->id }}">{{ $tiv->nombre }}
                                    </option>
                                @endforeach
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

@can('operacion.espacios_parqueo.index')
    <div class="col-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">LISTA DE ESPACIOS PARQUEOS</h5>
                <p class="card-text">
                <div class="table-responsive" style="background:#FFF;">
                    <table class="table" id="tabla_espacios_parqueo">
                        <thead style="background-color:#123D75;color: #fff;">
                            <tr>
                                <th scope="col">N°</th>
                                <th scope="col">Codigo</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Cochera</th>
                                <th scope="col">T. Vehiculo</th>
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

            var table = $('#tabla_espacios_parqueo').DataTable({
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
                ajax: "{{ route('operacion.espacios_parqueo.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'codigo',
                        name: 'codigo'
                    },
                    {
                        data: 'state',
                        name: 'state'
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
                        data: null,
                        name: '',
                        'render': function(data, type, row) {
                            return  @can('operacion.espacios_parqueo.show')
                                    data.action3 + ' ' +
                                @endcan
                            ''
                            @can('operacion.espacios_parqueo.edit')
                                +data.action1 + ' ' +
                            @endcan
                            ''
                            @can('operacion.espacios_parqueo.destroy')
                                +data.action2
                            @endcan ;
                        }
                    }
                ]
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                const form = document.getElementById('espacios_parqueo_form');
                if (form.checkValidity()) {
                    let formData = new FormData($('#espacios_parqueo_form')[0]);
                    $.ajax({
                        data: $('#espacios_parqueo_form').serialize(),
                        url: "{{ route('operacion.espacios_parqueo.store') }}",
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
                                    title: 'espacios_parqueo fallo al Registrarse.'
                                })
                            }
                        }
                    });
                } else {
                    form.reportValidity();
                }

            });

            $('body').on('click', '.editEspacioParqueo', function() {
                var EspacioParqueo_id_edit = $(this).data('identificador');
                $.get('{{ route('operacion.espacios_parqueo.edit', ['espacios_parqueo' => ':espacios_parqueo']) }}'.replace(':espacios_parqueo',
                        EspacioParqueo_id_edit),
                    function(result) {
                        console.log(result);
                        $('#espacios_parqueo_id_edit').val(result.data.id);
                        $('#idcodigo').val(result.data.codigo);
                        $('#COC_Id').val(result.data.COC_Id);
                        $('#COC_Id').change();
                        $('#TIV_Id').val(result.data.TIV_Id);
                        $('#TIV_Id').change();


                        // Mostrar botón Actualizar y ocultar botón Guardar
                        $('#_method').val('PUT').show();
                        $("#saveBtn").hide();
                        $("#updateBtn").show();
                    })
            });

            $('#updateBtn').click(function(e) {
                e.preventDefault();
                const form = document.getElementById('espacios_parqueo_form');
                if (form.checkValidity()) {
                    EspacioParqueo_id_update = $('#espacios_parqueo_id_edit').val();
                    $.ajax({
                        data: $('#espacios_parqueo_form').serialize(),
                        url: '{{ route('operacion.espacios_parqueo.update', ['espacios_parqueo' => ':espacios_parqueo']) }}'.replace(':espacios_parqueo', EspacioParqueo_id_update),
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
                                    title: 'espacios_parqueo fallo al Registrarse.'
                                })
                            }
                        }
                    });
                } else {
                    form.reportValidity();
                }
            });

            $('body').on('click', '.eyeEspacioParqueo', function() {
                var EspacioParqueo_id_ver = $(this).data('id');
                $('#modalVerDetalle').modal('show');
                $.get('{{ route('operacion.espacios_parqueo.show', ['espacios_parqueo' => ':espacios_parqueo']) }}'.replace(':espacios_parqueo',
                        EspacioParqueo_id_ver),
                    function(data) {
                        console.log(data)
                        $('#ver_id').text(data.data.id);
                        $('#ver_codigo').text(data.data.empresa);
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

            $('body').on('click', '.deleteEspacioParqueo', function() {

                var EspacioParqueo_id_delete = $(this).data("id");
                $confirm = confirm("¿Estás seguro de que quieres eliminarlo?");
                if ($confirm == true) {
                    $.ajax({
                        type: "DELETE",

                        url: '{{ route('operacion.espacios_parqueo.destroy', ['espacios_parqueo' => ':espacios_parqueo']) }}'
                            .replace(
                                ':espacios_parqueo', EspacioParqueo_id_delete),
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
                                title: 'EspacioParqueo fallo al Eliminarlo.',
                                icon: 'info'
                            })
                        }
                    });
                } else {
                    Toast.fire({
                        title: 'Acción cancelada',
                        text: 'La espacios_parqueo no ha sido eliminada.',
                        icon: 'info'
                    });
                }
            });
        });

        function cancelarUpdate() {
            $('#espacios_parqueo_form').trigger("reset");
            $('#COC_Id').val('');
            $('#COC_Id').change();   
            $('#TIV_Id').val('');
            $('#TIV_Id').change();     
            $("#espacios_parqueo_id_edit").val('');
            $('#_method').val('').hide();
            $("#saveBtn").show(); // Mostrar botón Guardar
            $("#updateBtn").hide();
        }

        function ocultar() {
            document.getElementById('Buscar_EspacioParqueo').style.display = 'none';
            document.getElementById('cargando').style.display = 'block';
            setInterval('mostrar()', 1000);
        }

        function mostrar() {
            $valorcito = $('#idnombre').val();
            $valor = $valorcito.length;
            if ($valor > 0) {
                document.getElementById('Buscar_EspacioParqueo').style.display = 'block';
                document.getElementById('cargando').style.display = 'none';
            }
        }
    </script>
@endsection
