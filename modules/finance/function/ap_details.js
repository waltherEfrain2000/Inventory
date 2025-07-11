
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

const id = getQueryParam("id");

if (id != null) {
    $(".saldoPendienteContenedor").hide();
    cargarRegistro(id);
}

/* Cargar Selects */
cargarProveedores();

$("#proveedor").on("change",function(){
    let idProveedor=$(this).val();
    cargarDisponible(idProveedor)
});

$("#btnGuardarAbono").on("click", function () {
    let idRegistro = id ? id : 0;
    const datosPago = {
        id:idRegistro,
        proveedor: $("#proveedor").val(),
        numeroDocumento: $("#numeroDocumento").val(),
        fechaEmision: $("#fechaEmision").val(),
        monto: $("#montoTotal").val(),
        saldoDisponible: $("#saldoPendiente").val(),
        observaciones: $("#comentarios").val()
    };

    // Validar que todos los campos estén llenos
    for (const key in datosPago) {
        if (datosPago[key] === "" || datosPago[key] === null) {
            Swal.fire("Registro de Anticipo", "Todos los campos son obligatorios", "info");
            return;
        }
    }
    console.log(id)
    if (id == null) {
        console.log("guarda");
        guardarAnticipo(datosPago);
    }else{
        console.log("actualiza")
        actualizarRegistro(datosPago);
    }
});

function guardarAnticipo(datosPago) {
    $.ajax({
        url: './modules/finance/controller/account_payable/addNew.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(datosPago),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                Swal.fire("Registro de Anticipo", "La cuenta por pagar se ha guardado correctamente", "success").then(() => {
                    window.location.href = "?module=accounts_payable";
                });
            } else {
                Swal.fire("Registro de Anticipo", "Error al guardar: " + response.error, "error");
            }
        },
        error: function () {
            Swal.fire("Registro de Anticipo", "Hubo un problema al comunicarse con el servidor", "error");
        }
    });
}
function actualizarRegistro(datosPago) {
    $.ajax({
        url: './modules/finance/controller/account_payable/update.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(datosPago),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                Swal.fire("Registro de Anticipo", "La cuenta por pagar se ha guardado correctamente", "success").then(() => {
                    window.location.href = "?module=accounts_payable";
                });
            } else {
                Swal.fire("Registro de Anticipo", "Error al guardar: " + response.error, "error");
            }
        },
        error: function () {
            Swal.fire("Registro de Anticipo", "Hubo un problema al comunicarse con el servidor", "error");
        }
    });
}

function cargarRegistro(idDocumento) {

    $.ajax({
        url: './modules/finance/controller/account_payable/getDocumentInfo.php',
        type: 'GET',
        data: { id: idDocumento },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                // Llenar el formulario con los datos recibidos
                $('#numeroDocumento').val(response.data.noDocumento);
                $('#fechaEmision').val(response.data.fechaEmision);
                $('#montoTotal').val(response.data.monto);
                $('#comentarios').val(response.data.observaciones);
                setTimeout(() => {
                    $('#proveedor').val(response.data.idProveedor).trigger('change');
                }, 1000);

             

            } else {
                console.error('Error al obtener la información:', response.error);
            }
        },
        error: function () {
            console.log('Error al cargar la información del documento.');
        }
    });
}


function cargarProveedores() {
    $.ajax({
        url: './modules/finance/controller/account_payable/getSuppliers.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            let select = $('#proveedor');
            select.empty();
            select.append('<option value="">Seleccione...</option>');

            if (response.success) {
                $.each(response.data, function (index, proveedor) {
                    select.append('<option value="' + proveedor.id + '">' + proveedor.nombre + '</option>');
                });
            } else {
                console.log('Error: ' + response.error);
            }
            select.trigger('change');

        },
        error: function () {
            console.log('Error al cargar los proveedores.');
        }
    });
}

function cargarDisponible(idProveedor) {
    $.ajax({
        url: './modules/finance/controller/account_payable/getAvailable.php',
        type: 'GET',
        data: { id: idProveedor },
        dataType: 'json',
        success: function (response) {
            if (response.success && response.data) {
                $('#saldoPendiente').val(response.data.montoDisponible);
            } else {
                console.log('No se pudo obtener el monto disponible.');
            }
        },
        error: function () {
            console.log('Error al cargar el monto disponible.');
        }
    });
}




