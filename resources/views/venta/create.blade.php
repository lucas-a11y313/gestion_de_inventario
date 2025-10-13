@extends('template')

@section('title', 'Realizar venta')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Realizar Venta</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></div>
                <div class="breadcrumb-item active">Realizar venta</div>
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

        <form id="formVenta" action="{{ route('ventas.store') }}" method="post">
            @csrf

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!--Detalles de la venta-->
                    <div class="lg:col-span-2">
                        <div class="bg-blue-600 text-white px-4 py-2 text-center font-semibold rounded-t-lg">
                            Detalles de la venta
                        </div>

                        <div class="p-6 border-2 border-blue-600 rounded-b-lg bg-white">
                            <div>
                                <!--Nombre producto y stock-->
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="producto_id" class="block text-sm font-medium text-gray-700 mb-1">Producto:</label>
                                        <select name="producto_id" id="producto_id" class="form-select">
                                            <option value="">Seleccione un producto</option>
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}-{{ $producto->stock }}-{{ $producto->precio_venta }}">{{ $producto->codigo . ' ' . $producto->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">En stock:</label>
                                        <input disabled id="stock" type="text" class="form-control">
                                    </div>
                                </div>

                                <!--Campos en una fila horizontal-->
                                <div class="row mb-3">
                                    <!--Cantidad-->
                                    <div class="col-md-4">
                                        <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-1">Cantidad:</label>
                                        <input type="number" name="cantidad" id="cantidad" class="form-control">
                                    </div>

                                    <!--Precio de venta-->
                                    <div class="col-md-4">
                                        <label for="precio_venta" class="block text-sm font-medium text-gray-700 mb-1">Precio de venta:</label>
                                        <input disabled type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.1">
                                    </div>

                                    <!--Descuento-->
                                    <div class="col-md-4">
                                        <label for="descuento" class="block text-sm font-medium text-gray-700 mb-1">Descuento:</label>
                                        <input type="number" name="descuento" id="descuento" class="form-control" step="0.1">
                                    </div>
                                </div>

                                <!--Botón para agregar-->
                                <div class="text-right mb-3">
                                    <button type="button" id="btn_agregar" class="btn btn-primary">Agregar</button>
                                </div>

                                <!--Tabla para el detalle de la venta-->
                                <div>
                                    <div class="overflow-x-auto">
                                        <table id="tabla_detalle" class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-blue-600">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">#</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Producto</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Cantidad</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Precio de venta</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Descuento</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Subtotal</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
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

                                <!--Botón para cancelar la venta-->
                                <div class="text-center">
                                    <button id="botonCancelar" type="button" class="btn btn-danger" onclick="window.ventaCreate.openCancelModal()">Cancelar venta</button>
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
                                <!--Cliente-->
                                <div class="mb-3">
                                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente:</label>
                                    <select name="cliente_id" id="cliente_id" class="form-select">
                                        <option value="">Seleccione un cliente</option>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->persona->razon_social }}</option>
                                        @endforeach
                                    </select>
                                    @error('cliente_id')
                                        <small class="text-red-600 text-sm">{{'*'.$message}}</small>
                                    @enderror
                                </div>

                                <!--Tipo de comprobante-->
                                <div class="mb-3">
                                    <label for="comprobante_id" class="block text-sm font-medium text-gray-700 mb-1">Comprobante:</label>
                                    <select name="comprobante_id" id="comprobante_id" class="form-select">
                                        <option value="">Seleccione un comprobante</option>
                                        @foreach ($comprobantes as $comprobante)
                                            <option value="{{ $comprobante->id }}" {{ old('comprobante_id') == $comprobante->id ? 'selected' : '' }}>{{ $comprobante->tipo_comprobante }}</option>
                                        @endforeach
                                    </select>
                                    @error('comprobante_id')
                                        <small class="text-red-600 text-sm">{{'*'.$message}}</small>
                                    @enderror
                                </div>

                                <!--Número de comprobante-->
                                <div class="mb-3">
                                    <label for="numero_comprobante" class="block text-sm font-medium text-gray-700 mb-1">Número de comprobante:</label>
                                    <input type="text" name="numero_comprobante" id="numero_comprobante" class="form-control" value="{{ old('numero_comprobante') }}">
                                    @error('numero_comprobante')
                                        <small class="text-red-600 text-sm">{{'*'.$message}}</small>
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
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
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

        <!-- Modal para cancelar la venta -->
        <div id="cancelModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.ventaCreate.closeCancelModal()"></div>

                <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium text-gray-900">Mensaje de confirmación</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">¿Seguro que quieres cancelar la venta?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button id="btnCancelarVenta" type="button" class="btn btn-danger mr-2" onclick="window.ventaCreate.confirmCancel()">
                            Confirmar
                        </button>
                        <button onclick="window.ventaCreate.closeCancelModal()" class="btn btn-secondary">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Global venta create object
        window.ventaCreate = {
            openCancelModal() {
                document.getElementById('cancelModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            },

            closeCancelModal() {
                document.getElementById('cancelModal').style.display = 'none';
                document.body.style.overflow = '';
            },

            confirmCancel() {
                cancelarVenta();
                this.closeCancelModal();
            }
        };

        $(document).ready(function() {
            // Restaurar productos del sessionStorage si existen
            restaurarProductos();

            //cuando mi select "producto_id" cambie(haga un change) se ejecutará una función
            $('#producto_id').on('change', function(){
                //"$(this)" hace referencia al select '#producto_id', por lo cual el ".val()" trae el valor que está en el select y se asigna a la variable dataProducto
                let dataProducto = $(this).val().split('-');//el split separa los valores de la cadena en donde haya '-'
                $('#stock').val(dataProducto[1]);
                $('#precio_venta').val(dataProducto[2]);
            });

            $('#btn_agregar').click(function() {
                agregarProducto();
            });

            disableButtons();//esta funcion hacer desaparecer los botones mientras no se inserten valores en la tabla
        });

        //Variables
        let count = 0;
        let subtotal = [];
        let sumas = 0;
        let total = 0;

        function agregarProducto() {
            let dataProducto = document.getElementById('producto_id').value.split('-');
            let idProducto = dataProducto[0];
            let nameProducto = $('#producto_id option:selected').text();
            let cantidad = $('#cantidad').val();
            let precioVenta = $('#precio_venta').val();
            let descuento = $('#descuento').val();
            let stock = $('#stock').val();

            if(descuento == '') {
                descuento = 0;
            }

            //Validaciones
            //1.Para que los campos no estén vacíos
            if (idProducto != '' && cantidad != '') {

                //2. Para que los valores ingresados sean correctos
                if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(descuento) >= 0) {

                    //3. Para que la cantidad no supere el stock
                    if (parseFloat(cantidad) <= parseInt(stock)) {

                        //Calcular valores
                        subtotal[count] = round(cantidad * precioVenta - descuento);
                        sumas += subtotal[count];
                        total = round(sumas);

                        //Crear la fila
                        let fila = '<tr id="fila' + count + '">' +
                            '<td>' + (count + 1) + '</td>' +
                            '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                            '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                            '<td><input type="hidden" name="arrayprecioventa[]" value="' + precioVenta + '">' + precioVenta + '</td>' +
                            '<td><input type="hidden" name="arraydescuento[]" value="' + descuento + '">' + descuento + '</td>' +
                            '<td>' + subtotal[count] + '</td>' +
                            '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + count + ')"><i class="fa-solid fa-trash"></button></i></td>' +
                            '</tr>';

                        //Mostrar los datos insertados por el usuario en la tabla con id="tabla_detalle"
                        $('#tabla_detalle').append(fila);

                        //Limpiamos los campos luego de haber añadido los datos a la tabla
                        limpiarCampos();
                        count++;

                        //Llamamos a la funcion disableButtons despues de haber añadido un registro, para que me aparezcan los botones escondidos
                        disableButtons();

                        //Mostrar los campos calculados en el <tfoot> de la tabla
                        $('#total').html(total);
                        $('#inputTotal').val(total);

                        //Guardar en sessionStorage
                        guardarEnSessionStorage();
                    } else {
                        showModal('La cantidad debe ser menor o igual que el stock');
                    }
                } else {
                    showModal('Valores incorrectos en estos dos posibles campos: cantidad o descuento');
                }
            } else {
                showModal('Le faltan campos por llenar');
            }
        };

        function eliminarProducto(index) {
            //Calcular valores de sumas y total
            sumas -= round(subtotal[index]);
            total = round(sumas);

            //Mostrar los campos calculados en el <tfoot> de la tabla
            $('#total').html(total);
            $('#inputTotal').val(total);

            //Eliminar la fila
            $('#fila' + index).remove();

            //Llamamos a la funcion disableButtons despues de haber eliminado un registro, para que me aparezcan o desaparezcan los botones escondidos dependiendo del caso
            disableButtons();

            //Actualizar sessionStorage
            guardarEnSessionStorage();
        };

        function cancelarVenta() {
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
            $('#tabla_detalle').append(fila);

            //Reiniciar los valores de las variables
            count = 0;
            subtotal = [];
            sumas = 0;
            total = 0;

            //Mostrar los campos calculados
            $('#total').html(total);
            $('#inputTotal').val(total);

            //Ejecutar la funcion para limpiar los campos, por si hay campos por limpiar
            limpiarCampos();

            //Llamamos a la funcion disableButtons despues de haber eliminado todos los registros, para que se escondan todos los botones
            disableButtons();

            //Limpiar sessionStorage
            sessionStorage.removeItem('ventaProductos');
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
            $('#precio_venta').val('');
            $('#descuento').val('');
            $('#stock').val('');
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

            // Recorrer todas las filas de la tabla que tengan id (las filas con datos)
            $('#tabla_detalle tbody tr[id^="fila"]').each(function() {
                let fila = $(this);
                let idProducto = fila.find('input[name="arrayidproducto[]"]').val();

                productos.push({
                    id: fila.attr('id'),
                    idProducto: idProducto,
                    nombreProducto: fila.find('td:eq(1)').clone().children().remove().end().text().trim(),
                    cantidad: fila.find('input[name="arraycantidad[]"]').val(),
                    precioVenta: fila.find('input[name="arrayprecioventa[]"]').val(),
                    descuento: fila.find('input[name="arraydescuento[]"]').val(),
                    subtotal: fila.find('td:eq(5)').text().trim()
                });
            });

            // Guardar en sessionStorage
            sessionStorage.setItem('ventaProductos', JSON.stringify({
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
            let datos = sessionStorage.getItem('ventaProductos');

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
                                '<td><input type="hidden" name="arrayprecioventa[]" value="' + producto.precioVenta + '">' + producto.precioVenta + '</td>' +
                                '<td><input type="hidden" name="arraydescuento[]" value="' + producto.descuento + '">' + producto.descuento + '</td>' +
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
