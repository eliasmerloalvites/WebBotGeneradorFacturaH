@extends('layout.appAdminLte')

@section('titulo', 'Cocheras')

@section('contenido')

    <head>
        <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
        <link href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
        <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    </head>
    @can('operacion.usuario_cocheras.index')
        <form method="POST" id="usuario_cocheras_form">
            @csrf
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Nueva Marcación</h5>
                </div>
                <div class="card-body row">
                    <p class="card-text">
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
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">LISTA DE USUARIO POR ASIGNAR</h5>
                            <p class="card-text">
                            <div class="table-responsive" style="background:#FFF;">
                                <table class="table" id="tabla_usuario_all">
                                    <thead style="background-color:#123D75;color: #fff;">
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" id="checkAllUsuarios" />
                                            </th>
                                            <th scope="col">Nombre</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2" style="align-content: center;  ">
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="btnAsignar" value="create">Asignar</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger" id="btnDesignar">Designar</button>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">LISTA USUARIO ASIGNADOS</h5>
                            <p class="card-text">
                            <div class="table-responsive" style="background:#FFF;">
                                <table class="table" id="tabla_usuario_asignados">
                                    <thead style="background-color:#123D75;color: #fff;">
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" id="checkUsuariosAsignado" />
                                            </th>
                                            <th scope="col">Nombre</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
            dataCocheras();
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                icon: 'info'
            });

            /* let seleccionados = [];
            $('.usuario-check:checked').each(function() {
                seleccionados.push($(this).val());
            }); */
            $('#checkAllUsuarios').on('click', function() {
                let isChecked = $(this).is(':checked');
                $('.usuario-check').prop('checked', isChecked);
                $('.usuario-asignados-check').prop('checked', false);
                $('#checkUsuariosAsignado').prop('checked', false);
            });

            /* let seleccionadosAsignados = [];
            $('.usuario-asignados-check:checked').each(function() {
                seleccionadosAsignados.push($(this).val());
            }); */
            $('#checkUsuariosAsignado').on('click', function() {
                let isChecked = $(this).is(':checked');
                $('.usuario-asignados-check').prop('checked', isChecked);
                $('.usuario-check').prop('checked', false);
                $('#checkAllUsuarios').prop('checked', false);
            });
            $('#EMP_Id').on('change', function() {
                dataCocheras();
            });



            var table = $('#tabla_usuario_all').DataTable({
                responsive: true, // Habilitar la opción responsive
                autoWidth: false,
                searchDelay: 2000,
                processing: true,
                serverSide: true,
                "language": {
                    "lengthMenu": "_MENU_",
                    "zeroRecords": "Nada encontrado - disculpa",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "Sin registro",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },

                order: [
                    [0, "asc"]
                ],
                ajax: {
                    url: "{{ route('operacion.usuario_cocheras.userall') }}",
                    type: "GET",
                    data: function(d) {
                        d.COC_Id = $('#COC_Id').val();
                        d.EMP_Id = $('#EMP_Id').val();
                    }
                },
                columns: [{
                        data: 'check',
                        name: 'check',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    }
                ]
            });
            // 🛠️ Aquí modificamos el tamaño del input, después de inicializar
            $('#tabla_usuario_all_filter').css({
                'text-align': 'left',
                'float': 'left', // también ayuda a forzar alineación
            });
            $('#tabla_usuario_all_filter input[type="search"]').css({
                'width': '250px',
                'display': 'inline-block',
                'margin': '0'
            });

            table.on('draw.dt', function() {
                // Reasociar evento cada vez que se redibuja la tabla
                $('#checkAllUsuarios').prop('checked', false);
            });

            var table2 = $('#tabla_usuario_asignados').DataTable({
                responsive: true, // Habilitar la opción responsive
                autoWidth: false,
                searchDelay: 2000,
                processing: true,
                serverSide: true,
                "language": {
                    "lengthMenu": "_MENU_",
                    "zeroRecords": "Nada encontrado - disculpa",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "Sin registro",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                order: [
                    [0, "asc"]
                ],
                ajax: {
                    url: "{{ route('operacion.usuario_cocheras.userscochera') }}",
                    type: "GET",
                    data: function(d) {
                        d.COC_Id = $('#COC_Id').val();
                        d.EMP_Id = $('#EMP_Id').val();
                    }
                },
                columns: [{
                        data: 'check',
                        name: 'check',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    }
                ]
            });
            // 🛠️ Aquí modificamos el tamaño del input, después de inicializar
            $('#tabla_usuario_asignados_filter').css({
                'text-align': 'left',
                'float': 'left', // también ayuda a forzar alineación
            });
            $('#tabla_usuario_asignados_filter input[type="search"]').css({
                'width': '250px',
                'display': 'inline-block',
                'margin': '0'
            });

            table2.on('draw.dt', function() {
                // Reasociar evento cada vez que se redibuja la tabla
                $('#checkUsuariosAsignado').prop('checked',
                    false); // opcional: desmarcar header al cambiar de página
            });

            $('#COC_Id').on('change', function() {
                table.ajax.reload();
                table2.ajax.reload();
            });

            $('#btnAsignar').click(function(e) {
                e.preventDefault();
                let form = $('#usuario_cocheras_form')[0];
                let formData = new FormData(form);
                // Agregar el parámetro adicional
                formData.append('type', 'asignar');

                $.ajax({
                    url: "{{ route('operacion.usuario_cocheras.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false, // 👈 evita que jQuery establezca el contentType por defecto
                    processData: false,
                    success: function(data) {
                        console.log('Success:', data);
                        Toast.fire({
                            type: 'success',
                            title: data.success
                        })
                        table.draw();
                        table2.draw();
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
                                title: 'cochera fallo al Registrarse.'
                            })
                        }
                    }
                });

            });

            $('#btnDesignar').click(function(e) {
                e.preventDefault();
                let form = $('#usuario_cocheras_form')[0];
                let formData = new FormData(form);
                // Agregar el parámetro adicional
                formData.append('type', 'designar');

                $.ajax({
                    url: "{{ route('operacion.usuario_cocheras.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false, // 👈 evita que jQuery establezca el contentType por defecto
                    processData: false,
                    success: function(data) {
                        console.log('Success:', data);
                        Toast.fire({
                            type: 'success',
                            title: data.success
                        })
                        table.draw();
                        table2.draw();
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
                                title: 'cochera fallo al Registrarse.'
                            })
                        }
                    }
                });

            });

        });

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
