@extends('layout.appAdminLte')

@section('titulo', 'Tipo Vehículo')

@section('contenido')

    @can('operacion.tipo_vehiculo.create')
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">TIPO VEHICULO</h5>
                    <p class="card-text"></p>
                    <form method="POST" id="tipoVehiculo_form" action="{{ route('operacion.tipo_vehiculo.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="_method" name="_method" value="" style="display: none;">
                        <input type="hidden" id="empresa_id_edit">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Nombre:</label>
                            <input type="text" id="idnombre" name="nombre" class="form-control"
                                placeholder="Ingrese Nombre" required>
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Tamaño:</label>
                            <input type="text" id="idtamano" name="tamaño" class="form-control"
                                placeholder="Ingrese Tamaño" required>
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Descripción:</label>
                            <textarea id="iddescripcion" name="descripcion" class="form-control" placeholder="Ingrese Descripción" rows="3"></textarea>
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: left;">
                            <label>Añadir Imagen</label>
                            <div class="custom-file center">
                                <input type="file" class="custom-file-input" accept="image/*" name="file" id="fileLogo">
                                <label class="custom-file-label" id="idFileLogo">Añadir Imagen</label>
                            </div>
                        </div>
                        <p></p>
                        <button id="saveBtn" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Guardar</button>
                        <button id="updateBtn" class="btn btn-info" style="display: none;"><i
                                class="fas fa-save mr-2"></i>Actualizar</button>
                        <button type="reset" id="btncancelar" class="btn btn-danger"> <i class="fas fa-ban mr-2"></i>Cancelar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('operacion.tipo_vehiculo.index')
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">LISTA DE TIPO VEHICULO</h5>
                    <p class="card-text"></p>
                    <div class="table-responsive" style="background:#FFF;">
                        <table class="table" id="tabla_tipoM">
                            <thead style="background-color:#123D75;color: #fff;">
                                <tr>
                                    <th scope="col">N°</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Tamaño</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Opciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <!-- Modal Ver detalles inline -->
    <div class="modal fade" id="modalVerDetalle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalle Tipo Vehículo</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <p class="col text-start">Id: </p>
                        <p id="ver_id" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Nombre: </p>
                        <p id="ver_nombre" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Tamaño: </p>
                        <p id="ver_tamano" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Descripción: </p>
                        <p id="ver_descripcion" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <div class="col-4" style="text-align: left;">
                            <p>Imagen: </p>
                        </div>
                        <div class="col-8" style="text-align: left;">
                            <img id="ver_imagen" style="width: 120px; height: 120px; margin-right: 10px;"
                                alt="Imagen Tipo Vehículo" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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

            $("#fileLogo").change(function() {
                $('#idFileLogo').text(this.files[0].name);
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                icon: 'info'
            });

            var table = $('#tabla_tipoM').DataTable({
                responsive: true,
                autoWidth: false,
                searchDelay: 2000,
                processing: true,
                serverSide: true,
                language: {
                    lengthMenu: "Mostrar _MENU_",
                    zeroRecords: "Nada encontrado - disculpa",
                    info: "Mostrando la página _PAGE_ de _PAGES_",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    search: "Buscar:",
                    paginate: {
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                order: [
                    [0, "asc"]
                ],
                ajax: "{{ route('operacion.tipo_vehiculo.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'tamaño',
                        name: 'tamaño'
                    },
                    {
                        data: 'descripcion',
                        name: 'descripcion'
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `
                            @can('operacion.tipo_vehiculo.show') ${data.action3} @endcan
                            @can('operacion.tipo_vehiculo.edit') ${data.action1} @endcan
                            @can('operacion.tipo_vehiculo.destroy') ${data.action2} @endcan
                        `;
                        }
                    }
                ]
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                const form = document.getElementById('tipoVehiculo_form');
                if (form.checkValidity()) {
                    let formData = new FormData($('#tipoVehiculo_form')[0]);
                    $.ajax({
                        url: "{{ route('operacion.tipo_vehiculo.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false, // Evita que jQuery procese el FormData
                        contentType: false, // Evita que jQuery establezca el tipo de contenido
                        success: function(data) {
                            Toast.fire({
                                icon: 'success',
                                title: data.success
                            });
                            cancelarUpdate();
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            if (data.responseText) {
                                Toast.fire({
                                    type: 'error',
                                    title: data.responseText
                                })
                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Tipo Vehículo fallo al Registrarse.'
                                })
                            }
                        }
                    });
                } else {
                    form.reportValidity();
                }
            });

            $('body').on('click', '.editEmpresa', function() {
                var id = $(this).data('identificador');
                $.get('{{ route('operacion.tipo_vehiculo.show', ['tipo_vehiculo' => ':id']) }}'.replace(':id',
                id),
                    function(result) {
                        console.log(result);
                        $('#empresa_id_edit').val(result.data.id);
                        $('#idnombre').val(result.data.nombre);
                        $('#idtamano').val(result.data.tamaño);
                        $('#iddescripcion').val(result.data.descripcion);


                        // Mostrar botón Actualizar y ocultar botón Guardar
                        $('#_method').val('PUT').show();
                        $("#saveBtn").hide();
                        $("#updateBtn").show();
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

            $('#updateBtn').click(function(e) {
                e.preventDefault();
                const form = document.getElementById('tipoVehiculo_form');
                if (form.checkValidity()) {
                    Empresa_id_update = $('#empresa_id_edit').val();
                    let formData = new FormData($('#tipoVehiculo_form')[0]);
                    $.ajax({
                        url: '{{ route('operacion.tipo_vehiculo.update', ['tipo_vehiculo' => ':id']) }}'
                            .replace(
                                ':id', Empresa_id_update),
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
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
                            Toast.fire({
                                type: 'error',
                                title: 'Tipo Vehículo fallo al actualizarse.'
                            })
                        }
                    });
                } else {
                    form.reportValidity();
                }
            });

            
            $('body').on('click', '.deleteEmpresa', function() {

                var Empresa_id_delete = $(this).data("id");
                $confirm = confirm("¿Estás seguro de que quieres eliminarlo?");
                if ($confirm == true) {
                    $.ajax({
                        type: "DELETE",

                        url: '{{ route('operacion.tipo_vehiculo.destroy', ['tipo_vehiculo' => ':id']) }}'
                            .replace(
                                ':id', Empresa_id_delete),
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
                                title: 'Tipo Vehículo fallo al Eliminarlo.',
                                icon: 'info'
                            })
                        }
                    });
                } else {
                    Toast.fire({
                        title: 'Acción cancelada',
                        text: 'El Tipo Vehículo no ha sido eliminada.',
                        icon: 'info'
                    });
                }
            });

            $('body').on('click', '.eyeEmpresa', function() {
                var id = $(this).data('id');
                $('#modalVerDetalle').modal('show');
                $.get('{{ route('operacion.tipo_vehiculo.show', ['tipo_vehiculo' => ':id']) }}'.replace(':id',
                        id),
                    function(data) {
                        $('#ver_id').text(data.data.id);
                        $('#ver_nombre').text(data.data.nombre);
                        $('#ver_tamano').text(data.data.tamaño);
                        $('#ver_descripcion').text(data.data.descripcion);
                            
                        $('#ver_imagen').attr('src', data.imagen);
                    })
            });

            function cancelarUpdate() {
                $('#tipoVehiculo_form').trigger("reset");
                document.querySelector('#idFileLogo').innerText = "Añadir Logo";
                $("#empresa_id_edit").val('');
                $('#_method').val('').hide();
                $("#saveBtn").show(); // Mostrar botón Guardar
                $("#updateBtn").hide();
            }

        });
    </script>
@endsection
