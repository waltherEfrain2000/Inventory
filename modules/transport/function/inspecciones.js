import { apiFetch, cargarTabla } from './helpers.js';

let inspecciones = [];
let kilometraje_actual = 0;
let isUserEvent = true;
$(function () {
    $("#navTransporte").addClass("pc-trigger");
    $("#navTransporte ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navInspecciones").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    obtenerInspecciones();
    obtenerVehiculos();

    $('#btnAgregarInspeccion').on('click', function() {
        $("#modalInspeccion").modal("show");
        $(".form-control, select").prop("disabled", false);
        $(".form-control").val("");
        $("#vehiculo").val("").val("").trigger("change");
    });

    $('#tabla tbody').on('click', '.view-btn', function() {
        $("#modalInspeccion").modal("show");
        $(".form-control, select").prop("disabled", true);

        isUserEvent = false;
        $("#vehiculo").val($(this).closest('tr').find('[data-vehiculo]').data('vehiculo')).trigger("change");
        $("#kilometraje").val($(this).closest('tr').find('[data-kilometraje]').data('kilometraje')).trigger("change");
        $("#observaciones").val($(this).closest('tr').find('[data-observaciones]').data('observaciones')).trigger("change");
        isUserEvent = true;
    });

    $("#vehiculo").on("change", function () {
        if (!isUserEvent) return;
        kilometraje_actual = Number($(this).find('option:selected').data('kilometraje'));
        $("#kilometraje").val(kilometraje_actual).trigger( "change" );
    });

    $("#kilometraje").on("change", function () {
        if (!isUserEvent) return;
        if (this.value < kilometraje_actual){
            Swal.fire({
                title: "Atención",
                icon: "warning",
                text: "El kilometraje actual no puede ser menor que el anterior",
                confirmButtonColor: "#3085d6",
                target: "#modalInspeccion",
            });
            this.value = kilometraje_actual;
        }
    });

    $('#tabla tbody').on('click', '.delete-btn', function() {
        Swal.fire({
            title: "¿Está seguro de inactivar el vehículo?",
            text: "Se definirá como estado inactivo",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const data = {
                    id: $(this).closest('tr').find('[data-id]').data('id'),
                    accion: "eliminar"
                };
                eliminarVehiculo(data);
            }
            
        });

        
    });

    $('#vehiculo').select2({
        dropdownParent: $('#modalInspeccion'),
        placeholder: "Seleccione..."
    });
    

    function listarInspecciones() {

        var columns = [
            {
                className: "text-center",
                mDataProp: "id",
                width: '5%',
            },
            {
                className: "text-center tdPlaca",
                mDataProp: "placa",  
                width: '10%',
                render: function (data, types, full, meta){

                    // return `${full.placa.substr(0, 3) + "&nbsp;" + full.placa.substr(3, 4)}`;
                    return `${full.placa}`;
                }                      
            },
            {
                className: "text-center",
                mDataProp: "infoVehiculo",
            },
            {
                className: "text-center",
                mDataProp: "fecha",
            },
            {
                className: "text-center",
                mDataProp: "kilometraje",
                render: function (data, types, full, meta){

                    // return `${full.placa.substr(0, 3) + "&nbsp;" + full.placa.substr(3, 4)}`;
                    return `${Intl.NumberFormat("en-US", {minimumFractionDigits: 0,}).format(full.kilometraje)} km`;
                } 
            },
            {
                className: "text-center",
                mDataProp: "observaciones",
            },
            {
                className: "text-center",
                width: '5%',
                render: function (data, types, full, meta) {
                    let menu = `<center data-id="${full.id}" data-vehiculo="${full.id_vehiculo}" data-kilometraje="${full.kilometraje}" data-observaciones="${full.observaciones}">

                                    <button class="btn btn-secondary btn-sm view-btn" title="Ver Inspección">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </center>`;

                    return `${menu}`;

                },
            },
        ];

        // Llamado a la función para crear la tabla con los datos
        cargarTabla("#tabla", inspecciones, columns);
    }

    $("#btnGuardar").on("click", function () {

        if($("#vehiculo").val().trim() == "" || $("#kilometraje").val().trim() == "" || $("#observaciones").val().trim() == ""){
            Swal.fire({
                icon: "warning",
                title: "Atención",
                text: "Debe ingresar toda la información",
                target: "#modalInspeccion",
            });
            return;
        }

        if ($("#kilometraje").val().trim() < kilometraje_actual){
            Swal.fire({
                title: "Atención",
                icon: "warning",
                text: "El kilometraje actual no puede ser menor que el anterior",
                confirmButtonColor: "#3085d6",
                target: "#modalInspeccion",
            });
            return;
        }
        
        $("#btnGuardar").prop('disabled', true);
        // Mensaje de carga
        Swal.fire({
            title: "Cargando...",
            text: "Por favor espera mientras se guardan los datos.",
            target: "#modalInspeccion",
            allowOutsideClick: false,
            didOpen: () => {
            Swal.showLoading();
            },
        });

        const data = {
            id_vehiculo: document.getElementById("vehiculo").value.trim(),
            kilometraje: document.getElementById("kilometraje").value.trim(),
            observaciones: document.getElementById("observaciones").value.trim(),
            accion: "guardarInspeccion"
        };
        guardarInspeccion(data);
        
        
        
    });

    async function obtenerInspecciones(){
        try {

            const result = await apiFetch('./modules/transport/controller/vehiculoController.php?action=getInspecciones');

            if (result.success && !result.data.error) {
                inspecciones = result.data;
                listarInspecciones();
            } else {
                throw new Error("Ha ocurrido un error al obtener los vehiculos");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerVehiculos(){
        try {

            const result = await apiFetch('./modules/transport/controller/vehiculoController.php?action=getVehiculos');

            if (result.success && !result.data.error) {
                
                let vehiculos = result.data;
                
                let html = '<option value=""></option>';
                vehiculos.forEach(vehiculo => {
                    html += `<option value="${vehiculo.id}" data-kilometraje="${vehiculo.kilometraje_actual}" data-placa="${vehiculo.placa}">${vehiculo.placa} - ${vehiculo.marca} ${vehiculo.modelo} ${vehiculo.anio}</option>`;
                });

                document.getElementById("vehiculo").innerHTML = html;

            } else {
                throw new Error("Ha ocurrido un error al obtener los vehiculos");
            }
        } catch (error) {
            console.log(error);
        }
    }
      
    async function guardarInspeccion(data){
        try {
            const result = await apiFetch('./modules/transport/controller/vehiculoController.php', 'POST', data);

            Swal.close();
            
            if (result.success && !result.data.error) {
                const res = result.data; 
                Swal.fire({
                    icon: "success",
                    title: "Inspección",
                    text: res.message || "Guardado exitosamente",
                    target: "#modalInspeccion",
                }).then(function () {
                    obtenerInspecciones();
                    $("#modalInspeccion").modal("hide");
                    $("input, select").val("").trigger("change");
                });
                
            } else {
                const errorData = result;
                console.error('Error:', errorData.message || 'Error al guardar el registro');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al guardar el registro',
                    target: "#modalInspeccion",
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al guardar el registro',
                target: "#modalInspeccion",
            });
        }
        $("#btnGuardar").prop('disabled', false);
    }
});