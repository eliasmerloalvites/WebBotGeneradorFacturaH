@extends('layout.appAdminLte')

@section('titulo', 'Empresas')

@section('contenido')

@can('seguridad.empresa.create')
    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">CREAR EMPRESA</h5>
                <p class="card-text"></p>
                <form method="POST" id="empresa_form" action="{{ route('empresa.store') }}">
                    @csrf
                    <input type="hidden" id="_method" name="_method" value="" style="display: none;">
                    <input type="text" id="empresa_id_edit" hidden>
                    <div class="form-group row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: left;">
                            <label for="name" class=" control-label">Numero Documento</label>
                            <div class="input-group ">
                                <input type="text" class="form-control sm" id="idruc"
                                    name="ruc" placeholder="Ingrese Nº Documento" maxlength="11"
                                    required>
                                <div class="input-group-append">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="Buscar_Empresa" style="display: block;"
                                            onclick="buscarEmpresa()"><i class="fas fa-search"></i></span>
                                        <span class="input-group-text hide" id="cargando"
                                            style="display: none;"><img width="15px"
                                                src="{{ asset('images/gif/cargando1.gif') }}"></span>
                                    </div>
                                </div>
                            </div>
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
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Celular:</label>
                            <input type="number" id="idtelefono" name="telefono" class="form-control "
                                placeholder="Ingrese Celular">
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label" style="text-align: left; display: block;">Correo:</label>
                            <input type="email" id="idemail" name="email" class="form-control "
                                placeholder="Ingrese correo">
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: left;">
                            <label>Añadir Logo </label>
                            <div class="custom-file center">
                                <input type="file" class="custom-file-input"
                                    accept="image/*"
                                    name="file" id="fileLogo">
                                <label class="custom-file-label" id="idFileLogo">Añadir Logo</label>
                            </div>
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

@can('seguridad.empresa.index')
    <div class="col-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">LISTA DE EMPRESAS</h5>
                <p class="card-text">
                <div class="table-responsive" style="background:#FFF;">
                    <table class="table" id="tabla_empresa">
                        <thead style="background-color:#123D75;color: #fff;">
                            <tr>
                                <th scope="col">N°</th>
                                <th scope="col">N° Doc</th>
                                <th scope="col">Nombre</th>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalle Empresa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <p class="col text-start">Id Empresa: </p>
                        <p id="ver_id" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Numero Documento:</p>
                        <p id="ver_ruc" class="col text-start"></p>
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
                        <p class="col text-start">Celular: </p>
                        <p id="ver_telefono" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Correo: </p>
                        <p id="ver_email" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Fecha de registro: </p>
                        <p id="ver_fecha_registro" class="col text-start"></p>
                    </div>
                    <div class="row">
                        <p class="col text-start">Fecha de actualización: </p>
                        <p id="ver_fecha_update" class="col text-start"></p>
                    </div>                    
                    <div class="row">
                        <div class="col-4" style="text-align: left;" >
                            <p>Logo: </p>
                        </div>
                        <div class="col-8" style="text-align: left;" >
                            <img id="ver_Logo"  style="width: 120px; height: 120px; margin-right: 10px;" />
                        </div>
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

            var table = $('#tabla_empresa').DataTable({
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
                ajax: "{{ route('empresa.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'ruc',
                        name: 'ruc'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: null,
                        name: '',
                        'render': function(data, type, row) {
                            return  @can('seguridad.empresa.show')
                                    data.action3 + ' ' +
                                @endcan
                            ''
                            @can('seguridad.empresa.edit')
                                +data.action1 + ' ' +
                            @endcan
                            ''
                            @can('seguridad.empresa.destroy')
                                +data.action2
                            @endcan ;
                        }
                    }
                ]
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                const form = document.getElementById('empresa_form');
                if (form.checkValidity()) {
                    let formData = new FormData($('#empresa_form')[0]);
                    $.ajax({
                        url: "{{ route('empresa.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false, // Evitar que jQuery procese el FormData
                        contentType: false, // Evitar que jQuery establezca el tipo de contenido
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
                            Toast.fire({
                                type: 'error',
                                title: 'empresa fallo al Registrarse.'
                            })
                        }
                    });
                } else {
                    form.reportValidity();
                }

            });

            $('body').on('click', '.editEmpresa', function() {
                var Empresa_id_edit = $(this).data('identificador');
                $.get('{{ route('empresa.edit', ['empresa' => ':empresa']) }}'.replace(':empresa',
                        Empresa_id_edit),
                    function(result) {
                        console.log(result);
                        $('#empresa_id_edit').val(result.data.id);
                        $('#idruc').val(result.data.ruc);
                        $('#idnombre').val(result.data.nombre);
                        $('#iddireccion').val(result.data.direccion);
                        $('#idtelefono').val(result.data.telefono);
                        $('#idemail').val(result.data.email);


                        // Mostrar botón Actualizar y ocultar botón Guardar
                        $('#_method').val('PUT').show();
                        $("#saveBtn").hide();
                        $("#updateBtn").show();
                    })
            });

            $('#updateBtn').click(function(e) {
                e.preventDefault();
                const form = document.getElementById('empresa_form');
                if (form.checkValidity()) {
                    Empresa_id_update = $('#empresa_id_edit').val();
                    let formData = new FormData($('#empresa_form')[0]);
                    $.ajax({
                        url: '{{ route('empresa.update', ['empresa' => ':empresa']) }}'
                            .replace(
                                ':empresa', Empresa_id_update),
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
                                title: 'Empresa fallo al actualizarse.'
                            })
                        }
                    });
                } else {
                    form.reportValidity();
                }
            });

            $('body').on('click', '.eyeEmpresa', function() {
                var Empresa_id_ver = $(this).data('id');
                $('#modalVerDetalle').modal('show');
                $.get('{{ route('empresa.show', ['empresa' => ':empresa']) }}'.replace(':empresa',
                        Empresa_id_ver),
                    function(data) {
                        $('#ver_id').text(data.data.id);
                        $('#ver_ruc').text(data.data.ruc);
                        $('#ver_nombre').text(data.data.nombre);
                        $('#ver_direccion').text(data.data.direccion);
                        $('#ver_telefono').text(data.data.telefono);
                        $('#ver_email').text(data.data.email);
                        $('#ver_fecha_registro').text(moment(data.data.created_at).format(
                            'YYYY-MM-DD HH:mm:ss'));
                        $('#ver_fecha_update').text(moment(data.data.updated_at).format(
                            'YYYY-MM-DD HH:mm:ss'));
                            
                        $('#ver_Logo').attr('src', data.imagen);
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

            $('body').on('click', '.deleteEmpresa', function() {

                var Empresa_id_delete = $(this).data("id");
                $confirm = confirm("¿Estás seguro de que quieres eliminarlo?");
                if ($confirm == true) {
                    $.ajax({
                        type: "DELETE",

                        url: '{{ route('empresa.destroy', ['empresa' => ':empresa']) }}'
                            .replace(
                                ':empresa', Empresa_id_delete),
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
                                title: 'Empresa fallo al Eliminarlo.',
                                icon: 'info'
                            })
                        }
                    });
                } else {
                    Toast.fire({
                        title: 'Acción cancelada',
                        text: 'La empresa no ha sido eliminada.',
                        icon: 'info'
                    });
                }
            });
        });

        function cancelarUpdate() {
            $('#empresa_form').trigger("reset");
            document.querySelector('#idFileLogo').innerText = "Añadir Logo";
            $("#empresa_id_edit").val('');
            $('#_method').val('').hide();
            $("#saveBtn").show(); // Mostrar botón Guardar
            $("#updateBtn").hide();
        }

        function Limitar() {
            var cod = document.getElementById("idCLI_TipoDocumento").value;
            if (cod == 'DNI') {
                $("#idruc").val("");
                $("#idruc").attr('maxlength', '8');
            } else {
                $("#idruc").val("");
                $("#idruc").attr('maxlength', '11');
            }
        }

        function buscarEmpresa() {
            if ($('#idCLI_TipoDocumento').val() == 'DNI') {
                var numdni = $('#idruc').val();
                if (numdni != '') {
                    ocultar()
                    var url = '/consultardni/' + numdni + '?';
                    $.ajax({
                        type: 'GET',
                        url: url,
                        success: function(dat) {
                            if (dat.success[1] == false) {
                                Swal
                                    .fire({
                                        title: "DNI Inválido",
                                        icon: 'error',
                                        confirmButtonColor: "#26BA9A",
                                        width: '350px',
                                        confirmButtonText: "Ok"
                                    })
                                    .then(resultado => {
                                        if (resultado.value) {
                                            $("#idnombre").val("");
                                        } else {}
                                    });
                            } else {
                                $('#idnombre').val(dat.success[0].apellido + ' ' + dat.success[0].nombre);
                            }
                        }

                    });
                } else {
                    //mostrar()
                    alert('Escriba el DNI.!');
                    $('#idruc').focus();
                }
            } else if ($('#idCLI_TipoDocumento').val() == 'ruc') {
                var numdni = $('#idruc').val();
                if (numdni != '') {
                    ocultar()
                    var url = '/consultarruc/' + numdni + '?';
                    $.ajax({
                        type: 'GET',
                        url: url,
                        success: function(dat) {
                            console.log(dat)
                            if (dat.success[0] == "") {
                                $('#idnombre').val(dat.success[1]);
                                $('#iddireccion').val(dat.success[2] + ' ' + dat.success[3]);
                            } else {
                                $('#idnombre').val(dat.success[0].apellido + ' ' + dat.success[0].nombre);
                            }

                        }

                    });
                } else {
                    alert('Escriba el ruc.!');
                    $('#idruc').focus();
                }
            }
        }

        function ocultar() {
            document.getElementById('Buscar_Empresa').style.display = 'none';
            document.getElementById('cargando').style.display = 'block';
            setInterval('mostrar()', 1000);
        }

        function mostrar() {
            $valorcito = $('#idnombre').val();
            $valor = $valorcito.length;
            if ($valor > 0) {
                document.getElementById('Buscar_Empresa').style.display = 'block';
                document.getElementById('cargando').style.display = 'none';
            }
        }
    </script>
@endsection
