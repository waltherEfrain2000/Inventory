//========================= Variables =========================

let ventaseleccionada = 0;
let datosVehiculos = [];
let datosConductores = [];
let choferSeleccionado = 0;
let vehiculoSeleccionado = 0;
let urlimagen = "";
let accion = 1; // 1 = guardar, 2 = editar
let idventasedit = 0; // Variable para almacenar el ID de la venta a editar

//========================= Inicializar =========================
function mostrarEspera() {
    Swal.fire({
        title: 'Cargando datos',
        text: 'Por favor espere...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}
function ocultarEspera() {
    Swal.close();
}

$(document).ready(function () {
    listarProveedores();
    listarClientes();
    listarVehiculos();
    listarConductores();
    cargarVentasDirectas();


    var location = window.location.search;
    variables = location.split("&");
    if (variables.length > 1) {
        idventasedit = parseInt(variables[1])
        if (variables[2] == 1) {
            listarEditar(idventasedit);
        } else {
            listarVer(idventasedit);
        }
        Swal.fire({
            title: 'Cargando datos',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        setTimeout(() => {
            Swal.close();
        }, 2000);
    }
});


//====================================================================================================
//====================================== Cargar Datos ================================================
//====================================================================================================
{
    function listarProveedores() {
        $.ajax({
            url: "./modules/sales/controller/cargarDatos.php?action=listarProveedores",
            type: "POST",
            dataType: "json",
            success: function (response) {

                let data = response;
                let select = $("#proveedor");
                select.empty();
                select.append(`<option value="">Seleccionar cliente</option>`);

                $.each(data, function (index, data) {
                    select.append(`<option value="${data.id}" 
                    data-precio="${data.preciocompra}">${data.nombre}</option>`);
                });

            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los Clientes:", error);
                //alert("Error al cargar los Clientes.");
            }
        });
    }

    function listarClientes() {
        $.ajax({
            url: "./modules/sales/controller/cargarDatos.php?action=listarclientes",
            type: "POST",
            dataType: "json",
            success: function (response) {

                let data = response;
                let select = $("#cliente");
                select.empty();
                select.append(`<option value="">Seleccionar cliente</option>`);

                $.each(data, function (index, data) {
                    select.append(`<option value="${data.idCliente}" 
                    data-precio="${data.precioVenta}"
                    data-direccion="${data.direccion}">${data.nombre}</option>`);
                });

            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los Clientes:", error);
                //alert("Error al cargar los Clientes.");
            }
        });
    }

    function listarVehiculos() {
        $.ajax({
            url: "./modules/sales/controller/cargarDatos.php?action=listarvehiculos",
            type: "POST",
            dataType: "json",
            success: function (response) {
                datosVehiculos = response;
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los vehículos:", error);
            }
        });
    }

    function listarConductores() {
        $.ajax({
            url: "./modules/sales/controller/cargarDatos.php?action=listarconductores",
            type: "POST",
            dataType: "json",
            success: function (response) {
                datosConductores = response;
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los conductores:", error);
            }
        });
    }

    function cargarVentasDirectas() {
        $.ajax({
            url: "./modules/sales/controller/salesC.php?action=cargarVentasTerceros",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if ($.fn.DataTable.isDataTable("#tablaventasdirectas")) {
                    $("#tablaventasdirectas").DataTable().clear().destroy();  // Limpia y destruye correctamente
                }

                $("#tablaventasdirectas tbody").empty();

                let data = response;
                let tableBody = $("#tablaventasdirectas tbody");
                tableBody.empty();

                // Contadores para cada estado
                let estadoCounts = {
                    pendientes: 0, // Estado 1
                    procesadas: 0, // Estado 2
                    cobrando: 0, // Estado 3
                    completadas: 0, // Estado 4
                    canceladas: 0 // Estado 5
                };

                var botones = ``;
                $.each(data, function (index, data) {
                    let badge = '';
                    let descripcionestado = '';
                    if (data.idestado == 4) {
                        badge = "secondary";
                        descripcionestado = "Pendiente";
                        estadoCounts.pendientes++;
                        botones =
                            `
                            <a class="dropdown-item editar" href="#!">Continuar venta <i class="ph-duotone ph-gear"></i></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item cancelar" href="#!" style="color:red">Cancelar <i class="ph-duotone ph-trash"></i></a>
                            `
                    }
                    if (data.idestado == 3) {
                        badge = "warning";
                        descripcionestado = "En proceso";
                        estadoCounts.procesadas++;
                        botones =
                            `
                            <a class="dropdown-item nota" href="#!">Nota recepción <i class="ph-duotone ph-file-text"></i></a>
                            <a class="dropdown-item ver" href="#!">Detalle venta <i class="ph-duotone ph-eye"></i></a>
                            <a class="dropdown-item rastrear" href="#!">Rastrear <i class="ph-duotone ph-truck"></i></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item cancelar" href="#!" style="color:red">Cancelar <i class="ph-duotone ph-trash"></i></a>
                            `
                    }
                    if (data.idestado == 2) {
                        badge = "info";
                        descripcionestado = "Cobro Pendiente";
                        estadoCounts.cobrando++;
                        botones =
                            `
                            <a class="dropdown-item pagar" href="#!">Recibir pago <i class="ph-duotone ph-money"></i></a>
                            <a class="dropdown-item ver" href="#!">Detalle venta <i class="ph-duotone ph-eye"></i></a>
                            <a class="dropdown-item verpagos" href="#!">Detalle pagos <i class="ph-duotone ph-eye"></i></a>
                            `
                    }
                    if (data.idestado == 1) {
                        badge = "success";
                        descripcionestado = "Completada";
                        estadoCounts.completadas++;
                        botones =
                            `
                            <a class="dropdown-item ver" href="#!">Detalle venta <i class="ph-duotone ph-eye"></i></a>
                            <a class="dropdown-item verpagos" href="#!">Detalle pagos <i class="ph-duotone ph-eye"></i></a>
                            `
                    }

                    tableBody.append(`
                    <tr>
                        <td>${data.id}</td>
                        <td>${data.nombre}</td>
                        <td>${data.boleta}</td>
                        <td>${data.peso}</td>
                        <td>${data.monto}</td>
                        <td>${data.flete}</td>
                        <td>${data.placa}</td>
                        <td><span class="badge rounded-pill text-bg-${badge}">${descripcionestado}</span></td>
                        <td class="text-center sticky-column p-0">
                            <div class="btn-group mb-2 me-2">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ph-duotone ph-file-text"></i></button>
                                <div class="dropdown-menu">
                                    ${botones}
                                </div>
                            </div>
                        </td>
                    </tr>
                `);
                });

                setInterval(function () {
                    $(".text-bg-info").toggleClass("text-bg-light");
                }, 800);

                // Inicializar la tabla DataTable una vez con opciones personalizadas
                $("#tablaventasdirectas").DataTable({
                    "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                });

                // Actualizar los contadores en el DOM
                $(".todas").text(estadoCounts.pendientes + estadoCounts.procesadas + estadoCounts.completadas + estadoCounts.cobrando);
                $(".pendientes").text(estadoCounts.pendientes);
                $(".procesadas").text(estadoCounts.procesadas);
                $(".cobrando").text(estadoCounts.cobrando);
                $(".completadas").text(estadoCounts.completadas);

            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los Ventas:", error);
            }
        });
    }



}
//====================================================================================================
//====================================== eventosReactivos ============================================
//====================================================================================================
{
    // ------------------------------------ definir el precio de compra
    // Función para calcular la ganancia
    function calcularGanancia() {
        let cantidad = parseFloat($("#peso").val());
        if (isNaN(cantidad) || cantidad <= 0) {
            cantidad = 0;
        }

        let precioVenta = parseFloat($("#pventa").val());
        if (isNaN(precioVenta) || precioVenta <= 0) {
            precioVenta = 0;
        }

        let precioCompra = parseFloat($("#pcompra").val());
        if (isNaN(precioCompra) || precioCompra <= 0) {
            precioCompra = 0;
        }

        // Calcular total y ganancia
        let total = cantidad * precioVenta;
        let ganancia = total - (cantidad * precioCompra);

        // Actualizar los campos en el DOM
        $("#totalp").val(total.toFixed(2));
        $("#ganancia").val(ganancia.toFixed(2));
    }

    // ------------------------------------ definir el precio de compra
    $("#proveedor").change(function () {
        let selectedOption = $(this).find("option:selected");
        let precioVenta = selectedOption.data("precio");
        $("#pcompra").val(precioVenta);

        // Calcular ganancia después de cambiar el proveedor
        calcularGanancia();
    });

    // ------------------------------------ definir el precio de venta
    $("#cliente").change(function () {
        let selectedOption = $(this).find("option:selected");
        let precioVenta = selectedOption.data("precio");
        $("#pventa").val(precioVenta);

        // Calcular ganancia después de cambiar el cliente
        calcularGanancia();
    });

    // ------------------------------------ calcular total a pagar
    $("#peso").on("input", function () {
        // Calcular ganancia después de cambiar el peso
        calcularGanancia();
    });

    // ------------------------------------ habilitar el campo de flete

    $(document).ready(function () {
        $('input[name="vehiculo_propio"]').on('change', function () {
            if ($('#vehiculo_propio_no').is(':checked')) {
                $('#pflete').prop('disabled', false).prop('required', true); // Habilitar y hacer obligatorio
            } else {
                $('#pflete').prop('disabled', true).prop('required', false); // Deshabilitar y quitar obligatorio
                $('#pflete').val(''); // Opcional: limpiar el valor del campo
            }
        });

        // Inicializar el estado al cargar la página
        if ($('#vehiculo_propio_no').is(':checked')) {
            $('#pflete').prop('disabled', false).prop('required', true);
        } else {
            $('#pflete').prop('disabled', true).prop('required', false);
        }
    });

    // ------------------------------------ visualizar boleta
    $(document).on("click", "#verboleta", function () {
        let file = $("#fileboleta")[0].files[0];

        if (!file && !urlimagen) {
            Swal.fire({
                title: 'Error',
                text: 'No se ha seleccionado un archivo ni se tiene una URL cargada',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                mostrarImagen(e.target.result);
            };
            reader.readAsDataURL(file);
        } else if (urlimagen) {
            alert("URL de imagen: " + urlimagen);
            mostrarImagen(urlimagen);
        }
    });

    function mostrarImagen(src) {
        Swal.fire({
            title: 'Nota de recepción',
            html: `<img src="${src}" style="max-width: 100%; height: auto;">`,
            showCloseButton: true,
            confirmButtonText: 'Aceptar',
            customClass: {
                popup: 'swal-wide'
            }
        });
    }

    // ------------------------------------ Filtro de Conductores
    {
        const $conductor = $('#conductor');
        const $resultadosConductor = $('#resultadosConductor');

        // Escuchar la entrada del usuario y filtrar resultados
        $conductor.on('keyup', function () {
            const inputValue = $conductor.val().trim().toUpperCase();
            $resultadosConductor.empty();

            if (!inputValue) return;

            const filteredData = datosConductores.filter(item =>
                item.nombre?.toUpperCase().includes(inputValue)
            );

            if (filteredData.length > 0) {
                renderResultadosConductor(filteredData);
            } else {
                renderSinResultadosConductor();
            }
        });

        // Manejar clic en un resultado de conductor
        $resultadosConductor.on('click', 'li[data-id]', function () {
            const selectedNombre = $(this).data('nombre');
            const selectedId = $(this).data('id');

            if (selectedNombre && selectedId) {
                $conductor.val(selectedNombre).trigger('input');
                // Aquí puedes guardar el idConductor en un campo oculto si lo necesitas
                choferSeleccionado = selectedId; // Guardar el ID del conductor seleccionado
                $resultadosConductor.empty();
            }
        });

        // Limpiar resultados al enfocar otro input
        $('input').on('focus', function () {
            $resultadosConductor.empty();
        });

        $conductor.on('blur', function () {
            if (choferSeleccionado == 0) {
                $conductor.val(''); // Limpiar el campo de entrada
            }
        });

        // Función para mostrar resultados
        function renderResultadosConductor(data) {
            data.forEach(item => {
                $resultadosConductor.append(`
            <li style="cursor: pointer; padding: 5px; border: 1px solid #ccc;" 
                data-id="${item.idConductor}" 
                data-nombre="${item.nombre}">
                ${item.nombre}
            </li>
        `);
            });
        }

        // Función para mostrar mensaje de "sin resultados"
        function renderSinResultadosConductor() {
            choferSeleccionado = 0
            $resultadosConductor.append(`
        <li style="padding: 2px; font-style: italic; border: 1px solid #ccc;">
            No se encontraron coincidencias.
        </li>
    `);
        }
    }
    // ------------------------------------ Filtro de Placa 
    {
        // Funcionalidad de filtro para la placa
        const $placa = $('#placa');
        const $resultadosPlaca = $('#resultadosPlaca');

        // Escuchar la entrada del usuario y filtrar resultados
        $placa.on('keyup', function () {
            const inputValue = $placa.val().trim().toUpperCase(); // Convertir la entrada del usuario a mayúsculas
            $resultadosPlaca.empty(); // Limpiar resultados previos

            if (!inputValue) return; // Si el campo está vacío, salir

            // Filtrar datos que coincidan parcialmente con la placa, comparando todo en mayúsculas
            const filteredData = datosVehiculos.filter(item =>
                item.placa?.toUpperCase().includes(inputValue) // Convertir los datos a mayúsculas también
            );

            // Mostrar resultados filtrados o mensaje si no hay coincidencias
            if (filteredData.length > 0) {
                renderResultadosPlaca(filteredData);
            } else {
                renderSinResultadosPlaca();
            }
        });

        // Manejar clic en un resultado de placa
        $resultadosPlaca.on('click', 'li[data-placa]', function () {
            const selectedPlaca = $(this).data('placa');
            const selectedplaca = $(this).data('placa'); // Asegúrate de que el ID del vehículo esté disponible en los datos

            if (selectedPlaca && selectedplaca) {
                $placa.val(selectedPlaca).trigger('input');
                vehiculoSeleccionado = selectedplaca; // Guardar el ID del vehículo seleccionado
                $resultadosPlaca.empty();
            }
        });

        // Limpiar lista de resultados al cambiar de input
        $('input').on('focus', function () {
            $resultadosPlaca.empty();
        });
        // Limpiar el input si no hay coincidencias y el usuario hace clic fuera
        $placa.on('blur', function () {
            if (vehiculoSeleccionado == 0) {
                $placa.val(''); // Limpiar el campo de entrada
            }
        });

        // Función para renderizar resultados filtrados para la placa
        function renderResultadosPlaca(data) {
            data.forEach(item => {
                $resultadosPlaca.append(`
          <li style="cursor: pointer; padding: 5px; border: 1px solid #ccc;" 
              data-placa="${item.placa}">
            ${item.placa} - ${item.marca_nombre}
          </li>
        `);
            });
        }

        // Función para mostrar mensaje de "sin resultados" para la placa
        function renderSinResultadosPlaca() {
            vehiculoSeleccionado = 0
            $resultadosPlaca.append(`
      <li style="padding: 2px; font-style: italic; border: 1px solid #ccc;">
        No se encontraron coincidencias.
      </li>
    `);
        }
    }

    // ------------------------------------ Ver Nota de Recepción y Raqui
    $(document).on("click", "#verrecepcion", function () {
        let file = $("#filerecepcion")[0].files[0];
        if (!file) {
            Swal.fire({
                target: document.getElementById('comprobanteModal'),
                title: 'Error',
                text: 'No se ha seleccionado un archivo',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        let reader = new FileReader();
        reader.onload = function (e) {
            Swal.fire({
                target: document.getElementById('comprobanteModal'),
                title: 'Nota de recepción',
                html: `<img src="${e.target.result}" class="img-fluid">`,
                confirmButtonText: 'Aceptar',
                customClass: {
                    popup: 'swal-wide' // Clase personalizada para el modal
                }
            });
        };
        reader.readAsDataURL(file);
    });

    $(document).on("click", "#verrecepcionraqui", function () {
        let file = $("#filerecepcionraqui")[0].files[0];
        if (!file) {
            Swal.fire({
                target: document.getElementById('comprobanteModal'),
                title: 'Error',
                text: 'No se ha seleccionado un archivo',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        let reader = new FileReader();
        reader.onload = function (e) {
            Swal.fire({
                target: document.getElementById('comprobanteModal'),
                title: 'Nota de recepción raqui',
                html: `<img src="${e.target.result}" class="img-fluid">`,
                confirmButtonText: 'Aceptar',
                customClass: {
                    popup: 'swal-wide' // Clase personalizada para el modal
                }
            });
        };
        reader.readAsDataURL(file);
    });



    // Función para calcular la ganancia de raqui
    $("#precioCompraRaqui, #precioVentaRaqui").on("input", function () {

        var preciocompraRaqui = parseFloat($("#precioCompraRaqui").val());
        var precioVentaRaqui = parseFloat($("#precioVentaRaqui").val());
        
        $("#gananciaRaqui").val((precioVentaRaqui - preciocompraRaqui).toFixed(2));
    })


}
//====================================================================================================
//====================================== Guardar Venta ===============================================
//====================================================================================================
{

    $("#procesarventa").click(function () {
        guardarventa(2);
    });
    $("#guardaravance").click(function () {
        guardarventa(1);
    });


    function guardarventa(hacer) {
        mostrarEspera();
        let datos = new FormData();

        $("#ventaterceros").find("input, select").each(function () {
            let input = $(this);
            if (input.attr("type") === "file") {
                datos.append(input.attr("id"), input[0].files[0]);
            } else {
                datos.append(input.attr("id"), input.val());
            }
        });

        if (hacer == 2 && (!datos.get("proveedor") || !datos.get("cliente") || !datos.get("totalp") || !datos.get("peso") || !datos.get("boleta"))) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, complete todos los campos',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        datos.append("idConductor", choferSeleccionado);
        datos.append("idVehiculo", vehiculoSeleccionado);
        datos.append("hacer", hacer);

        console.log([...datos]);

        var accionseleccionada = "";
        if (accion == 2) {
            datos.append("id", idventasedit);
            accionseleccionada = "editarventadirecta";
        } else {
            accionseleccionada = "guardarventadirecta";
        }

        $.ajax({
            url: "./modules/sales/controller/salesC.php?action=" + accionseleccionada,
            type: "POST",
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                ocultarEspera();
                try {
                    let res = JSON.parse(response);

                    if (res.success) {
                        Swal.fire({
                            title: 'Guardado',
                            text: 'La venta ha sido guardada correctamente',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            accion = 1;
                            $("#addAccountModal").modal("hide");
                            cargarVentasDirectas();
                            window.location.href = `?module=historial`
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: res.error || 'Ocurrió un error al guardar la venta',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } catch (e) {
                    console.error("Error al procesar la respuesta:", e);
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error inesperado al procesar la respuesta',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Error en la petición AJAX:", error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });

    }
}
//====================================================================================================
//====================================== Editar ventas ===============================================
//====================================================================================================
{
    //editar venta
    function listarEditar(idventasedit) {
        accion = 2;
        ventaseleccionada = idventasedit;

        $.ajax({
            url: "./modules/sales/controller/salesC.php?action=listarventadirecta",
            type: "POST",
            data: { id: ventaseleccionada },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    let data = response.data;

                    console.log(data);

                    var fecha = new Date(data.fechaCreado);
                    const opciones = {
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    };

                    let fechaFormateada = fecha.toLocaleDateString('es-ES', opciones);

                    // Capitalizar primera letra (día de la semana)
                    fechaFormateada = fechaFormateada.charAt(0).toUpperCase() + fechaFormateada.slice(1);

                    // Agregar punto después del mes
                    fechaFormateada = fechaFormateada.replace(/de ([a-záéíóúñ]+) /i, (match, mes) => {
                        const mesCapitalizado = mes.charAt(0).toUpperCase() + mes.slice(1);
                        return `de ${mesCapitalizado}. `;
                    });

                    $('#fechalarga').html(fechaFormateada);


                    $("#proveedor").val(data.idproveedor);
                    $("#cliente").val(data.idcliente);
                    $("#boleta").val(data.boleta);
                    urlimagen = window.location.origin + "/FPTRAX/modules/sales/uploads/boleta_" + data.boleta + ".jpg";
                    $("#peso").val(data.peso);

                    $("#pcompra").val(data.pcompra);
                    $("#pventa").val(data.pventa);
                    $("#totalp").val(data.monto);
                    $("#ganancia").val(data.ganancia);

                    $("#conductor").val(data.nombreConductor).trigger("input");
                    choferSeleccionado = data.idConductor;
                    $("#placa").val(data.placa).trigger("input");
                    vehiculoSeleccionado = data.placa;

                    $("#pflete").val(data.flete);
                    if (data.flete > 0) {
                        $('#vehiculo_propio_no').prop('checked', true);
                        $('#pflete').prop('disabled', false).prop('required', true);
                    } else {
                        $('#vehiculo_propio_si').prop('checked', true);
                        $('#pflete').prop('disabled', true).prop('required', false);
                    }

                    $("#observacion").val(data.observaciones);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo cargar los datos de la venta',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function (xhr, status, error) {
                setTimeout(() => {
                    Swal.close();
                }, 1000);
                console.error("Error al cargar los datos de la venta:", error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });

    };

    //vert venta
    function listarVer(idventasedit) {
        //ocultar botones

        $("#procesarventa").hide()
        $("#guardaravance").hide()

        accion = 2;
        ventaseleccionada = idventasedit;

        $.ajax({
            url: "./modules/sales/controller/salesC.php?action=listarventadirecta",
            type: "POST",
            data: { id: ventaseleccionada },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    let data = response.data;

                    console.log(data);

                    var fecha = new Date(data.fechaCreado);
                    const opciones = {
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    };

                    let fechaFormateada = fecha.toLocaleDateString('es-ES', opciones);

                    // Capitalizar primera letra (día de la semana)
                    fechaFormateada = fechaFormateada.charAt(0).toUpperCase() + fechaFormateada.slice(1);

                    // Agregar punto después del mes
                    fechaFormateada = fechaFormateada.replace(/de ([a-záéíóúñ]+) /i, (match, mes) => {
                        const mesCapitalizado = mes.charAt(0).toUpperCase() + mes.slice(1);
                        return `de ${mesCapitalizado}. `;
                    });



                    // Recolectar datos del formulario
                    datos = {
                        fecha: fechaFormateada,
                        proveedor: data.nombreProductor,
                        cliente: data.nombreCliente,
                        boleta: data.boleta,
                        peso: data.peso,
                        pcompra: data.pcompra,
                        pventa: data.pventa,
                        totalp: data.monto,
                        ganancia: data.ganancia,
                        conductor: data.nombreConductor,
                        placa: data.placa,
                        vehiculo: data.placa,
                        pflete: data.flete,
                        observacion: data.observaciones
                    };

                    // Construir HTML para mostrar
                    const contenido = `
                            <style>
                                .swal-wide {
                                    max-width: 800px !important;
                                }
                                .info-label {
                                    font-weight: bold;
                                    color: #333;
                                }
                                .info-value {
                                    margin-bottom: 0.5rem;
                                    color: #555;
                                }
                                .info-row {
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 1rem;
                                    margin-bottom: 1rem;
                                }
                                .info-col {
                                    flex: 1 1 45%;
                                }
                                .section-divider {
                                    border-top: 1px solid #ccc;
                                    margin: 1rem 0;
                                }
                            </style>
                
                            <div class="text-start">
                                <div class="info-row">
                                    <div class="info-col">
                                        <div class="info-label">Fecha</div>
                                        <div class="info-value">${datos.fecha}</div>
                                    </div>
                                </div>
                
                                <div class="info-row">
                                    <div class="info-col">
                                        <div class="info-label">Proveedor</div>
                                        <div class="info-value">${datos.proveedor}</div>
                                    </div>
                                    <div class="info-col">
                                        <div class="info-label">Cliente</div>
                                        <div class="info-value">${datos.cliente}</div>
                                    </div>
                                </div>
                
                                <div class="info-row">
                                    <div class="info-col">
                                        <div class="info-label">Boleta</div>
                                        <div class="info-value">${datos.boleta}</div>
                                    </div>
                                    <div class="info-col">
                                        <div class="info-label">Peso (toneladas)</div>
                                        <div class="info-value">${datos.peso}</div>
                                    </div>
                                </div>
                
                                <div class="info-row">
                                    <div class="info-col">
                                        <div class="info-label">Precio Compra</div>
                                        <div class="info-value">Lps. ${datos.pcompra}</div>
                                    </div>
                                    <div class="info-col">
                                        <div class="info-label">Precio Venta</div>
                                        <div class="info-value">Lps. ${datos.pventa}</div>
                                    </div>
                                </div>
                
                                <div class="info-row">
                                    <div class="info-col">
                                        <div class="info-label">Total a Pagar</div>
                                        <div class="info-value">Lps. ${datos.totalp}</div>
                                    </div>
                                    <div class="info-col">
                                        <div class="info-label">Ganancia</div>
                                        <div class="info-value">Lps. ${datos.ganancia}</div>
                                    </div>
                                </div>
                
                                <div class="section-divider"></div>
                
                                <div class="info-row">
                                    <div class="info-col">
                                        <div class="info-label">Conductor</div>
                                        <div class="info-value">${datos.conductor}</div>
                                    </div>
                                    <div class="info-col">
                                        <div class="info-label">Placa</div>
                                        <div class="info-value">${datos.placa}</div>
                                    </div>
                                </div>
                
                                <div class="info-row">
                                    <div class="info-col">
                                        <div class="info-label">Flete</div>
                                        <div class="info-value">Lps. ${datos.pflete}</div>
                                    </div>
                                    <div class="info-col">
                                        <div class="info-label">Observación</div>
                                        <div class="info-value">${datos.observacion}</div>
                                    </div>
                                </div>
                            </div>
                        `;

                    // Mostrar SweetAlert
                    Swal.fire({
                        title: 'Resumen de venta',
                        html: contenido,
                        confirmButtonText: 'Aceptar',
                        customClass: {
                            popup: 'swal-wide'
                        }
                    });

                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo cargar los datos de la venta',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function (xhr, status, error) {
                setTimeout(() => {
                    Swal.close();
                }, 1000);
                console.error("Error al cargar los datos de la venta:", error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }

    //ver pagos
    function listarVerpagos(idventasedit) {
        ventaseleccionada = idventasedit;

        $.ajax({
            url: "./modules/sales/controller/salesC.php?action=listarpagosventa",
            type: "POST",
            data: { id: ventaseleccionada },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    let listaPagos = response.data; // Esto debe ser un array de pagos
                    console.log(listaPagos);
                
                    let contenido = `
                        <style>
                            .swal-wide {
                                max-width: 800px !important;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin: 1rem 0;
                            }
                            th, td {
                                border: 1px solid #ccc;
                                padding: 8px;
                                text-align: left;
                            }
                            th {
                                background-color: #f4f4f4;
                                font-weight: bold;
                            }
                        </style>
                        <table>
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Abono</th>
                                    <th>Metodo de pago</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                
                    listaPagos.forEach(item => {
                        let fecha = new Date(item.fechaCreado);
                        let opciones = { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' };
                        let fechaFormateada = fecha.toLocaleDateString('es-ES', opciones);
                        fechaFormateada = fechaFormateada.charAt(0).toUpperCase() + fechaFormateada.slice(1);
                        fechaFormateada = fechaFormateada.replace(/de ([a-záéíóúñ]+) /i, (match, mes) => {
                            return `de ${mes.charAt(0).toUpperCase() + mes.slice(1)}. `;
                        });
                
                        contenido += `
                            <tr>
                                <td>${fechaFormateada}</td>
                                <td>Lps. ${item.abono}</td>
                                <td>${item.descripcion}</td>
                            </tr>
                        `;
                    });
                
                    contenido += `</tbody></table>`;
                
                    Swal.fire({
                        title: 'Resumen de Pagos',
                        html: contenido,
                        confirmButtonText: 'Aceptar',
                        customClass: {
                            popup: 'swal-wide'
                        }
                    });
                }
                 else {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo cargar los datos de la venta',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function (xhr, status, error) {
                setTimeout(() => {
                    Swal.close();
                }, 1000);
                console.error("Error al cargar los datos de la venta:", error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error inesperado',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }
}
//====================================================================================================
//====================================== Acciones Venta ==============================================
//====================================================================================================
{
    //======================= editar venta ========================
    $("#tablaventasdirectas").on("click", ".editar", function () {
        ventaseleccionada = $(this).closest("tr").find("td:eq(0)").text();
        window.location.href = `?module=sales2&${ventaseleccionada}&1`
    });
    //======================= ver venta ========================
    $("#tablaventasdirectas").on("click", ".ver", function () {
        ventaseleccionada = $(this).closest("tr").find("td:eq(0)").text();
        listarVer(ventaseleccionada);
        //window.location.href = `?module=sales2&${ventaseleccionada}&2`
    });

    //======================= ver pagos ========================
    $("#tablaventasdirectas").on("click", ".verpagos", function () {
        ventaseleccionada = $(this).closest("tr").find("td:eq(0)").text();
        listarVerpagos(ventaseleccionada);
        //window.location.href = `?module=sales2&${ventaseleccionada}&2`
    });

    //======================= ver pagos ========================
    $("#tablaventasdirectas").on("click", ".nota", function () {
        vehiculoSeleccionado = $(this).closest("tr").find("td:eq(6)").text();
        //window.location.href = `?module=sales2&${ventaseleccionada}&2`
    });

    //======================= Nota de Recepción ========================

    $(document).on("click", ".nota", function () {
        ventaseleccionada = $(this).closest("tr").find("td:eq(0)").text();
        $("#comprobanteModal").modal("show");
    });

    $("#btnguardarrecepcion").click(function (e) {
        e.preventDefault(); // Evita que el formulario se envíe de forma predeterminada
        

        $("#addNotarecepcionForm").addClass("was-validated");

        if ($("#addNotarecepcionForm")[0].checkValidity() === false) {
            Swal.fire({
                target: document.getElementById('comprobanteModal'),
                title: 'Campos vacíos',
                text: 'Por favor, seleccione una descripción.',
            });
            return;
        }

        var data = new FormData();
        data.append('idVenta', ventaseleccionada);
        data.append('nrecepcion', $("#nrecepcion").val());
        data.append('nrecepcionraqui', $("#nrecepcionraqui").val());
        data.append('file1', $("#filerecepcion")[0].files[0]);
        data.append('file2', $("#filerecepcionraqui")[0].files[0]);
        data.append('flete', $("#fleteraqui").val());
        data.append('cantidad', $("#cantidadRaqui").val());
        data.append('precioCompra', $("#precioCompraRaqui").val());
        data.append('precioVenta', $("#precioVentaRaqui").val());
        data.append('placa', vehiculoSeleccionado);

        $.ajax({
            url: "./modules/sales/controller/accionesVentas.php?action=guardarnotarecepcion",
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                try {
                    let res = JSON.parse(response);
                    if (res.success) {
                        Swal.fire({
                            target: document.getElementById('comprobanteModal'),
                            title: 'Guardado',
                            text: res.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            cargarVentasDirectas(); // Actualizar la lista de ventas
                            $("#comprobanteModal").modal("hide");
                            $("#addNotarecepcionForm").removeClass("was-validated");
                            $("#addNotarecepcionForm")[0].reset();
                        });
                    } else {
                        Swal.fire({
                            target: document.getElementById('comprobanteModal'),
                            title: 'Error',
                            text: res.error,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } catch (e) {
                    console.error("Error al procesar la respuesta:", e);
                    Swal.fire({
                        target: document.getElementById('comprobanteModal'),
                        title: 'Error',
                        text: 'Ocurrió un error inesperado al procesar la respuesta del servidor.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Error en la petición AJAX:", error);
                Swal.fire({
                    target: document.getElementById('comprobanteModal'),
                    title: 'Error',
                    text: 'Ocurrió un error inesperado en la comunicación con el servidor.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });


    //======================= Recibir Pago ========================
    $("#tablaventasdirectas").on("click", ".pagar", function () {
        ventaseleccionada = $(this).closest("tr").find("td:eq(0)").text();
        //abrir modal
        $("#PagoModal").modal("show");

        let table = $("#tablaventasdirectas").DataTable();
        let row = $(this).closest("tr");
        let rowData = table.row(row).data();

        let id = rowData[0];
        let monto = rowData[4];

        $.ajax({
            url: "./modules/sales/controller/accionesVentas.php?action=listarSaldo",
            type: "POST",
            data: { idVenta: id },
            success: function (response) {
                let res = JSON.parse(response);
                let total = res.success[0].total;

                if (total > 0) {
                    $("#todono").prop("checked", true).trigger("change");
                    $("#todosi").prop("disabled", true);
                } else {
                    $("#todosi").prop("checked", true).trigger("change");
                    $("#todosi").prop("disabled", false);
                    $("#todono").prop("disabled", false);
                }
                $("#montototal").val(total);
                $("#labelmontoabono").html("Abonado: Lps. <b>" + total + "</b> - pendiente Lps. <b>" + (monto - total) + "</b>");
                $("#labelmonto").html("Monto a abonar: Lps. <b>" + monto + "</b>");
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los Ventas:", error);
                //alert("Error al cargar los Ventas.");
            }
        });


    });

    $("#btnguarabono").click(function () {
        $("#addPagoForm").addClass("was-validated");


        var monto = 0;
        var pago = 0;
        if ($("#todosi").is(":checked")) { monto = $("#montototal").val(); pago = 2 } else { monto = $("#abono").val(); pago = 1 }

        let data = {
            idVenta: ventaseleccionada,
            monto: monto,
            metodo: $("#metodoPago").val(),
            pago: pago
        };

        console.log(data);
        $.ajax({
            url: "./modules/sales/controller/accionesVentas.php?action=recibirpago",
            type: "POST",
            data: data,
            success: function (response) {
                try {
                    let res = JSON.parse(response);

                    if (res.success) {
                        Swal.fire({
                            target: document.getElementById('PagoModal'),
                            title: 'Guardado',
                            text: 'El pago ha sido saldado correctamente',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            $("#PagoModal").modal("hide");
                            $("#addPagoForm").removeClass("was-validated");
                            $("#addPagoForm")[0].reset();
                            listarVentas();
                        });
                    } else {
                        Swal.fire({
                            target: document.getElementById('PagoModal'),
                            title: 'Error',
                            text: res.error || 'Ocurrió un error al saldar el pago',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                } catch (e) {
                    console.error("Error al procesar la respuesta:", e, response); // Agrega la respuesta para depuración
                    Swal.fire({
                        target: document.getElementById('PagoModal'),
                        title: 'Error',
                        text: 'Ocurrió un error inesperado al procesar la respuesta',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Error en la petición AJAX:", error);
                Swal.fire({
                    target: document.getElementById('PagoModal'),
                    title: 'Error',
                    text: 'Ocurrió un error inesperado',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }
    );

    //======================= Cancelar Venta ========================
    $("#tablaventasdirectas").on("click", ".cancelar", function () {
        let row = $(this).closest("tr");
        let id = row.find("td:eq(0)").text();

        Swal.fire({
            title: 'Cancelar Venta',
            text: `¿Desea cancelar la venta #${id}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "./modules/sales/controller/salesC.php?action=cancelarVentaTerceros",
                    type: "POST",
                    data: { id },
                    success: function (response) {
                        let res = JSON.parse(response);

                        Swal.fire({
                            title: 'Guardado',
                            text: 'La venta ha sido cancelada correctamente',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            cargarVentasDirectas();
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en la petición AJAX:", error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error inesperado',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    });

    //======================= Rastrear ========================
$(document).on("click", ".rastrear", function () {
    var placa = $(this).closest("tr").find("td:eq(4)").text();
    window.location.href = `?module=rastrear&${placa}`
});
}

