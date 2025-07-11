  let kardexTable = null;
    let historyTable = null;
    let allKardexData = [];
    let currentProductId = null;


    function formatPrice(value, currency = 'L') {
        return `${currency} ${parseFloat(value).toLocaleString('es-HN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })}`;
    }


    function formatDateForDisplay(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('es-HN') + ' ' + date.toLocaleTimeString('es-HN');
    }


    function buscarKardex() {
        const productoId = $('#selectProducto').val();

        if (!productoId) {
            alert('Por favor, seleccione un producto.');
            return;
        }

        if (currentProductId === productoId && allKardexData.length > 0) {
            aplicarFiltros();
            return;
        }


        $('#table-Kardex').closest('.card').append('<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>');

        $.ajax({
            url: './modules/inventory/controller/kardex/list_KardexPerProduct.php',
            type: 'GET',
            data: {
                productoId: productoId
            },
            dataType: 'json',
            success: function(response) {
                $('.overlay').remove();

                if (!Array.isArray(response)) {
                    alert('Error en el formato de los datos recibidos.');
                    return;
                }

                allKardexData = response;
                currentProductId = productoId;
                aplicarFiltros();
            },
            error: function(xhr, status, error) {
                $('.overlay').remove();
                console.error("Error al cargar kardex:", error);
                alert('Error al cargar los datos del kardex. Por favor, intente nuevamente.');
            }
        });
    }


    function aplicarFiltros() {
        let fechaInicio = $('#fechaInicio').val();
        let fechaFin = $('#fechaFin').val();

        let filteredData = allKardexData.filter(item => {
            if (!item.FechaMovimiento) return false;

            const fechaMovimiento = new Date(item.FechaMovimiento);
            const fechaMovimientoDate = fechaMovimiento.toISOString().split('T')[0];

            if (!fechaInicio && !fechaFin) return true;

            const cumpleInicio = !fechaInicio || fechaMovimientoDate >= fechaInicio;
            const cumpleFin = !fechaFin || fechaMovimientoDate <= fechaFin;

            return cumpleInicio && cumpleFin;
        });

        let totals = calcularTotales(filteredData);
        actualizarResumen(totals);
        actualizarDataTable(filteredData, totals);
    }


    function calcularTotales(data) {
        let totals = {
            entrada: 0,
            precioEntrada: 0,
            salida: 0,
            precioSalida: 0,
            saldoFinal: 0,
            costoPromedioFinal: 0
        };

        if (data.length === 0) return totals;

        data.forEach(item => {
            const cantidadEntrada = parseFloat(item.CantidadEntrada) || 0;
            const precioEntrada = parseFloat(item.PrecioEntrada) || 0;
            const cantidadSalida = parseFloat(item.CantidadSalida) || 0;
            const precioSalida = parseFloat(item.PrecioSalida) || 0;

            totals.entrada += cantidadEntrada;
            totals.precioEntrada += cantidadEntrada * precioEntrada;
            totals.salida += cantidadSalida;
            totals.precioSalida += cantidadSalida * precioSalida;
        });

        const lastItem = data[data.length - 1];
        totals.saldoFinal = parseFloat(lastItem.SaldoCantidad) || 0;
        totals.costoPromedioFinal = parseFloat(lastItem.CostoPromedio) || 0;

        return totals;
    }


    function actualizarResumen(totals) {
        $('#totalEntrada').text(totals.entrada.toFixed(2));
        $('#totalPrecioEntrada').text(formatPrice(totals.precioEntrada));
        $('#totalSalida').text(totals.salida.toFixed(2));
        $('#totalPrecioSalida').text(formatPrice(totals.precioSalida));
        $('#saldoFinal').text(totals.saldoFinal.toFixed(2));
        $('#costoPromedioFinal').text(formatPrice(totals.costoPromedioFinal));

        const balanceTotal = totals.precioEntrada - totals.precioSalida;
        const valoracionActual = totals.saldoFinal * totals.costoPromedioFinal;

        $('#balanceTotal').text(formatPrice(balanceTotal));
        $('#stockActual').text(totals.saldoFinal.toFixed(2) + ' unidades');
        $('#valoracionActual').text(formatPrice(valoracionActual));

        $('#resumenKardex, #balanceResumen').show();
    }


    function actualizarDataTable(data, totals) {
        const rows = data.map(item => {
            const cantidadEntrada = parseFloat(item.CantidadEntrada) || 0;
            const precioEntrada = parseFloat(item.PrecioEntrada) || 0;
            const cantidadSalida = parseFloat(item.CantidadSalida) || 0;
            const precioSalida = parseFloat(item.PrecioSalida) || 0;
            const fechaMovimiento = formatDateForDisplay(item.FechaMovimiento);

            return [
                item.NombreArticulo || '',
                fechaMovimiento,
                item.TipoMovimiento || '',
                cantidadEntrada.toFixed(2),
                precioEntrada ? formatPrice(precioEntrada) : formatPrice(0),
                cantidadSalida.toFixed(2),
                precioSalida ? formatPrice(precioSalida) : formatPrice(0),
                (item.SaldoCantidad ? parseFloat(item.SaldoCantidad) : 0).toFixed(2),
                (item.CostoPromedio ? parseFloat(item.CostoPromedio) : 0) ? formatPrice(item.CostoPromedio) : formatPrice(0)
            ];
        });

        if (kardexTable) {
            kardexTable.clear();
            kardexTable.rows.add(rows).draw();
            $('#table-Kardex tbody tr').each(function(index) {
                const tipoMovimiento = (kardexTable.row(this).data()[2] || '').toString().toLowerCase().trim();

                if (tipoMovimiento === 'ingreso') {
                    $(this).addClass('ingreso-row');
                } else if (tipoMovimiento === 'salida') {
                    $(this).addClass('salida-row');
                }


            });
        } else {
            kardexTable = $('#table-Kardex').DataTable({
                data: rows,
                columns: [{
                        title: "Nombre Artículo"
                    },
                    {
                        title: "Fecha Movimiento",
                        type: 'date',
                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                return data;
                            }
                            return new Date(data).getTime();
                        }
                    },
                    {
                        title: "Tipo Movimiento"
                    },
                    {
                        title: "Cantidad Entrada",
                        className: "dt-right"
                    },
                    {
                        title: "Precio Entrada",
                        className: "dt-right"
                    },
                    {
                        title: "Cantidad Salida",
                        className: "dt-right"
                    },
                    {
                        title: "Precio Salida",
                        className: "dt-right"
                    },
                    {
                        title: "Saldo Cantidad",
                        className: "dt-right"
                    },
                    {
                        title: "Costo Promedio",
                        className: "dt-right"
                    }
                ],
                createdRow: function(row, data, rowIndex) {
                    const tipoMovimiento = (data[2] || '').toLowerCase().trim();
                    console.log("Tipo Movimiento:", tipoMovimiento); // Depuración

                    if (tipoMovimiento === 'ingreso') {
                        $(row).addClass('ingreso-row');
                    } else if (tipoMovimiento === 'salida') {
                        $(row).addClass('salida-row');
                    }


                },
                dom: '<"row mb-2"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip<"row mt-2"<"col-sm-12 col-md-6"B>>',
                buttons: configurarBotonesExportacion(totals),
              
                responsive: true,
                ordering: true,
                paging: true,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [
                    [1, 'desc']
                ]
            });
        }

        actualizarFooter(totals);
    }


    function configurarBotonesExportacion(totals) {
        return [{
                extend: 'excel',
                text: '<i class="bi bi-file-excel"></i> Excel',
                className: 'buttons-excel',
                title: 'Kardex del Producto',
                messageTop: generarTituloReporte(),
                exportOptions: {
                    columns: ':visible',
                    modifier: {
                        page: 'all'
                    }
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row:last c', sheet).attr('s', '25');
                }
            },
            {
                extend: 'pdf',
                text: '<i class="bi bi-file-pdf"></i> PDF',
                className: 'buttons-pdf',
                title: 'Kardex del Producto',
                messageTop: generarTituloReporte(),
                exportOptions: {
                    columns: ':visible',
                    modifier: {
                        page: 'all'
                    }
                },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    doc.content[1].table.body.push([{
                            text: 'Totales:',
                            bold: true,
                            colSpan: 3,
                            alignment: 'right'
                        },
                        {},
                        {},
                        {
                            text: totals.entrada.toFixed(2),
                            bold: true
                        },
                        {
                            text: formatPrice(totals.precioEntrada),
                            bold: true
                        },
                        {
                            text: totals.salida.toFixed(2),
                            bold: true
                        },
                        {
                            text: formatPrice(totals.precioSalida),
                            bold: true
                        },
                        {
                            text: totals.saldoFinal.toFixed(2),
                            bold: true
                        },
                        {
                            text: formatPrice(totals.costoPromedioFinal),
                            bold: true
                        }
                    ]);
                }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer"></i> Imprimir',
                className: 'buttons-print',
                title: 'Kardex del Producto',
                messageTop: '<h3>' + generarTituloReporte() + '</h3>',
                exportOptions: {
                    columns: ':visible',
                    modifier: {
                        page: 'all'
                    }
                },
                customize: function(win) {
                    $(win.document.body).find('h1').css('text-align', 'center');
                    $(win.document.body).find('table')
                        .append('<tr><th colspan="3" style="text-align:right">Totales:</th>' +
                            '<th>' + totals.entrada.toFixed(2) + '</th>' +
                            '<th>' + formatPrice(totals.precioEntrada) + '</th>' +
                            '<th>' + totals.salida.toFixed(2) + '</th>' +
                            '<th>' + formatPrice(totals.precioSalida) + '</th>' +
                            '<th>' + totals.saldoFinal.toFixed(2) + '</th>' +
                            '<th>' + formatPrice(totals.costoPromedioFinal) + '</th></tr>');
                }
            }
        ];
    }


    function generarTituloReporte() {
        const producto = $('#selectProducto option:selected').text();
        let rangeText = '';
        if ($('#fechaInicio').val() && $('#fechaFin').val()) {
            rangeText = ` (Del ${$('#fechaInicio').val()} al ${$('#fechaFin').val()})`;
        }
        return `Kardex de ${producto}${rangeText}`;
    }


    function actualizarFooter(totals) {
        $('#footerEntrada').text(totals.entrada.toFixed(2));
        $('#footerPrecioEntrada').text(formatPrice(totals.precioEntrada));
        $('#footerSalida').text(totals.salida.toFixed(2));
        $('#footerPrecioSalida').text(formatPrice(totals.precioSalida));
        $('#footerSaldo').text(totals.saldoFinal.toFixed(2));
        $('#footerCostoPromedio').text(formatPrice(totals.costoPromedioFinal));
    }


    function cargarProductos() {
        $.ajax({
            url: "./modules/inventory/controller/inventoryEntry/list_ActiveArticles.php",
            type: "GET",
            dataType: "json",
            success: function(response) {
                const selectProducto = $('#selectProducto');
                selectProducto.empty().append('<option value="">-- Seleccione un producto --</option>');

                if (response && Array.isArray(response.data)) {
                    response.data.forEach(producto => {
                        selectProducto.append(`<option value="${producto.id}">${producto.NombreArticulo}</option>`);
                    });
                }

                selectProducto.select2({
                    placeholder: "-- Seleccione un producto --",
                    allowClear: true,
                    width: '100%'
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar productos:", error);
                alert("Error al cargar la lista de productos.");
            }
        });
    }



    $(document).ready(function() {

        const today = new Date();
        const lastMonth = new Date();
        lastMonth.setMonth(lastMonth.getMonth() - 1);

        $('#fechaInicio').val(lastMonth.toISOString().split('T')[0]);
        $('#fechaFin').val(today.toISOString().split('T')[0]);


        $('#fechaInicio, #fechaFin').change(function() {
            if (allKardexData.length > 0) {
                aplicarFiltros();
            }
        });


        historyTable = $('#table-Historial').DataTable({
         
            dom: '<"top"<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>><"row"<"col-sm-12"tr>><"bottom"<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>><"row"<"col-sm-12"B>>>',
            buttons: [{
                    extend: 'excel',
                    text: '<i class="bi bi-file-excel"></i> Excel',
                    className: 'buttons-excel',
                    title: 'Historial de Transacciones',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-pdf"></i> PDF',
                    className: 'buttons-pdf',
                    title: 'Historial de Transacciones',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Imprimir',
                    className: 'buttons-print',
                    title: 'Historial de Transacciones',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [
                [0, 'desc']
            ],
            serverSide: false, 
            processing: true, 
            initComplete: function() {
                
                const today = new Date();
                const lastMonth = new Date();
                lastMonth.setMonth(lastMonth.getMonth() - 1);

                $('#fechaInicioHistorial').val(lastMonth.toISOString().split('T')[0]);
                $('#fechaFinHistorial').val(today.toISOString().split('T')[0]);
            }
        });

        cargarProductos();
        cargarHistorialTransacciones()
    });

    function exportToExcel() {

        var table = document.getElementById("table-Kardex");


        var wb = XLSX.utils.table_to_book(table, {
            sheet: "Registro de entradas y salidas"
        });


        XLSX.writeFile(wb, "Kardex.xlsx");
    }

    /**
     * Seccion para el Historial de Transacciones
     * Se carga la tabla de transacciones y se configura el DataTable
     */

    function actualizarHistorialTable(data) {
        const rows = data.map(item => {

            const cantidadIngreso = parseFloat(item.CantidadEntrada) || 0;
            const cantidadSalida = parseFloat(item.CantidadSalida) || 0;
            const precioEntrada = parseFloat(item.PrecioEntrada) || 0;
            const precioSalida = parseFloat(item.PrecioSalida) || 0;
            const total = parseFloat(item.Total) || 0;
            const fechaMovimiento = formatDateForDisplay(item.FechaMovimiento);

            return [

                fechaMovimiento,
                item.TipoMovimiento || '',
                item.NombreArticulo || '',
                item.NombreBodega || '',
                cantidadIngreso.toFixed(2),
                cantidadSalida.toFixed(2),
                precioEntrada ? formatPrice(precioEntrada) : formatPrice(0),
                precioSalida ? formatPrice(precioSalida) : formatPrice(0),
                formatPrice(total)
            ];
        });

        if (historyTable) {
            historyTable.clear();
            historyTable.rows.add(rows).draw();

            $('#table-Historial tbody tr').each(function() {
                const tipoMovimiento = (historyTable.row(this).data()[1] || '').toString().toLowerCase().trim();

                if (tipoMovimiento.includes('ingreso')) {
                    $(this).addClass('ingreso-row');
                } else if (tipoMovimiento.includes('salida')) {
                    $(this).addClass('salida-row');
                }
            });
        }

        actualizarResumenHistorial(data);
        actualizarFooterHistorial(data);
    }

    function actualizarResumenHistorial(data) {
        let totalEntradas = 0;
        let totalSalidas = 0;
        let valorEntradas = 0;
        let valorSalidas = 0;

        data.forEach(item => {
            const cantidad = parseFloat(item.CantidadEntrada) || 0;
            const cantidadSalidas = parseFloat(item.CantidadSalida) || 0;
            const precioUnitario = parseFloat(item.PrecioEntrada) || 0;
            const precioUnitarioSalidas = parseFloat(item.PrecioSalida) || 0;
            const totalEntrada = cantidad * precioUnitario;
            const totalSalida = cantidadSalidas * precioUnitarioSalidas;

            if (item.TipoMovimiento.toLowerCase().includes('ingreso')) {
                totalEntradas += cantidad;
                valorEntradas += totalEntrada;
            } else if (item.TipoMovimiento.toLowerCase().includes('salida')) {
                totalSalidas += cantidadSalidas;
                valorSalidas += totalSalida;
            }
        });

        $('#totalEntradasHistorial').text(totalEntradas.toFixed(2));
        $('#totalDiferencias').text(formatPrice(valorEntradas - valorSalidas));
        $('#valorTotalEntradas').text(formatPrice(valorEntradas));
        $('#valorTotalSalidas').text(formatPrice(valorSalidas));
    }


    function actualizarFooterHistorial(data) {
        let totalCantidad = 0;
        let totalGeneral = 0;
        let totalCantidadSalida = 0;
        let totalGeneralSalida = 0;

        data.forEach(item => {
            const cantidadEntrada = parseFloat(item.CantidadEntrada) || 0;
            const cantidadSalida = parseFloat(item.CantidadSalida) || 0;
            const precioUnitario = parseFloat(item.PrecioEntrada) || 0;
            const precioUnitarioSalida = parseFloat(item.PrecioSalida) || 0;

            totalCantidad += cantidadEntrada;
            totalGeneral += cantidadEntrada * precioUnitario;

            totalCantidadSalida += cantidadSalida;
            totalGeneralSalida += cantidadSalida * precioUnitarioSalida;
        });

        $('#footerCantidadHistorialIngreso').text(totalCantidad.toFixed(2));
        $('#footerCantidadHistorialSalida').text(totalCantidadSalida.toFixed(2));
        $('#footerTotalHistorialIngreso').text(formatPrice(totalGeneral));
        $('#footerTotalSalida').text(formatPrice(totalGeneralSalida));
    }


    function filtrarHistorial() {
        let fechaInicio = $('#fechaInicioHistorial').val();
        let fechaFin = $('#fechaFinHistorial').val();

        let filteredData = allHistoryData.filter(item => {
            if (!item.FechaMovimiento) return false;

            const fechaMovimiento = new Date(item.FechaMovimiento);
            const fechaMovimientoDate = fechaMovimiento.toISOString().split('T')[0];

            if (!fechaInicio && !fechaFin) return true;

            const cumpleInicio = !fechaInicio || fechaMovimientoDate >= fechaInicio;
            const cumpleFin = !fechaFin || fechaMovimientoDate <= fechaFin;

            return cumpleInicio && cumpleFin;
        });

        actualizarHistorialTable(filteredData);
    }

    function limpiarFiltrosHistorial() {
        $('#fechaInicioHistorial').val('');
        $('#fechaFinHistorial').val('');
        actualizarHistorialTable(allHistoryData);
    }

    function cargarHistorialTransacciones() {
        $.ajax({
            url: "./modules/inventory/controller/kardex/list_HistoryTransactions.php",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response && Array.isArray(response.data)) {
                    allHistoryData = response.data;
                    actualizarHistorialTable(allHistoryData);
                } else {
                    console.error("Formato de respuesta inválido:", response);
                    alert("Error en el formato de los datos recibidos.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar el historial de transacciones:", error);
                alert("Error al cargar el historial de transacciones.");
            }
        });
    }

    function exportToExcel() {
        var table = document.getElementById("table-Historial");
        var wb = XLSX.utils.table_to_book(table, {
            sheet: "Historial de Transacciones"
        });
        XLSX.writeFile(wb, "Historial_Transacciones.xlsx");
    }