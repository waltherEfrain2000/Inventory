$(document).ready(function() {
        // Mostrar overlay de carga
        $('body').append('<div class="loading-overlay"><div class="spinner-border" role="status"></div></div>');

        let table;
        const API_URL = './modules/inventory/controller/kardex/list_GeneralInventory.php';

        // Configuración DataTable
        function initDataTable(data) {
            if ($.fn.DataTable.isDataTable('#table-inventory')) {
                table.destroy();
            }

            table = $('#table-inventory').DataTable({
                data: data,
                columns: [{
                        data: 'NombreArticulo'
                    },
                    {
                        data: 'NombreBodega',
                        render: function(data, type, row) {
                            return data || 'Sin bodega';
                        }
                    },
                    {
                        data: 'UnidadMedida',
                        render: function(data, type, row) {
                            return `${data || 'N/A'}${row.AbreviaturaUnidad ? ` (${row.AbreviaturaUnidad})` : ''}`;
                        }
                    },
                    {
                        data: 'Existencia',
                        render: function(data) {
                            const stock = parseFloat(data) || 0;
                            let badge = 'badge-stock';
                            if (stock < 10 && stock > 0) badge = 'badge-low';
                            if (stock <= 0) badge = 'badge-out';
                            return `<span class="badge ${badge}">${stock.toLocaleString('es-HN')}</span>`;
                        }
                    },
                    {
                        data: 'PrecioCompra',
                        render: function(data) {
                            const price = parseFloat(data) || 0;
                            return `L ${price.toLocaleString('es-HN', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}`;
                        }
                    },
                    {
                        data: 'PrecioVenta',
                        render: function(data) {
                            const price = parseFloat(data) || 0;
                            return `L ${price.toLocaleString('es-HN', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}`;
                        }
                    },
                    {
                        data: null,
                        render: function(_, __, row) {
                            const profit = calculateProfit(row.PrecioCompra, row.PrecioVenta);
                            return `<span class="badge badge-profit">${profit}%</span>`;
                        }
                    },
                    {
                        data: null,
                        render: function(_, __, row) {
                            const total = (parseFloat(row.Existencia) || 0) * (parseFloat(row.PrecioCompra) || 0);
                            return `L ${total.toLocaleString('es-HN', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}`;
                        }
                    },
                    {
                        data: null,
                        render: function(_, __, row) {
                            const projection = (parseFloat(row.Existencia) || 0) * (parseFloat(row.PrecioVenta) || 0);
                            return `L ${projection.toLocaleString('es-HN', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })}`;
                        }
                    },
                    {
                        data: null,
                        render: function() {
                            return '<span class="badge bg-success">Activo</span>';
                        }
                    }
                ],
                dom: '<"row"<"col-md-6"B><"col-md-6"f>>rtip',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel"></i> Exportar Excel',
                        className: 'btn btn-success',
                        title: 'Inventario General',
                        filename: 'Inventario_' + new Date().toISOString().split('T')[0],
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                body: function(data, row, column, node) {
                                    // Eliminar el símbolo L para el Excel
                                    return $(node).text().replace('L ', '');
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer"></i> Imprimir',
                        className: 'btn btn-secondary'
                    }
                ],
                responsive: true,
                paging: true,
                lengthMenu: [10, 25, 50, 100],
                initComplete: function() {
                    $('.loading-overlay').remove();
                }
            });
        }

        // Calcular porcentaje de ganancia
        function calculateProfit(buyPrice, sellPrice) {
            const buy = parseFloat(buyPrice) || 0;
            const sell = parseFloat(sellPrice) || 0;
            if (buy <= 0) return 0;
            const profit = ((sell - buy) / buy) * 100;
            return profit.toFixed(2);
        }

        // Cargar datos iniciales
        function loadData() {
            $.ajax({
                url: API_URL,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && Array.isArray(response.data)) {
                        initDataTable(response.data);
                        updateCards(response.data);
                        loadFilters(response.data);
                    } else {
                        console.error('Error en respuesta:', response);
                        alert('Error en formato de datos');
                    }
                },
                error: function(xhr, status, error) {
                    $('.loading-overlay').remove();
                    console.error("Error AJAX:", error);
                    alert('Error cargando datos');
                }
            });
        }

        // Actualizar tarjetas
        function updateCards(data) {
            const products = new Set(data.map(item => item.idArticulo)).size;
            const stock = data.reduce((sum, item) => sum + (parseFloat(item.Existencia) || 0), 0);
            const value = data.reduce((sum, item) => sum + ((parseFloat(item.Existencia) || 0) * (parseFloat(item.PrecioCompra) || 0)), 0);
            const warehouses = new Set(data.map(item => item.idBodega)).size;

            // Calcular ganancia promedio
            let totalProfit = 0;
            let itemsWithProfit = 0;
            data.forEach(item => {
                const buyPrice = parseFloat(item.PrecioCompra) || 0;
                if (buyPrice > 0) {
                    totalProfit += parseFloat(calculateProfit(item.PrecioCompra, item.PrecioVenta));
                    itemsWithProfit++;
                }
            });
            const avgProfit = itemsWithProfit > 0 ? (totalProfit / itemsWithProfit) : 0;

            // Calcular proyección de ventas
            const projectedSales = data.reduce((sum, item) => sum + ((parseFloat(item.Existencia) || 0) * (parseFloat(item.PrecioVenta) || 0)), 0);

            $('#total-products').text(products.toLocaleString('es-HN'));
            $('#total-stock').text(stock.toLocaleString('es-HN'));
            $('#total-value').html(`L ${value.toLocaleString('es-HN', {minimumFractionDigits: 2})}`);
            $('#total-warehouses').text(warehouses.toLocaleString('es-HN'));
            $('#total-profit').text(avgProfit.toFixed(2) + '%');
            $('#total-projected').html(`L ${projectedSales.toLocaleString('es-HN', {minimumFractionDigits: 2})}`);
        }

        // Cargar filtros
        function loadFilters(data) {
            const warehouses = [...new Map(data.map(item => [item.idBodega, {
                id: item.idBodega,
                name: item.NombreBodega || 'Sin bodega'
            }])).values()];

            $('#filter-warehouse').empty().append('<option value="">Todas las bodegas</option>');
            warehouses.forEach(warehouse => {
                $('#filter-warehouse').append(`<option value="${warehouse.id}">${warehouse.name}</option>`);
            });
        }

        // Aplicar filtros
        $('#btn-filter').click(function() {
            const warehouseId = $('#filter-warehouse').val();
            if (warehouseId) {
                table.column(1).search('^' + $('#filter-warehouse option:selected').text() + '$', true, false).draw();
            } else {
                table.column(1).search('').draw();
            }
            updateCards(table.rows({
                search: 'applied'
            }).data().toArray());
        });

        $('#btn-reset').click(function() {
            $('#filter-warehouse').val('');
            table.search('').columns().search('').draw();
            updateCards(table.rows().data().toArray());
        });

        // Botón de exportación adicional
        $('#btn-export-excel').click(function() {
            $('.buttons-excel').click();
        });

        // Inicializar
        loadData();
    });
    function exportToExcel() {
 
        var table = document.getElementById("table-inventory");

 
        var wb = XLSX.utils.table_to_book(table, { sheet: "Registro de inventario" });

   
        XLSX.writeFile(wb, "Inventario.xlsx");
}
