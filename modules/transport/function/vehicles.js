import { apiFetch, cargarTabla, obtenerNotificaciones } from './helpers.js';

let vehiculos = [];
$(function () {
    $("#navTransporte").addClass("pc-trigger");
    $("#navTransporte ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navVehiculos").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    obtenerVehiculosTarjetas();
    obtenerVehiculos();
    obtenerNotificaciones();

    $('#ListadoVehiculosTabla tbody').on('click', '.edit-btn', function() {
        const id = $(this).closest('tr').find('[data-id]').data('id');
        window.location.href = `?module=editarVehiculo&id=${id}`;
    });

    $('#ListadoVehiculosTabla tbody').on('click', '.view-btn', function() {
        const id = $(this).closest('tr').find('[data-id]').data('id');
        window.location.href = `?module=verVehiculo&id=${id}`;
    });

    $('#ListadoVehiculosTabla tbody').on('click', '.delete-btn', function() {
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

    function listarVehiculos() {

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
                mDataProp: "marca",
            },
            {
                className: "text-center",
                mDataProp: "modelo",
            },
            {
                className: "text-center",
                mDataProp: "anio",
            },
            {
                className: "text-center",
                mDataProp: "tipo_vehiculo",
            },
            
            {
                className: "text-center",
                mDataProp: "estado",
                render: function (data, types, full, meta){
                    return `
                    <span class="badge ${full.estado == 1 ? 'bg-success' : full.estado == 2 ? 'bg-danger' : full.estado == 3 ? 'bg-warning' : full.estado == 4 ? 'bg-danger-subtle' : 'bg-secondary'}">${full.nombre_estado}</span>
                    ${full.estado == 2 ? `<br><span class="remision fw-bold">${full.infoVenta == null ? '' :  `Remisión No.${full.infoVenta}`}</span>` : ''}
                `;
                }
                
            },
            {
                className: "text-center",
                mDataProp: "pertenencia",
            },
            {
                className: "text-center",
                width: '5%',
                render: function (data, types, full, meta) {
                    let menu = `<center data-id="${full.id}">

                                    <button class="btn btn-secondary btn-sm view-btn" title="Ver Vehiculo">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    

                                    ${full.estado != 4 ? `
                                        <button class="btn btn-warning btn-sm edit-btn">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm delete-btn" title="Inactivar Vehiculo">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    ` : '' }
                                </center>`;

                    return `${menu}`;

                },
            },
        ];

        // Llamado a la función para crear la tabla con los datos
        cargarTabla("#ListadoVehiculosTabla", vehiculos, columns);
    }

    async function obtenerVehiculos(){
        try {

            const result = await apiFetch('./modules/transport/controller/vehiculoController.php?action=getVehiculos');

            if (result.success && !result.data.error) {
                vehiculos = result.data;
                listarVehiculos();
            } else {
                throw new Error("Ha ocurrido un error al obtener los vehiculos");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerVehiculosTarjetas(){
        try {

            const result = await apiFetch('./modules/transport/controller/vehiculoController.php?action=getCountVehiculosEstados');
            
            if (result.success && !result.data.error) {
                const estados = result.data;
                estados.forEach(item => {
                    let card = document.getElementById('card_estado_' + item.estado);
                    if (card) card.innerHTML = `
                                                <h6 class="mb-1">Vehículos ${item.estado == 3 ? item.nombre_estado : item.nombre_estado+'s'}</h6>
                                                <h4>${item.total}</h4>`;
                });

            } else {
                throw new Error("Ha ocurrido un error al cargar las tarjetas de los vehiculos");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function eliminarVehiculo(data){
        try {

            const result = await apiFetch('./modules/transport/controller/vehiculoController.php', 'DELETE', data);
            if (result.success && !result.data.error) {
                const res = result.data; 
                Swal.fire({
                    icon: "success",
                    title: "Vehículos",
                    text: res.message || "Actualizado exitosamente",
                });
                obtenerVehiculosTarjetas();
                obtenerVehiculos();
                
            } else {
                const errorData = result;
                console.error('Error:', errorData.message || 'Error al inactivar el registro');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al inactivar el registro',
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al inactivar el registro',
            });
        }
    }
      
});
