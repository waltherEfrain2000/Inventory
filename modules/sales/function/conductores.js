//========================= Variables =========================
// la variable conductorId se utiliza para almacenar el id del conductor que se va a editar o eliminar
let conductorId = 0;

//=========================
$(document).ready(function () {
    listarConductor();
});

$("#nuevoconductor").click(function () {
    $(".ocultar").hide();

    if(conductorId != 0)
    {
        $("#addConductorForm").removeClass("was-validated");
        $("#addConductorForm")[0].reset();
        $("#addConductorForm").find("input, select").val("");
    }
    conductorId = 0;
    $("#conductorModal").modal("show");
});

//========================= Listar conductor ========================= pican los mosquitos :v
function listarConductor() {
    $.ajax({
        url: "./modules/sales/controller/conductoresC.php?action=listar",
        type: "POST",
        dataType: "json",
        success: function (response) {

            let data = response;
            let tableBody = $("#tabla_conductores tbody");
            tableBody.empty();

            $.each(data, function (index, data) {
                tableBody.append(`
                    <tr>
                        <td >${data.idConductor}</td>
                        <td >${data.nombre}</td>
                        <td >${data.identificacion}</td>
                        <td >${data.cel}</td>
                        <td >${data.direccion}</td>
                        <td >${data.sucursal}</td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <a href="#" class="btn btn-warning btn-sm" id="editar"><i class="ph-duotone ph-pencil-line"></i></a>
                                <a href="#" class="btn btn-danger btn-sm" id="eliminar"><i class="ph-duotone ph-trash"></i></a>
                            </div>
                        </td>

                    </tr>
                `);
            });

        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los conductor:", error);
            alert("Error al cargar los conductor.");
        }
    });
}


//========================= Guardar conductor // Editar conductor ========================= no me salen las validaciones que quiero :v

$("#tabla_conductores").on("click", "#editar", function () {

    let row = $(this).closest("tr");
    conductorId = row.find("td:eq(0)").text();
    let nombre = row.find("td:eq(1)").text();
    let rtn = row.find("td:eq(2)").text();
    let cel = row.find("td:eq(3)").text();
    let direccion = row.find("td:eq(4)").text();
    let idAcopio = row.find("td:eq(5)").text();

    $("#nombre").val(nombre);
    $("#rtn").val(rtn);
    $("#telefono").val(cel);
    $("#direccion").val(direccion);
    $("#acopio").val(idAcopio);
    $(".ocultar").show();

    $("#conductorModal").modal("show");
}
);

$("#btnguardar").click(function () {
    $("#addConductorForm").addClass("was-validated");

    let formData = $("#addConductorForm").serialize()+"&conductorId="+conductorId;
        console.log(formData);
    var accion = (conductorId == 0) ? "guardar" : "editar";
    
    $.ajax({
        url: "./modules/sales/controller/conductoresC.php?action=" + accion,
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    target: document.getElementById('conductorModal'),
                    title: 'Conductor agregado',
                    text: 'El conductor ha sido guardado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $("#conductorModal").modal("hide");
                    $("#addConductorForm").removeClass("was-validated");
                    $("#addConductorForm")[0].reset();
                    listarConductor();
                });
            } else {
                Swal.fire({
                    target: document.getElementById('conductorModal'),
                    title: 'Error',
                    text: response.error || 'No se pudo guardar el conductor.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                target: document.getElementById('conductorModal'),
                title: 'Error',
                text: 'Hubo un problema al guardar el conductor.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
});


//========================= Eliminar conductor =========================
$("#tabla_conductores").on("click", "#eliminar", function () {
    let row = $(this).closest("tr");
    conductorId = row.find("td:eq(0)").text();

    Swal.fire({
        title: '¿Está seguro?',
        text: 'El conductor será eliminado.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "./modules/sales/controller/conductoresC.php?action=eliminar",
                type: "POST",
                data: { conductorId: conductorId },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Conductor eliminado',
                            text: 'El conductor ha sido eliminado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            listarConductor();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.error || 'No se pudo eliminar el conductor.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al eliminar el conductor.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
});



