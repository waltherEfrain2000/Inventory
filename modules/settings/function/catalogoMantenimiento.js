let catalogo = [];
let idEditar = 0;
$(function () {
    $("#navParametrizacion").addClass("pc-trigger");
    $("#navParametrizacion ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navTransporteParametrizacion").addClass("pc-trigger");
    $("#navParametrizacion").children("a").prop("style", "background-color:rgb(239 239 239); border-radius: 8px;");
    $("#navTransporteParametrizacion").children("a").prop("style", "background-color:rgb(239 239 239); border-radius: 8px;");
    $("#navCatalogoMantenimiento").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    obtenerCatalogo();
    obtenerTiposMantenimiento();

    function listarCatalogo() {
        
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
                mDataProp: "tipoMantenimiento",
            },
            {
                className: "text-center",
                width: '5%',
                render: function (data, types, full, meta) {
                    let menu = `<center>
                                    <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="${full.id}" data-nombre="${full.nombre}" data-idtipomantenimiento="${full.id_tipo_mantenimiento}">
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
        cargarTabla("#tabla", catalogo, columns);
    }
    
    // Función para cargar la tabla con la información
    function cargarTabla(tableID, data, columns) {
        $(tableID).DataTable().destroy();
        var params = {
            aaData: data,
            aoColumns: columns,
            ordering: true,
            pageLength: 25,
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
        let tipoMantenimiento = $(this).data('idtipomantenimiento');

        $("#tipoEditar").val(tipoMantenimiento).trigger("change");
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
                eliminarCatalogo(data);
            }
        });
    });
    
    $("#guardarCambios").on("click", function (e) {
        if($("#editarNombre").val().trim() == "" || $("#tipoEditar").val().trim() == ""){
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Debe ingresar toda la información",
                target: "#editarModal"
            });
            return;
        }
        const data = {
            id: idEditar,
            nombre: document.getElementById("editarNombre").value.trim(),
            id_tipo_mantenimiento: document.getElementById("tipoEditar").value.trim(),
            accion: "editar"
        };
        editarCatalogo(data);
    });

    $("#guardar").on("click", function (e) {
        
        if($("#nombre").val().trim() == "" || $("#tipo").val().trim() == ""){
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Debe ingresar toda la información",
            });
            return;
        }
        const data = {
            nombre: document.getElementById("nombre").value.trim(),
            id_tipo_mantenimiento: document.getElementById("tipo").value.trim(),
            accion: "guardar"
        };
        guardarCatalogo(data);
    });

    $('#tipoEditar').select2({
        dropdownParent: $('#editarModal'),
        placeholder: "Seleccione..."
    });

    async function obtenerCatalogo() {
        try {
            const response = await fetch('./modules/settings/controller/catalogoController.php?action=getCatalogo', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
            });
            if (response.ok) {
                catalogo = await response.json();

                listarCatalogo();
                
            } else {
                throw new Error("Ha ocurrido un error al obtener el catálogo");
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: error || 'Ha ocurrido un error al obtener el catálogo',
            });
        }
    }

    async function obtenerTiposMantenimiento() {
        try {
            const response = await fetch('./modules/settings/controller/catalogoController.php?action=getTiposMantenimiento', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
            });
            if (response.ok) {
                tiposMantenimiento = await response.json(); 
                
                let html = '<option value=""></option>';
                tiposMantenimiento.forEach(tipo => {
                    html += `<option value="${tipo.id}">${tipo.nombre}</option>`;
                });

                document.getElementById("tipo").innerHTML = html;
                document.getElementById("tipoEditar").innerHTML = html;
                
            } else {
                throw new Error("Ha ocurrido un error al obtener los tipos de mantenimiento");
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: error || 'Ha ocurrido un error al obtener los tipos de mantenimiento',
            });
        }
    }

    const guardarCatalogo = async (data) => {
        try {
            
            const response = await fetch('./modules/settings/controller/catalogoController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                const result = await response.json(); 
                Swal.fire({
                    icon: "success",
                    title: "Catálogo de Mantenimiento",
                    text: result.message || "Guardado exitosamente",
                });

                document.getElementById("nombre").value = '';
                $("#tipo").val('').trigger('change');

                obtenerCatalogo();

            } else {
                const errorData = await response.json();
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

    async function editarCatalogo(data) {
        try {
            
            const response = await fetch('./modules/settings/controller/catalogoController.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                const result = await response.json(); 
                Swal.fire({
                    icon: "success",
                    title: "Catálogo de Mantenimiento",
                    text: result.message || "Actualizado exitosamente",
                });

                obtenerCatalogo();
                $("#editarModal").modal("hide");

            } else {
                const errorData = await response.json();
                console.error('Error:', errorData.message || 'Error al actualizar el registro');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al actualizar el registro',
                    target: "#editarModal"
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al actualizar el registro',
                target: "#editarModal"
            });
        }
    }

    async function eliminarCatalogo(data) {
        try {
            
            const response = await fetch('./modules/settings/controller/catalogoController.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                const result = await response.json(); 
                Swal.fire({
                    icon: "success",
                    title: "Catálogo de Mantenimiento",
                    text: result.message || "Eliminado exitosamente",
                });
                obtenerCatalogo();

            } else {
                const errorData = await response.json();
                console.error('Error:', errorData.message || 'Error al eliminar el registro');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al eliminar el registro',
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al eliminar el registro',
            });
        }
    }
});