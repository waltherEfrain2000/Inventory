// CRUD de Proveedores usando jQuery
$(function () {
    const id = obtenerParametroUrl("id");

    if(id){
        cargarProveedor(id);
    }
    $("#guardarRegistro").on("click", function (e) {
        e.preventDefault();

        let datos = obtenerDatosProveedor();
        if (!datos) return;

     
        console.log(id)
        if (id) {
            datos.id = id;
            actualizarProveedor(datos);
        } else {
            agregarProveedor(datos);
        }
    });
});

function agregarProveedor(datos) {
    $.ajax({
        url: "modules/finance/controller/suppliers.php?action=agregar",
        type: "POST",
        data: JSON.stringify(datos),
        contentType: "application/json",
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    title: 'Éxito',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = "?module=supplier";
                });
            } else {
                Swal.fire('Error', response.error, 'error');
            }
        }
    });
}

function actualizarProveedor(datos) {
    $.ajax({
        url: "modules/finance/controller/suppliers.php?action=actualizar",
        type: "POST",
        data: JSON.stringify(datos),
        contentType: "application/json",
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    title: 'Éxito',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = "?module=supplier";
                });
            } else {
                Swal.fire('Error', response.error, 'error');
            }
        }
    });
}

function cargarProveedor(id) {
    $.get(`modules/finance/controller/suppliers.php?action=obtener&id=${id}`, function (response) {
        if (response.success) {
            let p = response.data;
            $("#proveedor_id").val(p.id);
            $("#nombreProveedor").val(p.nombre);
            $("#rtn").val(p.rtn ?? "");
            $("#direccion").val(p.direccion ?? "");
            $("#telefono").val(p.telefono ?? "");
            $("#correo").val(p.correo ?? "");
        } else {
            Swal.fire('Error', response.error, 'error');
        }
    }, 'json');
}

function obtenerDatosProveedor() {
    let nombre = $("#nombreProveedor").val()?.trim() ?? "";
    let rtn = $("#rtn").val()?.trim() ?? "";
    let direccion = $("#direccion").val()?.trim() ?? "";
    let telefono = $("#telefono").val()?.trim() ?? "";
    let correo = $("#correo").val()?.trim() ?? "";

    if (!nombre) {
        Swal.fire("Campo requerido", "El nombre del proveedor es obligatorio", "warning");
        return null;
    }
    if (!direccion) {
        Swal.fire("Campo requerido", "La dirección del proveedor es obligatoria", "warning");
        return null;
    }
    if (!telefono) {
        Swal.fire("Campo requerido", "El teléfono del proveedor es obligatorio", "warning");
        return null;
    }
    if (!correo) {
        Swal.fire("Campo requerido", "El correo del proveedor es obligatorio", "warning");
        return null;
    }


    return {
        nombre,
        rtn,
        direccion,
        telefono,
        correo,
    };
}

function limpiarFormularioProveedor() {
    $("#formProveedor")[0].reset();
    $("#proveedor_id").val("");
}

function obtenerParametroUrl(nombre) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(nombre); 
}

