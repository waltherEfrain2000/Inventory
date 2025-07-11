
// Establecer "Unidades" como opción seleccionada por defecto
const seleccionPorDefecto = 'Unidades';
const valorPorDefecto = 1;
let valorSeleccionado = 1;
// Mostrar el texto y asignar el valor por defecto
$('#tiposeleccionado').text(seleccionPorDefecto);
$('#tiposeleccionado').data('value', valorPorDefecto);

// Escuchar cambios en el menú desplegable
$('.dropdown-item').on('click', function () {
    const seleccion = $(this).text().trim(); // Obtiene el texto de la opción seleccionada


    // Determina el valor según la selección
    if (seleccion.includes('Unidades')) {
        valorSeleccionado = 1;
    } else if (seleccion.includes('Porcentaje')) {
        valorSeleccionado = 2;
    }

    // Actualiza el contenido del elemento con el texto seleccionado
    $('#tiposeleccionado').text(seleccion);
    $('#tiposeleccionado').data('value', valorSeleccionado);
});

//========================= Variables =========================
// la variable proveedorId se utiliza para almacenar el id del proveedor que se va a editar o eliminar
let proveedorId = 0;

//=========================
$(document).ready(function () {
    listarProveedores();
});

$("#nuevoproveedor").click(function () {
    $("#labelprecio").html("Precio de compra:");
    $(".ocultar").hide();

    if (proveedorId != 0) {
        $("#addCostomerForm").removeClass("was-validated");
        $("#addCostomerForm")[0].reset();
        $("#addCostomerForm").find("input, select").val("");
    }
    proveedorId = 0;
    $("#proveedorModal").modal("show");
});

//====================================================================================================
//====================================== Listar ======================================================
//====================================================================================================

//---cargar tabla
function listarProveedores() {
    $.ajax({
        url: "./modules/finance/controller/suppliers.php?action=listar",
        type: "POST",
        dataType: "json",
        success: function (response) {

            let data = response;
            let tableBody = $("#tabla_proveedores tbody");
            tableBody.empty();

            $.each(data, function (index, data) {
                let indent = "&nbsp;".repeat(data.nombre * 4);
                tableBody.append(`
                    <tr>
                        <td >${data.id}</td>
                        <td >${data.nombre}</td>
                        <td >${data.rtn}</td>
                        <td >${data.telefono}</td>
                        <td >${data.direccion}</td>
                        <td  class="d-none" >${data.correo}</td>
                        <td >${data.idAcopio}</td>
                        <td >${data.precioActual}</td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <a href="#" class="btn btn-info btn-sm" id="historial"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-warning btn-sm" id="editar"><i class="ph-duotone ph-pencil-line"></i></a>
                                <a href="#" class="btn btn-danger btn-sm" id="eliminar"><i class="ph-duotone ph-trash"></i></a>
                            </div>
                        </td>

                    </tr>
                `);
            });

        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los proveedores:", error);
            alert("Error al cargar los proveedores.");
        }
    });
}

//---cargar historial
$("#tabla_proveedores").on("click", "#historial", function () {
    let row = $(this).closest("tr");
    proveedorId = row.find("td:eq(0)").text();
    let nombre = row.find("td:eq(1)").text();

    $.ajax({
        url: "./modules/finance/controller/suppliers.php?action=historial",
        type: "POST",
        data: { proveedorId: proveedorId },
        dataType: "json",
        success: function (response) {
            let data = response;
            let tableContent = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Precio</th>
                            <th>Cambio</th>
                            
                        </tr>
                    </thead>
                    <tbody>
            `;

            $.each(data, function (index, item) {

                if (item.incremento > 0) {
                    item.incremento = `<span class="text-success"><i class="ph-duotone ph-arrow-fat-line-up"></i>   ${item.incremento}</span>`;
                } else if (item.incremento < 0) {
                    item.incremento = `<span class="text-danger"><i class="ph-duotone ph-arrow-fat-line-down"></i>   ${item.incremento}</span>`;
                }

                tableContent += `
                    <tr>
                        <td>${item.fechaCreado}</td>
                        <td>${item.preciocompra}</td>
                        <td>${item.incremento}</td>
                    </tr>
                `;
            });

            tableContent += `
                    </tbody>
                </table>
            `;

            Swal.fire({
                title: `Historial de precios de ${nombre}`,
                html: tableContent,
                width: '800px',
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#007bff',
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el historial de precios:", error);
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al cargar el historial de precios.',
                icon: 'error',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#dc3545',
            });
        }
    });
});
//====================================================================================================
//====================================== Operaciones Proveedores =====================================
//====================================================================================================

//---guardar
$("#btnguardar").click(function () {
    $("#addCostomerForm").addClass("was-validated");

    let formData = $("#addCostomerForm").serialize() + "&proveedorId=" + proveedorId;
    // agregar el id del valorSeleccionado a la variable formData
    formData += "&valorSeleccionado=" + valorSeleccionado;
    console.log(formData);
    var accion = (proveedorId == 0) ? "guardar" : "editar";

    $.ajax({
        url: "./modules/finance/controller/suppliers.php?action=" + accion,
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    target: document.getElementById('proveedorModal'),
                    title: 'Proveedor agregado',
                    text: 'El proveedor ha sido guardado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $("#proveedorModal").modal("hide");
                    $("#addCostomerForm").removeClass("was-validated");
                    $("#addCostomerForm")[0].reset();
                    listarProveedores();
                });
            } else {
                Swal.fire({
                    target: document.getElementById('proveedorModal'),
                    title: 'Error',
                    text: response.error || 'No se pudo guardar el proveedor.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar el proveedor.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
});
//---editar
$("#tabla_proveedores").on("click", "#editar", function () {

    let row = $(this).closest("tr");
    proveedorId = row.find("td:eq(0)").text();
    let nombre = row.find("td:eq(1)").text();
    let rtn = row.find("td:eq(2)").text();
    let telefono = row.find("td:eq(3)").text();
    let direccion = row.find("td:eq(4)").text();
    let correo = row.find("td:eq(5)").text();
    let idAcopio = row.find("td:eq(6)").text();
    let precio = row.find("td:eq(7)").text();

    $("#nombre").val(nombre);
    $("#rtn").val(rtn);
    $("#telefono").val(telefono);
    $("#direccion").val(direccion);
    $("#correo").val(correo);
    $("#acopio").val(idAcopio);
    $(".ocultar").show();
    $("#labelprecio").html("Precio de compra: <b>Lps. " + precio + "</b>");

    $("#proveedorModal").modal("show");
}
);
//---eliminar
$("#tabla_proveedores").on("click", "#eliminar", function () {
    let row = $(this).closest("tr");
    proveedorId = row.find("td:eq(0)").text();

    Swal.fire({
        title: '¿Está seguro?',
        text: 'El proveedor será eliminado.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "./modules/finance/controller/suppliers.php?action=eliminar",
                type: "POST",
                data: { proveedorId: proveedorId },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Proveedor eliminado',
                            text: 'El proveedor ha sido eliminado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            listarProveedores();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.error || 'No se pudo eliminar el proveedor.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al eliminar el proveedor.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
});



//========================= Cambiar precios de compra =========================
{
$("#cambiarprecio").click(function () {
    Swal.fire({
        title: 'Cambiar precios de compra',
        html: `
            <div class="form-group">
                <label for="precio">El valor ingresado se aplicará a los precios de compra de cada proveedor</label>
                <input type="number" class="form-control" id="precio" name="precio" required>
            </div>
            </br>
            <div class="form-group d-flex align-items-center">
                <label class="mr-3">Tipo de cambio:&nbsp;&nbsp;&nbsp;</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipoCambio" id="cantidad" value="1" checked>
                    <label class="form-check-label" for="cantidad">Cantidad</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipoCambio" id="porcentaje" value="2">
                    <label class="form-check-label" for="porcentaje">Porcentaje</label>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        preConfirm: () => {
            let precio = Swal.getPopup().querySelector("#precio").value;
            let tipoCambio = Swal.getPopup().querySelector('input[name="tipoCambio"]:checked').value;
            if (!precio) {
                Swal.showValidationMessage("Debe ingresar un precio de compra.");
            }
            return { precio: precio, tipoCambio: tipoCambio };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let precio = result.value.precio;
            let tipoCambio = result.value.tipoCambio;
            $.ajax({
                url: "./modules/finance/controller/suppliers.php?action=cambiarPrecio",
                type: "POST",
                data: { precio: precio, tipoCambio: tipoCambio },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Precios actualizados',
                            text: 'Los precios de compra han sido actualizados correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                        listarProveedores();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.error || 'No se pudieron actualizar los precios de compra.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al actualizar los precios de compra.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
});

}
