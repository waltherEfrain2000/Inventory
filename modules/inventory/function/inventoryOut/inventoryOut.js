document.getElementById("tipoSalida").addEventListener("change", function () {
    let tipo = this.value;
    document.getElementById("seccionVenta").style.display = tipo === "3" ? "block" : "none";
    document.getElementById("seccionDescargo").style.display = tipo === "1" ? "block" : "none";
});

let productos = [];
let bodegas = [];
let idModificar = null;
let productosEnTabla = [];

$(document).ready(function () {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    cargarClientes();
    cargarProductos();
    cargarBodegas();

    if (id) {
        loadForEdit(id);
        loadForEditHeader(id);
        idModificar = id;
    }

    $('#formsalida').submit(function (event) {
        event.preventDefault();
        id ? update_ProductOut(id) : save_ProductOut();
    });

    // Eventos para cálculos
    $('#detallesalida').on('input', '.cantidad, .precioUnitario', function () {
        calcularSubtotalFila($(this).closest('tr'));
        recalcularTotales();
    });

    $('#impuesto').on('change', function () {
        recalcularTotales();
    });

    $('#detallesalida tbody').on('change', 'select[name="articulo[]"]', function () {
        cargarBodegasProducto(this);
    });

    $('#cliente').select2({
        placeholder: "Seleccione un cliente",
        allowClear: true
    });

    $('#agregarLinea').click(function () {
        agregarNuevaFila();
    });

    // Eliminar fila
    $('#detallesalida tbody').on('click', '.eliminar', function () {
        let fila = $(this).closest('tr');
        let productoId = fila.find('select[name="articulo[]"]').val();
        let bodegaId = fila.find('select[name="bodega[]"]').val();

        if (productoId && bodegaId) {
            productosEnTabla = productosEnTabla.filter(item =>
                !(item.productoId === productoId && item.bodegaId === bodegaId)
            );
        }

        fila.remove();
        recalcularTotales();
    });

    // Cambio de bodega
    $('#detallesalida tbody').on('change', 'select[name="bodega[]"]', function () {
        actualizarExistenciasBodega(this);
    });
});

function calcularSubtotalFila(fila) {
    let cantidad = parseFloat(fila.find('.cantidad').val()) || 0;
    let precio = parseFloat(fila.find('.precioUnitario').val()) || 0;
    fila.find('.subTotal').val((cantidad * precio).toFixed(2));
}

function agregarNuevaFila() {
    let productoOptions = `<option value="">Seleccione un artículo</option>` +
        productos.map(p => `<option value="${p.id}">${p.NombreArticulo}</option>`).join('');

    let bodegaOptions = `<option value="">Seleccione una bodega</option>` +
        bodegas.map(b => `<option value="${b.id}">${b.NombreBodega}</option>`).join('');

    let nuevaFila = `
    <tr>
        <td>
            <select class="form-select select2" name="articulo[]" required>
                ${productoOptions}
            </select>
        </td>
        <td>
            <select class="form-select select2" name="bodega[]" required>
                ${bodegaOptions}
            </select>
        </td>
        <td>
            <input type="number" class="form-control cantidad" name="cantidad[]" 
                   required step="0.01" min="0.01" style="width: 120px;">
        </td>
        <td class="existencias-bodega" style="text-align:center;">-</td>
        <td>
            <input type="number" class="form-control precioUnitario" name="precioUnitario[]" 
                   required step="0.01" min="0.01" style="width: 130px;">
        </td>
        <td>
            <input type="number" class="form-control subTotal" name="subTotal[]" 
                   required step="0.01" readonly style="width: 130px;">
        </td>
        <td><button type="button" class="btn btn-danger btn-sm eliminar">Eliminar</button></td>
    </tr>`;

    $('#detallesalida tbody').append(nuevaFila);
    $('#detallesalida tbody .select2').select2({ width: '100%' });
}

function cargarBodegasProducto(selectProducto) {
    let productoId = $(selectProducto).val();
    let fila = $(selectProducto).closest('tr');
    let bodegaSelect = fila.find('select[name="bodega[]"]');
    let existenciasCell = fila.find('.existencias-bodega');

    if (!productoId) {
        bodegaSelect.html('<option value="">Seleccione una bodega</option>');
        existenciasCell.text('-');
        fila.find('.cantidad').removeAttr('max').val('');
        fila.find('.precioUnitario').val('');
        fila.find('.subTotal').val('');
        return;
    }

    $.ajax({
        url: "./modules/inventory/controller/inventoryOut/getProductStock.php",
        type: "GET",
        data: { id: productoId },
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                mostrarError('Error al cargar bodegas: ' + response.error);
                return;
            }

            let stockData = response.data;
            bodegaSelect.empty().append('<option value="">Seleccione una bodega</option>');

            if (stockData.bodegas.length > 0) {
                // Ordenar para que bodegas "General" aparezcan primero
                stockData.bodegas.sort((a, b) => {
                    const aIsGeneral = a.NombreBodega.toLowerCase().includes('general');
                    const bIsGeneral = b.NombreBodega.toLowerCase().includes('general');
                    if (aIsGeneral && !bIsGeneral) return -1;
                    if (!aIsGeneral && bIsGeneral) return 1;
                    return 0;
                });

                stockData.bodegas.forEach(bodega => {
                    const yaExiste = productosEnTabla.some(item =>
                        item.productoId == productoId && item.bodegaId == bodega.idBodega
                    );

                    if (!yaExiste || idModificar) {
                        bodegaSelect.append(
                            `<option value="${bodega.idBodega}" 
                             data-existencias="${bodega.Existencias}"
                             data-precio="${bodega.PrecioVenta || 0}">
                             ${bodega.NombreBodega} (${bodega.Existencias})
                             </option>`
                        );
                    }
                });

                // Seleccionar primera bodega disponible (preferiblemente General)
                if (bodegaSelect.find('option').length > 1) {
                    bodegaSelect.val(bodegaSelect.find('option:eq(1)').val()).trigger('change');
                } else {
                    existenciasCell.text('0').addClass('text-danger');
                    mostrarAdvertencia('Este producto no tiene existencias disponibles en bodegas no utilizadas.');
                }
            } else {
                existenciasCell.text('0').addClass('text-danger');
                mostrarAdvertencia('Este producto no tiene existencias disponibles.');
            }
        },
        error: function () {
            mostrarError('Error al consultar bodegas.');
        }
    });
}

function actualizarExistenciasBodega(selectBodega) {
    let fila = $(selectBodega).closest('tr');
    let productoId = fila.find('select[name="articulo[]"]').val();
    let bodegaId = $(selectBodega).val();
    let existenciasCell = fila.find('.existencias-bodega');
    let cantidadInput = fila.find('.cantidad');
    let precioInput = fila.find('.precioUnitario');

    if (!productoId || !bodegaId) {
        existenciasCell.text('-');
        cantidadInput.removeAttr('max').val('');
        precioInput.val('');
        return;
    }

    // Obtener datos de la opción seleccionada
    let opcionSeleccionada = $(selectBodega).find('option:selected');
    let existencias = parseFloat(opcionSeleccionada.data('existencias')) || 0;
    let precio = parseFloat(opcionSeleccionada.data('precio')) || 0;

    // Actualizar interfaz
    existenciasCell.text(existencias)
        .removeClass('text-danger text-success')
        .addClass(existencias <= 5 ? 'text-danger' : 'text-success');

    cantidadInput.attr('max', existencias);
    precioInput.val(precio.toFixed(2));

    // Validar cantidad actual si existe
    let cantidadActual = parseFloat(cantidadInput.val());
    if (cantidadActual > existencias) {
        cantidadInput.val(existencias);
        mostrarAdvertencia('La cantidad se ajustó al máximo disponible en esta bodega.');
    }

    // Validar duplicados (excepto en edición)
    if (!idModificar) {
        const existe = productosEnTabla.some(item =>
            item.productoId == productoId && item.bodegaId == bodegaId
        );

        if (existe) {
            mostrarError('Este producto ya fue agregado desde esta bodega. Seleccione otra bodega.');
            $(selectBodega).val('').trigger('change');
            return;
        }

        productosEnTabla.push({ productoId, bodegaId });
    }

    calcularSubtotalFila(fila);
}

function cargarClientes() {
    $.ajax({
        url: "./modules/finance/controller/account_receivable/loadClients.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener clientes:", response.error);
                return;
            }

            $('#cliente').empty().append('<option value="">Seleccione un cliente</option>');
            response.data.forEach(cliente => {
                $('#cliente').append(`<option value="${cliente.id}">${cliente.nombre}</option>`);
            });
            $('#cliente').trigger('change');
        },
        error: function () {
            mostrarError("Error al cargar clientes.");
        }
    });
}

function cargarProductos() {
    $.ajax({
        url: "./modules/inventory/controller/inventoryEntry/list_ActiveArticles.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener productos:", response.error);
                return;
            }
            productos = response.data;
        },
        error: function () {
            mostrarError("Error al cargar productos.");
        }
    });
}

function cargarBodegas() {
    $.ajax({
        url: "./modules/inventory/controller/inventoryEntry/list_ActiveWarehouses.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener bodegas:", response.error);
                return;
            }
            bodegas = response.data;
        },
        error: function () {
            mostrarError("Error al cargar bodegas.");
        }
    });
}

function recalcularTotales() {
    let subtotal = 0;
    $('#detallesalida tbody tr').each(function () {
        let cantidad = parseFloat($(this).find('.cantidad').val()) || 0;
        let precio = parseFloat($(this).find('.precioUnitario').val()) || 0;
        subtotal += cantidad * precio;
    });

    let impuesto = parseFloat($('#impuesto').val()) || 0;
    let total = subtotal + (subtotal * impuesto);
    $('#totalsalida').val(total.toFixed(2));
}

function loadForEdit(id) {
    productosEnTabla = [];

    $.ajax({
        url: './modules/inventory/controller/inventoryOut/List_OutsForUpdate.php?id=' + id,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (!response.success) {
                mostrarError("Error al obtener la salida: " + response.error);
                return;
            }

            $('#detallesalida tbody').empty();
            $("#salida_id").val(id);

            response.data.forEach(detalle => {
                let productoOptions = `<option value="">Seleccione un artículo</option>` +
                    productos.map(p =>
                        `<option value="${p.id}" ${p.id == detalle.idArticulo ? 'selected' : ''}>
                        ${p.NombreArticulo}</option>`
                    ).join('');

                let bodegaOptions = `<option value="">Seleccione una bodega</option>` +
                    bodegas.map(b =>
                        `<option value="${b.id}" ${b.id == detalle.idBodega ? 'selected' : ''}>
                        ${b.NombreBodega}</option>`
                    ).join('');

                let nuevaFila = `
                    <tr>
                        <td>
                            <select class="form-select select2" name="articulo[]" required>
                                ${productoOptions}
                            </select>
                        </td>
                        <td>
                            <select class="form-select select2" name="bodega[]" required>
                                ${bodegaOptions}
                            </select>
                        </td>
                        <td>
    <input type="number" class="form-control cantidad" name="cantidad[]" 
           required value="${detalle.CantidadSalida}" step="0.01" 
           style="width: 150px;"> 
</td>
                        <td class="existencias-bodega" style="text-align:center;">-</td>
                        <td><input type="number" class="form-control precioUnitario" name="precioUnitario[]" 
                                   required value="${detalle.PrecioSalida}" step="0.01"></td>
                        <td><input type="number" class="form-control subTotal" name="subTotal[]" 
                                   required value="${(detalle.CantidadSalida * detalle.PrecioSalida).toFixed(2)}" 
                                   step="0.01" readonly  style="width: 150px;"></td>
                        <td><button type="button" class="btn btn-danger btn-sm eliminar">Eliminar</button></td>
                    </tr>`;

                $('#detallesalida tbody').append(nuevaFila);
                let fila = $('#detallesalida tbody tr:last');

                productosEnTabla.push({
                    productoId: detalle.idArticulo,
                    bodegaId: detalle.idBodega
                });

                fila.find('.select2').select2({ width: '100%' });
                cargarBodegasProducto(fila.find('select[name="articulo[]"]'));
            });
        },
        error: function () {
            mostrarError("Error al cargar detalles de la salida.");
        }
    });
}

function loadForEditHeader(id) {
    $('#loader').show();
    $.ajax({
        url: './modules/inventory/controller/inventoryOut/List_OutsHeader.php?id=' + id,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            $('#loader').hide();
            if (!response.success) {
                mostrarError("Error al obtener cabecera: " + response.error);
                return;
            }

            let data = response.data;
            document.getElementById("seccionVenta").style.display = data.TipoSalida == "3" ? "block" : "none";
            document.getElementById("seccionDescargo").style.display = data.TipoSalida == "1" ? "block" : "none";

            setTimeout(() => {
                $('#tipoSalida').val(data.TipoSalida).trigger('change');
                $('#cliente').val(data.IdCliente).trigger('change');
                $('#impuesto').val(data.ImpuestoSalida.toString()).trigger('change');
                $('#comentarios').val(data.Comentarios);
                $('#totalsalida').val(data.TotalSalida);
            }, 1000);
        },
        error: function () {
            $('#loader').hide();
            mostrarError("Error al cargar cabecera de la salida.");
        }
    });
}

function save_ProductOut() {
    if (!validarFormulario()) return;

    let detalles = obtenerDetallesDesdeTabla();
    if (detalles.length === 0) {
        mostrarError("Debe agregar al menos un artículo.");
        return;
    }

    let data = {
        TipoSalida: $('#tipoSalida').val(),
        idCliente: $('#cliente').val(),
        idMantenimiento: $('#mantenimiento').val(),
        Comentarios: $('#comentarios').val(),
        TotalSalida: $('#totalsalida').val(),
        ImpuestoSalida: $('#impuesto').val(),
        detalles: detalles
    };

    confirmarOperacion(
        '¿Desea guardar esta salida?',
        data,
        'modules/inventory/controller/inventoryOut/save_inventoryOut.php',
        'Salida guardada',
        'La salida ha sido guardada correctamente.'
    );
}

function update_ProductOut() {
    if (!validarFormulario()) return;

    let detalles = obtenerDetallesDesdeTabla();
    if (detalles.length === 0) {
        mostrarError("Debe agregar al menos un artículo.");
        return;
    }

    let data = {
        Id: idModificar,
        TipoSalida: $('#tipoSalida').val(),
        idCliente: $('#cliente').val(),
        idMantenimiento: $('#mantenimiento').val(),
        Comentarios: $('#comentarios').val(),
        TotalSalida: $('#totalsalida').val(),
        ImpuestoSalida: $('#impuesto').val(),
        detalles: detalles
    };

    confirmarOperacion(
        '¿Desea actualizar esta salida?',
        data,
        'modules/inventory/controller/inventoryOut/update_inventoryOut.php',
        'Salida actualizada',
        'La salida ha sido actualizada correctamente.'
    );
}

// Funciones auxiliares
function validarFormulario() {
    if ($('#tipoSalida').val() === "") {
        mostrarError("Debe seleccionar un tipo de salida.");
        return false;
    }

    let valido = true;
    $('#detallesalida tbody tr').each(function () {
        let fila = $(this);
        let articulo = fila.find('[name="articulo[]"]').val();
        let bodega = fila.find('[name="bodega[]"]').val();
        let cantidad = parseFloat(fila.find('.cantidad').val());
        let precio = parseFloat(fila.find('.precioUnitario').val());
        let maxCantidad = parseFloat(fila.find('.cantidad').attr('max'));

        if (!articulo || !bodega || isNaN(cantidad) || isNaN(precio)) {
            mostrarError("Todos los campos son obligatorios en todas las filas.");
            valido = false;
            return false;
        }

        if (cantidad <= 0) {
            mostrarError("La cantidad debe ser mayor que cero.");
            valido = false;
            return false;
        }

        if (maxCantidad && cantidad > maxCantidad) {
            mostrarError("La cantidad no puede ser mayor a las existencias disponibles.");
            valido = false;
            return false;
        }

        if (precio <= 0) {
            mostrarError("El precio unitario debe ser mayor que cero.");
            valido = false;
            return false;
        }
    });

    return valido;
}

function obtenerDetallesDesdeTabla() {
    let detalles = [];
    $('#detallesalida tbody tr').each(function () {
        let fila = $(this);
        detalles.push({
            idArticulo: fila.find('[name="articulo[]"]').val(),
            idBodega: fila.find('[name="bodega[]"]').val(),
            cantidad: parseFloat(fila.find('.cantidad').val()),
            PrecioUnitario: parseFloat(fila.find('.precioUnitario').val())
        });
    });
    return detalles;
}

function confirmarOperacion(mensaje, data, url, tituloExito, mensajeExito) {
    Swal.fire({
        title: '¿Está seguro?',
        html: `
            ${mensaje}<br><br>
            Tipo de salida: <strong>${$('#tipoSalida option:selected').text()}</strong><br>
            Cliente: <strong>${$('#cliente option:selected').text() || 'Ninguno'}</strong><br>
            Total: <strong>${Number($('#totalsalida').val()).toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong><br>
            Impuesto: <strong>${$('#impuesto option:selected').text()}</strong><br><br>

            <input type="checkbox" id="chkCredito" />
            <label for="chkCredito"> ¿Es al crédito?</label><br><br>

            <label for="fechaVencimiento">Fecha de vencimiento:</label>
            <input type="date" id="fechaVencimiento" class="datepicker" disabled />
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar',
        didOpen: () => {
            const chkCredito = document.getElementById('chkCredito');
            const fechaVenc = document.getElementById('fechaVencimiento');

            chkCredito.addEventListener('change', () => {
                fechaVenc.disabled = !chkCredito.checked;
                if (!chkCredito.checked) {
                    fechaVenc.value = ''; 
                }
            });
        },
        preConfirm: () => {
            const esCredito = document.getElementById('chkCredito').checked;
            const fechaVencimiento = document.getElementById('fechaVencimiento').value;

            if (esCredito && !fechaVencimiento) {
                Swal.showValidationMessage('Debe seleccionar una fecha de vencimiento si es al crédito.');
                return false;
            }

            return { esCredito, fechaVencimiento };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { esCredito, fechaVencimiento } = result.value;

            data.EsCredito = esCredito;
            data.FechaVencimiento = fechaVencimiento;

            console.log("Datos a enviar:", data);
            realizarPeticion(data, url, tituloExito, mensajeExito);
        }
    });
}


function realizarPeticion(data, url, tituloExito, mensajeExito) {
    $.ajax({
        url: url,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: "json",
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    title: tituloExito,
                    text: mensajeExito,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = '?module=inventoryOut';
                });
            } else {
                mostrarError(response.error || 'No se pudo completar la operación.');
            }
        },
        error: function (xhr, status, error) {
            mostrarError('Hubo un problema: ' + error);
        }
    });
}

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje,
        confirmButtonText: 'Aceptar'
    });
}

function mostrarAdvertencia(mensaje) {
    Swal.fire({
        icon: 'warning',
        title: 'Advertencia',
        text: mensaje,
        confirmButtonText: 'Aceptar'
    });
}