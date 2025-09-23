@extends('template')

@section('title', 'Crear compra')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>{{-- Importar la libreria para utilizar el bootstrap select o para hacer consultas javaScript--}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Compra</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compras</a></li>
            <li class="breadcrumb-item active">Crear compra</li>
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

    <form action="{{ route('compras.store') }}" method="post">
        @csrf

        <div class="container mt-4">
            <div class="row gy-4">{{-- la clase gy-4 se utiliza en el framework Bootstrap para agregar un margen vertical (gap) entre las filas de un contenedor. --}}
                <!--Compra producto-->
                <div class="col-md-8">
                    <div class="text-white bg-primary p-1 text-center ">
                        Detalles de la compra
                    </div>

                    <div class="p-3 border border-3 border-primary">
                        <div class="row">

                            <!--Nombre producto-->
                            <div class="col-md-12 mb-2">
                                <select title="Busque un producto aquí." name="producto_id" id="producto_id" class="form-control selectpicker show-tick" data-live-search="true" data-size="2">
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>{{ $producto->codigo . ' ' . $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!--Cantidad-->
                            <div class="col-md-4 mb-2">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                            <!--Precio de compra-->
                            <div class="col-md-4 mb-2">
                                <label for="precio_compra" class="form-label">Precio de compra:</label>
                                <input type="number" name="precio_compra" id="precio_compra" class="form-control" step="0.1">
                            </div>

                            <!--Precio de venta-->
                            <div class="col-md-4 mb-2">
                                <label for="precio_venta" class="form-label">Precio de venta:</label>
                                <input type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.1">
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
                                                <th class="text-white">Precio de compra</th>
                                                <th class="text-white">Precio de venta</th>
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
                                            </tr>--}}

                                            {{-- IGV(Impuesto general a las ventas)
                                            <tr>
                                                <th></th>
                                                <th colspan="4">IGV %</th> 
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

                            <!--Botón para cancelar la compra-->
                            <div class="col-md-12 mb-2 text-center">
                                <button id="botonCancelar" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">Cancelar compra</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Producto-->
                <div class="col-md-4">
                    <div class="text-white bg-success p-1 text-center ">
                        Datos generales
                    </div>

                    <div class="p-3 border border-3 border-success">
                        <div class="row">

                            <!--Proveedor-->
                            <div class="col-md-12 mb-2">
                                <label for="proveedore_id" class="form-label">Proveedor:</label>
                                <select name="proveedore_id" id="proveedore_id" class="form-control selectpicker show-tick" title="Seleccione un provedor." data-live-search="true" data-size="2">
                                    @foreach ($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" {{ old('proveedore_id') == $proveedor->id ? 'selected' : '' }}>{{ $proveedor->persona->razon_social }}</option>
                                    @endforeach
                                </select>
                                @error('proveedore_id')
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
                                <input type="text" name="numero_comprobante" id="numero_comprobante" class="form-control" value="{{ old('numero_comprobante') }}">{{-- el required hace que sea obligatorio rellenar, evita que laravel cargue otra vez la pagina web --}}
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
                                <input readonly type="date" name="fecha" id="fecha" class="form-control border-success" value="{{ date('Y-m-d') }}">{{--este input solo muestra la fecha al usuario--}}

                                <?php 
                                    use Carbon\Carbon;//Importamos la clase Carbon 
                                    $fecha_hora = Carbon::now()->toDateTimeString();//es una función de la librería Carbon en Laravel y PHP que se usa para obtener la fecha y hora actual. 
                                ?>
                                <input type="hidden" name="fecha_hora" value="{{ $fecha_hora }}">{{--este input de tipo hidden envia el valor de la fecha y hora al servidor para añadir a la base de datos--}}
                            </div>

                            <!--Botón-->
                            <div class="col-md-12 mb-2 text-center">
                                <button id="botonGuardar" type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para cancelar la compra -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación:</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Seguro que quieres cancelar la compra?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btnCancelarCompra" type="button" class="btn btn-danger" data-bs-dismiss="modal">Confirmar</button>
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
            $('#btn_agregar').click(function() {
                agregarProducto();
            });

            $('#btnCancelarCompra').click(function() {
                cancelarCompra();
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
            let idProducto = $('#producto_id').val(); //aca obtenemos lo que el select obtuvo del value de option
            let nameProducto = ($('#producto_id option:selected').text()).split(' ')[1]; //acá entramos dentro del select accediendo al option y obteniendo el valor que se muestra al usuario, en el split tomamos referencia a partir de los 1 espacios en blanco de la cadena y que de ahí empiece en el indice 1 que estaria el nombre del producto, no utilizamos el indice 0 porque ahí estaría el código. por ejemplo: la cadena es "438-DRD   Plátanos" lo que estariamos recuperando sería solo "Plátanos".
            let cantidad = $('#cantidad').val();
            let precioCompra = $('#precio_compra').val();
            let precioVenta = $('#precio_venta').val();

            /*console.log("Producto ID:", idProducto);
            console.log("Nombre Producto:", nameProducto);
            console.log("Cantidad:", cantidad);
            console.log("Precio de Compra:", precioCompra);
            console.log("Precio de Venta:", precioVenta);*/
            
            //Validaciones
            //1.Para que los campos no estén vacíos
            if (nameProducto != '' && nameProducto != undefined && cantidad != '' && precioCompra != '' && precioVenta != '') {

                //2. Para que los valores ingresados sean correctos
                if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(precioCompra) > 0 && parseFloat(precioVenta) > 0) { //cantidad,precioCompra y precioVenta sean mayor que cero, que cantidad sea un número entero aplicando (cantidad%1==0)

                    //3. Para que el precio de compra sea menor que el precio de venta
                    if (parseFloat(precioVenta) > parseFloat(precioCompra)) {

                        //Calcular valores
                        subtotal[count] = round(cantidad * precioCompra);
                        sumas += subtotal[count];
                        //igv = round((sumas * impuesto) / 100); //IGV(Impuesto general a las ventas)
                        //total = round(sumas + igv);
                        total = round(sumas);

                        //Crear la fila
                        let fila = '<tr id="fila' + count + '">' +
                            '<td>' + (count + 1) + '</td>' +
                            '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                            '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                            '<td><input type="hidden" name="arraypreciocompra[]" value="' + precioCompra + '">' + precioCompra + '</td>' +
                            '<td><input type="hidden" name="arrayprecioventa[]" value="' + precioVenta + '">' + precioVenta + '</td>' +
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
                        showModal('Precio de venta debe ser mayor que el precio de compra');
                    }
                } else {
                    showModal('Valores incorrectos en algunos de estos tres campos: cantidad/precio de compra/precio de venta');
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
        };

        function cancelarCompra() {
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
            $('#precio_compra').val('');
            $('#precio_venta').val('');
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
