@extends('template')

@section('title', 'Realizar venta')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>{{-- Importar la libreria para utilizar el bootstrap select o para hacer consultas javaScript--}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Realizar Venta</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Venta</a></li>
            <li class="breadcrumb-item active">Realizar venta</li>
        </ol>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ventas.store') }}" method="post">
        @csrf

        <div class="container mt-4">
            <div class="row gy-4">{{-- la clase gy-4 se utiliza en el framework Bootstrap para agregar un margen vertical (gap) entre las filas de un contenedor. --}}
                <!--producto venta-->
                <div class="col-md-8">
                    <div class="text-white bg-primary p-1 text-center ">
                        Detalles de la venta
                    </div>

                    <div class="p-3 border border-3 border-primary">
                        <div class="row">

                            <!--Nombre producto-->
                            <div class="col-md-8 mb-2">
                                <select title="Busque un producto aquí." name="producto_id" id="producto_id" class="form-control selectpicker show-tick" data-live-search="true" data-size="2">
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}-{{ $producto->stock }}-{{ $producto->precio_venta }}">{{ $producto->codigo . ' ' . $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!--Stock-->
                            <div class="col-md-4 mb-4">
                                <div class="row">
                                    <label for="stock" class="form-label col-sm-4">En stock:</label>
                                    <div class="col-sm-8">
                                        <input disabled id="stock" type="text" class="form-control border-primary">
                                    </div>
                                </div>
                            </div>
                            
                            
                            <!--Cantidad-->
                            <div class="col-md-4 mb-2">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                            <!--Precio de venta-->
                            <div class="col-md-4 mb-2">
                                <label for="precio_venta" class="form-label">Precio de venta:</label>
                                <input disabled type="number" name="precio_venta" id="precio_venta" class="form-control border-primary" step="0.1">
                            </div>
                            
                            <!--Descuento-->
                            <div class="col-md-4 mb-2">
                                <label for="descuento" class="form-label">Descuento:</label>
                                <input type="number" name="descuento" id="descuento" class="form-control">
                            </div>

                            <!--Botón para agregar-->
                            <div class="col-md-12 mb-2 text-end">
                                <button type="button" id="btn_agregar" class="btn btn-primary">Agregar</button>
                            </div>

                            <!--Tabla para el detalle de la compra-->
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabla_detalle" class="table table-striped table-hover ">
                                        <thead class="bg-primary">{{-- no me funciona el text-white en mi thead por lo cual lo puse en cada <th> el text-white --}}
                                            <tr>
                                                <th class="text-white">#</th>
                                                <th class="text-white">Producto</th>
                                                <th class="text-white">Cantidad</th>
                                                <th class="text-white">Precio de venta</th>
                                                <th class="text-white">Descuento</th>
                                                <th class="text-white">Subtotal</th>
                                                <th class="text-white"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider">
                                            
                                            {{-- aca van los datos que inserta el usuario através de javaScript --}}
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="table-group-divider">
                                            {{--<tr>
                                                <th></th>
                                                <th colspan="4">Sumas</th>
                                                <th colspan="2"><span id="sumas">0</span></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">IGV %</th>{{-- IGV(Impuesto general a las ventas) 
                                                <th colspan="2"><span id="igv">0</span></th>
                                            </tr>--}}
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Total</th>
                                                <th colspan="2"><input id="inputTotal" type="hidden" name="total" value="0"><span id="total">0</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!--Botón para cancelar la venta-->
                            <div class="col-md-12 mb-2 text-center">
                                <button id="botonCancelar" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">Cancelar venta</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Venta-->
                <div class="col-md-4">
                    <div class="text-white bg-success p-1 text-center ">
                        Datos generales
                    </div>

                    <div class="p-3 border border-3 border-success">
                        <div class="row">

                            <!--Cliente-->
                            <div class="col-md-12 mb-2">
                                <label for="cliente_id" class="form-label">Cliente:</label>
                                <select name="cliente_id" id="cliente_id" class="form-control selectpicker show-tick" title="Seleccione un cliente." data-live-search="true" data-size="2">
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->persona->razon_social }}</option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Tipo de comprobante-->
                            <div class="col-md-12 mb-2">
                                <label for="comprobante_id" class="form-label">Comprobante:</label>
                                <select name="comprobante_id" id="comprobante_id" class="form-control selectpicker show-tick" title="Seleccione un comprobante.">
                                    @foreach ($comprobantes as $comprobante)
                                        <option value="{{ $comprobante->id }}" {{ old('comprobante_id') == $comprobante->id ? 'selected' : '' }}>{{ $comprobante->tipo_comprobante }}</option>
                                    @endforeach
                                </select>
                                @error('comprobante_id')
                                    <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Número de comprobante-->
                            <div class="col-md-12 mb-2">
                                <label for="numero_comprobante" class="form-label">Número de comprobante:</label>
                                <input type="text" name="numero_comprobante" id="numero_comprobante" class="form-control" value="{{ old('numero_comprobante') }}">
                                @error('numero_comprobante')
                                    <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Impuesto no usamos en este caso-->
                            <!--
                            <div class="col-md-6 mb-2">
                                <label for="impuesto" class="form-label">Impuesto(IGV):</label>
                                <input readonly type="text" name="impuesto" id="impuesto" class="form-control border-success" value="{{-- old('impuesto') --}}">
                                {{--@error('impuesto')
                                    <small class="text-danger">{{'*'.$message}}</small>
                                @enderror--}}
                            </div>
                            -->

                            <!--Fecha-->
                            <div class="col-md-6 mb-2">
                                <label for="fecha" class="form-label">Fecha:</label>
                                <input disabled type="date" name="fecha" id="fecha" class="form-control border-success" value="{{ date('Y-m-d') }}">{{--este input solo muestra la fecha al usuario--}}

                                <?php 
                                    use Carbon\Carbon;//Importamos la clase Carbon 
                                    $fecha_hora = Carbon::now()->toDateTimeString();//es una función de la librería Carbon en Laravel y PHP que se usa para obtener la fecha y hora actual.
                                ?>
                                <input type="hidden" name="fecha_hora" value="{{ $fecha_hora }}">{{--este input de tipo hidden envia el valor de la fecha y hora al servidor para añadir a la base de datos--}}
                            </div>

                            <!-- User -->
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}"><!-- Acá te trae el nombre del usuario con el que actualmente hiciste sesión-->


                            <!--Botón-->
                            <div class="col-md-12 mb-2 text-center">
                                <button id="botonGuardar" type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para cancelar la venta -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación:</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Seguro que quieres cancelar la venta?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btnCancelarVenta" type="button" class="btn btn-danger" data-bs-dismiss="modal">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>


    </form>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>

        //$(document).ready(function()Esta línea indica que el código dentro de la función se ejecutará una vez que el documento HTML esté completamente cargado y listo. Es un evento de jQuery que asegura que los elementos del DOM están disponibles antes de intentar manipularlos.
        $(document).ready(function() {

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

            $('#btnCancelarVenta').click(function() {
                cancelarVenta();
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

        //Constantes 
        //let impuesto = 11;


        function agregarProducto() {
            let dataProducto = document.getElementById('producto_id').value.split('-');
            let idProducto = dataProducto[0]; //aca obtenemos lo que el select obtuvo del value de option
            let nameProducto = $('#producto_id option:selected').text(); //acá entramos dentro del select accediendo al option y obteniendo el valor que se muestra al usuario
            let cantidad = $('#cantidad').val();
            let precioVenta = $('#precio_venta').val();
            let descuento = $('#descuento').val();
            let stock = $('#stock').val();

            if(descuento == '') {
                descuento = 0;
            }
            /*console.log("Producto ID:", idProducto);
            console.log("Nombre Producto:", nameProducto);
            console.log("Cantidad:", cantidad);
            console.log("Precio de Compra:", precioCompra);
            console.log("Precio de Venta:", precioVenta);*/

            //Validaciones
            //1.Para que los campos no estén vacíos
            if (idProducto != '' && cantidad != '') {

                //2. Para que los valores ingresados sean correctos
                if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(descuento) >= 0) { //cantidad,precioCompra y precioVenta sean mayor que cero, que cantidad sea un número entero aplicando (cantidad%1==0)

                    //3. Para que la cantidad no supere el stock
                    if (parseFloat(cantidad) <= parseInt(stock)) {

                        //Calcular valores
                        subtotal[count] = round(cantidad * precioVenta - descuento);
                        sumas += subtotal[count];
                        //igv = round((sumas * impuesto) / 100); //IGV(Impuesto general a las ventas)
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
                            '</tr>'; //Al button le vamos añadir el método onClick de javaScript para que esté a la escucha del evento click y pueda ejecutar la función eliminarProducto()

                        //Mostrar los datos insertados por el usuario en la tabla con id="tabla_detalle"
                        $('#tabla_detalle').append(fila); //a la tabla le vamos agregar con la función append() lo que está en la variable fila, el método append en jQuery se utiliza para insertar contenido al final de los elementos seleccionados.

                        //Limpiamos los campos luego de haber añadido los datos a la tabla
                        limpiarCampos();
                        count++;
                        
                        //Llamamos a la funcion disableButtons despues de haber añadido un registro, para que me aparezcan los botones escondidos
                        disableButtons();

                        //Mostrar los campos calculados en el <tfoot> de la tabla
                        //$('#sumas').html(sumas);//es para mostrar en el elemento 
                        //$('#igv').html(igv);
                        $('#total').html(total);
                        //$('#impuesto').val(igv);//va a mostrar en nuestro input id='impuesto' el valor del impuesto
                        $('#inputTotal').val(total);//este es lo que se va a estar enviando al servidor a traves de del input tipo hidden
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

        function eliminarProducto(index) { //el parámetro es el índice que vamos a eliminar
            //Calcular valores de sumas,igv y total 
            sumas -= round(subtotal[index]);
            //igv = round((sumas * impuesto) / 100); //IGV(Impuesto general a las ventas)
            total = round(sumas);

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
            let select = $('#producto_id');
            select.selectpicker(); //Esta línea inicializa el elemento select como un "selectpicker"
            select.selectpicker('val',''); //Esta línea establece el valor del "selectpicker" a una cadena vacía, lo que efectivamente restablece el menú desplegable a su estado inicial, sin ninguna opción seleccionada.
            $('#cantidad').val('');
            $('#precioVenta').val('');
            $('#descuento').val('');
            $('#stock').val('');
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
    </script>
@endpush
