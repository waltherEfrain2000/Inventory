import { apiFetch, cargarTabla, obtenerNotificaciones } from './helpers.js';

let mantenimientos = [];
let idEliminar = null;
$(function () {
    $("#navTransporte").addClass("pc-trigger");
    $("#navTransporte ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navMantenimientos").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    obtenerMantenimientosTarjetas();
    obtenerMantenimientos();
    obtenerNotificaciones();

    $(document).on('click', '.view-btn', function() {
        const id = $(this).closest('tr').find('[data-id]').data('id');
        window.location.href = `?module=verMantenimiento&id=${id}`;
    });

    $(document).on('click', '.approve-btn', function() {
        Swal.fire({
            title: "¿Está seguro de aprobar el registro?",
            text: "Se cambiará el estado a aprobado",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "#d33",
            confirmButtonText: "Aprobar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const id = $(this).closest('tr').find('[data-id]').data('id');
                const data = {
                    id: id,
                    id_vehiculo: $(this).data('id-vehiculo'),
                    kilometrajeMantenimiento: $(this).data('kilometraje'),
                    kilometrajeVehiculo: $(this).data('kilometraje-ficha'),
                    accion: "aprobarMantenimiento"
                };
                aprobarMantenimiento(data);
                
            }
        });
    });

    $(document).on('click', '.execute-btn', function() {
        const estadoVehiculo = $(this).data('estado-vehiculo');
        if(estadoVehiculo == 2){
            Swal.fire({
                icon: "warning",
                title: "No se puede ejecutar mantenimiento",
                text: "El vehículo actualmente está ocupado",
            });
            return;
        }
        Swal.fire({
            title: "¿Está seguro?",
            text: "Se cambiará el estado a en proceso y el vehículo se establecerá como en mantenimiento",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const id = $(this).closest('tr').find('[data-id]').data('id');
                const id_vehiculo = $(this).data('id-vehiculo');
                const data = {
                    id: id,
                    id_vehiculo: id_vehiculo,
                    accion: "ejecutarMantenimiento"
                };
                ejecutarMantenimiento(data);
                
            }
        });
    });

    $(document).on('click', '.edit-btn', function() {
        const id = $(this).closest('tr').find('[data-id]').data('id');
        window.location.href = `?module=editarMantenimiento&id=${id}`;
    });
    
    $(document).on('click', '.delete-btn', function() {

        $("#modalMotivoRechazo").modal("show");
        idEliminar = $(this).closest('tr').find('[data-id]').data('id');

    });

    $(document).on('click', '.end-btn', function() {
        Swal.fire({
            title: "¿Está seguro de dar por finalizado el mantenimiento?",
            text: "Se marcará el vehículo como disponible",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const id = $(this).closest('tr').find('[data-id]').data('id');
                const id_vehiculo = $(this).data('id-vehiculo');
                const data = {
                    id: id,
                    id_vehiculo: id_vehiculo,
                    accion: "finalizarMantenimiento"
                };
                finalizarMantenimiento(data);
                
            }
        });
    });
    
    function listarMantenimientos() {
    
        var columns = [
          {
              className: "text-center",
              mDataProp: "id",
              width: '5%',
          },
          {
              className: "text-center",
              mDataProp: "vehiculo",
          },
          {
            className: "text-center",
            mDataProp: "estado",
            render: function (data, types, full, meta){
                return `
                <span class="badge ${full.estado == 1 ? 'bg-primary' : full.estado == 2 ? 'bg-info' : full.estado == 3 ? 'bg-warning' : full.estado == 4 ? 'bg-success' : full.estado == 5 ? 'bg-danger' : 'bg-secondary'}">${full.nombre_estado}</span>
                `;
            }
            
        },
          {
              className: "text-center",
              mDataProp: "tipo_mantenimiento",
          },
          {
              className: "text-center",
              mDataProp: "tipo_servicio",
          },
          {
              className: "text-center",
              mDataProp: "taller",
          },
          {
              className: "text-center",
              mDataProp: "fecha_generado",
          },
          {
              className: "text-center",
              mDataProp: "fecha_inicio",
          },
          {
              className: "text-center",
              mDataProp: "fecha_fin",
          },
          {
              className: "text-center",
              width: '5%',
              render: function (data, types, full, meta) {
                  let menu = `<center data-id="${full.id}">

                                <button class="btn btn-secondary btn-sm view-btn" title="Ver Mantenimiento">
                                    <i class="fas fa-eye"></i>
                                </button>

                                ${full.estado == 1 ? `

                                    <button class="btn btn-warning btn-sm edit-btn" title="Editar">
                                      <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-info btn-sm approve-btn" title="Aprobar" data-id-vehiculo="${full.id_vehiculo}" data-kilometraje="${full.kilometraje_vehiculo}" data-kilometraje-ficha="${full.kilometraje_ficha}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    
                                    <button class="btn btn-danger btn-sm delete-btn" title="Rechazar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    ` : '' }

                                ${full.estado == 2 ? `
                                    <button class="btn btn-outline-success btn-sm execute-btn" title="Ejecutar Mantenimiento" data-estado-vehiculo="${full.estado_vehiculo}" data-id-vehiculo="${full.id_vehiculo}">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    ` : '' }
                                
                                ${full.estado == 3 ? `
                                    <button class="btn btn-success btn-sm end-btn" title="Finalizar" data-id-vehiculo="${full.id_vehiculo}">
                                        <i class="fas fa-clipboard-check"></i>
                                    </button>
                                    ` : '' }            
                                    
                                ${full.estado == 5 ? `
                                    <button class="btn btn-warning btn-sm edit-btn" title="Editar">
                                      <i class="fas fa-edit"></i>
                                    </button>
                                    ` : '' }
                  
                                  
                              </center>`;
  
                  return `${menu}`;
  
              },
          },
      ];
  
      // Llamado a la función para crear la tabla con los datos
      cargarTabla("#tablaMantenimientos", mantenimientos, columns);
    }

    $("#btnRechazarMantenimiento").on("click", function () {

        if($("#motivo_rechazo").val().trim() == ""){
            Swal.fire({
                icon: "warning",
                title: "Atención",
                text: "Debe ingresar el motivo del rechazo",
                target: "#modalMotivoRechazo",
            });
            return;
        }

        Swal.fire({
            title: "¿Está seguro de rechazar mantenimiento?",
            text: "Se establecerá el estado como rechazado",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, Rechazar",
            cancelButtonText: "Cancelar",
            target: "#modalMotivoRechazo",
        }).then((result) => {
            if (result.isConfirmed) {
                const data = {
                    id: idEliminar,
                    motivo_rechazo: document.getElementById("motivo_rechazo").value.trim(),
                    accion: "rechazarMantenimiento"
                };
                rechazarMantenimiento(data)
            }
        });
        
    });

    async function obtenerMantenimientosTarjetas(){
        try {

            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php?action=getCountMantenimientosEstados');
            
            if (result.success && !result.data.error) {
                const estados = result.data;
                estados.forEach(item => {
                    let card = document.getElementById('card_estado_' + item.estado);
                    if (card) card.innerHTML = `
                                                <h6 class="mb-1">Mantenimientos ${item.estado == 3 ? item.nombre_estado : item.nombre_estado+'s'}</h6>
                                                <h4>${item.total}</h4>`;
                });

            } else {
                throw new Error("Ha ocurrido un error al cargar las tarjetas de los mantenimientos");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function obtenerMantenimientos(){
        try {

            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php?action=getMantenimientos');

            if (result.success && !result.data.error) {
                mantenimientos = result.data;
                listarMantenimientos();
            } else {
                throw new Error("Ha ocurrido un error al obtener los mantenimientos");
            }
        } catch (error) {
            console.log(error);
        }
    }

    async function aprobarMantenimiento(data){
        try {
            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php', 'PATCH', data);

            if (result.success && !result.data.error) {
                Swal.fire({
                    icon: "success",
                    title: "Aprobado",
                    text: result.data.message || "El mantenimiento ha sido aprobado correctamente.",
                });

                obtenerMantenimientos();
                obtenerMantenimientosTarjetas();
            } else {
                const errorData = result;
                console.error('Error:', errorData.message || 'Error al aprobar el mantenimiento');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al aprobar el mantenimiento',
                });
                // throw new Error("Ha ocurrido un error al aprobar el mantenimiento");
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al aprobar el mantenimiento',
            });
        }
    }

    async function ejecutarMantenimiento(data){
        try {
            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php', 'PATCH', data);

            if (result.success && !result.data.error) {
                Swal.fire({
                    icon: "success",
                    title: "Iniciado",
                    text: result.data.message || "El mantenimiento ha sido iniciado.",
                });

                obtenerMantenimientos();
                obtenerMantenimientosTarjetas();
            } else {
                const errorData = result;
                console.error('Error:', errorData.message || 'Error al iniciar el mantenimiento');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al iniciar el mantenimiento',
                });
                // throw new Error("Ha ocurrido un error al iniciar el mantenimiento");
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al iniciar el mantenimiento',
            });
        }
    }

    async function finalizarMantenimiento(data){
        try {
            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php', 'PATCH', data);

            if (result.success && !result.data.error) {
                Swal.fire({
                    icon: "success",
                    title: "Iniciado",
                    text: result.data.message || "El mantenimiento ha sido completado.",
                });

                obtenerMantenimientos();
                obtenerMantenimientosTarjetas();
            } else {
                const errorData = result;
                console.error('Error:', errorData.message || 'Error al completar el mantenimiento');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al completar el mantenimiento',
                });
                // throw new Error("Ha ocurrido un error al completar el mantenimiento");
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al completar el mantenimiento',
            });
        }
    }

    async function rechazarMantenimiento(data){
        try {
            const result = await apiFetch('./modules/transport/controller/mantenimientoController.php', 'DELETE', data);

            if (result.success && !result.data.error) {
                $("#modalMotivoRechazo").modal("hide");
                $("#motivo_rechazo").val("").trigger("change");
                Swal.fire({
                    icon: "success",
                    title: "Rechazado",
                    text: result.data.message || "El mantenimiento ha sido marcado como rechazado.",
                });

                obtenerMantenimientos();
                obtenerMantenimientosTarjetas();
            } else {
                $("#modalMotivoRechazo").modal("hide");
                const errorData = result;
                console.error('Error:', errorData.message || 'Error al rechazar el mantenimiento');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al rechazar el mantenimiento',
                });
                // throw new Error("Ha ocurrido un error al completar el mantenimiento");
            }
        } catch (error) {
            $("#modalMotivoRechazo").modal("hide");
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al rechazar el mantenimiento',
            });
        }
    }
      
});
