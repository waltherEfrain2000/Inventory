function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

const id = getQueryParam("id");

if (id == null) {
    $("#contenedor_historial").hide();
    setTimeout(() => {
        $("#estadoDocumento").val(1).trigger("change");
        $("#estadoDocumento").attr("disabled", true);
        $("#saldoPendiente").attr("disabled", true);
    }, 1500);
} else {
    cargarRegistro(id);
}

$("#montoTotal").on("change",function(){
    console.log(id)
    if (id == null) {
        let monto= $("#montoTotal").val();
        console.log(monto)
        $("#saldoPendiente").val(monto);

    }
});


/* Cargar Selects */
cargarClientes();
cargarEstados();
cargarMetodosPago();

function cargarClientes() {
    $.ajax({
        url: './modules/finance/controller/account_receivable/loadClients.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            let select = $('#cliente');
            select.empty();
            select.append('<option value="">Seleccione...</option>');

            if (response.success) {
                $.each(response.data, function (index, cliente) {
                    select.append('<option value="' + cliente.id + '">' + cliente.nombre + '</option>');
                });
            } else {
                console.log('Error: ' + response.error);
            }
            select.trigger('change');

        },
        error: function () {
            console.log('Error al cargar los clientes.');
        }
    });
}
function cargarEstados() {
    $.ajax({
        url: './modules/finance/controller/account_receivable/loadStatus.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            let select = $('#estadoDocumento');
            select.empty();
            select.append('<option value="">Seleccione...</option>');

            if (response.success) {
                $.each(response.data, function (index, estados) {
                    select.append('<option value="' + estados.id + '">' + estados.descripcion + '</option>');
                });
            } else {
                console.log('Error: ' + response.error);
            }
            select.trigger('change');

        },
        error: function () {
            console.log('Error al cargar los estados.');
        }
    });
}
function cargarMetodosPago() {
    $.ajax({
        url: './modules/finance/controller/account_receivable/loadPayment.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            let select = $('#metodoPago');
            select.empty();

            if (response.success) {
                $.each(response.data, function (index, pagos) {
                    select.append('<option value="' + pagos.id + '">' + pagos.descripcion + '</option>');
                });
            } else {
                console.log('Error: ' + response.error);
            }
            select.trigger('change');

        },
        error: function () {
            console.log('Error al cargar los metodos de pagos.');
        }
    });
}

/* Agregar registros */
$("#btnGuardarPago").on("click", function () {
    const datosPago = {
        cliente: $("#cliente").val(),
        numeroDocumento: $("#numeroDocumento").val(),
        fechaEmision: $("#fechaEmision").val(),
        fechaVencimiento: $("#fechaVencimiento").val(),
        estado: $("#estadoDocumento").val(),
        monto: $("#montoTotal").val(),
        observaciones: $("#comentarios").val(),
    };

    // Validar que todos los campos estén llenos
    for (const key in datosPago) {
        if (datosPago[key] === "" || datosPago[key] === null) {
            Swal.fire("Registro de Pagos", "Todos los campos son obligatorios", "info");
            return;
        }
    }

    guardarPago(datosPago);
});

function guardarPago(datosPago) {
    console.log(datosPago);
    $.ajax({
        url: './modules/finance/controller/account_receivable/addNew.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(datosPago),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                Swal.fire("Registro de Pagos", "El pago se ha guardado correctamente", "success").then(() => {
                    window.location.href = "?module=accounts_receivable";
                });
            } else {
                Swal.fire("Registro de Pagos", "Error al guardar el pago: " + response.error, "error");
            }
        },
        error: function () {
            Swal.fire("Registro de Pagos", "Hubo un problema al comunicarse con el servidor", "error");
        }
    });
}

/* Cargar Información */
function cargarRegistro(idDocumento) {

    $.ajax({
        url: './modules/finance/controller/account_receivable/getDocumentInfo.php',
        type: 'GET',
        data: { id: idDocumento },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                // Llenar el formulario con los datos recibidos
                $('#numeroDocumento').val(response.data.noDocumento);
                $('#fechaEmision').val(response.data.fechaEmision);
                $('#fechaVencimiento').val(response.data.fechaVencimiento);
                $('#montoTotal').val(response.data.monto);
                $('#saldoPendiente').val(response.data.saldoPendiente);
                $('#comentarios').val(response.data.observaciones);

                setTimeout(() => {
                    $('#cliente').val(response.data.idCliente).trigger('change');
                    $('#estadoDocumento').val(response.data.estado).trigger('change');
                    $('#estadoDocumento').attr("disabled", true);
                }, 1000);

                listarAbonos(id);

            } else {
                console.error('Error al obtener la información:', response.error);
            }
        },
        error: function () {
            console.log('Error al cargar la información del documento.');
        }
    });
}


/* Agregar abono */
$("#btnGuardarAbono").on("click", function () {
    const datosPago = {
        idDocumento: id,
        fecha: $("#fechaAbono").val(),
        monto: $("#montoAbono").val(),
        metodoPago: $("#metodoPago").val(),
    };

    // Validar que todos los campos estén llenos
    for (const key in datosPago) {
        if (datosPago[key] === "" || datosPago[key] === null) {
            Swal.fire("Registro de Pagos", "Todos los campos son obligatorios", "info");
            return;
        }
    }
    guardarAbono(datosPago);
});

function guardarAbono(datosPago) {
    $.ajax({
        url: './modules/finance/controller/account_receivable/addDeposit.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(datosPago),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#modalRegistrarAbono').modal('hide');

                Swal.fire("Registro de Pagos", "El abono se ha guardado correctamente", "success").then(() => {
                    listarAbonos(id)
                });
            } else {
                Swal.fire("Registro de Pagos", "Error al guardar el abono: " + response.error, "error");
            }
        },
        error: function () {
            Swal.fire("Registro de Pagos", "Hubo un problema al comunicarse con el servidor", "error");
        }
    });
}

function listarAbonos(idDocumento) {
    if (!idDocumento) {
        console.error("ID de documento no proporcionado.");
        return;
    }

    $('#abonosTabla').DataTable({
        destroy: true,
        processing: true,
        serverSide: false,
        ajax: {
            url: './modules/finance/controller/account_receivable/getDeposit.php',
            type: 'GET',
            data: { idDocumento: idDocumento },
            dataType: 'json',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'fechaAbono', title: 'Fecha' },
            {
                data: 'monto',
                title: 'Monto',
                render: $.fn.dataTable.render.number(',', '.', 2, 'L ')
            },
            { data: 'metodoPago', title: 'Método de Pago' },
            {
                data: null,
                title: 'Acciones',
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                        <a class="btn btn-danger btn-sm eliminar" data-id="${row.id}">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    `;
                }
            }
        ],
        responsive: true,
        language: {
            url: './dist/assets/json/Spanish.json'
        }
    });

    // Evento para eliminar abonos
    $('#abonosTabla').off('click', '.eliminar').on('click', '.eliminar', function () {
        let id = $(this).data('id');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Este abono será anulado y no podrá recuperarse.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, anularlo'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: './modules/finance/controller/account_receivable/voidPayment.php',
                    type: 'POST',
                    data: JSON.stringify({ id: id }),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire("Anulación Exitosa", "El abono ha sido anulado correctamente.", "success");
                            $('#abonosTabla').DataTable().ajax.reload(); // Recargar la tabla
                        } else {
                            Swal.fire("Error", "No se pudo anular el abono: " + response.error, "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Hubo un problema al comunicarse con el servidor.", "error");
                    }
                });
            }
        });
    });

}



