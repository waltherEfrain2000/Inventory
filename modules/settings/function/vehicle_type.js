let tipos = [];
let idEditar = 0;
$(function () {
    $("#navParametrizacion").addClass("pc-trigger");
    $("#navParametrizacion ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navTransporteParametrizacion").addClass("pc-trigger");
    $("#navParametrizacion").children("a").prop("style", "background-color:rgb(239 239 239); border-radius: 8px;");
    $("#navTransporteParametrizacion").children("a").prop("style", "background-color:rgb(239 239 239); border-radius: 8px;");
    $("#navTipoVehiculo").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    obtenerTipos();

    function listarTipos() {

          var columns = [
            {
                className: "text-center",
                mDataProp: "id",
                width: '5%',
            },
            {
                className: "text-center",
                mDataProp: "nombre",
            },
            {
                className: "text-center",
                width: '5%',
                render: function (data, types, full, meta) {
                    let menu = `<center>
                                    <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="${full.id}" data-nombre="${full.nombre}">
                                    <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="${full.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </center>`;
    
                    return `${menu}`;
    
                },
            },
        ];
    
        // Llamado a la función para crear la tabla con los datos
        cargarTabla("#tabla", tipos, columns);
    }
    
    // Función para cargar la tabla con la información
    function cargarTabla(tableID, data, columns) {
        $(tableID).DataTable().destroy();
        var params = {
            aaData: data,
            aoColumns: columns,
            ordering: true,
            pageLength: 25,
            // "scrollY": "600px",
            language: {
                sProcessing: "Procesando...",
                sLengthMenu: "Mostrar _MENU_ registros",
                sZeroRecords: "No se encontraron resultados",
                sEmptyTable: "Ningún dato disponible en esta tabla",
                sInfo:
                    "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                sInfoPostFix: "",
                sSearch: "Buscar:",
                sUrl: "",
                sInfoThousands: ",",
                sLoadingRecords: "Cargando...",
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Último",
                    sNext: "Siguiente",
                    sPrevious: "Anterior",
                },
                oAria: {
                    sSortAscending:
                        ": Activar para ordenar la columna de manera ascendente",
                    sSortDescending:
                        ": Activar para ordenar la columna de manera descendente",
                },
        
            },
            columnDefs: [
                {
                    //   targets: 1,
                    //  visible: false,
                }
            ],
            // order: [[0, 'desc']]
        };
    
        $(tableID).DataTable(params);
    }

    $(document).on('click', '.edit-btn', function() {
        idEditar = $(this).data('id');
        let nombre = $(this).data('nombre');
        $("#editarNombre").val(nombre);
        $("#editarModal").modal("show");
    });

    $(document).on('click', '.delete-btn', function() {
        Swal.fire({
            title: "¿Está seguro de eliminar el registro?",
            text: "Se perderá la información",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const data = {
                    id: $(this).data('id'),
                    accion: "eliminar"
                };
                eliminarTipo(data);
            }
        });
    });

    $("#guardarCambios").on("click", function (e) {
        if($("#editarNombre").val().trim() == ""){
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Debe ingresar el nombre",
                target: "#editarModal"
            });
            return;
        }

        const data = {
            id: idEditar,
            nombre: document.getElementById("editarNombre").value.trim(),
            accion: "editar"
        };
        editarTipo(data);
    });

    $("#guardar").on("click", function (e) {
        
        if($("#nombre").val().trim() == ""){
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Debe ingresar el nombre",
            });
            return;
        }

        const data = {
            nombre: document.getElementById("nombre").value.trim(),
            accion: "guardar"
        };
        guardarTipo(data);
    });


    async function obtenerTipos() {
        try {
            const response = await fetch('./modules/settings/controller/vehicleTypeController.php', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
            });
            if (response.ok) {
                tipos = await response.json(); 
                listarTipos();
            } else {
                throw new Error("Ha ocurrido un error al obtener los tipos de vehículo");
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: error || 'Ha ocurrido un error al obtener los tipos de vehículo',
            });
        }
    }

    const guardarTipo = async (data) => {
        try {
            
            const response = await fetch('./modules/settings/controller/vehicleTypeController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                const result = await response.json(); 
                Swal.fire({
                    icon: "success",
                    title: "Tipos de Vehículo",
                    text: result.message || "Guardado exitosamente",
                });
                obtenerTipos();
                document.getElementById("nombre").value = '';
            } else {
                const errorData = await response.json();
                console.error('Error:', errorData.message || 'Error al guardar tipo');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al guardar tipo',
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al guardar tipo',
            });
        }
    }

    async function editarTipo(data) {
        try {
            
            const response = await fetch('./modules/settings/controller/vehicleTypeController.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                const result = await response.json(); 
                obtenerTipos();
                $("#editarModal").modal("hide");
                Swal.fire({
                    icon: "success",
                    title: "Tipos de Vehículo",
                    text: result.message || "Actualizado exitosamente",
                });
            } else {
                const errorData = await response.json();
                console.error('Error:', errorData.message || 'Error al actualizar tipo de vehículo');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al actualizar tipo de vehículo',
                    target: "#editarModal"
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al actualizar tipo de vehículo',
                target: "#editarModal"
            });
        }
    }

    async function eliminarTipo(data) {
        try {
            const response = await fetch('./modules/settings/controller/vehicleTypeController.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                const result = await response.json(); 
                Swal.fire({
                    icon: "success",
                    title: "Tipos de Vehículo",
                    text: result.message || "Eliminado exitosamente",
                });
                obtenerTipos();
            } else {
                const errorData = await response.json();
                console.error('Error:', errorData.message || 'Error al eliminar el tipo de vehículo');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al eliminar el tipo de vehículo',
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al eliminar el tipo de vehículo',
            });
        }
    }
});