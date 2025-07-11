let marcas = [];
let idEditar = 0;
$(function () {
    $("#navParametrizacion").addClass("pc-trigger");
    $("#navParametrizacion ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navTransporteParametrizacion").addClass("pc-trigger");
    $("#navParametrizacion").children("a").prop("style", "background-color:rgb(239 239 239); border-radius: 8px;");
    $("#navTransporteParametrizacion").children("a").prop("style", "background-color:rgb(239 239 239); border-radius: 8px;");
    $("#navMarca").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    obtenerMarcas();

    function listarMarcas() {
    
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
        cargarTabla("#tabla", marcas, columns);
    }

    $(document).on('click', '.edit-btn', function() {
        idEditar = $(this).data('id');
        let nombre = $(this).data('nombre');
        $("#editarNombreMarca").val(nombre);
        $("#editarMarcaModal").modal("show");
    });

    $(document).on('click', '.delete-btn', function() {
        Swal.fire({
            title: "¿Está seguro de eliminar la marca?",
            text: "Se perderá la información",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                let id = $(this).data('id');
                eliminarMarca(id);
            }
        });
    });
    
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

    $("#guardarCambiosMarca").on("click", function (e) {
        if($("#editarNombreMarca").val().trim() == ""){
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Debe ingresar el nombre de la marca",
                target: "#editarMarcaModal"
            });
            return;
        }
        editarMarca();
    });

    $("#guardarMarca").on("click", function (e) {
        
        if($("#nombreMarca").val().trim() == ""){
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Debe ingresar el nombre de la marca",
            });
            return;
        }
        guardarMarca();
    });

    async function obtenerMarcas() {
        try {
            const response = await fetch('./modules/settings/controller/brandController.php', {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
            });
            if (response.ok) {
                marcas = await response.json(); 
                listarMarcas();
            } else {
                throw new Error("Ha ocurrido un error al obtener las marcas");
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: error || 'Ha ocurrido un error al obtener las marcas',
            });
        }
    }

    const guardarMarca = async () => {
        try {
            const data = {
                nombre: document.getElementById("nombreMarca").value.trim(),
                accion: "guardar"
            };
            const response = await fetch('./modules/settings/controller/brandController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                const result = await response.json(); 
                Swal.fire({
                    icon: "success",
                    title: "Marcas",
                    text: result.message || "Guardado exitosamente",
                });
                obtenerMarcas();
                document.getElementById("nombreMarca").value = '';
            } else {
                const errorData = await response.json();
                console.error('Error:', errorData.message || 'Error al guardar marca');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al guardar marca',
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al guardar marca',
            });
        }
    }

    async function editarMarca() {
        try {
            const data = {
                id: idEditar,
                nombre: document.getElementById("editarNombreMarca").value.trim(),
                accion: "editar"
            };
            const response = await fetch('./modules/settings/controller/brandController.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                const result = await response.json(); 
                $("#editarMarcaModal").modal("hide");
                Swal.fire({
                    icon: "success",
                    title: "Marcas",
                    text: result.message || "Actualizado exitosamente",
                });
                obtenerMarcas();
            } else {
                const errorData = await response.json();
                console.error('Error:', errorData.message || 'Error al actualizar marca');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al actualizar marca',
                    target: "#editarMarcaModal"
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al actualizar marca',
                target: "#editarMarcaModal"
            });
        }
    }

    async function eliminarMarca(id) {
        try {
            const data = {
                id: id,
                accion: "eliminar"
            };
            const response = await fetch('./modules/settings/controller/brandController.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                const result = await response.json(); 
                Swal.fire({
                    icon: "success",
                    title: "Marcas",
                    text: result.message || "Eliminado exitosamente",
                });
                obtenerMarcas();
            } else {
                const errorData = await response.json();
                console.error('Error:', errorData.message || 'Error al eliminar la marca');
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorData.message || 'Ha ocurrido un error al eliminar la marca',
                });
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: 'Ha ocurrido un error al eliminar la marca',
            });
        }
    }

});
