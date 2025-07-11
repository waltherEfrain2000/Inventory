listarCuentasPorCobrar();

function listarCuentasPorCobrar() {
    $('#cuentasPorCobrarTabla').DataTable({
        processing: true,
        serverSide: false, 
        ajax: {
            url: './modules/finance/controller/account_receivable/getData.php', 
            type: 'GET',
            dataType: 'json',
            dataSrc: 'data' 
        },
        columns: [
            { data: 'idDocumento', title: 'ID' },
            { data: 'nombre', title: 'Cliente' },
            { data: 'noDocumento', title: 'No. de Documento' },
            { data: 'fechaEmision', title: 'Fecha de Emisión' },
            { data: 'fechaVencimiento', title: 'Fecha Vencimiento' },
            { 
                data: 'saldoPendiente', 
                title: 'Saldo Pendiente', 
                render: $.fn.dataTable.render.number(',', '.', 2, 'L ') 
            },
            { 
                data: 'monto', 
                title: 'Monto Total', 
                render: $.fn.dataTable.render.number(',', '.', 2, 'L ') 
            },
            { 
                data: 'estadoDescripcion', 
                title: 'Estado', 
                render: function(data, type, row) {
                    let badgeColor = "secondary"; 
                    
                    switch (data.toLowerCase()) {
                        case "pagado":
                            badgeColor = "success"; 
                            break;
                        case "en proceso":
                            badgeColor = "primary"; 
                            break;
                        case "pendiente":
                            badgeColor = "warning"; 
                            break;
                        case "vencido":
                            badgeColor = "danger"; 
                            break;
                        case "cancelado":
                            badgeColor = "secondary";
                            break;
                    }
                    
                    return `<span class="badge bg-${badgeColor}">${data}</span>`;
                }
            },
            { 
                data: null, 
                title: 'Acciones', 
                className: 'text-center', 
                render: function (data, type, row) {
                    return `
                       
                        <button class="btn btn-primary btn-sm editar" data-id="${row.idDocumento}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm eliminar" data-id="${row.idDocumento}">
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
    $('#cuentasPorCobrarTabla').on('click', '.editar', function () {
        let id = $(this).data('id');
        window.location.href = `?module=ar_details&id=${id}`;
    });

    // Evento para eliminar
    $('#cuentasPorCobrarTabla').on('click', '.eliminar', function () {
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
                console.log("Eliminar ID:", id);
                // Aquí puedes llamar a una función AJAX para eliminar
            }
        });
    });
}

cargarIndicadoresCXC();
function cargarIndicadoresCXC() {
    $.getJSON('./modules/finance/controller/account_receivable/cards/getReceivable.php', function (data) {
        $('#pendienteCobro').text(formatearMoneda(data.data));
    });

    $.getJSON('./modules/finance/controller/account_receivable/cards/getUpcomingDue.php', function (data) {
        $('#proximoVencer').text(data.data ?? '0');
    });

    $.getJSON('./modules/finance/controller/account_receivable/cards/getOverdue.php', function (data) {
        $('#vencidas').text(formatearMoneda(data.data));
    });

    $.getJSON('./modules/finance/controller/account_receivable/cards/getMonthReceivable.php', function (data) {
        $('#pagosMes').text(formatearMoneda(data.data));
    });
}


function formatearMoneda(valor) {
    valor = parseFloat(valor ?? 0);
    return valor.toLocaleString('es-HN', { style: 'currency', currency: 'HNL' });
}
