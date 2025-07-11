let datosInventario = [];
    let datosMantenimientos = [];
    let datosEntregas = [];
    let tablaInventario;
    let tablaMantenimientos;
    let tablaEntregasdatosEntregas;

    let datosSemilla = [];
    let tablaSemilla;

    $(function() {
        // Inicializa DataTable vacío
        tablaInventario = $('#tabla-inventario').DataTable({

            responsive: true,
            destroy: true,
            data: [],
            columns: [{
                    data: 'FechaCreacion'
                },
                {
                    data: 'NumeroFactura'
                },
                {
                    data: 'NombreArticulo'
                },
                {
                    data: 'NombreCategoria'
                },
                {
                    data: 'nombreSubCategoria'
                },
                {
                    data: 'NombreBodega'
                },
                {
                    data: 'CantidadIngreso'
                },
                {
                    data: 'PrecioCompra'
                },
                {
                    data: 'SubTotal'
                }
            ]
        });


        tablaVehiculos = $('#tabla-vehiculos').DataTable({
            responsive: true,
            destroy: true,
            data: [],
            columns: [{
                    data: 'FechaCreacion'
                },
                {
                    data: 'NombreArticulo'
                },
                {
                    data: 'CantidadSalida'
                },
                {
                    data: 'placa'
                },
                {
                    data: 'TipoVehiculo'
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: '<i class="bi bi-file-earmark-excel"></i> Exportar Excel',
                title: 'Reporte Insumos Vehículos',
                className: 'btn btn-success',
                exportOptions: {
                    columns: ':visible'
                }
            }]
        });



        tablaSemilla = $('#tabla-semilla').DataTable({
            responsive: true,
            destroy: true,
            data: [],
            columns: [{
                    data: 'FechaCreacion'
                },
                {
                    data: 'NombreCliente'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                       if (row.Financiado) {
                            return 'Crédito';
                        } else  {
                            return 'Contado';
                        }
                    }
                },
                {
                    data: 'NombreCategoria'
                },
                {
                    data: 'NombreArticulo'
                },
                {
                    data: 'NombreBodega'
                },
                {
                    data: 'CantidadSalida'
                },
                {
                    data: 'PrecioSalida'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return (row.CantidadSalida * row.PrecioSalida).toFixed(2);
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: '<i class="bi bi-file-earmark-excel"></i> Exportar Excel',
                title: 'Reporte Inventario Semilla',
                className: 'btn btn-success',
                exportOptions: {
                    columns: ':visible'
                }
            }]
        });
        // Carga inicial de todos los datos
        function cargarDatosIniciales() {
            $.ajax({
                url: './modules/inventory/controller/inventoryEntry/get_ReportGeneral.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    datosInventario = response.data || [];
                    actualizarTabla(datosInventario);
                    cargarFiltrosDinamicos(datosInventario);
                }
            });
        }


        function cargarDatosInicialesVehiculos() {
            $.ajax({
                url: './modules/inventory/controller/inventoryOut/get_ReportJobOrder.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        datosVehiculos = response.data || [];
                        actualizarTablaVehiculos(datosVehiculos);
                        cargarFiltrosDinamicosVehiculos(datosVehiculos);
                    } else {
                        console.error('Error en los datos:', response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar datos de vehículos:", error);
                }
            });
        }


        function cargarDatosInicialesSemilla() {
            $.ajax({
                url: './modules/inventory/controller/inventoryOut/get_Entregas.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        datosSemilla = response.data || [];
                        actualizarTablaSemilla(datosSemilla);
                        cargarFiltrosDinamicosSemilla(datosSemilla);
                    } else {
                        console.error('Error en los datos:', response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar datos de semilla:", error);
                }
            });
        }

        // Actualizar tabla de vehículos
        function actualizarTablaVehiculos(datos) {
            tablaVehiculos.clear().rows.add(datos).draw();
        }


        function actualizarTablaSemilla(datos) {
            tablaSemilla.clear().rows.add(datos).draw();
        }
        // Cargar filtros dinámicos para vehículos
        function cargarFiltrosDinamicosVehiculos(datos) {

            // Producto
            let productos = [...new Set(datos.map(r => r.NombreArticulo).filter(Boolean))];
            let $prod = $('#producto-vehiculos').empty().append('<option value="">Todos</option>');
            productos.forEach(p => $prod.append(`<option value="${p}">${p}</option>`));
            // Placa
            let placas = [...new Set(datos.map(r => r.placa).filter(Boolean))];
            let $placa = $('#placa-vehiculos').empty().append('<option value="">Todas</option>');
            placas.forEach(p => $placa.append(`<option value="${p}">${p}</option>`));

        }

        // Filtrar datos de vehículos
        function filtrarDatosVehiculos() {
            let desde = $('#fecha-desde-vehiculos').val();
            let hasta = $('#fecha-hasta-vehiculos').val();
            let categoria = $('#categoria-vehiculos').val();
            let producto = $('#producto-vehiculos').val();
            let placa = $('#placa-vehiculos').val();

            let filtrados = datosVehiculos.filter(row => {
                let cumple = true;
                if (desde && row.FechaCreacion < desde) cumple = false;
                if (hasta && row.FechaCreacion > hasta) cumple = false;
                if (categoria && row.NombreCategoria != categoria) cumple = false;
                if (producto && row.NombreArticulo != producto) cumple = false;
                if (placa && row.placa != placa) cumple = false;
                return cumple;
            });
            actualizarTablaVehiculos(filtrados);
        }

        function cargarFiltrosDinamicosSemilla(datos) {
            // Categoría
            let categorias = [...new Set(datos.map(r => r.NombreCategoria).filter(Boolean))];
            let $cat = $('#categoria-semilla').empty().append('<option value="">Todas</option>');
            categorias.forEach(c => $cat.append(`<option value="${c}">${c}</option>`));

            // Producto
            let productos = [...new Set(datos.map(r => r.NombreArticulo).filter(Boolean))];
            let $prod = $('#producto-semilla').empty().append('<option value="">Todos</option>');
            productos.forEach(p => $prod.append(`<option value="${p}">${p}</option>`));

            // Bodega
            let bodegas = [...new Set(datos.map(r => r.NombreBodega).filter(Boolean))];
            let $bod = $('#bodega-semilla').empty().append('<option value="">Todas</option>');
            bodegas.forEach(b => $bod.append(`<option value="${b}">${b}</option>`));

            // Cliente (si aplica)
            let clientes = [...new Set(datos.map(r => r.NombreCliente).filter(Boolean))];
            if ($('#cliente-semilla').length) {
                let $cli = $('#cliente-semilla').empty().append('<option value="">Todos</option>');
                clientes.forEach(c => $cli.append(`<option value="${c}">${c}</option>`));
            }
        }

        function filtrarDatosSemilla() {
            let desde = $('#fecha-desde-semilla').val();
            let hasta = $('#fecha-hasta-semilla').val();
            let categoria = $('#categoria-semilla').val();
            let producto = $('#producto-semilla').val();
            let bodega = $('#bodega-semilla').val();
            let cliente = $('#cliente-semilla').val();

            let filtrados = datosSemilla.filter(row => {
                let cumple = true;
                if (desde && row.FechaCreacion < desde) cumple = false;
                if (hasta && row.FechaCreacion > hasta) cumple = false;
                if (categoria && row.NombreCategoria != categoria) cumple = false;
                if (producto && row.NombreArticulo != producto) cumple = false;
                if (bodega && row.NombreBodega != bodega) cumple = false;
                if (cliente && row.NombreCliente != cliente) cumple = false;
                return cumple;
            });
            actualizarTablaSemilla(filtrados);
        }

        // Exportar a Excel para semilla
        $('#export-semilla').click(function() {
            let datosExportar = tablaSemilla.rows({
                search: 'applied'
            }).data().toArray();
            if (datosExportar.length === 0) {
                alert('No hay datos para exportar.');
                return;
            }

            const ws_data = [
                ["Fecha", "Cliente", "Categoría", "Producto", "Bodega", "Cantidad", "Precio", "Total"],
                ...datosExportar.map(row => [
                    row.FechaCreacion,
                    row.NombreCliente,
                    row.NombreCategoria,
                    row.NombreArticulo,
                    row.NombreBodega,
                    row.CantidadSalida,
                    row.PrecioSalida,
                    (row.CantidadSalida * row.PrecioSalida).toFixed(2)
                ])
            ];

            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, "InventarioSemilla");
            XLSX.writeFile(wb, "Reporte_Inventario_Semilla.xlsx");
        });
        $('#semilla-tab').on('shown.bs.tab', function() {
            if (datosSemilla.length === 0) {
                cargarDatosInicialesSemilla();
            }
        });
        // Eventos para el tab de vehículos
        $('#filtrar-vehiculos').click(function() {
            filtrarDatosVehiculos();
        });

        $('#reset-vehiculos').click(function() {
            $('#filtro-vehiculos')[0].reset();
            actualizarTablaVehiculos(datosVehiculos);
        });

        // Exportar a Excel para vehículos
        $('#export-vehiculos').click(function() {
            let datosExportar = tablaVehiculos.rows({
                search: 'applied'
            }).data().toArray();
            if (datosExportar.length === 0) {
                alert('No hay datos para exportar.');
                return;
            }

            const ws_data = [
                ["Fecha", "Producto", "Cantidad", "Placa", "Tipo Vehículo"],
                ...datosExportar.map(row => [
                    row.FechaCreacion,
                    row.NombreArticulo,
                    row.CantidadSalida,
                    row.placa,
                    row.TipoVehiculo
                ])
            ];

            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, "InsumosVehículos");
            XLSX.writeFile(wb, "Reporte_Insumos_Vehiculos.xlsx");
        });

        // Cargar datos iniciales cuando se muestre el tab
        $('#vehiculos-tab').on('shown.bs.tab', function() {
            if (datosVehiculos.length === 0) {
                cargarDatosInicialesVehiculos();
            }
        });
        // Actualiza la tabla con los datos dados
        function actualizarTabla(datos) {
            tablaInventario.clear().rows.add(datos).draw();
        }

        function actualizarTablaMantenimiento(datos) {
            tablaMantenimientos.clear().rows.add(datos).draw();
        }


        // Llena los selects de filtros con los valores únicos de los datos
        function cargarFiltrosDinamicos(datos) {
            // Categoría
            let categorias = [...new Set(datos.map(r => r.NombreCategoria).filter(Boolean))];
            let $cat = $('#categoria-inventario').empty().append('<option value="">Todas</option>');
            categorias.forEach(c => $cat.append(`<option value="${c}">${c}</option>`));

            // Producto
            let productos = [...new Set(datos.map(r => r.NombreArticulo).filter(Boolean))];
            let $prod = $('#producto-inventario').empty().append('<option value="">Todos</option>');
            productos.forEach(p => $prod.append(`<option value="${p}">${p}</option>`));

            // Bodega
            let bodegas = [...new Set(datos.map(r => r.NombreBodega).filter(Boolean))];
            let $bod = $('#bodega-inventario').empty().append('<option value="">Todas</option>');
            bodegas.forEach(b => $bod.append(`<option value="${b}">${b}</option>`));
        }

        // Filtra los datos en frontend según los selects y fechas
        function filtrarDatos() {
            let desde = $('#fecha-desde-inventario').val();
            let hasta = $('#fecha-hasta-inventario').val();
            let categoria = $('#categoria-inventario').val();
            let producto = $('#producto-inventario').val();
            let bodega = $('#bodega-inventario').val();

            let filtrados = datosInventario.filter(row => {
                let cumple = true;
                if (desde && row.FechaCreacion < desde) cumple = false;
                if (hasta && row.FechaCreacion > hasta) cumple = false;
                if (categoria && row.NombreCategoria != categoria) cumple = false;
                if (producto && row.NombreArticulo != producto) cumple = false;
                if (bodega && row.NombreBodega != bodega) cumple = false;
                return cumple;
            });
            actualizarTabla(filtrados);
        }

        // Eventos
        $('#filtrar-inventario').click(function() {
            filtrarDatos();
        });

        $('#reset-inventario').click(function() {
            $('#filtro-inventario')[0].reset();
            actualizarTabla(datosInventario);
        });

        // Exportar a Excel usando SheetJS
        $('#export-inventario').click(function() {
            let datosExportar = tablaInventario.rows({
                search: 'applied'
            }).data().toArray();
            if (datosExportar.length === 0) {
                alert('No hay datos para exportar.');
                return;
            }
            const ws_data = [
                ["Fecha Creación", "N° Factura", "Artículo", "Categoría", "Subcategoría", "Bodega", "Cantidad Ingreso", "Precio Compra", "SubTotal"],
                ...datosExportar.map(row => [
                    row.FechaCreacion,
                    row.NumeroFactura,
                    row.NombreArticulo,
                    row.NombreCategoria,
                    row.nombreSubCategoria,
                    row.NombreBodega,
                    row.CantidadIngreso,
                    row.PrecioCompra,
                    row.SubTotal
                ])
            ];
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, "Inventario");
            XLSX.writeFile(wb, "ReporteInventario.xlsx");
        });



        $('#filtrar-semilla').click(function() {
            filtrarDatosSemilla();
        });

        $('#reset-semilla').click(function() {
            $('#filtro-semilla')[0].reset();
            actualizarTablaSemilla(datosSemilla);
        });
        // Carga inicial
        cargarDatosIniciales();
        cargarDatosInicialesVehiculos();
        cargarDatosInicialesSemilla();
    });