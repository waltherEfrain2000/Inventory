listarCuentasPorPagar();

function listarCuentasPorPagar() {
    if ($.fn.DataTable.isDataTable('#cuentasPorPagarTabla')) {
        $('#cuentasPorPagarTabla').DataTable().destroy();
        $('#cuentasPorPagarTabla').empty(); // Limpia el contenido para evitar duplicados
    }
    $('#cuentasPorPagarTabla').DataTable({
        processing: true,
        serverSide: false, 
        ajax: {
            url: './modules/finance/controller/account_payable/getData.php', 
            type: 'GET',
            dataType: 'json',
            dataSrc: 'data' 
        },
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'proveedor', title: 'Proveedor' },
            { data: 'noDocumento', title: 'No. de Documento' },
            { data: 'fechaEmision', title: 'Fecha de Emisión' },
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
                        <button class="btn btn-primary btn-sm editar" data-id="${row.id}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm eliminar" data-id="${row.id}" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
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

    // Evento para editar
    $('#cuentasPorPagarTabla').on('click', '.editar', function () {
        let id = $(this).data('id');
        window.location.href = `?module=ap_details&id=${id}`;
    });

    // Evento para eliminar
    $('#cuentasPorPagarTabla').on('click', '.eliminar', function () {
        let id = $(this).data('id');
    
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esta acción.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarRegistro(id); 
            }
        });
    });
    
}

function eliminarRegistro(id) {
    $.ajax({
        url: './modules/finance/controller/account_payable/delete.php', // Asegúrate de usar el endpoint correcto
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ id: id }),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                Swal.fire("Eliminado", "La cuenta por pagar fue eliminada correctamente", "success").then(() => {
                    listarCuentasPorPagar();                });
            } else {
                Swal.fire("Error", response.error, "error");
            }
        },
        error: function () {
            Swal.fire("Error", "Hubo un problema al comunicarse con el servidor", "error");
        }
    });
}


cargarProveedores();

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


$("#btnConsultarProveedor").on("click", function () {
    const idProveedor = $("#proveedor").val();
    if (idProveedor) {
        window.location.href = `?module=ap_details_supplier&id=${idProveedor}`;
    } else {
        Swal.fire("Atención", "Por favor selecciona un proveedor antes de continuar.", "warning");
    }
});
