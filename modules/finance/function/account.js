$(function () {
    listarCuentas();
    $("#nivel").on("change", function () {
        let nivel = $(this).val();
        if (nivel) {
            cargarCuentasPadre(nivel);
        }
    });

    $("#addAccountForm").on("submit", function (e) {
        e.preventDefault();
        let id = $("#account_id").val();
        console.log(id);
        if (id) {
            actualizarCuenta(id);
        } else {
            guardarCuenta();
        }
    });
});

function cargarCuentasPadre(nivel) {
    $.ajax({
        url: "./modules/finance/controller/account_list.php?action=listarPadres&nivel=" + nivel,
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener las cuentas padre:", response.error);
                return;
            }

            let cuentasPadre = response.data; // Extraer la data correctamente
            let selectPadre = $("#padre_id");
            selectPadre.empty();
            selectPadre.append('<option value="">Ninguna</option>');

            if (cuentasPadre.length === 0) {
                selectPadre.append('<option disabled>No hay cuentas padre disponibles</option>');
            } else {
                $.each(cuentasPadre, function (index, cuenta) {
                    selectPadre.append(`<option value="${cuenta.id}">${cuenta.codigo} - ${cuenta.nombre}</option>`);
                });
            }
        },
        error: function () {
            alert("Error al cargar las cuentas padre.");
        }
    });
}


function listarCuentas() {
    $.ajax({
        url: "./modules/finance/controller/account_list.php?action=listar",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener el catálogo:", response.error);
                return;
            }

            let data = response.data;
            let tableBody = $("#table-style-hover tbody");
            tableBody.empty();

            $.each(data, function (index, cuenta) {
                let indent = "&nbsp;".repeat(cuenta.nivel * 4); // Indentación según el nivel
                let boldClass = cuenta.nivel === 1 ? "fw-bold" : "";

                tableBody.append(`
                    <tr>
                        <td class="${boldClass}">${cuenta.codigo}</td>
                        <td class="${boldClass}">${indent}${cuenta.nombre}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm edit-btn" data-id="${cuenta.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${cuenta.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            $(".edit-btn").on("click", function () {
                let id = $(this).data("id");
                cargarCuentaParaEditar(id);
            });

            $(".delete-btn").on("click", function () {
                let id = $(this).data("id");

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post("modules/finance/controller/account_list.php", { action: "eliminar", id: id }, function (response) {
                            let data = JSON.parse(response);
                            if (data.success) {
                                listarCuentas();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se pudo eliminar la cuenta.',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });
                    }
                });
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el catálogo contable:", error);
            alert("Error al cargar el catálogo contable.");
        }
    });
}

function guardarCuenta() {
    let formData = $("#addAccountForm").serialize();
    
    $.ajax({
        url: "./modules/finance/controller/account_list.php?action=guardar",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    title: 'Cuenta guardada',
                    text: 'La cuenta ha sido guardada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $("#addAccountModal").modal("hide");
                    listarCuentas();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo guardar la cuenta.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar la cuenta.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function cargarCuentaParaEditar(id) {
    $.ajax({
        url: "./modules/finance/controller/account_list.php?action=obtener&id=" + id,
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (response.success) {
                let cuenta = response.data;
                $("#account_id").val(cuenta.id);
                $("[name='codigo']").val(cuenta.codigo);
                $("[name='nombre']").val(cuenta.nombre);
                $("[name='tipo_id']").val(cuenta.tipo_id);
                $("#nivel").val(cuenta.nivel).change();
                setTimeout(() => {
                    $("#padre_id").val(cuenta.padre_id);
                }, 3000);
                $("#addAccountModal").modal("show");
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la cuenta para editar.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function actualizarCuenta(id) {
    let formData = $("#addAccountForm").serialize();
    
    $.ajax({
        url: "./modules/finance/controller/account_list.php?action=actualizar",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#addAccountModal").modal("hide");
                $("#addAccountForm")[0].reset();
                $("#account_id").val("");
                listarCuentas();
                Swal.fire({
                    title: 'Cuenta actualizada',
                    text: 'La cuenta ha sido actualizada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo actualizar la cuenta.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al actualizar la cuenta.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}