let productos = [];

    $(document).ready(function() {
        cargarBodegas();

        $(document).ready(function() {
            cargarHistorialTomas();
            $('#tablaHistorial tbody').on('click', '.ver-detalle', function() {
                let idRevision = $(this).data('id');
                mostrarDetalleToma(idRevision);
            });

            function mostrarDetalleToma(idRevision) {
                $.ajax({
                    url: `./modules/inventory/controller/inventoryEntry/get_InventoryCountDetails.php?id=${idRevision}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            Swal.fire("Error", "No se pudo cargar el detalle de la toma.", "error");
                            return;
                        }

                        const detalles = response.data;

                        // Destruye si ya existe
                        if ($.fn.DataTable.isDataTable('#tablaDetalleToma')) {
                            $('#tablaDetalleToma').DataTable().destroy();
                        }

                        // Limpia cuerpo de tabla
                        const $tbody = $('#tablaDetalleToma tbody');
                        $tbody.empty();

                        detalles.forEach(item => {
                            const diferencia = item.cantidadFisica - item.cantidadSistema;
                            const movimiento = item.Diferencia > 0 ? 'Ingreso' : (item.Diferencia < 0 ? 'Salida' : 'Sin cambio');
                            const rowClass = item.Diferencia > 0 ? 'table-success' : (item.Diferencia < 0 ? 'table-danger' : '');

                            const row = `
                    <tr class="${rowClass}">
                        <td>${item.FechaCorte}</td>
                        <td>${item.Articulo}</td>
                        <td>${item.CantidadSistema}</td>
                        <td>${item.CantidadFisica}</td>
                        <td>${item.Diferencia}</td>
                        <td>${item.Comentarios}</td>
                        <td>${movimiento}</td>
                    </tr>`;
                            $tbody.append(row);
                        });

                        // Inicializa DataTable
                        $('#tablaDetalleToma').DataTable({
                            pageLength: 5,
                            language: {
                                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                            }
                        });

                        $('#modalDetalleToma').modal('show');
                    },
                    error: function() {
                        Swal.fire("Error", "No se pudo obtener los detalles.", "error");
                    }
                });
            }

            function cargarHistorialTomas() {
                $.ajax({
                    url: 'modules/inventory/controller/inventoryEntry/list_InventoryHistory.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        let tabla = $('#tablaHistorial').DataTable({
                            data: data.data,
                            destroy: true,
                            columns: [{
                                    data: 'id'
                                },
                                {
                                    data: 'FechaCorte'
                                },
                                {
                                    data: 'Usuario'
                                },
                                {
                                    data: 'Comentarios'
                                },
                                {
                                    data: 'Estado',
                                    render: function(data) {
                                        return data == 1 ? '<span class="badge bg-success">Activo</span>' :
                                            '<span class="badge bg-secondary">Inactivo</span>';
                                    }
                                },
                                {
                                    data: null,
                                    render: function(data) {
                                        return `<button class="btn btn-sm btn-primary ver-detalle" data-id="${data.id}">
                                            Ver Detalle
                                        </button>`;
                                    }
                                }
                            ]
                        });


                    },
                    error: function(xhr, status, error) {
                        Swal.fire("Error", "No se pudo cargar el historial", "error");
                        console.error(error);
                    }
                });
            }
        });

        $('#bodegaSelect').on('change', function() {
            let idBodega = $(this).val();
            if (idBodega) {
                cargarProductos(idBodega);
            } else {
                $('#productoSelect').empty().append('<option value="">Seleccione un producto</option>');
            }
        });

        $('#productoSelect').on('change', function() {
            const idArticulo = $(this).val();
            const productoSeleccionado = productos.find(p => p.idArticulo == idArticulo);

            if (productoSeleccionado) {
                $('#existenciaActual').val(productoSeleccionado.Existencia);
            } else {
                $('#existenciaActual').val('');
            }
        });

        $('#formToma').on('submit', function(e) {
            e.preventDefault();

            const id = $('#productoSelect').val();
            const nombre = $('#productoSelect option:selected').text();
            const existencia = parseFloat($('#existenciaActual').val());
            const contado = parseFloat($('#cantidadContada').val());
            const motivo = $('#motivoAjuste').val();

            if (!id || isNaN(contado)) return;

            const diferencia = contado - existencia;

            const fila = `
            <tr>
                <td>${nombre}</td>
                <td>${existencia}</td>
                <td>${contado}</td>
                <td>${diferencia}</td>
                <td>${motivo}</td>
                <td><button type="button" class="btn btn-danger btn-sm eliminar-fila">Eliminar</button></td>
            </tr>
        `;

            $('#tablaToma tbody').append(fila);
            $('#modalToma').modal('hide');
            $('#formToma')[0].reset();
        });

        $(document).on('click', '.eliminar-fila', function() {
            $(this).closest('tr').remove();
        });

        $('#btnGuardarToma').on('click', function() {
            const idBodega = $('#bodegaSelect').val();
            const comentarios = $('#comentariosToma').val().trim();

            if (!idBodega) {
                Swal.fire('Atención', 'Seleccione una bodega.', 'warning');
                return;
            }

            const datosToma = [];

            $('#tablaToma tbody tr').each(function() {
                const tds = $(this).find('td');
                const nombreProducto = tds.eq(0).text();
                const existencia = parseFloat(tds.eq(1).text());
                const contado = parseFloat(tds.eq(2).text());
                const motivo = tds.eq(4).text();

                const producto = productos.find(p => p.NombreArticulo === nombreProducto);
                if (!producto) return;

                datosToma.push({
                    idArticulo: producto.idArticulo,
                    idBodega: parseInt(idBodega),
                    cantidadSistema: existencia,
                    cantidadFisica: contado,
                    motivo: motivo
                });
            });

            if (datosToma.length === 0) {
                Swal.fire('Atención', 'Agregue al menos un producto.', 'warning');
                return;
            }

            // ⚠️ Advertencias previas a la toma
            Swal.fire({
                title: '¿Desea continuar con la toma?',
                html: `
                <strong>Advertencia 1:</strong> Se generarán movimientos automáticos:<br>
                <span style="color:green">• Diferencia positiva ➜ Ingreso</span><br>
                <span style="color:red">• Diferencia negativa ➜ Salida</span><br><br>
                <strong>Advertencia 2:</strong> Se tomarán los <u>precios de compra</u> configurados desde el apartado de <b>Artículos</b>.
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './modules/inventory/controller/inventoryEntry/save_InventoryCount.php',
                        type: 'POST',
                        dataType: 'json',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            Comentarios: comentarios,
                            detalles: datosToma
                        }),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Éxito', 'Toma guardada correctamente.', 'success');
                                $('#tablaToma tbody').empty();
                                $('#comentariosToma').val('');
                            } else {
                                Swal.fire('Error', response.error || 'Ocurrió un error al guardar la toma.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Ocurrió un error al guardar la toma.', 'error');
                        }
                    });
                }
            });
        });
    });

    function cargarBodegas() {
        $.ajax({
            url: "./modules/inventory/controller/inventoryEntry/list_ActiveWarehouses.php",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (!response.success) {
                    Swal.fire('Error', 'No se pudieron obtener las bodegas.', 'error');
                    return;
                }

                const bodegas = response.data;
                const $select = $('#bodegaSelect');
                $select.empty().append('<option value="">Seleccione una bodega</option>');

                bodegas.forEach(bodega => {
                    const option = $('<option>', {
                        value: bodega.id,
                        text: bodega.NombreBodega
                    });
                    $select.append(option);
                });
            },
            error: function() {
                Swal.fire('Error', 'Error al cargar las bodegas.', 'error');
            }
        });
    }

    function cargarProductos(idBodega = null) {
        let url = "./modules/inventory/controller/inventoryEntry/list_ActiveArticles.php";
        if (idBodega !== null) {
            url += `?idBodega=${idBodega}`;
        }

        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (!response.success) {
                    Swal.fire('Error', 'No se pudieron obtener los productos.', 'error');
                    return;
                }

                productos = response.data;
                const $selectProducto = $('#productoSelect');
                $selectProducto.empty().append('<option value="">Seleccione un producto</option>');

                productos.forEach(producto => {
                    $selectProducto.append(`<option value="${producto.idArticulo}">${producto.NombreArticulo}</option>`);
                });
            },
            error: function() {
                Swal.fire('Error', 'Error al cargar los productos.', 'error');
            }
        });
    }