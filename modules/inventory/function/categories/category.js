$(function () { 
    $("#table-style-hover").DataTable();

    listCategories();
    listSubCategories();
    $('#formCategoria').submit(function(event) {
        event.preventDefault();
        let id = $("#category_id").val();
        id ? update_Category(id) : save_Categories(); 
    });
    $('#formSubcategoria').submit(function(event) {
        event.preventDefault();
        let id = $("#subcategory_id").val();
        id ? update_SubCategory(id) : save_SubCategories(); 
    });
    
});

function listCategories() {
    // Destroy DataTable before adding new data
    var table = $("#table-style-hover").DataTable();
    table.clear().draw();  // Limpiar la tabla antes de agregar nuevas filas
    let selectCategorias = $("#subcategoriaIdCategoria");
    $.ajax({
        url: "./modules/inventory/controller/categories/list_Categories.php", 
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log("Categorías obtenidas:", response);
            if (!response.success) {
                console.error("Error al obtener las categorías:", response.error);
                return;
            }

            let data = response.data;
            let tableBody = $("#table-style-hover tbody");
            tableBody.empty();  // Limpiar la tabla antes de agregar nuevas filas

            // Agregar las nuevas filas
            $.each(data, function (index, caregoria) {
                tableBody.append(`
                    <tr>
                        <td class="">${caregoria.nombreCategoria}</td>
                        <td class="">${caregoria.DescripcionCategoria}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm edit-btn" data-id="${caregoria.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${caregoria.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            // Volver a inicializar el DataTable
            table.rows.add(tableBody.find('tr')).draw();

            // Delegar eventos para editar y eliminar
            $("#table-style-hover").on("click", ".edit-btn", function () {
                let id = $(this).data("id");
                loadCategoryForEdit(id);
            });

            $("#table-style-hover").on("click", ".delete-btn", function () {
                let id = $(this).data("id");

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#FF0000',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post("modules/inventory/controller/categories/disable_Categories.php", { action: "eliminar", id: id }, function (response) {
                            let data = JSON.parse(response);
                            if (data.success) {
                                listCategories();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se pudo eliminar la categoria.',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });
                    }
                });
            });

            selectCategorias.empty();
            selectCategorias.append('<option value="">Ninguna</option>');

            $.each(data, function (index, categoria) {
                selectCategorias.append(`<option value="${categoria.id}">${categoria.nombreCategoria}</option>`);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar las categorías:", error);
            alert("Error al cargar las categorías.");
        }
    });
}

function loadCategoryForEdit(id) {
    $.ajax({
        url: "./modules/inventory/controller/categories/list_Category_For_Update.php?id=" + id,
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log("Categoría obtenida:", response);
            if (response.success) {
                let caregoria = response.data;
                $("#category_id").val(caregoria.id);
                $("#categoriaNombre").val(caregoria.nombreCategoria);
                $("#categoriaDescripcion").val(caregoria.DescripcionCategoria);
                $("#modalCategoria").modal("show");
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la categoría para editar.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function save_Categories() {
    $('#modalCategoria').modal('hide');
    var name = $('#categoriaNombre').val();
    var descripcion = $('#categoriaDescripcion').val();

    $.ajax({
        url: 'modules/inventory/controller/categories/save_Categories.php',
        type: 'POST',
        data: {
            categoriaNombre: name,
            categoriaDescripcion: descripcion
        },
        dataType: "json",
        success: function (response) {
            $("#formCategoria")[0].reset();
            if (response.success) {
                Swal.fire({
                    title: 'Categoría guardada',
                    text: 'La categoría ha sido guardada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $("#modalCategoria").modal("hide");
                    listCategories();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo guardar la categoría.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar la categoría.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function update_Category(id) {
    var name = $('#categoriaNombre').val();
    var descripcion = $('#categoriaDescripcion').val();

    $.ajax({
        url: "modules/inventory/controller/categories/update_Category.php",
        type: "POST",
        data: {
            id: id,
            categoriaNombre: name,
            categoriaDescripcion: descripcion
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#category_id").val("")  ;
                $("#modalCategoria").modal("hide");
                $("#formCategoria")[0].reset();
                listCategories();
                Swal.fire({
                    title: 'Categoría actualizada',
                    text: 'La categoría ha sido actualizada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo actualizar la categoría.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al actualizar la categoría.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}


/*
! Seccion para subcategorias
*/

function listSubCategories() {
    // Acceder a la tabla por su ID
    var table = $("#table-subcategoria").DataTable();

    // Limpiar la tabla antes de agregar nuevas filas
    table.clear().draw();

    $.ajax({
        url: "./modules/inventory/controller/subCategories/list_SubCategories.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            console.log("Subcategorías obtenidas:", response);
            if (!response.success) {
                console.error("Error al obtener las subcategorías:", response.error);
                return;
            }

            let data = response.data;
            let tableBody = $("#table-subcategoria tbody");
            tableBody.empty(); // Limpiar el contenido de la tabla

            // Agregar las nuevas filas
            $.each(data, function (index, subCategoria) {
                tableBody.append(`
                    <tr>
                        <td class="">${subCategoria.nombreCategoria}</td>
                        <td class="">${subCategoria.nombreSubCategoria}</td>
                        <td class="">${subCategoria.DescripcionSubCategoria}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm edit-btn" data-id="${subCategoria.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${subCategoria.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            // Volver a inicializar el DataTable
            table.rows.add(tableBody.find('tr')).draw();

            // Delegar eventos para editar y eliminar
            $("#table-subcategoria").on("click", ".edit-btn", function () {
                let id = $(this).data("id");
                loadSubCategoryForEdit(id);
            });

            $("#table-subcategoria").on("click", ".delete-btn", function () {
                let id = $(this).data("id");

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#FF0000',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post("modules/inventory/controller/subCategories/disable_SubCategories.php", { action: "eliminar", id: id }, function (response) {
                            let data = JSON.parse(response);
                            if (data.success) {
                                listSubCategories();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se pudo eliminar la subcategoría.',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        });
                    }
                });
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar las subcategorías:", error);
            alert("Error al cargar las subcategorías.");
        }
    });
}


function save_SubCategories() {
    $('#modalSubcategoria').modal('hide');
    var idCategoria = $('#subcategoriaIdCategoria').val();
    var subName = $('#subcategoriaNombre').val();   
    var descripcion = $('#subcategoriaDescripcion').val();

    $.ajax({
        url: 'modules/inventory/controller/subCategories/save_SubCategories.php',
        type: 'POST',
        data: {
            idCategoria: idCategoria,
            subCategoriaNombre: subName,
            subCategoriaDescripcion: descripcion
        },
        dataType: "json",
        success: function (response) {
            $("#formCategoria")[0].reset();
            if (response.success) {
                Swal.fire({
                    title: 'Subcategoría guardada',
                    text: 'La subcategoría ha sido guardada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $("#modalCategoria").modal("hide");
                    listSubCategories();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo guardar la subcategoría.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar la subcategoría.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}


function loadSubCategoryForEdit(id) {
    $.ajax({
        url: "./modules/inventory/controller/subCategories/list_SubCategory_For_Update.php?id=" + id,
        type: "GET",
        dataType: "json",
        success: function (response) {
          
            if (response.success) {
                let subcategoria = response.data;
                $("#subcategory_id").val(subcategoria.id);
                $("#subcategoriaNombre").val(subcategoria.nombreSubCategoria);
                $("#subcategoriaDescripcion").val(subcategoria.DescripcionSubCategoria);
                setTimeout(() => {
                    $("#subcategoriaIdCategoria").val(subcategoria.idCategoria);
                    $("#modalSubcategoria").modal("show");
                }, 500);
                
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la subcategoría para editar.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function update_SubCategory(id) {
    var idCategoria = $('#subcategoriaIdCategoria').val();
    var subName = $('#subcategoriaNombre').val();   
    var descripcion = $('#subcategoriaDescripcion').val();

    $.ajax({
        url: "modules/inventory/controller/subCategories/update_SubCategory.php",
        type: "POST",
        data: {
            id: id,
            idCategoria: idCategoria,
            subCategoriaNombre: subName,
            subCategoriaDescripcion: descripcion
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $("#subcategory_id").val("")  ;
                $("#modalSubcategoria").modal("hide");
                $("#formSubcategoria")[0].reset();
                listSubCategories();
                Swal.fire({
                    title: 'Subcategoría actualizada',
                    text: 'La subcategoría ha sido actualizada correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo actualizar la subcategoría.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al actualizar la subcategoría.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

