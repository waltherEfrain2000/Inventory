//========================= Variables =========================
let datosTiposCertificacion = [];
let datosCertificacion = [];
let ventaseleccionada = 0;
let placaseleccionada = "";
let precioventa = 0;
let descripcionProducto = "";
let idventasedit = 0;
let valorventa = 0;
//=========================
$(document).ready(function () {
    listarVentas();
    listarClientes();
    listarmotivos();
    listartipocertificacion();
    listarcertificacion();
    listarTransportistas();
    listarVehiculos();

    var location = window.location.search;
    variables = location.split("&");
    if(variables.length > 1){
        // mostrar un sweet alert mistras se carga la pagina
            idventasedit = parseInt(variables[1])
            listarEditar(idventasedit);
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

//========================= Vrnta para editar =========================
function listarEditar(idventasedit) {
    $.ajax({
        url: "./modules/sales/controller/cargarDatos.php?action=listarEditar",
        type: "POST",
        data: { idventasedit: idventasedit },
        dataType: "json",
        success: function (response) {
            venta = response;
            console.log(venta);
  

        $("#nremision").val(venta.nRemision);
        $("#fecha").val(formatDate(venta.fechaVenta));
        $("#destinatario").val(venta.idCliente).trigger("change");
        $("#partida").val(venta.puntoPartida);
        $("#destino").val(venta.destino);
        $("#traslado").val(venta.idMotivoTraslado).trigger("change");
        
        $("#otrotraslado").val(venta.otrotraslado);
        $("#nAutorizacion").val(venta.nAutorizacion);
        $("#numeracion").val(venta.numeracion);
        $("#fechaDocotrotraslado").val(formatDate(venta.fechaDocotrotraslado));

            // Llenar la tabla de detalles con venta.detalles
            $("#tablaDetalle tbody").empty(); // Limpiar la tabla antes de llenarla
        
            // Agrupar detalles por idProducto y sumar cantidades
            let detallesAgrupados = {};
            venta.detalles.forEach(detalle => {
                if (!detallesAgrupados[detalle.idProducto]) {
                    detallesAgrupados[detalle.idProducto] = {
                        idProducto: detalle.idProducto,
                        lote: detalle.lote,
                        descripcion: detalle.nombreProducto || `Producto ${detalle.idProducto}`, // Ajusta según los datos disponibles
                        cantidadTotal: 0,
                        detalles: []
                    };
                }
                detallesAgrupados[detalle.idProducto].cantidadTotal += parseInt(detalle.cantidad, 10);
                detallesAgrupados[detalle.idProducto].detalles.push({
                    idProductor: detalle.idProductor,
                    lote: detalle.lote,
                    cantidad: detalle.cantidad,
                    nombre: detalle.nombre
                });
                $("#descripcion").val(detalle.idProducto).trigger("change");
            });
        
            // Agregar filas a la tabla con los datos agrupados
            Object.values(detallesAgrupados).forEach(agrupado => {
                let jsonDetalle = JSON.stringify(agrupado.detalles);
        
                $("#tablaDetalle tbody").append(`
                    <tr>
                        <td>1</td>
                        <td>${agrupado.descripcion}</td>
                        <td>${agrupado.cantidadTotal}</td>
                        <td class="d-none json-detalle">${jsonDetalle}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning editardetalle">
                                <i class="ph-duotone ph-pencil-line"></i> 
                            </button>
                            <button type="button" class="btn btn-danger eliminardetalle">
                                <i class="ph-duotone ph-trash"></i> 
                            </button>
                        </td>
                    </tr>
                `);
            });
            actualizartotal();
        


        $("#fechainicio").val(formatDate(venta.fechaTraslado));
        $("#tipocertificacion").val(venta.tipocertificacion).trigger("change");
        $("#ncertificacion").val(venta.ncertificacion).trigger("change");
        $("#emisiones").val(venta.emisiones);
        $("#km").val(venta.km);
        $("#transportista").val(venta.idTransportista).trigger("change");
        setTimeout(() => {
        $("#conductor").val(venta.idConductor).trigger("change");
        }, 1000);
        $("#placa").val(venta.placa).trigger("input");
        $("#marca").val(venta.marca_nombre);
        $("#licencia").val(venta.licencia);


        },
        error: function (xhr, status, error) {
            console.error("Error al cargar la venta:", error);
        }
    });
}
//========================= Función para formatear fechas =========================
function formatDate(dateTime) {
    if (!dateTime || dateTime === "0000-00-00 00:00:00") {
        return "";
    }
    return dateTime.split(" ")[0]; // Extrae solo la fecha en formato YYYY-MM-DD
}


//====================================================================================================
//====================================== Cargar Datos ================================================
//====================================================================================================

//========================= Listar Ventas ========================= iba a ir al gimnasio en la mañana
function listarVentas(selectedEstado) {
    $.ajax({
        url: "./modules/sales/controller/salesC.php?action=listar",
        type: "POST",
        dataType: "json",
        success: function (response) {
            if ($.fn.DataTable.isDataTable("#tablaventas")) {
                $("#tablaventas").DataTable().clear().destroy();  // Limpia y destruye correctamente
            }
    
            $("#tablaventas tbody").empty();

            let data = response;
            let tableBody = $("#tablaventas tbody");
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
                if (data.estado == 1) { 
                    badge = "warning"; 
                    estadoCounts.pendientes++; 
                    botones = 
                    `
                    <a class="dropdown-item continuar" href="#!">Continuar venta <i class="ph-duotone ph-pencil-line"></i></a>
                    <a class="dropdown-item cancelar" href="#!" style="color:red">Cancelar <i class="ph-duotone ph-trash"></i></a>
                    `
                }
                if (data.estado == 2) { 
                    badge = "info"; 
                    estadoCounts.procesadas++; 
                    botones = 
                    `
                    <a class="dropdown-item nota" href="#!">Nota recepción <i class="ph-duotone ph-file-text"></i></a>
                    <a class="dropdown-item editar" href="#!">Editar venta <i class="ph-duotone ph-gear"></i></a>
                    <a class="dropdown-item rastrear" href="#!">Rastrear <i class="ph-duotone ph-truck"></i></a>
                    <a class="dropdown-item cancelar" href="#!" style="color:red">Cancelar <i class="ph-duotone ph-trash"></i></a>
                    `
                }
                if (data.estado == 3) { 
                    badge = "secondary"; 
                    estadoCounts.cobrando++; 
                    botones =
                    `
                    <a class="dropdown-item pagar" href="#!">Recibir pago <i class="ph-duotone ph-money"></i></a>
                    `
                }
                if (data.estado == 4) { 
                    badge = "success"; 
                    estadoCounts.completadas++; 
                    botones =
                    `
                    <a class="dropdown-item" href="#!">Ver detalle <i class="ph-duotone ph-eye"></i></a>
                    `
                }
                if (data.estado == 5) { 
                    badge = "danger"; 
                    estadoCounts.canceladas++; 
                    botones =
                    `
                    <a class="dropdown-item editar" href="#!">Editar venta <i class="ph-duotone ph-eye"></i></a>
                    `
                }

                tableBody.append(`
                    <tr>
                        <td>${data.idVenta}</td>
                        <td>${data.nombre}</td>
                        <td>${data.nRemision}</td>
                        <td>${data.total_cantidad}</td>
                        <td>${data.placa}</td>
                        <td>${data.nombreconductor}</td>
                        <td>${data.venta_total}</td>
                        <td><span class="badge rounded-pill text-bg-${badge}">${data.descripcionestado}</span></td>
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

            setInterval(function() {
                $(".text-bg-secondary").toggleClass("text-bg-light");
            }, 800);

            // Inicializar la tabla DataTable una vez con opciones personalizadas
            $("#tablaventas").DataTable({
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                "columnDefs": [
                    {
                        "targets": [6], // Índice de la columna que deseas ocultar (empieza desde 0)
                        "visible": false, // Oculta la columna
                        "searchable": false // Opcional: excluye la columna de las búsquedas
                    }
                ]
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

$(".selecttodas").click(function () {
    listarVentas();
});
$(".selectpendientes").click(function () {
    listarVentas(1);
});
$(".selectprocesadas").click(function () {
    listarVentas(2);
});
$(".selectcompletadas").click(function () {
    listarVentas(3);
});
$(".selectcanceladas").click(function () {
    listarVentas(4);
});




//========================= Listar Clientes =========================
function listarClientes() {
    $.ajax({
        url: "./modules/sales/controller/cargarDatos.php?action=listarclientes",
        type: "POST",
        dataType: "json",
        success: function (response) {

            let data = response;
            let select = $("#destinatario");
            select.empty();
            select.append(`<option value="">Seleccionar cliente</option>`);

            $.each(data, function (index, data) {
                select.append(`<option value="${data.idCliente}" 
                    data-precio="${data.precioVenta}"
                    data-direccion="${data.direccion}"
                    data-rtn="${data.rtn}">${data.nombre}</option>`);
            });

        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los Clientes:", error);
            //alert("Error al cargar los Clientes.");
        }
    });
}

//========================= Listar Motivos =========================
function listarmotivos() {
    $.ajax({
        url: "./modules/sales/controller/cargarDatos.php?action=listarmotivostraslado",
        type: "POST",
        dataType: "json",
        success: function (response) {

            let data = response;
            let select = $("#traslado");
            select.empty();
            select.append(`<option value="">Seleccionar motivo</option>`);

            $.each(data, function (index, data) {
                select.append(`<option value="${data.idMotivo}">${data.descripcion}</option>`);
            });

        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los motivos:", error);
            //alert("Error al cargar los motivos.");
        }
    });
}

const OTRO_TRASLADO_ID = 12;

$("#traslado").change(function () {
    let idMotivo = $(this).val();
    const camposRequeridos = ["#otrotraslado", "#nAutorizacion", "#numeracion", "#fechaDocotrotraslado"];

    if (idMotivo == OTRO_TRASLADO_ID) {
        $("#divotrotraslado").show();
        camposRequeridos.forEach(campo => $(campo).attr("required", true));
    } else {
        $("#divotrotraslado").hide();
        camposRequeridos.forEach(campo => {
            $(campo).removeAttr("required").val("");
        });
    }
});

//========================= Listar tipos de certificacion =========================


// Cargar tipos de certificación
function listartipocertificacion() {
    $.ajax({
        url: "./modules/sales/controller/cargarDatos.php?action=listartipoCertificacion",
        type: "POST",
        dataType: "json",
        success: function (response) {
            datosTiposCertificacion = response;
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los tipos de certificación:", error);
        }
    });
}

// Cargar número de certificación
function listarcertificacion() {
    $.ajax({
        url: "./modules/sales/controller/cargarDatos.php?action=listarcertificacion",
        type: "POST",
        dataType: "json",
        success: function (response) {
            datosCertificacion = response;
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los números de certificación:", error);
        }
    });
}

const $tipocertificacion = $('#tipocertificacion');
const $ncertificacion = $('#ncertificacion');
const $resultados = $('#resultados');
const $resultados2 = $('#resultados2');

// Filtrar y mostrar resultados para Tipo de Certificación
$tipocertificacion.on('keyup', function () {
    const inputValue = $tipocertificacion.val().trim();
    $resultados.empty(); // Limpiar resultados previos

    if (!inputValue) return; // Si el campo está vacío, salir

    const filteredData = datosTiposCertificacion.filter(item =>
        item.descripcion?.toLowerCase().includes(inputValue.toLowerCase())
    );

    if (filteredData.length > 0) {
        renderResultados(filteredData, $resultados);
    } else {
        renderSinResultados($resultados, "tipo de certificación");
    }
});

// Filtrar y mostrar resultados para Número de Certificación
$ncertificacion.on('keyup', function () {
    const inputValue = $ncertificacion.val().trim();
    $resultados2.empty(); // Limpiar resultados previos

    if (!inputValue) return; // Si el campo está vacío, salir

    const filteredData = datosCertificacion.filter(item =>
        item.descripcion?.toLowerCase().includes(inputValue.toLowerCase())
    );

    if (filteredData.length > 0) {
        renderResultados(filteredData, $resultados2);
    } else {
        renderSinResultados($resultados2, "número de certificación");
    }
});

// Manejar clic en un resultado de Tipo de Certificación
$resultados.on('click', 'li[data-tipo]', function () {
    const selectedTipo = $(this).data('tipo');
    if (selectedTipo) {
        $tipocertificacion.val(selectedTipo).trigger('input');
        $resultados.empty();
    }
});

// Manejar clic en un resultado de Número de Certificación
$resultados2.on('click', 'li[data-tipo]', function () {
    const selectedTipo = $(this).data('tipo');
    if (selectedTipo) {
        $ncertificacion.val(selectedTipo).trigger('input');
        $resultados2.empty();
    }
});

// Limpiar lista de resultados al cambiar de input
$('input').on('focus', function () {
    $resultados.empty();
    $resultados2.empty();
});

// Función para renderizar resultados filtrados
function renderResultados(data, $container) {
    data.forEach(item => {
        $container.append(`
            <li style="cursor: pointer; padding: 5px; border: 1px solid #ccc;" 
                data-tipo="${item.descripcion}">
                ${item.descripcion}
            </li>
        `);
    });
}

// Función para mostrar mensaje de "sin resultados"
function renderSinResultados($container, tipo) {
    $container.append(`
        <li style="padding: 2px; font-style: italic; border: 1px solid #ccc;">
            No se encontraron coincidencias, por favor complete el ${tipo} manualmente.
        </li>
    `);
}



//========================= Listar Trasportistas =========================

function listarTransportistas() {
    $.ajax({
        url: "./modules/sales/controller/cargarDatos.php?action=listartransportistas",
        type: "POST",
        dataType: "json",
        success: function (response) {
            let data = response;
            let select = $("#transportista");
            select.empty();
            select.append(`<option value="">Seleccionar transportista</option>`);

            $.each(data, function (index, data) {
                select.append(`<option value="${data.idTransportista}" 
                    data-rtn="${data.identificacion}">${data.denominacion}</option>`);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los transportistas:", error);
        }
    });
}

$("#transportista").change(function () {
    let selectedOption = $(this).find("option:selected");
    let rtn = selectedOption.data("rtn");
    let idTransportista = selectedOption.val();
    $("#rtn3").val(rtn);
    listarConductores(idTransportista);
});

//========================= Listar conductores =========================
function listarConductores(idTransportista) {
    $.ajax({
        url: "./modules/sales/controller/cargarDatos.php?action=listarconductores",
        data: { idTransportista: idTransportista },
        type: "POST",
        dataType: "json",
        success: function (response) {
            let data = response;
            let select = $("#conductor");
            select.empty();
            select.append(`<option value="">Seleccionar conductor</option>`);

            $.each(data, function (index, data) {
                select.append(`<option value="${data.idConductor}" 
                    data-rtn="${data.identificacion}">${data.nombre}</option>`);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los conductores:", error);
        }
    });
}

$("#conductor").change(function () {
    let selectedOption = $(this).find("option:selected");
    let rtn = selectedOption.data("rtn");
    $("#rtn4").val(rtn);
});


//========================= Listar vehiculos =========================
let datosVehiculos = [];

// Función para cargar los vehículos
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
    if (selectedPlaca) {
        // Buscar la marca relacionada con la placa seleccionada
        const vehiculo = datosVehiculos.find(item => item.placa === selectedPlaca);
        if (vehiculo) {
            $('#marca').val(vehiculo.marca_nombre); // Colocar la marca en el input
        }
        $placa.val(selectedPlaca).trigger('input');
        $resultadosPlaca.empty(); // Limpiar la lista
    }
});

// Limpiar lista de resultados al cambiar de input
$('input').on('focus', function () {
    $resultadosPlaca.empty();
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
    $resultadosPlaca.append(`
      <li style="padding: 2px; font-style: italic; border: 1px solid #ccc;">
        No se encontraron coincidencias, por favor ingrese la placa manualmente.
      </li>
    `);
}





//====================================================================================================
//====================================== Generar detalle =============================================
//====================================================================================================

//========================= Llenado de detalle ========================= que confuso estuvo esto
let detalles = [];

// Botón para abrir modal y listar productores
$("#nuevodetalle").click(function () {
    $("#seleccionados tbody").empty();
    $("#modalDetalle").modal("show");
});

function listarProductores() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: "./modules/sales/controller/cargarDatos.php?action=listarproductores",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (!response || response.length === 0) {
                    console.warn("No hay productores disponibles.");
                    resolve(); // Resuelve la promesa incluso si no hay datos
                    return;
                }

                let table = $("#disponibles");

                if ($.fn.DataTable.isDataTable("#disponibles")) {
                    table.DataTable().destroy();
                }

                let tableBody = table.find("tbody");
                tableBody.empty();

                $.each(response, function (index, item) {
                    if (item.cantidad_disponible <= 0) return;
                    let existe = detalles.some(det => det.idProductor == item.idProductor);
                    let displayStyle = existe ? 'style="display:none;"' : '';

                    tableBody.append(`
                        <tr ${displayStyle} data-id="${item.idProductor}" data-id="${item.lote}">
                            <td class="d-none">${item.idProductor}</td>
                            <td>${item.nombre}</td>
                            <td>${item.lote}</td>
                            <td>${item.cantidad_disponible}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-primary seleccionar">
                                    <i class="ph-duotone ph-caret-double-right"></i> 
                                </button>
                            </td>
                        </tr>
                    `);
                });

                table.DataTable({
                    responsive: true,
                    destroy: true,
                    autoWidth: false
                });

                resolve(); // Resuelve la promesa cuando todo está listo
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los productores:", error);
                reject(error); // Rechaza la promesa en caso de error
            }
        });
    });
}

// definir el precio de venta
$("#destinatario").change(function () {
    let selectedOption = $(this).find("option:selected");
    precioventa = parseFloat(selectedOption.data("precio"));
    let direccion = selectedOption.data("direccion");
    let rtn = selectedOption.data("rtn");
    $("#destino").val(direccion);
    $("#rtn2").val(rtn);
});

// Seleccionar productor y agregar a la tabla de seleccionados
$(document).on("click", ".seleccionar", function () {
    let tr = $(this).closest("tr");
    let idProductor = tr.find("td:eq(0)").text();
    let nombre = tr.find("td:eq(1)").text();
    let lote = tr.find("td:eq(2)").text();
    let cantidad = parseInt(tr.find("td:eq(3)").text());

    Swal.fire({
        target: document.getElementById('disponibles'),
        title: 'Cantidad',
        html: `Coloque la cantidad que desea usar<br>
               <input id="swal-input1" class="swal2-input" type="number" min="1" max="${cantidad}" value="${cantidad}" >`,
        focusConfirm: false,
        preConfirm: () => {
            let cantidadSeleccionada = parseInt(document.getElementById('swal-input1').value);
            if (!cantidadSeleccionada || cantidadSeleccionada < 1 || cantidadSeleccionada > cantidad) {
                Swal.showValidationMessage('La cantidad debe ser mayor a 0 y menor o igual a la cantidad disponible.');
                return false;
            }
            return [cantidadSeleccionada];
        }
    }).then((result) => {
        if (!result.isConfirmed) return;

        let cantidadSeleccionada = result.value[0];

        tr.hide(); 

        detalles.push({
            idProductor: idProductor,
            nombre: nombre,
            lote:lote,
            cantidad: cantidadSeleccionada
        });

        $("#seleccionados tbody").append(`
            <tr data-id="${idProductor}">
                <td class="d-none">${idProductor}</td>
                <td>${nombre}</td>
                <td>${lote}</td>
                <td>${cantidadSeleccionada}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-danger eliminar">
                        <i class="ph-duotone ph-trash"></i>
                    </button>
                </td>
            </tr>
        `);
        actualizartotalseleccionados();
        
        });
});

function actualizartotalseleccionados() {
    $("#seleccionados tfoot").empty();
        let total = 0;
        $("#seleccionados tbody tr").each(function () {
            total += parseInt($(this).find("td:eq(3)").text());
        });
        $("#seleccionados tfoot").append(`
            <tr>
                <td colspan="2" class="text-end">Total</td>
                <td>${total}</td>
                <td></td>
            </tr>
        `);
}


// Eliminar fila de seleccionados y devolver cantidad a disponibles
// Crear un objeto que recuerde las cantidades iniciales de los productores cargados en la transacción anterior
let cantidadesIniciales = {};  // Ejemplo: {"1": 1000, "2": 500, etc.}

$(document).on("click", ".eliminar", async function () {
    // Llamar a listarProductores y esperar a que termine
    await listarProductores();

    let tr = $(this).closest("tr");
    let idProductor = tr.find("td:eq(0)").text();
    let cantidadEliminada = parseInt(tr.find("td:eq(3)").text());

    // Eliminar fila de seleccionados
    tr.remove();

    // Restaurar cantidad disponible en la tabla de disponibles
    $("#disponibles tbody tr").each(function () {
        let trDisponible = $(this);
        if (trDisponible.find("td:eq(0)").text() == idProductor) {
            let cantidadActual = parseInt(trDisponible.find("td:eq(3)").text());

            // Obtener la cantidad inicial que ya estaba cargada si existe
            let cantidadInicialCargada = cantidadesIniciales[idProductor] || 0;

            // Ajustar cantidad considerando la eliminación y la carga previa
            let nuevaCantidad = cantidadActual - cantidadInicialCargada + cantidadEliminada;

            // Actualizar la cantidad disponible
            trDisponible.find("td:eq(3)").text(nuevaCantidad);
            trDisponible.show();
        }
    });

    // Eliminar el productor de la lista "detalles"
    detalles = detalles.filter(det => det.idProductor != idProductor);

    // Actualizar el total en el pie de la tabla seleccionados
    $("#seleccionados tfoot").empty();
    let total = 0;
    $("#seleccionados tbody tr").each(function () {
        total += parseInt($(this).find("td:eq(3)").text());
    });
    $("#seleccionados tfoot").append(`
        <tr>
            <td colspan="2" class="text-end">Total</td>
            <td>${total}</td>
            <td></td>
        </tr>
    `);
});

//========================= eliminar al cerrar =========================

// Manejar el evento al cerrar el modal
$('#modalDetalle').on('hidden.bs.modal', function () {
    
    listarProductores();
});




// ======================= GUARDAR DETALLE =======================

$("#descripcion").change(function () {
    listarProductores();
    descripcionProducto = $(this).find("option:selected").text();
});

let editandoDetalle = null;
$("#guardardetalle").click(function () {
    $("#formdetallemodal").addClass("was-validated");

    if ($("#formdetallemodal")[0].checkValidity() === false) {
        Swal.fire({
            target: document.getElementById('disponibles'),
            title: 'Campos vacíos',
            text: 'Por favor, seleccione una descripcion.',
        });
        return;
    }

    let total = 0;
    let seleccionados = [];

    // Recorrer la tabla de seleccionados y calcular el total
    $("#seleccionados tbody tr").each(function () {
        let tr = $(this);
        let idProductor = tr.find("td:eq(0)").text();
        let nombre = tr.find("td:eq(1)").text();
        let lote = tr.find("td:eq(2)").text();  
        let cantidad = parseInt(tr.find("td:eq(3)").text());

        if (!isNaN(cantidad)) {
            seleccionados.push({
                idProducto: $("#descripcion").val(),
                idProductor: idProductor,
                nombre: nombre,
                lote: lote,
                cantidad: cantidad
            });
            total += cantidad;
        }
    });

    if (seleccionados.length === 0) {
        Swal.fire({
            target: document.getElementById('disponibles'),
            title: 'Error',
            text: 'Debe seleccionar al menos un productor.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

    let jsonDetalle = JSON.stringify(seleccionados);

    if (editandoDetalle) {
        // Si se está editando, actualizar la fila existente
        editandoDetalle.find("td:eq(3)").text(total);
        editandoDetalle.find(".json-detalle").text(jsonDetalle);
        editandoDetalle = null; // Reiniciar variable
    } else {
        // Si no se está editando, agregar una nueva fila
        let table = $("#tablaDetalle tbody");
        table.append(`
            <tr>
                <td>1</td>
                <td>${descripcionProducto}</td>
                <td>${total}</td>
                <td class="d-none json-detalle">${jsonDetalle}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-warning editardetalle">
                        <i class="ph-duotone ph-pencil-line"></i> 
                    </button>
                    <button type="button" class="btn btn-danger eliminardetalle">
                        <i class="ph-duotone ph-trash"></i> 
                    </button>
                </td>
            </tr>
        `);

    }

    // Limpiar la tabla de seleccionados y cerrar modal
    $("#seleccionados tbody").empty();
    $("#modalDetalle").modal("hide");
    actualizartotal();
});

function actualizartotal() {
    $("#tablaDetalle tbody tr").each(function () {
        valorventa += parseInt($(this).find("td:eq(2)").text())*precioventa;
    });
    let tablefooter = $("#tablaDetalle tfoot");
    tablefooter.empty();
    
    tablefooter.append(`
        <tr>
            <td colspan="2" class="text-end">Total</td>
            <td>${valorventa}</td>
            <td></td>
        </tr>
    `);
}




//====================================================================================================
//====================================== Editar Datos ================================================
//====================================================================================================

// ======================= EDITAR DETALLE =======================
$(document).on("click", ".editardetalle", function () {
    let tr = $(this).closest("tr");
    let jsonDetalle = tr.find(".json-detalle").text();

    if (!jsonDetalle) return;

    let seleccionados = JSON.parse(jsonDetalle);

    // Guardar referencia a la fila que se está editando
    editandoDetalle = tr;

    // Mostrar modal
    $("#modalDetalle").modal("show");
    $("#seleccionados tbody").empty();

    seleccionados.forEach(prod => {
        $("#seleccionados tbody").append(`
            <tr data-id="${prod.idProductor}">
                <td class="d-none">${prod.idProductor}</td>
                <td>${prod.nombre}</td>
                <td>${prod.lote}</td>
                <td>${prod.cantidad}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-danger eliminar">
                        <i class="ph-duotone ph-trash"></i>
                    </button>
                </td>
            </tr>
        `);
    });

    $("#disponibles tbody tr").each(function () {
        let idDisponible = $(this).find("td:eq(0)").text();
        if (seleccionados.some(prod => prod.idProductor == idDisponible)) {
            $(this).hide();
        }
    });
    actualizartotalseleccionados();
});


// ======================= ELIMINAR DETALLE =======================
$(document).on("click", ".eliminardetalle", function () {
    let tr = $(this).closest("tr");
    let jsonDetalle = tr.find(".json-detalle").text();

    if (jsonDetalle) {
        let seleccionados = JSON.parse(jsonDetalle);

        // Restaurar productores en la tabla de disponibles y eliminar del array detalles
        seleccionados.forEach(prod => {
            // Eliminar del array detalles
            detalles = detalles.filter(d => d.idProductor !== prod.idProductor);

            // Restaurar la visibilidad en la tabla de disponibles
            $("#disponibles tbody tr").each(function () {
                let idDisponible = $(this).find("td:eq(0)").text();
                if (idDisponible == prod.idProductor) {
                    $(this).show();
                }
            });
        });
    }

    tr.remove(); // Eliminar la fila de la tablaDetalle
    actualizartotal();
});


//====================================================================================================
//====================================== Guardar Venta ===============================================
//====================================================================================================

$("#procesarventa").click(function () {
    if(idventasedit == 0){
        guardarventa(2);
    }
    else{
        editarventa(2,idventasedit);
    }
});
$("#guardaravance").click(function () {
    if(idventasedit == 0){
        guardarventa(1);
    }
    else{
        editarventa(1,idventasedit);
    }
});


//========================= Guardar Venta =========================
function guardarventa(hacer) {

    Swal.fire({
        title: 'Procesando',
        text: 'Por favor espere...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    let detallesCompletos = [];
    let datos = {};

    $("#contactForm, #formdetalle, #formtransporte").find("input, select").each(function () {
        let input = $(this);
        datos[input.attr("id")] = input.val();
    });
    
    $("#tablaDetalle tbody tr").each(function () {
        let jsonDetalle = $(this).find(".json-detalle").text();
        if (jsonDetalle) {
            let detalle = JSON.parse(jsonDetalle);
            detallesCompletos = detallesCompletos.concat(detalle);
        }
    });

    datos["detalles"] = detallesCompletos;
    datos["hacer"] = hacer;
    datos["valorventa"] = valorventa;
    console.log(datos);

    $.ajax({
        url: "./modules/sales/controller/salesC.php?action=guardar",
        type: "POST",
        data: datos,
        success: function (response) {
            try {
                let res = JSON.parse(response);
                Swal.close();
                if (res.success) {
                    
                    Swal.fire({
                        title: 'Guardado',
                        text: 'La venta ha sido guardada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        $("#addAccountModal").modal("hide");
                        //redirigir a la pagina de ventas
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
//========================= Editar Venta =========================
function editarventa(hacer,idventasedit) {
    Swal.fire({
        title: 'Procesando',
        text: 'Por favor espere...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    let detallesCompletos = [];
    let datos = {};

    $("#contactForm, #formdetalle, #formtransporte").find("input, select").each(function () {
        let input = $(this);
        datos[input.attr("id")] = input.val();
    });
    
    $("#tablaDetalle tbody tr").each(function () {
        let jsonDetalle = $(this).find(".json-detalle").text();
        if (jsonDetalle) {
            let detalle = JSON.parse(jsonDetalle);
            detallesCompletos = detallesCompletos.concat(detalle);
        }
    });

    datos["detalles"] = detallesCompletos;
    datos["hacer"] = hacer;
    datos["idVenta"] = idventasedit;
    datos["idProducto"] = $("#descripcion").val();
    console.log(datos);

    $.ajax({
        url: "./modules/sales/controller/salesC.php?action=editar",
        type: "POST",
        data: datos,
        success: function (response) {
            try {
                let res = JSON.parse(response);
                Swal.close();
    
                if (res.success) {
                    Swal.fire({
                        title: 'Guardado',
                        text: 'La venta ha sido guardada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        $("#addAccountModal").modal("hide");
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


//====================================================================================================
//====================================== Funciones ===================================================
//====================================================================================================

//subir un documento al precionar el boton de clase .nota en una ventana modal
$(document).on("click", ".nota", function () {
    ventaseleccionada = $(this).closest("tr").find("td:eq(0)").text();
    placaseleccionada = $(this).closest("tr").find("td:eq(4)").text();
    console.log(placaseleccionada);
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
    data.append('placa', placaseleccionada);
    data.append('nrecepcion', $("#nrecepcion").val());
    data.append('nrecepcionraqui', $("#nrecepcionraqui").val());
    data.append('file1', $("#filerecepcion")[0].files[0]);
    data.append('file2', $("#filerecepcionraqui")[0].files[0]);

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
                        listarVentas(); // Actualizar la lista de ventas
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

//============================= ver imagenes seleccionadas =============================
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


//====================================================================== recibir pago

$("#tablaventas").on("click", ".pagar", function () {
    ventaseleccionada = $(this).closest("tr").find("td:eq(0)").text();
    //abrir modal
    $("#PagoModal").modal("show");

    let table = $("#tablaventas").DataTable();
    let row = $(this).closest("tr");
    let rowData = table.row(row).data();

    let id = rowData[0];
    let monto = rowData[6];

    $.ajax({
        url: "./modules/sales/controller/accionesVentas.php?action=listarSaldo",
        type: "POST",
        data: { idVenta: id },
        success: function (response) {
            let res = JSON.parse(response);
            let total = res.success[0].total;
            
            if(total > 0){
                $("#todono").prop("checked", true).trigger("change");
                $("#todosi").prop("disabled", true);
            }else{
                $("#todosi").prop("checked", true).trigger("change");
                $("#todosi").prop("disabled", false);
                $("#todono").prop("disabled", false);
            }
            $("#montototal").val(total);
            $("#labelmontoabono").html("Abonado: Lps."+total+" - pendiente Lps."+ (monto-total) );
            $("#labelmonto").html("Monto a abonar: Lps. "+monto);
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los Ventas:", error);
            //alert("Error al cargar los Ventas.");
        }
    });


});

$("#btnguarabono").click(function () {
    $("#addPagoForm").addClass("was-validated");

    /* if ($("#addPagoForm")[0].checkValidity() === false) {
        Swal.fire({
            target: document.getElementById('PagoModal'),
            title: 'Campos vacíos',
            text: 'Por favor, complete los campos requeridos.',
        });
        return;
    } */

    var monto = 0;
    var pago = 0;
    if($("#todosi").is(":checked")){monto =$("#montototal").val();pago=2}else{monto =$("#abono").val();pago=1}

    let data = {
        idVenta: ventaseleccionada,
        monto: monto,
        metodo: $("#metodoPago").val(),
        pago:pago
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
                        listarVentas();
                        $("#PagoModal").modal("hide");
                        $("#addPagoForm").removeClass("was-validated");
                        $("#addPagoForm")[0].reset();
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
                console.error("Error al procesar la respuesta:", e);
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

//cancelar venta
$(document).on("click", ".cancelar", function () {
    ventaseleccionada = $(this).closest("tr").find("td:eq(0)").text();
    var placa = $(this).closest("tr").find("td:eq(4)").text();

    let data = {
        idVenta: ventaseleccionada,
        placa: placa
    };
    Swal.fire({
        title: 'Cancelar venta',
        text: '¿Está seguro de cancelar la venta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "./modules/sales/controller/accionesVentas.php?action=cancelar",
                type: "POST",
                data: data,
                success: function (response) {
                    console.log(response);
                    Swal.fire({
                        title: 'Cancelado',
                        text: 'La venta ha sido cancelada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        listarVentas();
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error al cargar los Ventas:", error);
                    //alert("Error al cargar los Ventas.");
                }
            });
        }
    });
});

//editar venta
$(document).on("click", ".continuar", function () {
    ventaseleccionada = $(this).closest("tr").find("td:eq(0)").text();
    window.location.href = `?module=sales&${ventaseleccionada}`
});
//editar venta
$(document).on("click", ".editar", function () {
    ventaseleccionada = $(this).closest("tr").find("td:eq(0)").text();
    window.location.href = `?module=sales&${ventaseleccionada}`
});
//rastrear venta
$(document).on("click", ".rastrear", function () {
    var placa = $(this).closest("tr").find("td:eq(4)").text();
    window.location.href = `?module=rastrear&${placa}`
});
