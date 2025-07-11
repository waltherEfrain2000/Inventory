import { apiFetch, getParamFromUrl, generarSelect } from "./helpers.js";

$(function () {
    $("#navTransporte").addClass("pc-trigger");
    $("#navTransporte ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navVehiculos").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    const idVehiculo = getParamFromUrl('id');
    const action = getParamFromUrl('module');

    if(action != 'verVehiculo'){
        $("#divSave").html(
            '<button type="button" class="btn btn-shadow btn-success" id="btnGuardar">Guardar Cambios</button>'
        );

        $(".titulo").html("Editar Vehículo");
    } else {
        $(".titulo").html("Ver Vehículo");
    }
    
    setLoadingStateInputs();
    inicializarPantalla(idVehiculo);

    $("#marca").on("change", function () {
        const id_marca = document.getElementById("marca").value;
        obtenerModelos(id_marca);
    });

    $("#btnGuardar").on("click", function () {

        if($("#marca").val().trim() == "" || $("#modelo").val().trim() == "" || $("#placa").val().trim() == "" || $("#anio").val().trim() == "" || $("#color").val().trim() == "" || $("#tipo").val().trim() == "" || $("#pertenencia").val().trim() == ""){
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
            id: idVehiculo,
            id_marca: document.getElementById("marca").value.trim(),
            id_modelo: document.getElementById("modelo").value.trim(),
            placa: document.getElementById("placa").value.trim(),
            anio: document.getElementById("anio").value.trim(),
            color: document.getElementById("color").value.trim() || null,
            id_tipo_vehiculo: document.getElementById("tipo").value.trim(),
            id_pertenencia: document.getElementById("pertenencia").value.trim(),
            intervalo_mantenimiento: document.getElementById("txtIntervaloMantenimiento").value.trim() || 5000,
            accion: "editar"
        };
        guardarVehiculo(data);
        $("#btnGuardar").prop('disabled', false);
    });

    $("#placa").on("keyup", function () {
        var placa = $("#placa").val();
        $("#imgPlaca").html(placa.substr(0, 3) + " " + placa.substr(3, 4));
      });


    async function inicializarPantalla(idVehiculo) {
        try {
            await Promise.all([
                obtenerMarcas(),
                obtenerTiposVehiculo(),
                obtenerPertenencias()
            ]);
    
            if (idVehiculo) {
                await obtenerVehiculo(idVehiculo);
            }
        } catch (error) {
            console.error("Error inicializando la pantalla", error);
        }
    }
    

    async function obtenerMarcas(){
        try {

            const result = await apiFetch('./modules/settings/controller/brandController.php');

            if (result.success) {
                let marcas = result.data; 

                generarSelect(marcas, 'id', 'nombre', 'marca');
                
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
                let modelos = result.data;

                generarSelect(modelos, 'id', 'nombre', 'modelo');

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
                let tipos = result.data;

                generarSelect(tipos, 'id', 'nombre', 'tipo');
                
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
                let pertenencias = result.data;

                generarSelect(pertenencias, 'id', 'nombre', 'pertenencia');
                
            } else {
                throw new Error("Ha ocurrido un error al obtener las pertenencias");
            }
        } catch (error) {
            console.log(error);
        }
    }


    async function obtenerVehiculo(idVehiculo){
        
        try {

            const result = await apiFetch(`./modules/transport/controller/vehiculoController.php?action=getVehiculoId&id=${idVehiculo}`);

            if (result.success) {
                let vehiculo = result.data; 

                // Cargar los datos del vehiculo
                $("#marca").val(vehiculo.id_marca).trigger("change");

                await obtenerModelos(vehiculo.id_marca)
                $("#placa").val(vehiculo.placa);
                $("#imgPlaca").html(vehiculo.placa);
                $("#anio").val(vehiculo.anio);
                $("#color").val(vehiculo.color);
                $("#tipo").val(vehiculo.id_tipo_vehiculo).trigger("change");
                $("#pertenencia").val(vehiculo.id_pertenencia).trigger("change");
                $("#txtKilometrajeActual").val(vehiculo.kilometraje_actual);
                $("#txtIntervaloMantenimiento").val(vehiculo.intervalo_mantenimiento);
                
                $("#modelo").val(vehiculo.id_modelo).trigger("change");
 
            } else {
                throw new Error("Ha ocurrido un error al obtener el vehículo");
            }
        } catch (error) {
            console.log(error);
        } finally {
            removeLoadingStateInputs();
        }
    }

    function setLoadingStateInputs() {
        $("input, select").addClass("loading");
        if(action == 'verVehiculo'){
            $("input, select").prop("disabled", true);
        }
        $("select").each(function () {
            $(this).select2("destroy").addClass("loading");
            if(action == 'verVehiculo'){
                $(this).prop("disabled", true);
            }
        });

    }
    
    function removeLoadingStateInputs() {
        $("input, select").removeClass("loading");
        $("select").each(function () {
            $(this).select2({placeholder: "Seleccione..."});  // Volver a inicializar Select2
        });
    }

    async function guardarVehiculo(data){
        try {
            const result = await apiFetch('./modules/transport/controller/vehiculoController.php', 'PUT', data);
            Swal.close();
            if (result.success) {
                const res = result.data; 
                Swal.fire({
                    icon: "success",
                    title: "Vehículos",
                    text: res.message || "Actualizado exitosamente",
                }).then(function () {
                    window.location.href = `?module=vehicles`;
                });
                
            } else {
                const errorData = result;
                console.error('Error:', errorData.message || 'Error al actualizar el registro');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al actualizar el registro',
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al actualizar el registro',
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