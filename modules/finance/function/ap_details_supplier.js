function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

const id = getQueryParam("id");

setTimeout(() => {
    $("#proveedor").val(id).trigger("change");
}, 2000);

cargarProveedores();
cargarDisponible(id);
cargarAnticipado(id);
cargarPagado(id);
listarCuentasPorPagarID(id);


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
                $('#disponible').val(response.data.montoDisponible);
            } else {
                console.log('No se pudo obtener el monto disponible.');
            }
        },
        error: function () {
            console.log('Error al cargar el monto disponible.');
        }
    });
}
function cargarAnticipado(idProveedor) {
    $.ajax({
        url: './modules/finance/controller/account_payable/getPrepaid.php',
        type: 'GET',
        data: { id: idProveedor },
        dataType: 'json',
        success: function (response) {
            if (response.success && response.data) {
                $('#anticipado').val(response.data.anticipado);
            } else {
                console.log('No se pudo obtener el monto disponible.');
            }
        },
        error: function () {
            console.log('Error al cargar el monto disponible.');
        }
    });
}
function cargarPagado(idProveedor) {
    $.ajax({
        url: './modules/finance/controller/account_payable/getPaid.php',
        type: 'GET',
        data: { id: idProveedor },
        dataType: 'json',
        success: function (response) {
            if (response.success && response.data) {
                $('#montoPagado').val(response.data.pagado);
            } else {
                console.log('No se pudo obtener el monto disponible.');
            }
        },
        error: function () {
            console.log('Error al cargar el monto disponible.');
        }
    });
}


function listarCuentasPorPagarID(id) {
    if ($.fn.DataTable.isDataTable('#anticiposTabla')) {
        $('#anticiposTabla').DataTable().destroy();
        $('#anticiposTabla').empty(); // Limpia el contenido para evitar duplicados
    }

    $('#anticiposTabla').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: './modules/finance/controller/account_payable/getDataID.php',
            type: 'GET',
            dataType: 'json',
            data: { idProveedor: id },
            dataSrc: 'data'
        },
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'noDocumento', title: 'No. de Documento' },
            { data: 'fechaEmision', className: 'text-center', title: 'Fecha de Emisión' },
            {
                data: 'monto',
                title: 'Monto Total',
                render: $.fn.dataTable.render.number(',', '.', 2, 'L ')
            },
            {
                data: null,
                title: 'Acciones',
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-info btn-sm verMas" data-id="${row.id}" title="Ver más">
                            <i class="fas fa-eye"></i>
                        </button>
                    `;
                }
            }
        ],
        responsive: true,
        language: {
            url: './dist/assets/json/Spanish.json'
        }
    });
}

$('#anticiposTabla').on('click', '.verMas', function () {
    const id = $(this).data('id');
    window.location.href = `?module=ap_details&id=${id}`;
});

function listarCuentasPagadasID(id) {
    if ($.fn.DataTable.isDataTable('#anticiposTabla')) {
        $('#anticiposTabla').DataTable().destroy();
        $('#anticiposTabla').empty(); // Limpia el contenido para evitar duplicados
    }

    $('#anticiposTabla').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: './modules/finance/controller/account_payable/getSellsDataID.php',
            type: 'GET',
            dataType: 'json',
            data: { idProveedor: id },
            dataSrc: 'data'
        },
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'noDocumento', title: 'No. de Documento' },
            { data: 'fechaEmision', className: 'text-center', title: 'Fecha de Emisión' },
            {
                data: 'monto',
                title: 'Monto Total',
                render: $.fn.dataTable.render.number(',', '.', 2, 'L ')
            },
            {
                data: null,
                title: 'Acciones',
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-info btn-sm verMas" data-id="${row.id}" title="Ver más">
                            <i class="fas fa-eye"></i>
                        </button>
                    `;
                }
            }
        ],
        responsive: true,
        language: {
            url: './dist/assets/json/Spanish.json'
        }
    });
}
