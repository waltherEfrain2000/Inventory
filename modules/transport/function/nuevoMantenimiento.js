import { apiFetch, cargarTabla, generarSelect, getParamFromUrl } from "./helpers.js";

let trabajosARealizar = [];
let insumos = [];
let insumosLoaded = false;
let kilometraje_actual = 0;
let existencia_actual = 0;
$(function () {
    $("#navTransporte").addClass("pc-trigger");
    $("#navTransporte ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navMantenimientos").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    // Obtenemos el id del vehiculo desde la URL (en caso de que se haya redirigido desde la notificación)
    const idVehiculo = getParamFromUrl('vehiculo');

    obtenerVehiculos();
    obtenerTiposMantenimiento();
    obtenerTiposServicio();
    obtenerTalleres();
    obtenerBodegas();

    cargarTrabajos();
    cargarInsumos();

    $("#vehiculo").on("change", function () {
        const selected = $(this).find('option:selected');
        const placa = selected.data('placa');
        const estado = selected.data('estado');
        const nombre_estado = selected.data('nombre_estado');
        kilometraje_actual = Number(selected.data('kilometraje'));

        $("#imgPlaca").html(placa);
        const textcolor = (()=> { switch(estado){ case 1: return 'text-success'; case 3: return 'text-warning'; default: return 'text-danger';}})();
        $("#estado").prop("class",`form-control ${textcolor}`);
        $("#estado").val(nombre_estado).trigger( "change" );
        $("#txtKilometrajeActual").val(kilometraje_actual).trigger( "change" );
    });

    $("#insumos").on("change", function () {
        const unidad = $(this).find('option:selected').data('unidad');
        existencia_actual = $(this).find('option:selected').data('existencia');
        $("#unidad").val(unidad).trigger("change");
        $("#existencia").val(existencia_actual).trigger("change");
    });

    $("#bodega").on("change", function () {
        const id_bodega = Number(this.value);
        if(id_bodega != 0){
            obtenerInsumosBodega(id_bodega);
        }
        $("#unidad").val("--").trigger("change");
        $("#existencia").val("0.00").trigger("change");
        $("#cantidad").val("1").trigger("change");
    });

    $("#txtKilometrajeActual").on("change", function () {
        if (this.value < kilometraje_actual){
            Swal.fire({
                title: "Atención",
                icon: "warning",
                text: "El kilometraje actual no puede ser menor que el anterior",
                confirmButtonColor: "#3085d6",
            });
            this.value = kilometraje_actual;
        }
    });

    $("#tipoServicio").on("change", function () {
        if(this.value == 1){
            if(!insumosLoaded){
                // obtenerInsumos();
            }
            $(".insumosOT").prop("hidden", false)
            $("#costo").prop("disabled", true)
            $(".taller").prop("hidden", true)
            $("#costo").val("0.00").trigger("change");
            $("#taller").val("").trigger("change");
            cargarInsumos();
        }else{
            $(".insumosOT").prop("hidden", true)
            $("#costo").prop("disabled", false)
            $(".taller").prop("hidden", false)
            $("#bodega").val("").trigger("change");
            $("#insumos").html("");
            $("#unidad").val("--").trigger("change");
            $("#existencia").val("0.00").trigger("change");
            $("#cantidad").val("1").trigger("change");
            insumos = [];
        }
    });

    $("#tipoMantenimiento").on("change", function () {
        obtenerCatalogo(this.value);
        trabajosARealizar = [];
        cargarTrabajos();
    });

    $("#btnAgregarTrabajo").on("click", function () {
        if($("#catalogo").val() != ""){
            if(existeRegistro($("#catalogo").val(), trabajosARealizar)){
                Swal.fire({
                    title: "Atención",
                    icon: "warning",
                    text: "El trabajo seleccionado ya fue agregado a la lista",
                    confirmButtonColor: "#3085d6",
                  });
            }else{
                trabajosARealizar.push({
                    id: $("#catalogo").val(),
                    nombre: $("#catalogo option:checked").text().trim(),
                    costo: Number($("#costo").val()),
                });
    
                $("#catalogo").val("").trigger("change");
                $("#costo").val("0.00").trigger("change");
                
                cargarTrabajos();
            }
            
        } else {
            Swal.fire({
                title: "Atención",
                icon: "warning",
                text: "Debe seleccionar un trabajo de la lista",
                confirmButtonColor: "#3085d6",
              });
        }
    });

    $("#btnAgregarInsumo").on("click", function () {

        if($("#insumos").val() == "" || $("#insumos").val() == 0) {
            Swal.fire({
                title: "Atención",
                icon: "warning",
                text: "Debe seleccionar un insumo de la lista",
                confirmButtonColor: "#3085d6",
              });
            return;
        }

        if(existeRegistro($("#insumos").val(), insumos)){
            Swal.fire({
                title: "Atención",
                icon: "warning",
                text: "El insumo seleccionado ya fue agregado a la lista",
                confirmButtonColor: "#3085d6",
            });
            return;
        }

        if(Number($("#cantidad").val()) <= 0) {
            Swal.fire({
                title: "Atención",
                icon: "warning",
                text: "Debe ingresar una cantidad válida",
                confirmButtonColor: "#3085d6",
              });
            return;
        }

        if(Number($("#cantidad").val()) > Number(existencia_actual)) {
            Swal.fire({
                title: "Atención",
                icon: "warning",
                text: `La cantidad ingresada no puede ser mayor a la existencia actual (${existencia_actual})`,
                confirmButtonColor: "#3085d6",
              });
            return;
        }

        insumos.push({
            id: $("#insumos").val(),
            insumo: $("#insumos option:checked").text().trim(),
            unidad: $("#unidad").val(),
            cantidad: $("#cantidad").val(),
        });

        $("#insumos").val("").trigger("change");
        $("#unidad").val("--").trigger("change");
        $("#existencia").val("0.00").trigger("change");
        $("#cantidad").val("1").trigger("change");
        
        cargarInsumos();

    });

    $(document).on('click', '.delete-btn', function() {
        let index = $(this).data('index');
        let table = $(this).data('table');
        
        quitarElemento(index, table);
    });

    $("#btnGuardar").on("click", function () {

        if($("#vehiculo").val().trim() == "" || $("#tipoMantenimiento").val().trim() == "" || $("#tipoServicio").val().trim() == ""){
            Swal.fire({
                icon: "warning",
                title: "Atención",
                text: "Hay datos que no han sido ingresados",
            });
            return;
        }

        if(trabajosARealizar.length === 0){
            Swal.fire({
                icon: "warning",
                title: "Atención",
                text: "Debe seleccionar del catálogo los trabajos a realizar",
            });
            return;
        }

        if($("#tipoServicio").val().trim() == 2 && $("#taller").val().trim() == ''){
            Swal.fire({
                icon: "warning",
                title: "Atención",
                text: "Debe seleccionar un taller para el servicio tercerizado",
            });
            return;
        }

        if($("#tipoServicio").val().trim() == 1 && insumos.length != 0 && $("#bodega").val().trim() == ''){
            Swal.fire({
                icon: "warning",
                title: "Atención",
                text: "Debe seleccionar una bodega para los insumos",
            });
            return;
        }

        $("#btnGuardar").prop('disabled', true);

        let data = {
            id_vehiculo: $("#vehiculo").val().trim(),
            id_tipo_mantenimiento: $("#tipoMantenimiento").val().trim(),
            id_tipo_servicio: $("#tipoServicio").val().trim(),
            kilometraje_vehiculo: $("#txtKilometrajeActual").val().trim() || null,
            fecha_inicio: $("#fechaInicio").val() || null,
            trabajos: trabajosARealizar,
            insumos: insumos,
            comentarios: $("#comentarios").val().trim() || null,
            adjuntos: null,
            id_taller: $("#taller").val().trim() || null,
            id_bodega: $("#bodega").val().trim() || null,
            accion: "guardarMantenimiento"
        };
        guardarMantenimiento(data);
        $("#btnGuardar").prop('disabled', false);
        
    });

    $('#catalogo').select2({
        "language": {
            "noResults": function(){
                return "Debe seleccionar un tipo de mantenimiento para cargar el catálogo";
            }
        },
        placeholder: "Seleccione..."
    });

    $('#insumos').select2({
        "language": {
            "noResults": function(){
                return "Debe seleccionar una bodega para cargar los insumos";
            }
        },
        placeholder: "Seleccione..."
    });

    function quitarElemento (e, tabla) {
        if (tabla === 1) {
            trabajosARealizar.splice(e, 1);
            cargarTrabajos();

        } else {
            insumos.splice(e, 1);
            cargarInsumos();
        }
    };
    
    function cargarTrabajos() {
        // trabajosARealizar.length > 0 ? $("#tipoMantenimiento").prop("disabled", true) : $("#tipoMantenimiento").prop("disabled", false);
        var columns = [
            {
                className: "text-center",
                render: function (data, type, full, meta) {
                    return meta.row + 1;
                },
            },
            {
                className: "text-center",
                mDataProp: "nombre",
            },
            {
                className: "text-center",
                mDataProp: "costo",
                render: function (data, types, full, meta){
                    return `L. ${Intl.NumberFormat("en-US", {minimumFractionDigits: 2,}).format(full.costo)}`;
                }
            },
            
            {
                className: "text-center",
                width: '5%',
                render: function (data, types, full, meta) {
                    let menu = `<center>
                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-index="${meta.row}" data-table="1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </center>`;

                    return `${menu}`;

                },
            },
        ];

        // Llamado a la función para crear la tabla con los datos
        cargarTabla("#tablaTrabajos", trabajosARealizar, columns);

        const total = trabajosARealizar.reduce(
            (accumulator, currentValue) => accumulator + currentValue.costo,
            0
        );
        
        $("#totales").html(
            "L. " +
            Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(total)
        );

        trabajosARealizar.length > 0 ? $(".totalesFooter").prop("hidden", false) : $(".totalesFooter").prop("hidden", true);
        
    }

    function cargarInsumos() {
        // insumos.length > 0 ? '' : $("#bodega").val("").trigger("change");
        insumos.length > 0 ? $("#bodega").prop("disabled", true) : $("#bodega").prop("disabled", false);
        var columns = [
            {
                className: "text-center",
                render: function (data, type, full, meta) {
                    return meta.row + 1;
                },
            },
            {
                className: "text-center",
                mDataProp: "insumo",
            },
            {
                className: "text-center",
                mDataProp: "unidad",
            },
            {
                className: "text-center",
                mDataProp: "cantidad",
            },
            {
                className: "text-center",
                width: '5%',
                render: function (data, types, full, meta) {
                    let menu = `<center>
                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-index="${meta.row}" data-table="2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </center>`;

                    return `${menu}`;

                },
            },
        ];

        // Llamado a la función para crear la tabla con los datos
        cargarTabla("#tablaInsumos", insumos, columns);
    }


    function existeRegistro(id, objeto) {
        return objeto.some(function(item) {
            return item.id === id;
        });
    }

    async function obtenerVehiculos(){
        try {

            const result = await apiFetch('./modules/transport/controller/vehiculoController.php?action=getVehiculos');

            if (result.success && !result.data.error) {
                
                let vehiculos = result.data;
                
                let html = '<option value=""></option>';
                vehiculos.forEach(vehiculo => {
                    html += `<option value="${vehiculo.id}" data-kilometraje="${vehiculo.kilometraje_actual}" data-placa="${vehiculo.placa}" data-estado="${vehiculo.estado}" data-nombre_estado="${vehiculo.nombre_estado}">${vehiculo.placa} - ${vehiculo.marca} ${vehiculo.modelo} ${vehiculo.anio}</option>`;
                });

                document.getElementById("vehiculo").innerHTML = html;

                if(idVehiculo != null) $("#vehiculo").val(idVehiculo).trigger("change");
            } else {
                throw new Error("Ha ocurrido un error al obtener los vehiculos");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerTiposMantenimiento(){
        try {

            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php?action=getTiposMantenimiento');

            if (result.success && !result.data.error) {
                
                let tipos = result.data;

                generarSelect(tipos, "id", "nombre", "tipoMantenimiento");

            } else {
                throw new Error("Ha ocurrido un error al obtener los tipos de mantenimiento");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerTiposServicio(){
        try {

            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php?action=getTiposServicio');

            if (result.success && !result.data.error) {
                
                let tipos = result.data;

                generarSelect(tipos, "id", "nombre", "tipoServicio");

            } else {
                throw new Error("Ha ocurrido un error al obtener los tipos de servicio");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerTalleres(){
        try {

            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php?action=getTalleres');

            if (result.success && !result.data.error) {
                
                let talleres = result.data;

                generarSelect(talleres, "id", "nombre", "taller");

            } else {
                throw new Error("Ha ocurrido un error al obtener los talleres");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerCatalogo(id_tipo_mantenimiento){
        try {

            const result = await apiFetch(`./modules/transport/controller/mantenimientoController.php?action=getCatalogo&id_tipo_mantenimiento=${id_tipo_mantenimiento}`);

            if (result.success && !result.data.error) {
                
                let catalogo = result.data;

                generarSelect(catalogo, "id", "nombre", "catalogo");

            } else {
                throw new Error("Ha ocurrido un error al obtener el catalogo");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerBodegas(){
        try {

            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php?action=getBodegas');

            if (result.success && !result.data.error) {
                
                let bodegas = result.data;

                generarSelect(bodegas, "id", "nombre", "bodega");

                
            } else {
                throw new Error("Ha ocurrido un error al obtener las bodegas");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerInsumos(){
        try {

            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php?action=getArticulos');

            if (result.success && !result.data.error) {
                
                let insumos = result.data;

                let dataset={
                    "unidad": "unidad"
                }

                generarSelect(insumos, "id", "nombre", "insumos", dataset);

                insumosLoaded = true;
                
            } else {
                throw new Error("Ha ocurrido un error al obtener los insumos");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerInsumosBodega(id_bodega){
        try {

            const result = await apiFetch(`./modules/transport/controller/mantenimientoController.php?action=getArticulosBodega&id_bodega=${id_bodega}`);

            if (result.success && !result.data.error) {
                
                let insumos = result.data;

                let dataset={
                    "unidad": "unidad",
                    "existencia": "existencia"
                }

                generarSelect(insumos, "id", "nombre", "insumos", dataset);

                insumosLoaded = true;
                
            } else {
                throw new Error("Ha ocurrido un error al obtener los insumos");
            }
        } catch (error) {
            console.log(error);
        }
    }

    const guardarMantenimiento = async (data) => {
        Swal.fire({
            title: "Cargando...",
            text: "Por favor espera mientras se guardan los datos.",
            allowOutsideClick: false,
            didOpen: () => {
            Swal.showLoading();
            },
        });
        try {
            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php', 'POST', data);
            Swal.close();
            
            if (result.success && !result.data.error) {
                const res = result.data; 
                Swal.fire({
                    icon: "success",
                    title: "Mantenimiento",
                    text: res.message || "Guardado exitosamente",
                }).then(function () {
                    window.location.href = `?module=mantenimientos`;
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