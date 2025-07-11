$(function () {
    $("#table-bodegas").DataTable();
    
    listWarehouses();
    
    // Evitar que el formulario se envíe por defecto y llamar a la función adecuada
    $('#formBodega').submit(function (event) {
        event.preventDefault();
        let id = $("#bodega_id").val();
        id ? update_Warehouse(id) : save_Warehouse();
    });
});

let isModalOpen = false;

function listWarehouses() {
    let table = $("#table-bodegas").DataTable();

    if (table) {
        table.clear().destroy(); // Detener la tabla existente
    }

    let tableBody = $("#table-bodegas tbody");
    tableBody.empty();

    $.ajax({
        url: "./modules/inventory/controller/warehouses/list_Warehouses.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log("Bodegas obtenidas:", response);
            if (!response.success) {
                console.error("Error al obtener las bodegas:", response.error);
                return;
            }

            let data = response.data;

            // Agregar las filas dinámicamente
            $.each(data, function (index, bodega) {
                tableBody.append(`
                    <tr>
                        <td>${bodega.NombreBodega}</td>
                        <td>${bodega.DescripcionBodega}</td>
                        <td class="text-center">
                            <span class="badge ${bodega.Estado == 1 ? 'bg-success' : 'bg-danger'}">
                                ${bodega.Estado == 1 ? 'Activo' : 'Inactivo'}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm edit-btn" data-id="${bodega.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${bodega.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            // Inicializa DataTable nuevamente después de agregar las filas
            $("#table-bodegas").DataTable();

            // Delegar eventos para editar y eliminar
            $("#table-bodegas").on("click", ".edit-btn", function () {
                let id = $(this).data("id");
                loadWarehouseForEdit(id);
            });

            $("#table-bodegas").on("click", ".delete-btn", function () {
                let id = $(this).data("id");
                handleDeleteWarehouse(id);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar las bodegas:", error);
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al cargar las bodegas.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function save_Warehouse() {
    $('#modalBodega').modal('hide');
    var name = $('#nombreBodega').val();
    var descripcion = $('#descripcionBodega').val();
    var Estado = $('#estadoBodega').val();

    $.ajax({
        url: 'modules/inventory/controller/warehouses/save_Warehouse.php',
        type: 'POST',
        data: {
            NombreBodega: name,
            DescripcionBodega: descripcion,
            Estado: Estado
        },
        dataType: "json",
        success: function (response) {
            $("#formBodega")[0].reset();
            if (response.success) {
                Swal.fire({
                    title: 'Categoría guardada',
                    text: 'La bodega ha sido guardada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $("#modalBodega").modal("hide");
                    listWarehouses();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo guardar la bodega.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar la bodega.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function loadWarehouseForEdit(id) {
    if (isModalOpen) return; // Evita que la petición se haga más de una vez
    isModalOpen = true;

    $.ajax({
        url: "./modules/inventory/controller/warehouses/list_Warehouse_For_Update.php?id=" + id,
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log("Bodega obtenida:", response);
            if (response.success) {
                let bodega = response.data;
                $("#bodega_id").val(bodega.id);
                $("#nombreBodega").val(bodega.NombreBodega);
                $("#descripcionBodega").val(bodega.DescripcionBodega);
                setTimeout(() => {
                    $("#estadoBodega").val(bodega.Estado);
                }, 500);

                $("#modalBodega").modal("show");
            }
            isModalOpen = false; // Restablecer el flag cuando la operación haya terminado
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la categoría para editar.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            isModalOpen = false;
        }
    });
}

function update_Warehouse(id) {
    $('#modalBodega').modal('hide');
    var name = $('#nombreBodega').val();
    var descripcion = $('#descripcionBodega').val();
    var Estado = $('#estadoBodega').val();

    $.ajax({
        url: 'modules/inventory/controller/warehouses/update_Warehouse.php',
        type: 'POST',
        data: {
            id: id,
            NombreBodega: name,
            DescripcionBodega: descripcion,
            Estado: Estado
        },
        dataType: "json",
        success: function (response) {
            $("#bodega_id").val("");
            $("#formBodega")[0].reset();
            if (response.success) {
                Swal.fire({
                    title: 'Bodega guardada',
                    text: 'La bodega ha sido guardada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $("#modalBodega").modal("hide");
                    listWarehouses();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo guardar la bodega.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar la bodega.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function handleDeleteWarehouse(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#FF0000',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("modules/inventory/controller/warehouses/disable_Warehouse.php", { action: "eliminar", id: id }, function (response) {
                let data = JSON.parse(response);
                if (data.success) {
                    listWarehouses();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo eliminar la bodega.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
}
