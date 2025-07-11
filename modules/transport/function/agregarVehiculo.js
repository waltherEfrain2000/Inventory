// Función utilitaria para hacer peticiones fetch al controlador
const apiFetch = async (url, method = 'GET', data = null, customHeaders = {}) => {
    const options = { method, headers: {} };

    // Verifica si los datos son FormData para no agregar Content-Type manualmente (en caso de enviar archivos)
    if (data instanceof FormData) {
        options.body = data;
    } else if (data) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(data);
    }

    // Agrega headers personalizados si vienen en caso de que sean necesarios
    if (customHeaders && typeof customHeaders === 'object') Object.assign(options.headers, customHeaders);

    try {
        const response = await fetch(url, options);
        const result = await response.json();
        if (!response.ok) throw new Error(`${result.message}`);
        return { success: true, data: result };
    } catch (error) {
        console.error('Error en apiFetch: ', error);
        return { success: false, message: error.message };
    }
};

$(function () {
    $("#navTransporte").addClass("pc-trigger");
    $("#navTransporte ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navVehiculos").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    obtenerMarcas();
    obtenerTiposVehiculo();
    obtenerPertenencias();

    $("#marca").on("change", function () {
        const id_marca = document.getElementById("marca").value;
        obtenerModelos(id_marca);
    });

    $("#btnGuardar").on("click", function () {

        if($("#marca").val().trim() == "" || $("#modelo").val().trim() == "" || $("#placa").val().trim() == "" || $("#anio").val().trim() == "" || $("#color").val().trim() == "" || $("#tipo").val().trim() == "" || $("#pertenencia").val().trim() == "" || $("#txtKilometrajeActual").val().trim() == ""){
            Swal.fire({
                icon: "warning",
                title: "Atención",
                text: "Debe ingresar toda la información",
            });
            return;
        }
        $("#btnGuardar").prop('disabled', true);
        // Mensaje de carga
        Swal.fire({
            title: "Cargando...",
            text: "Por favor espera mientras se guardan los datos.",
            allowOutsideClick: false,
            didOpen: () => {
            Swal.showLoading();
            },
        });

        const data = {
            id_marca: document.getElementById("marca").value.trim(),
            id_modelo: document.getElementById("modelo").value.trim(),
            placa: document.getElementById("placa").value.trim(),
            anio: document.getElementById("anio").value.trim(),
            color: document.getElementById("color").value.trim() || null,
            id_tipo_vehiculo: document.getElementById("tipo").value.trim(),
            id_pertenencia: document.getElementById("pertenencia").value.trim(),
            kilometraje_actual: document.getElementById("txtKilometrajeActual").value.trim() || null,
            intervalo_mantenimiento: document.getElementById("txtIntervaloMantenimiento").value.trim() || 5000,
            accion: "guardar"
        };
        guardarVehiculo(data);
        $("#btnGuardar").prop('disabled', false);
        
    });

    $("#placa").on("keyup", function () {
        var placa = $("#placa").val();
        $("#imgPlaca").html(placa.substr(0, 3) + " " + placa.substr(3, 4));
    });

    $('#modelo').select2({
        "language": {
            "noResults": function(){
                return "No hay modelos registrados de esa marca.";
            }
        },
        placeholder: "Seleccione..."
    });

    async function obtenerMarcas(){
        try {

            const result = await apiFetch('./modules/settings/controller/brandController.php');

            if (result.success) {
                marcas = result.data; 
                
                let html = '<option value=""></option>';
                marcas.forEach(marca => {
                    html += `<option value="${marca.id}">${marca.nombre}</option>`;
                });

                document.getElementById("marca").innerHTML = html;
                
            } else {
                throw new Error("Ha ocurrido un error al obtener las marcas");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerModelos(id_marca) {
        try {
            const result = await apiFetch(`./modules/transport/controller/vehiculoController.php?action=getModelos&id_marca=${id_marca}`);

            if (result.success) {
                modelos = result.data;
                
                let html = '<option value=""></option>';
                modelos.forEach(modelo => {
                    html += `<option value="${modelo.id}">${modelo.nombre}</option>`;
                });

                document.getElementById("modelo").innerHTML = html;
                
            } else {
                throw new Error("Ha ocurrido un error al obtener los modelos");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerTiposVehiculo() {
        try {
            const result = await apiFetch('./modules/settings/controller/vehicleTypeController.php');

            if (result.success) {
                tipos = result.data;
                
                let html = '<option value=""></option>';
                tipos.forEach(tipo => {
                    html += `<option value="${tipo.id}">${tipo.nombre}</option>`;
                });

                document.getElementById("tipo").innerHTML = html;
                
            } else {
                throw new Error("Ha ocurrido un error al obtener los tipos");
            }
        } catch (error) {
            console.log(error);
        }
    }
    async function obtenerPertenencias() {
        try {
            const result = await apiFetch('./modules/settings/controller/ownershipController.php');

            if (result.success) {
                pertenencias = result.data;
                
                let html = '<option value=""></option>';
                pertenencias.forEach(pertenencia => {
                    html += `<option value="${pertenencia.id}">${pertenencia.nombre}</option>`;
                });

                document.getElementById("pertenencia").innerHTML = html;
                
            } else {
                throw new Error("Ha ocurrido un error al obtener las pertenencias");
            }
        } catch (error) {
            console.log(error);
        }
    }

    const guardarVehiculo = async (data) => {
        try {
            const result = await apiFetch('./modules/transport/controller/vehiculoController.php', 'POST', data);
            Swal.close();
            if (result.success) {
                const res = result.data; 
                Swal.fire({
                    icon: "success",
                    title: "Vehículos",
                    text: res.message || "Guardado exitosamente",
                }).then(function () {
                    window.location.href = `?module=vehicles`;
                });
                
            } else {
                const errorData = result;
                console.error('Error:', errorData.message || 'Error al guardar el registro');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al guardar el registro',
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al guardar el registro',
            });
        }
    }
});

FilePond.registerPlugin(
    FilePondPluginImagePreview
);

FilePond.setOptions({
    labelIdle: 'Para agregar adjuntos<br> arrastra archivos o <span class="filepond--label-action">haz clic aquí</span>',
});

FilePond.create(document.querySelector('#pics'));