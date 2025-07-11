$(document).ready(function() {
    $("#table-IngresoInventario").DataTable({
        columns: [
            { title: "Id", visible: false }, // Oculta la columna Id
            { title: "Cliente" },
            { title: "Estado" },
            { title: "Total" },
            { title: "Fecha", type: 'date-eu' },
            { title: "Financiado" },
            { title: "TotalSalida" },
            { title: "Impuesto" },
            { title: "Comentarios" },
            { title: "Acciones" }
        ],
        order: [[0, 'desc']] // Ordena por Id descendente
    });

    if ($.fn.DataTable.isDataTable("#table-IngresoInventario")) {
        $("#table-IngresoInventario").DataTable().destroy();
    }

    listOuts();
    cargarHistorialSalidas();

    /**
     * !* Actualiza el subtotal y el total al cambiar la cantidad o el precio unitario
     *  
     */
});

function exportToExcel() {
    var table = document.getElementById("tablaHistorialSalidas");
    var wb = XLSX.utils.table_to_book(table, { sheet: "Historial de Salidas" });
    XLSX.writeFile(wb, "HistorialSalidas.xlsx");
}

function listOuts() {
    if ($.fn.DataTable.isDataTable("#table-IngresoInventario")) {
        $("#table-IngresoInventario").DataTable().destroy();
    }

    let tableBody = $("#table-IngresoInventario tbody");
    tableBody.empty();

    $.ajax({
        url: "./modules/inventory/controller/inventoryOut/List_InventoryOuts.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener las salidas:", response.error);
                return;
            }

            let data = response.data;

            $.each(data, function (index, salida) {
                tableBody.append(`
                    <tr>
                        <td style="display:none;">${salida.Id}</td>
                        <td>${salida.NombreCliente ? salida.NombreCliente : "Descargo"}</td>
                        <td class="text-center">
                            <span class="badge ${salida.Estado == 1 ? 'bg-success' : 'bg-danger'}">
                                ${salida.Estado == 1 ? 'Activo' : 'Inactivo'}
                            </span>
                        </td>
                        <td>${Number(salida.TotalSalida).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td>${formatDateDMY(salida.FechaCreacion)}</td>
                        <td class="text-center">
                            <span class="badge ${salida.Financiado == 1 ? 'bg-primary' : 'bg-secondary'}">
                                ${salida.Financiado == 1 ? 'Credito' : 'Contado'}
                            </span>
                        </td>
                        <td class="text-center">
                            ${
                                salida.Financiado == 1
                                    ? `<span class="badge ${salida.PagoCompleto == 1 && salida.PagoCompleto != null ? 'bg-success' : 'bg-warning'}">
                                        ${salida.PagoCompleto == 1 && salida.PagoCompleto != null ? 'PAGADO' : 'PENDIENTE'}
                                    </span>`
                                    : `<span class="badge bg-success">Pagado al contado</span>`
                            }
                        </td>
                        <td>${salida.ImpuestoSalida ? (salida.ImpuestoSalida * 100).toFixed(2) + ' %' : '-'}</td>
                        <td>${salida.Comentarios ? salida.Comentarios : "-"}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm edit-btn" onclick="window.location.href='?module=inventoryOutFlowDetail&id=${salida.Id}'">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${salida.Id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            $("#table-IngresoInventario").DataTable({
                columns: [
                    { title: "Id", visible: false }, // Oculta la columna Id
                    { title: "Cliente" },
                    { title: "Estado" },
                    { title: "Total" },
                    { title: "Fecha", type: 'date-eu' },
                    { title: "Financiado" },
                    { title: "TotalSalida" },
                    { title: "Impuesto" },
                    { title: "Comentarios" },
                    { title: "Acciones" }
                ],
                order: [[0, 'desc']] // Ordena por Id descendente
            });

            $("#table-IngresoInventario").on("click", ".edit-btn", function () {
                let id = $(this).data("id");
                loadEntryForEdit(id);
            });

            $("#table-IngresoInventario").on("click", ".delete-btn", function () {
                let Id = $(this).data("id");
                handleDeleteEntry(Id);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los ingresos:", error);
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al cargar los ingresos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

let allSalidasData = [];
let salidasTable = null;

$(document).ready(function () {
    const today = new Date();
    const lastMonth = new Date();
    lastMonth.setMonth(lastMonth.getMonth() - 1);

    $('#fechaInicioSalidas').val(lastMonth.toISOString().split('T')[0]);
    $('#fechaFinSalidas').val(today.toISOString().split('T')[0]);

    $('#historial-tab').on('shown.bs.tab', function (e) {
        if (allSalidasData.length === 0) {
            cargarHistorialSalidas();
        }
    });

    if ($('#historial-tab').hasClass('active')) {
        cargarHistorialSalidas();
    }
});

function cargarHistorialSalidas() {
    $('#historial').append('<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>');

    $.ajax({
        url: "./modules/inventory/controller/inventoryOut/list_HistoricOuts.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            $('.overlay').remove();

            if (!response.success) {
                console.error("Error al obtener las salidas:", response.error);
                alert("Error al cargar el historial de salidas: " + response.error);
                return;
            }

            allSalidasData = response.data;
            actualizarTablaSalidas(allSalidasData);
            actualizarCardsResumen(allSalidasData);
        },
        error: function (xhr, status, error) {
            $('.overlay').remove();
            console.error("Error en la solicitud AJAX:", status, error);
            alert("Error al cargar el historial de salidas. Por favor, intente nuevamente.");
        }
    });
}

jQuery.extend(jQuery.fn.dataTable.ext.type.order, {
    "date-eu-pre": function (date) {
        if (!date) return 0;
        var eu_date = date.split('/');
        return (eu_date[2] + eu_date[1].padStart(2, '0') + eu_date[0].padStart(2, '0')) * 1;
    }
});

function actualizarTablaSalidas(data) {
    const rows = data.map(item => {
        const cantidad = parseFloat(item.CantidadSalida) || 0;
        const precio = parseFloat(item.PrecioSalida) || 0;
        const total = precio * cantidad;

        return [
            item.descripcionTipoSalida || '-',
            formatDateForDisplay(item.FechaSalida),
            item.NombreBodega || '-',
            item.NombreArticulo || '-',
            item.Categoria || '-',
            item.SubCategoria || '-',
            cantidad.toFixed(2),
            formatPrice(precio),
            formatPrice(total)
        ];
    });

    if (!salidasTable) {
        salidasTable = $('#tablaHistorialSalidas').DataTable({
            data: rows,
            columns: [
                { title: "Tipo salida" },
                { title: "Fecha", type: 'date-eu' },
                { title: "Bodega" },
                { title: "Artículo" },
                { title: "Categoría" },
                { title: "Subcategoría" },
                { title: "Cantidad", className: "dt-right" },
                { title: "Precio Unitario", className: "dt-right" },
                { title: "Total", className: "dt-right" }
            ],
            dom: '<"top"<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>><"row"<"col-sm-12"tr>><"bottom"<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>><"row"<"col-sm-12"B>>>',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-excel"></i> Excel',
                    className: 'buttons-excel',
                    title: 'Historial de Salidas',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-pdf"></i> PDF',
                    className: 'buttons-pdf',
                    title: 'Historial de Salidas',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Imprimir',
                    className: 'buttons-print',
                    title: 'Historial de Salidas',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100, 1000],
            order: [[1, 'desc']],
            initComplete: function() {
                actualizarFooterHistorialSalidas(data);
            }
        });
    } else {
        salidasTable.clear();
        salidasTable.rows.add(rows).draw();
        actualizarFooterHistorialSalidas(data);
    }
}

function actualizarCardsResumen(data) {
    console.log("Actualizando cards con:", data);

    if (!data || data.length === 0) {
        $('#totalSalidasCard').text('0');
        $('#valorTotalCard').text('L 0.00');
        $('#salidaPromedioCard').text('0');
        $('#articuloTopCard').text('-');
        return;
    }

    let totalSalidas = 0;
    let valorTotal = 0;
    const articulosMap = {};

    data.forEach(item => {
        const cantidad = parseFloat(item.CantidadSalida) || 0;
        const precio = parseFloat(item.PrecioSalida) || 0;

        totalSalidas += cantidad;
        valorTotal += cantidad * precio;

        if (articulosMap[item.NombreArticulo]) {
            articulosMap[item.NombreArticulo] += cantidad;
        } else {
            articulosMap[item.NombreArticulo] = cantidad;
        }
    });

    let articuloTop = '-';
    let maxCantidad = 0;
    for (const [articulo, cantidad] of Object.entries(articulosMap)) {
        if (cantidad > maxCantidad) {
            maxCantidad = cantidad;
            articuloTop = articulo;
        }
    }

    $('#totalSalidasCard').text(totalSalidas.toFixed(2));
    $('#valorTotalCard').text(formatPrice(valorTotal));
    $('#salidaPromedioCard').text((totalSalidas / data.length).toFixed(2));
    $('#articuloTopCard').text(articuloTop);

    console.log("Cards actualizadas:", {totalSalidas, valorTotal, articuloTop});
}

function actualizarFooterHistorialSalidas(data) {
    let totalCantidad = 0;
    let totalPrecio = 0;

    data.forEach(item => {
        const cantidad = parseFloat(item.CantidadSalida) || 0;
        const precio = parseFloat(item.PrecioSalida) || 0;

        totalCantidad += cantidad;
        totalPrecio += precio * cantidad;
    });

    $('#footerCantidadHistorialSalida').text(totalCantidad.toFixed(2));
    $('#footerTotalSalida').text(formatPrice(totalPrecio));
}

function filtrarSalidas() {
    let fechaInicio = $('#fechaInicioSalidas').val();
    let fechaFin = $('#fechaFinSalidas').val();

    let filteredData = allSalidasData.filter(item => {
        if (!item.FechaSalida) return false;

        const fechaSalida = new Date(item.FechaSalida);
        const fechaSalidaDate = fechaSalida.toISOString().split('T')[0];

        if (!fechaInicio && !fechaFin) return true;

        const cumpleInicio = !fechaInicio || fechaSalidaDate >= fechaInicio;
        const cumpleFin = !fechaFin || fechaSalidaDate <= fechaFin;

        return cumpleInicio && cumpleFin;
    });

    actualizarTablaSalidas(filteredData);
    actualizarCardsResumen(filteredData);
}

function limpiarFiltrosSalidas() {
    $('#fechaInicioSalidas').val('');
    $('#fechaFinSalidas').val('');
    actualizarTablaSalidas(allSalidasData);
    actualizarCardsResumen(allSalidasData);
}

function exportToExcelSalidas() {
    if (salidasTable) {
        salidasTable.button('.buttons-excel').trigger();
    }
}

function formatDateForDisplay(dateString) {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-HN');
    } catch (e) {
        console.error("Error formateando fecha:", dateString, e);
        return dateString;
    }
}

function formatPrice(value) {
    const numValue = parseFloat(value) || 0;
    return 'L. ' + numValue.toFixed(2);
}

function formatDateDMY(dateString) {
    if (!dateString) return '';
    if (/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString)) {
        return dateString;
    }
    const date = new Date(dateString);
    if (isNaN(date)) return dateString;
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}