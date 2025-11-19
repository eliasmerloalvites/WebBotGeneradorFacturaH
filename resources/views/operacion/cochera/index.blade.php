@extends('layout.appAdminLte')

@section('titulo', 'Cocheras')

@section('contenido')
<head>
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
</head>
@can('operacion.cochera.create')
    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">CREAR COCHERA</h5>
                <p class="card-text"></p>
                <form method="POST" id="cochera_form" action="{{ route('operacion.cochera.store') }}">
                    @csrf
                    <input type="hidden" id="_method" name="_method" value="" style="display: none;">
                    <input type="text" id="cochera_id_edit" hidden>
                    <div class="form-group row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" style=" text-align: left; display: block;">Empresa:</label>
                            <select class="form-control select2 select2-primary" id="EMP_Id" name="EMP_Id"
                                data-dropdown-css-class="select2-primary"  style="width: 100%; ">
                                <option value="">Seleccionar ...</option>
                                @foreach ($empresas as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Nombre:</label>
                            <input type="text" id="idnombre" name="nombre" class="form-control "
                                placeholder="Ingrese Nombre" required>
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label"
                                style="text-align: left; display: block;">Dirección:</label>
                            <input type="text" id="iddireccion" name="direccion" class="form-control "
                                placeholder="Ingrese Dirección">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Latitud:</label>
                            <input type="number" id="idlatitud" name="latitud" class="form-control "
                                placeholder="Ingrese Latitud" step="any">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Longitud:</label>
                            <input type="number" id="idlongitud" name="longitud" class="form-control "
                                placeholder="Ingrese Longitud" step="any">
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

@can('operacion.cochera.index')
    <div class="col-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">LISTA DE COCHERAS</h5>
                <p class="card-text">
                <div class="table-responsive" style="background:#FFF;">
                    <table class="table" id="tabla_cochera">
                        <thead style="background-color:#123D75;color: #fff;">
                            <tr>
                                <th scope="col">N°</th>
                                <th scope="col">Empresa</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Direccion</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endcan



    <!-- Modal Ver detalles-->
    <div class="modal fade" id="modalVerDetalle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalle Cochera</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <p class="col text-start">Id Cochera: </p>
                        <p id="ver_id" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Empresa:</p>
                        <p id="ver_empresa" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Nombre: </p>
                        <p id="ver_nombre" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Dirección: </p>
                        <p id="ver_direccion" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Latitud: </p>
                        <p id="ver_latitud" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Longtiud: </p>
                        <p id="ver_longitud" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Fecha de registro: </p>
                        <p id="ver_fecha_registro" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Fecha de actualización: </p>
                        <p id="ver_fecha_update" class="col text-start"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>

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

            var table = $('#tabla_cochera').DataTable({
                responsive: true, // Habilitar la opción responsive
                autoWidth: false,
                searchDelay: 2000,
                processing: true,
                serverSide: true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_",
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
                ajax: "{{ route('operacion.cochera.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'empresa',
                        name: 'empresa'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'direccion',
                        name: 'direccion'
                    },
                    {
                        data: null,
                        name: '',
                        'render': function(data, type, row) {
                            return  @can('operacion.cochera.show')
                                    data.action3 + ' ' +
                                @endcan
                            ''
                            @can('operacion.cochera.edit')
                                +data.action1 + ' ' +
                            @endcan
                            ''
                            @can('operacion.cochera.destroy')
                                +data.action2
                            @endcan ;
                        }
                    }
                ]
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                const form = document.getElementById('cochera_form');
                if (form.checkValidity()) {
                    let formData = new FormData($('#cochera_form')[0]);
                    $.ajax({
                        data: $('#cochera_form').serialize(),
                        url: "{{ route('operacion.cochera.store') }}",
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
                                    title: 'cochera fallo al Registrarse.'
                                })
                            }
                        }
                    });
                } else {
                    form.reportValidity();
                }

            });

            $('body').on('click', '.editCochera', function() {
                var Cochera_id_edit = $(this).data('identificador');
                $.get('{{ route('operacion.cochera.edit', ['cochera' => ':cochera']) }}'.replace(':cochera',
                        Cochera_id_edit),
                    function(result) {
                        console.log(result);
                        $('#cochera_id_edit').val(result.data.id);
                        $('#idnombre').val(result.data.nombre);
                        $('#iddireccion').val(result.data.direccion);
                        $('#idlatitud').val(result.data.latitud);
                        $('#idlongitud').val(result.data.longitud);
                        $('#EMP_Id').val(result.data.EMP_Id);
                        $('#EMP_Id').change();


                        // Mostrar botón Actualizar y ocultar botón Guardar
                        $('#_method').val('PUT').show();
                        $("#saveBtn").hide();
                        $("#updateBtn").show();
                    })
            });

            $('#updateBtn').click(function(e) {
                e.preventDefault();
                const form = document.getElementById('cochera_form');
                if (form.checkValidity()) {
                    Cochera_id_update = $('#cochera_id_edit').val();
                    $.ajax({
                        data: $('#cochera_form').serialize(),
                        url: '{{ route('operacion.cochera.update', ['cochera' => ':cochera']) }}'.replace(':cochera', Cochera_id_update),
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
                            if(data.responseText){
                                Toast.fire({
                                    type: 'error',
                                    title: data.responseText
                                })
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Cochera fallo al actualizarse.'
                                })
                            }
                        }
                    });
                } else {
                    form.reportValidity();
                }
            });

            $('body').on('click', '.eyeCochera', function() {
                var Cochera_id_ver = $(this).data('id');
                $('#modalVerDetalle').modal('show');
                $.get('{{ route('operacion.cochera.show', ['cochera' => ':cochera']) }}'.replace(':cochera',
                        Cochera_id_ver),
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

            $('body').on('click', '.deleteCochera', function() {

                var Cochera_id_delete = $(this).data("id");
                $confirm = confirm("¿Estás seguro de que quieres eliminarlo?");
                if ($confirm == true) {
                    $.ajax({
                        type: "DELETE",

                        url: '{{ route('operacion.cochera.destroy', ['cochera' => ':cochera']) }}'
                            .replace(
                                ':cochera', Cochera_id_delete),
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
                                title: 'Cochera fallo al Eliminarlo.',
                                icon: 'info'
                            })
                        }
                    });
                } else {
                    Toast.fire({
                        title: 'Acción cancelada',
                        text: 'La cochera no ha sido eliminada.',
                        icon: 'info'
                    });
                }
            });
        });

        function cancelarUpdate() {
            $('#cochera_form').trigger("reset");
            $('#EMP_Id').val('');
            $('#EMP_Id').change();    
            $("#cochera_id_edit").val('');
            $('#_method').val('').hide();
            $("#saveBtn").show(); // Mostrar botón Guardar
            $("#updateBtn").hide();
        }

        function ocultar() {
            document.getElementById('Buscar_Cochera').style.display = 'none';
            document.getElementById('cargando').style.display = 'block';
            setInterval('mostrar()', 1000);
        }

        function mostrar() {
            $valorcito = $('#idnombre').val();
            $valor = $valorcito.length;
            if ($valor > 0) {
                document.getElementById('Buscar_Cochera').style.display = 'block';
                document.getElementById('cargando').style.display = 'none';
            }
        }
    </script>
@endsection
