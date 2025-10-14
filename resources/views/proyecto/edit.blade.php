@extends('template')

@section('title', 'Editar proyecto')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            color: #374151;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #7c3aed;
        }
    </style>
@endpush

@section('content')
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Editar Proyecto</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('proyectos.index') }}">Proyectos</a></div>
                <div class="breadcrumb-item active">Editar proyecto</div>
            </nav>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="formProyecto" action="{{ route('proyectos.update', $proyecto->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!--Productos del proyecto-->
                <div class="lg:col-span-2">
                    <div class="bg-purple-600 text-white px-4 py-2 text-center font-semibold rounded-t-lg">
                        Productos del Proyecto
                    </div>

                    <div class="p-6 border-2 border-purple-600 rounded-b-lg bg-white">
                        <div>

                            <!--Nombre producto-->
                            <div class="mb-3">
                                <label for="producto_id" class="block text-sm font-medium text-gray-700 mb-1">Producto:</label>
                                <select name="producto_id" id="producto_id" class="form-select">
                                    <option value="">Seleccione un producto</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}">{{ $producto->codigo . ' ' . $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!--Cantidad-->
                            <div class="mb-3">
                                <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-1">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                            <!--Botón para agregar-->
                            <div class="text-right mb-3">
                                <button type="button" id="btn_agregar" class="btn btn-primary">Agregar</button>
                            </div>

                            <!--Tabla para el detalle del proyecto-->
                            <div>
                                <div class="overflow-x-auto">
                                    <table id="tabla_detalle" class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-purple-600">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">#</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Producto</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Cantidad</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            {{-- aca van los datos que inserta el usuario através de javaScript --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!--Botón para cancelar el proyecto-->
                            <div class="text-center mt-4">
                                <button id="botonCancelar" type="button" class="btn btn-danger" onclick="window.proyectoEdit.openCancelModal()">Cancelar cambios</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Datos generales del proyecto-->
                <div>
                    <div class="bg-indigo-600 text-white px-4 py-2 text-center font-semibold rounded-t-lg">
                        Datos del Proyecto
                    </div>

                    <div class="p-6 border-2 border-indigo-600 rounded-b-lg bg-white">
                        <div>

                            <!--Nombre del proyecto-->
                            <div class="mb-3">
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del proyecto: <span class="text-red-500">*</span></label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $proyecto->nombre) }}" required>
                                @error('nombre')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Fecha de ejecución-->
                            <div class="mb-3">
                                <label for="fecha_ejecucion" class="block text-sm font-medium text-gray-700 mb-1">Fecha de ejecución: <span class="text-red-500">*</span></label>
                                <input type="date" name="fecha_ejecucion" id="fecha_ejecucion" class="form-control" value="{{ old('fecha_ejecucion', $proyecto->fecha_ejecucion) }}" required>
                                @error('fecha_ejecucion')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Descripción-->
                            <div class="mb-3">
                                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción:</label>
                                <textarea name="descripcion" id="descripcion" rows="3" class="form-control" placeholder="Descripción del proyecto">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                                @error('descripcion')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Imagen actual-->
                            @if($proyecto->imagen)
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Imagen actual:</label>
                                <div class="border border-gray-300 rounded-lg p-2 bg-gray-50">
                                    <img src="{{ asset('storage/' . $proyecto->imagen) }}" alt="Imagen del proyecto" class="max-w-full h-auto rounded">
                                </div>
                            </div>
                            @endif

                            <!--Imagen del proyecto-->
                            <div class="mb-3">
                                <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">{{ $proyecto->imagen ? 'Cambiar imagen:' : 'Imagen del proyecto:' }}</label>
                                <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
                                <small class="text-gray-500 text-xs">Formatos aceptados: JPG, PNG, GIF</small>
                                @error('imagen')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Botón-->
                            <div class="text-center">
                                <button id="botonGuardar" type="submit" class="btn btn-success">Actualizar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal para cancelar cambios -->
    <div id="cancelModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.proyectoEdit.closeCancelModal()"></div>

            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium text-gray-900">Mensaje de confirmación</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">¿Seguro que quieres cancelar los cambios?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button id="btnCancelarProyecto" type="button" class="btn btn-danger mr-2" onclick="window.proyectoEdit.confirmCancel()">
                        Confirmar
                    </button>
                    <button onclick="window.proyectoEdit.closeCancelModal()" class="btn btn-secondary">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Global proyecto edit object
        window.proyectoEdit = {
            openCancelModal() {
                document.getElementById('cancelModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            },

            closeCancelModal() {
                document.getElementById('cancelModal').style.display = 'none';
                document.body.style.overflow = '';
            },

            confirmCancel() {
                window.location.href = "{{ route('proyectos.index') }}";
            }
        };

        $(document).ready(function() {
            // Inicializar Select2 en el select de productos
            $('#producto_id').select2({
                placeholder: 'Busque un producto aquí...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No se encontraron productos";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });

            // Cargar productos existentes del proyecto
            cargarProductosExistentes();

            $('#btn_agregar').click(function() {
                agregarProducto();
            });

            disableButtons();
        });

        //Variables
        let count = 0;
        let productosExistentes = @json($proyecto->productos);

        function cargarProductosExistentes() {
            // Limpiar la tabla
            $('#tabla_detalle > tbody').empty();

            // Cargar productos del proyecto
            productosExistentes.forEach(function(producto) {
                let fila = '<tr id="fila' + count + '">' +
                    '<td class="px-6 py-4">' + (count + 1) + '</td>' +
                    '<td class="px-6 py-4"><input type="hidden" name="arrayidproducto[]" value="' + producto.id + '">' + producto.nombre + '</td>' +
                    '<td class="px-6 py-4"><input type="hidden" name="arraycantidad[]" value="' + producto.pivot.cantidad + '">' + producto.pivot.cantidad + '</td>' +
                    '<td class="px-6 py-4"><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + count + ')"><i class="fa-solid fa-trash"></button></i></td>' +
                    '</tr>';

                $('#tabla_detalle').append(fila);
                count++;
            });

            disableButtons();
        }

        function agregarProducto() {
            let idProducto = $('#producto_id').val();
            let nameProducto = ($('#producto_id option:selected').text()).split(' ')[1];
            let cantidad = $('#cantidad').val();

            //Validaciones
            //1.Para que los campos no estén vacíos
            if (nameProducto != '' && nameProducto != undefined && cantidad != '') {

                //2. Para que los valores ingresados sean correctos
                if (parseInt(cantidad) > 0 && (cantidad % 1 == 0)) {

                    //Crear la fila
                    let fila = '<tr id="fila' + count + '">' +
                        '<td class="px-6 py-4">' + (count + 1) + '</td>' +
                        '<td class="px-6 py-4"><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                        '<td class="px-6 py-4"><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                        '<td class="px-6 py-4"><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + count + ')"><i class="fa-solid fa-trash"></button></i></td>' +
                        '</tr>';

                    //Mostrar los datos insertados por el usuario en la tabla
                    $('#tabla_detalle').append(fila);

                    //Limpiamos los campos
                    limpiarCampos();
                    count++;

                    //Llamamos a la funcion disableButtons
                    disableButtons();
                } else {
                    showModal('Valores incorrectos: la cantidad debe ser un número entero positivo');
                }
            } else {
                showModal('Le faltan campos por llenar');
            }
        };

        function eliminarProducto(index) {
            //Eliminar la fila
            $('#fila' + index).remove();

            //Llamamos a la funcion disableButtons despues de haber eliminado un registro
            disableButtons();
        };

        function disableButtons() {
            if($('#tabla_detalle tbody tr[id^="fila"]').length == 0) {
                $('#botonGuardar').hide();
                $('#botonCancelar').hide();
            } else {
                $('#botonGuardar').show();
                $('#botonCancelar').show();
            };
        }

        function limpiarCampos() {
            $('#producto_id').val('').trigger('change');
            $('#cantidad').val('');
        };

        function showModal(message, icon = 'error') {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: icon,
                title: message
            });
        }
    </script>
@endpush
