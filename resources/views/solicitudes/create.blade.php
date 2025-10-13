@extends('template')

@section('title', 'Crear solicitud')

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
            background-color: #3b82f6;
        }
    </style>
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Crear Solicitud</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('solicitudes.index') }}">Solicitudes</a></div>
                <div class="breadcrumb-item active">Crear solicitud</div>
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

        <form id="formSolicitud" action="{{ route('solicitudes.store') }}" method="post">
            @csrf

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!--Solicitud producto-->
                <div class="lg:col-span-2">
                    <div class="bg-blue-600 text-white px-4 py-2 text-center font-semibold rounded-t-lg">
                        Detalles de la solicitud
                    </div>

                    <div class="p-6 border-2 border-blue-600 rounded-b-lg bg-white">
                        <div>

                            <!--Tipo de solicitud con botones-->
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de solicitud:</label>
                                <div class="flex gap-3">
                                    <button type="button" id="btn-retiro" data-tipo="retiro"
                                        class="btn-tipo-solicitud flex-1 px-4 py-3 border-2 bg-white border-gray-300 rounded-lg text-gray-700 font-medium hover:border-red-500 hover:bg-red-50 transition-all duration-200 transform">
                                        <i class="fas fa-hand-holding-box mr-2"></i>Retiro
                                    </button>
                                    <button type="button" id="btn-prestamo" data-tipo="prestamo"
                                        class="btn-tipo-solicitud flex-1 px-4 py-3 border-2 bg-white border-gray-300 rounded-lg text-gray-700 font-medium hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 transform">
                                        <i class="fas fa-handshake mr-2"></i>Préstamo
                                    </button>
                                </div>
                                <input type="hidden" name="tipo_solicitud" id="tipo_solicitud" value="{{ old('tipo_solicitud') }}">
                                @error('tipo_solicitud')
                                    <small class="text-red-600 text-sm">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Razón (dinámico según tipo)-->
                            <div class="mb-3" id="box-razon" style="display: none;">
                                <label id="label-razon" for="razon" class="block text-sm font-medium text-gray-700 mb-1"></label>
                                <textarea name="razon" id="razon" rows="2" class="form-control">{{ old('razon') }}</textarea>
                                @error('razon')
                                    <small class="text-red-600 text-sm">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Nombre producto-->
                            <div class="mb-3">
                                <label for="producto_id" class="block text-sm font-medium text-gray-700 mb-1">Producto:</label>
                                <select name="producto_id" id="producto_id" class="form-select">
                                    <option value="">Seleccione un producto</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}-{{ $producto->ultimo_precio_compra }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>{{ $producto->codigo . ' ' . $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!--Campos en una fila horizontal-->
                            <div class="row mb-3">
                                <!--Cantidad-->
                                <div class="col-md-6">
                                    <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-1">Cantidad:</label>
                                    <input type="number" name="cantidad" id="cantidad" class="form-control">
                                </div>

                                <!--Precio de compra-->
                                <div class="col-md-6">
                                    <label for="precio_compra" class="block text-sm font-medium text-gray-700 mb-1">Precio de compra:</label>
                                    <input disabled type="number" name="precio_compra" id="precio_compra" class="form-control" step="0.1">
                                </div>
                            </div>

                            <!--Botón para agregar-->
                            <div class="text-right mb-3">
                                <button type="button" id="btn_agregar" class="btn btn-primary">Agregar</button>
                            </div>

                            <!--Tabla para el detalle de la solicitud-->
                            <div>
                                <div class="overflow-x-auto">
                                    <table id="tabla_detalle" class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-blue-600">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">#</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Producto</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Cantidad</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Precio de compra</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Subtotal</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            {{-- aca van los datos que inserta el usuario através de javaScript --}}
                                            <tr>
                                                <td class="px-6 py-4"></td>
                                                <td class="px-6 py-4"></td>
                                                <td class="px-6 py-4"></td>
                                                <td class="px-6 py-4"></td>
                                                <td class="px-6 py-4"></td>
                                                <td class="px-6 py-4"></td>
                                                <td class="px-6 py-4"></td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="bg-gray-50">
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!--Botón para cancelar la solicitud-->
                            <div class="text-center">
                                <button id="botonCancelar" type="button" class="btn btn-danger" onclick="window.solicitudCreate.openCancelModal()">Cancelar solicitud</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Datos generales-->
                <div>
                    <div class="bg-green-600 text-white px-4 py-2 text-center font-semibold rounded-t-lg">
                        Datos generales
                    </div>

                    <div class="p-6 border-2 border-green-600 rounded-b-lg bg-white">
                        <div>

                            <!--Usuario-->
                            <div class="mb-3">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario:</label>
                                <select name="user_id" id="user_id" class="form-select">
                                    <option value="">Seleccione un usuario</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Fecha-->
                            <div class="mb-3">
                                <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha:</label>
                                <input readonly type="date" name="fecha" id="fecha" class="form-control border-green-600" value="{{ date('Y-m-d') }}">

                                <?php
                                    use Carbon\Carbon;
                                    $fecha_hora = Carbon::now()->toDateTimeString();
                                ?>
                                <input type="hidden" name="fecha_hora" value="{{ $fecha_hora }}">
                            </div>

                            <!--Botón-->
                            <div class="text-center">
                                <button id="botonGuardar" type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal para cancelar la solicitud -->
    <div id="cancelModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.solicitudCreate.closeCancelModal()"></div>

            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium text-gray-900">Mensaje de confirmación</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">¿Seguro que quieres cancelar la solicitud?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button id="btnCancelarSolicitud" type="button" class="btn btn-danger mr-2" onclick="window.solicitudCreate.confirmCancel()">
                        Confirmar
                    </button>
                    <button onclick="window.solicitudCreate.closeCancelModal()" class="btn btn-secondary">
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
        // Global solicitud create object
        window.solicitudCreate = {
            openCancelModal() {
                document.getElementById('cancelModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            },

            closeCancelModal() {
                document.getElementById('cancelModal').style.display = 'none';
                document.body.style.overflow = '';
            },

            confirmCancel() {
                cancelarSolicitud();
                this.closeCancelModal();
            }
        };

        // Función para seleccionar tipo de solicitud con botones
        function seleccionarTipo(tipo) {
            // Actualizar el input hidden
            $('#tipo_solicitud').val(tipo);

            // Color único para ambos botones cuando estén activos
            const colorActivo = '#2563eb'; // azul-600

            // Aplicar estilos según el tipo seleccionado usando CSS inline
            if (tipo === 'retiro') {
                // Botón retiro activo (azul)
                $('#btn-retiro').css({
                    'background-color': colorActivo,
                    'border-color': colorActivo,
                    'color': '#ffffff',
                    'box-shadow': '0 10px 15px -3px rgba(0, 0, 0, 0.1)'
                });
                // Botón préstamo inactivo (gris)
                $('#btn-prestamo').css({
                    'background-color': '#ffffff',
                    'border-color': '#d1d5db',
                    'color': '#374151',
                    'box-shadow': 'none'
                });
                $('#label-razon').text('Razón del retiro:');
            } else if (tipo === 'prestamo') {
                // Botón préstamo activo (azul)
                $('#btn-prestamo').css({
                    'background-color': colorActivo,
                    'border-color': colorActivo,
                    'color': '#ffffff',
                    'box-shadow': '0 10px 15px -3px rgba(0, 0, 0, 0.1)'
                });
                // Botón retiro inactivo (gris)
                $('#btn-retiro').css({
                    'background-color': '#ffffff',
                    'border-color': '#d1d5db',
                    'color': '#374151',
                    'box-shadow': 'none'
                });
                $('#label-razon').text('Razón del préstamo:');
            }

            // Mostrar el campo de razón
            $('#box-razon').show();
        }

        $(document).ready(function() {
            // Event listeners para los botones de tipo de solicitud
            $('.btn-tipo-solicitud').on('click', function() {
                var tipo = $(this).data('tipo');
                seleccionarTipo(tipo);
            });

            // Inicializar el estado si hay un valor old
            @if(old('tipo_solicitud'))
                seleccionarTipo('{{ old('tipo_solicitud') }}');
            @endif

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

            // Event listener para autocompletar precio de compra
            $('#producto_id').on('change', function(){
                let dataProducto = $(this).val().split('-');
                $('#precio_compra').val(dataProducto[1]);
            });

            // Restaurar productos del sessionStorage si existen
            restaurarProductos();

            $('#btn_agregar').click(function() {
                agregarProducto();
            });

            disableButtons();
        });

        //Variables
        let count = 0;
        let subtotal = [];
        let sumas = 0;

        function agregarProducto() {
            let dataProducto = $('#producto_id').val().split('-');
            let idProducto = dataProducto[0];
            let nameProducto = ($('#producto_id option:selected').text()).split(' ')[1];
            let cantidad = $('#cantidad').val();
            let precioCompra = $('#precio_compra').val();

            //Validaciones
            if (nameProducto != '' && nameProducto != undefined && cantidad != '' && precioCompra != '') {
                if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(precioCompra) > 0) {
                    //Calcular valores
                    subtotal[count] = round(cantidad * precioCompra);
                    sumas += subtotal[count];

                    //Crear la fila
                    let fila = '<tr id="fila' + count + '">' +
                        '<td>' + (count + 1) + '</td>' +
                        '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                        '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                        '<td><input type="hidden" name="arraypreciocompra[]" value="' + precioCompra + '">' + precioCompra + '</td>' +
                        '<td>' + subtotal[count] + '</td>' +
                        '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + count + ')"><i class="fa-solid fa-trash"></button></i></td>' +
                        '</tr>';

                    $('#tabla_detalle').append(fila);
                    limpiarCampos();
                    count++;
                    disableButtons();
                    guardarEnSessionStorage();
                } else {
                    showModal('Valores incorrectos en los campos: cantidad/precio de compra');
                }
            } else {
                showModal('Le faltan campos por llenar');
            }
        };

        function eliminarProducto(index) {
            sumas -= round(subtotal[index]);
            $('#fila' + index).remove();
            disableButtons();
            guardarEnSessionStorage();
        };

        function cancelarSolicitud() {
            $('#tabla_detalle > tbody').empty();
            let fila = '<tr>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '</tr>';
            $('#tabla_detalle').append(fila);

            count = 0;
            subtotal = [];
            sumas = 0;

            limpiarCampos();
            disableButtons();
            sessionStorage.removeItem('solicitudProductos');
        };

        function disableButtons() {
            if(count == 0) {
                $('#botonGuardar').hide();
                $('#botonCancelar').hide();
            } else {
                $('#botonGuardar').show();
                $('#botonCancelar').show();
            };
        }

        function limpiarCampos() {
            $('#producto_id').val('');
            $('#cantidad').val('');
            $('#precio_compra').val('');
        };

        function round(num, decimales = 2) {
            var signo = (num >= 0 ? 1 : -1);
            num = num * signo;
            if (decimales === 0)
                return signo * Math.round(num);
            num = num.toString().split('e');
            num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
            num = num.toString().split('e');
            return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
        }

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

        function guardarEnSessionStorage() {
            let productos = [];

            $('#tabla_detalle tbody tr[id^="fila"]').each(function() {
                let fila = $(this);
                let idProducto = fila.find('input[name="arrayidproducto[]"]').val();

                productos.push({
                    id: fila.attr('id'),
                    idProducto: idProducto,
                    nombreProducto: fila.find('td:eq(1)').clone().children().remove().end().text().trim(),
                    cantidad: fila.find('input[name="arraycantidad[]"]').val(),
                    precioCompra: fila.find('input[name="arraypreciocompra[]"]').val(),
                    subtotal: fila.find('td:eq(4)').text().trim()
                });
            });

            sessionStorage.setItem('solicitudProductos', JSON.stringify({
                productos: productos,
                count: count,
                subtotal: subtotal,
                sumas: sumas
            }));
        }

        function restaurarProductos() {
            let datos = sessionStorage.getItem('solicitudProductos');

            if (datos) {
                try {
                    datos = JSON.parse(datos);

                    count = datos.count || 0;
                    subtotal = datos.subtotal || [];
                    sumas = datos.sumas || 0;

                    $('#tabla_detalle > tbody').empty();

                    if (datos.productos && datos.productos.length > 0) {
                        datos.productos.forEach(function(producto) {
                            let filaIndex = producto.id.replace('fila', '');
                            let fila = '<tr id="' + producto.id + '">' +
                                '<td>' + (parseInt(filaIndex) + 1) + '</td>' +
                                '<td><input type="hidden" name="arrayidproducto[]" value="' + producto.idProducto + '">' + producto.nombreProducto + '</td>' +
                                '<td><input type="hidden" name="arraycantidad[]" value="' + producto.cantidad + '">' + producto.cantidad + '</td>' +
                                '<td><input type="hidden" name="arraypreciocompra[]" value="' + producto.precioCompra + '">' + producto.precioCompra + '</td>' +
                                '<td>' + producto.subtotal + '</td>' +
                                '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + filaIndex + ')"><i class="fa-solid fa-trash"></button></i></td>' +
                                '</tr>';

                            $('#tabla_detalle').append(fila);
                        });

                        disableButtons();
                    }
                } catch (e) {
                    console.error('Error al restaurar productos:', e);
                }
            }
        }
    </script>
@endpush
