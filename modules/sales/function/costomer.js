//========================= Variables =========================
// la variable clienteId se utiliza para almacenar el id del cliente que se va a editar o eliminar
let clienteId = 0;

//=========================
$(document).ready(function () {
    listarClientes();
});

$("#nuevocliente").click(function () {
    $("#labelprecio").html("Precio de venta:");
    $(".ocultar").hide();

    if(clienteId != 0)
    {
        $("#addCostomerForm").removeClass("was-validated");
        $("#addCostomerForm")[0].reset();
        $("#addCostomerForm").find("input, select").val("");
    }
    clienteId = 0;
    $("#clienteModal").modal("show");
});

//========================= Listar clientes ========================= pican los mosquitos :v
function listarClientes() {
    $.ajax({
        url: "./modules/sales/controller/costomerC.php?action=listar",
        type: "POST",
        dataType: "json",
        success: function (response) {

            let data = response;
            let tableBody = $("#tabla_clientes tbody");
            tableBody.empty();

            $.each(data, function (index, data) {
                let indent = "&nbsp;".repeat(data.nombre * 4);
                tableBody.append(`
                    <tr>
                        <td >${data.idCliente}</td>
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
            console.error("Error al cargar los clientes:", error);
            alert("Error al cargar los clientes.");
        }
    });
}


//========================= Guardar clientes // Editar clientes ========================= no me salen las validaciones que quiero :v

$("#tabla_clientes").on("click", "#editar", function () {

    let row = $(this).closest("tr");
    clienteId = row.find("td:eq(0)").text();
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
    $("#labelprecio").html("Precio de venta: <b>Lps. "+precio+"</b>");

    $("#clienteModal").modal("show");
}
);

$("#btnguardar").click(function () {
    $("#addCostomerForm").addClass("was-validated");

    let formData = $("#addCostomerForm").serialize()+"&clienteId="+clienteId;
    // agregar el id del valorSeleccionado a la variable formData
    formData += "&valorSeleccionado=" + valorSeleccionado;
        console.log(formData);
    var accion = (clienteId == 0) ? "guardar" : "editar";
    
    $.ajax({
        url: "./modules/sales/controller/costomerC.php?action=" + accion,
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    target: document.getElementById('clienteModal'),
                    title: 'Cliente agregado',
                    text: 'El cliente ha sido guardado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $("#clienteModal").modal("hide");
                    $("#addCostomerForm").removeClass("was-validated");
                    $("#addCostomerForm")[0].reset();
                    listarClientes();
                });
            } else {
                Swal.fire({
                    target: document.getElementById('clienteModal'),
                    title: 'Error',
                    text: response.error || 'No se pudo guardar el cliente.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar el cliente.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
});


//========================= Eliminar clientes =========================
$("#tabla_clientes").on("click", "#eliminar", function () {
    let row = $(this).closest("tr");
    clienteId = row.find("td:eq(0)").text();

    Swal.fire({
        title: '¿Está seguro?',
        text: 'El cliente será eliminado.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "./modules/sales/controller/costomerC.php?action=eliminar",
                type: "POST",
                data: { clienteId: clienteId },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Cliente eliminado',
                            text: 'El cliente ha sido eliminado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            listarClientes();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.error || 'No se pudo eliminar el cliente.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al eliminar el cliente.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
});



//========================= Cambiar precios de venta =========================
//abrir un sweet alert para cambiar los precios de venta con "#cambiarprecio"
$("#cambiarprecio").click(function () {
    Swal.fire({
        title: 'Cambiar precios de venta',
        html: `
            <div class="form-group">
                <label for="precio">El valor ingresado se aplicará a los precios de venta de cada cliente</label>
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
                Swal.showValidationMessage("Debe ingresar un precio de venta.");
            }
            return { precio: precio, tipoCambio: tipoCambio };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let precio = result.value.precio;
            let tipoCambio = result.value.tipoCambio;
            $.ajax({
                url: "./modules/sales/controller/costomerC.php?action=cambiarPrecio",
                type: "POST",
                data: { precio: precio, tipoCambio: tipoCambio },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Precios actualizados',
                            text: 'Los precios de venta han sido actualizados correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                        listarClientes();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.error || 'No se pudieron actualizar los precios de venta.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al actualizar los precios de venta.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
});

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
//========================= Historial de precios =========================
$("#tabla_clientes").on("click", "#historial", function () {
    let row = $(this).closest("tr");
    clienteId = row.find("td:eq(0)").text();
    let nombre = row.find("td:eq(1)").text();

    $.ajax({
        url: "./modules/sales/controller/costomerC.php?action=historial",
        type: "POST",
        data: { clienteId: clienteId },
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

                if(item.incremento > 0)
                {
                    item.incremento = `<span class="text-success"><i class="ph-duotone ph-arrow-fat-line-up"></i>   ${item.incremento}</span>`;
                }else if(item.incremento < 0)
                {
                    item.incremento = `<span class="text-danger"><i class="ph-duotone ph-arrow-fat-line-down"></i>   ${item.incremento}</span>`;   
                }

                tableContent += `
                    <tr>
                        <td>${item.fechaCreado}</td>
                        <td>${item.precioventa}</td>
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