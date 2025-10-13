@extends('template')

@section('title', 'Crear adquisicion')

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
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Crear Adquisición</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('adquisiciones.index') }}">Adquisiciones</a></div>
                <div class="breadcrumb-item active">Crear adquisicion</div>
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

        <form id="formAdquisición" action="{{ route('adquisiciones.store') }}" method="post">
            @csrf

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!--Adquisición producto-->
                <div class="lg:col-span-2">
                    <div class="bg-blue-600 text-white px-4 py-2 text-center font-semibold rounded-t-lg">
                        Detalles de la adquisicion
                    </div>

                    <div class="p-6 border-2 border-blue-600 rounded-b-lg bg-white">
                        <div>

                            <!--Nombre producto-->
                            <div class="mb-3">
                                <label for="producto_id" class="block text-sm font-medium text-gray-700 mb-1">Producto:</label>
                                <select name="producto_id" id="producto_id" class="form-select">
                                    <option value="">Seleccione un producto</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>{{ $producto->codigo . ' ' . $producto->nombre }}</option>
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

                                <!--Precio de adquisicion-->
                                <div class="col-md-6">
                                    <label for="precio_adquisicion" class="block text-sm font-medium text-gray-700 mb-1">Precio de adquisicion:</label>
                                    <input type="number" name="precio_adquisicion" id="precio_adquisicion" class="form-control" step="0.1">
                                </div>
                            </div>

                            <!--Botón para agregar-->
                            <div class="text-right mb-3">
                                <button type="button" id="btn_agregar" class="btn btn-primary">Agregar</button>
                            </div>

                            <!--Tabla para el detalle de la adquisicion-->
                            <div>
                                <div class="overflow-x-auto">
                                    <table id="tabla_detalle" class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-blue-600">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">#</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Producto</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Cantidad</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Precio de adquisicion</th>
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
                                            <tr>
                                                <th class="px-6 py-3"></th>
                                                <th colspan="4" class="px-6 py-3 text-right text-sm font-bold text-gray-700">Total</th>
                                                <th colspan="2" class="px-6 py-3 text-left">
                                                    <input id="inputTotal" type="hidden" name="total" value="0">
                                                    <span id="total" class="text-sm font-bold text-gray-900">0</span>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!--Botón para cancelar la adquisicion-->
                            <div class="text-center">
                                <button id="botonCancelar" type="button" class="btn btn-danger" onclick="window.adquisicionCreate.openCancelModal()">Cancelar adquisicion</button>
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

                            <!--Proveedor-->
                            <div class="mb-3">
                                <label for="proveedore_id" class="block text-sm font-medium text-gray-700 mb-1">Proveedor:</label>
                                <select name="proveedore_id" id="proveedore_id" class="form-select">
                                    <option value="">Seleccione un proveedor</option>
                                    @foreach ($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" {{ old('proveedore_id') == $proveedor->id ? 'selected' : '' }}>{{ $proveedor->persona->razon_social }}</option>
                                    @endforeach
                                </select>
                                @error('proveedore_id')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Tipo de Adquisición-->
                            <div class="mb-3">
                                <label for="tipo_adquisicion" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Adquisición:</label>
                                <textarea name="tipo_adquisicion" id="tipo_adquisicion" rows="2" class="form-control" placeholder="Ej: Convenio, Proyecto, Donación, Préstamo, etc.">{{ old('tipo_adquisicion') }}</textarea>
                                <small class="text-gray-500 text-xs">Especifique si fue por convenio, proyecto, donación, préstamo, etc.</small>
                                @error('tipo_adquisicion')
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

    <!-- Modal para cancelar la adquisicion -->
    <div id="cancelModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.adquisicionCreate.closeCancelModal()"></div>

            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium text-gray-900">Mensaje de confirmación</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">¿Seguro que quieres cancelar la adquisicion?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button id="btnCancelarAdquisición" type="button" class="btn btn-danger mr-2" onclick="window.adquisicionCreate.confirmCancel()">
                        Confirmar
                    </button>
                    <button onclick="window.adquisicionCreate.closeCancelModal()" class="btn btn-secondary">
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
        //$(document).ready(function()Esta línea indica que el código dentro de la función se ejecutará una vez que el documento HTML esté completamente cargado y listo. Es un evento de jQuery que asegura que los elementos del DOM están disponibles antes de intentar manipularlos.
        // Global adquisicion create object
        window.adquisicionCreate = {
            openCancelModal() {
                document.getElementById('cancelModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            },

            closeCancelModal() {
                document.getElementById('cancelModal').style.display = 'none';
                document.body.style.overflow = '';
            },

            confirmCancel() {
                cancelarAdquisición();
                this.closeCancelModal();
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

            // Restaurar productos del sessionStorage si existen
            restaurarProductos();

            $('#btn_agregar').click(function() {
                agregarProducto();
            });

            disableButtons();//esta funcion hacer desaparecer los botones mientras no se inserten valores en la tabla

            //$('#impuesto').val(impuesto + '%'); //va a mostrar en nuestro input id='impuesto' el valor del impuesto en porcentaje
        });

        //Variables
        let count = 0;
        let subtotal = [];
        let sumas = 0;
        //let igv = 0;
        let total = 0;

        //constantes
        //const impuesto = 18; //nuestro impuesto sera de un 18%, esto puede cambiar dependiendo del tipo de producto que se estará adquiriendo

        function agregarProducto() {
            let idProducto = $('#producto_id').val();
            let nameProducto = ($('#producto_id option:selected').text()).split(' ')[1];
            let cantidad = $('#cantidad').val();
            let precioAdquisición = $('#precio_adquisicion').val();

            //Validaciones
            //1.Para que los campos no estén vacíos
            if (nameProducto != '' && nameProducto != undefined && cantidad != '' && precioAdquisición != '') {

                //2. Para que los valores ingresados sean correctos
                if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(precioAdquisición) > 0) {

                    //Calcular valores
                    subtotal[count] = round(cantidad * precioAdquisición);
                    sumas += subtotal[count];
                    total = round(sumas);

                    //Crear la fila
                    let fila = '<tr id="fila' + count + '">' +
                        '<td>' + (count + 1) + '</td>' +
                        '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                        '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                        '<td><input type="hidden" name="arraypreciocompra[]" value="' + precioAdquisición + '">' + precioAdquisición + '</td>' +
                        '<td>' + subtotal[count] + '</td>' +
                        '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + count + ')"><i class="fa-solid fa-trash"></button></i></td>' +
                        '</tr>';

                    //Mostrar los datos insertados por el usuario en la tabla
                    $('#tabla_detalle').append(fila);

                    //Limpiamos los campos
                    limpiarCampos();
                    count++;

                    //Llamamos a la funcion disableButtons
                    disableButtons();

                    //Mostrar el total
                    $('#total').html(total);
                    $('#inputTotal').val(total);

                    //Guardar en sessionStorage
                    guardarEnSessionStorage();
                } else {
                    showModal('Valores incorrectos en los campos: cantidad/precio de adquisicion');
                }
            } else {
                showModal('Le faltan campos por llenar');
            }
        };

        function eliminarProducto(index) { //el parámetro es el índice que vamos a eliminar
            //Calcular valores de sumas,igv y total
            sumas -= round(subtotal[index]);
            //igv = round((sumas * impuesto) / 100); //IGV(Impuesto general a las ventas)
            total = round(sumas);
            //total = round(sumas + igv);

            //Mostrar los campos calculados en el <tfoot> de la tabla
            //$('#sumas').html(sumas);
            //$('#igv').html(igv);
            $('#total').html(total);
            //$('#impuesto').val(igv);//va a mostrar en nuestro input id='impuesto' el valor del impuesto
            $('#inputTotal').val(total);//este es lo que se va a estar enviando al servidor a traves de del input tipo hidden

            //Eliminar la fila
            $('#fila' + index).remove();

            //Llamamos a la funcion disableButtons despues de haber eliminado un registro, para que me aparezcan o desaparezcan los botones escondidos dependiendo del caso
            disableButtons();

            //Actualizar sessionStorage
            guardarEnSessionStorage();
        };

        function cancelarAdquisición() {
            //Eliminar el contenido de tbody de la tabla con id = "#tabla_detalle"
            $('#tabla_detalle > tbody').empty();

            //Añadir una nueva fila a la tabla
            let fila = '<tr>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '<td></td>' +
                '</tr>';
            $('#tabla_detalle').append(fila);//a la tabla le vamos agregar con la función append() lo que está en la variable fila, el método append en jQuery se utiliza para insertar contenido al final de los elementos seleccionados.

            //Reiniciar los valores de las variables
            count = 0;
            subtotal = [];
            sumas = 0;
            //igv = 0;
            total = 0;

            //Mostrar los campos calculados
            //$('#sumas').html(sumas);
            //$('#igv').html(igv);
            $('#total').html(total);
            //$('#impuesto').val(impuesto + '%'); //va a mostrar en nuestro input id='impuesto' el valor del impuesto en porcentaje
            $('#inputTotal').val(total);//este es lo que se va a estar enviando al servidor a traves de del input tipo hidden

            //Ejecutar la funcion para limpiar los campos, por si hay campos por limpiar
            limpiarCampos();

            //Llamamos a la funcion disableButtons despues de haber eliminado todos los registros, para que se escondan todos los botones
            disableButtons();

            //Limpiar sessionStorage
            sessionStorage.removeItem('adquisicionProductos');
        };

        function disableButtons() {
            if(total == 0) {
                //Esconder los botones con la función hide()
                $('#botonGuardar').hide();
                $('#botonCancelar').hide();
            } else {
                //Mostrar los botones con la función show()
                $('#botonGuardar').show();
                $('#botonCancelar').show();
            };
        }

        function limpiarCampos() {
            $('#producto_id').val('');
            $('#cantidad').val('');
            $('#precio_adquisicion').val('');
        };

        function round(num, decimales = 2) { //TENGO QUE VER EL FUNCIONAMIENTO DE ESTA FUNCIÓN
            var signo = (num >= 0 ? 1 : -1);
            num = num * signo;
            if (decimales === 0) //con 0 decimales
                return signo * Math.round(num);
            // round(x * 10 ^ decimales)
            num = num.toString().split('e');
            num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
            // x * 10 ^ (-decimales)
            num = num.toString().split('e');
            return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
        }
        //Fuente de la función round: https://es.stackoverflow.com/questions/48958/redondear-a-dos-decimales-cuando-sea-necesario/49177#49177


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

            // Recorrer todas las filas de la tabla que tengan id (las filas con datos)
            $('#tabla_detalle tbody tr[id^="fila"]').each(function() {
                let fila = $(this);
                let idProducto = fila.find('input[name="arrayidproducto[]"]').val();

                productos.push({
                    id: fila.attr('id'),
                    idProducto: idProducto,
                    nombreProducto: fila.find('td:eq(1)').clone().children().remove().end().text().trim(),
                    cantidad: fila.find('input[name="arraycantidad[]"]').val(),
                    precioAdquisición: fila.find('input[name="arraypreciocompra[]"]').val(),
                    subtotal: fila.find('td:eq(4)').text().trim()
                });
            });

            // Guardar en sessionStorage
            sessionStorage.setItem('adquisicionProductos', JSON.stringify({
                productos: productos,
                count: count,
                subtotal: subtotal,
                sumas: sumas,
                total: total
            }));

            console.log('Guardado en sessionStorage:', {
                productos: productos,
                count: count,
                total: total
            });
        }

        function restaurarProductos() {
            let datos = sessionStorage.getItem('adquisicionProductos');

            console.log('Datos del sessionStorage:', datos);

            if (datos) {
                try {
                    datos = JSON.parse(datos);

                    console.log('Datos parseados:', datos);

                    // Restaurar variables
                    count = datos.count || 0;
                    subtotal = datos.subtotal || [];
                    sumas = datos.sumas || 0;
                    total = datos.total || 0;

                    // Limpiar la tabla antes de restaurar
                    $('#tabla_detalle > tbody').empty();

                    // Restaurar cada producto
                    if (datos.productos && datos.productos.length > 0) {
                        datos.productos.forEach(function(producto) {
                            let filaIndex = producto.id.replace('fila', '');
                            let fila = '<tr id="' + producto.id + '">' +
                                '<td>' + (parseInt(filaIndex) + 1) + '</td>' +
                                '<td><input type="hidden" name="arrayidproducto[]" value="' + producto.idProducto + '">' + producto.nombreProducto + '</td>' +
                                '<td><input type="hidden" name="arraycantidad[]" value="' + producto.cantidad + '">' + producto.cantidad + '</td>' +
                                '<td><input type="hidden" name="arraypreciocompra[]" value="' + producto.precioAdquisición + '">' + producto.precioAdquisición + '</td>' +
                                '<td>' + producto.subtotal + '</td>' +
                                '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + filaIndex + ')"><i class="fa-solid fa-trash"></button></i></td>' +
                                '</tr>';

                            $('#tabla_detalle').append(fila);
                        });

                        // Actualizar totales en la vista
                        $('#total').html(total);
                        $('#inputTotal').val(total);

                        // Actualizar botones
                        disableButtons();

                        console.log('Productos restaurados:', datos.productos.length);
                    }
                } catch (e) {
                    console.error('Error al restaurar productos:', e);
                }
            }
        }
    </script>
@endpush
