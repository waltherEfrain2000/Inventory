$(function () {
    // Inicializar DataTable para artículos
    $("#table-articulos").DataTable();

    // Llamar a la función para cargar los artículos
    listArticles();
    loadProviders();
    loadCategories();
    loadUnitMeasures();
    // Guardar nuevo artículo o actualizar
    $('#formArticulo').submit(function(event) {
        event.preventDefault();
        let id = $("#articulo_id").val();
        id ? update_Article(id) : save_Article(); 
    });

    $("#categoria").on("change", function () {
        let id = $(this).val();
        if (id) {
            loadSubCategories(id);
        }
    });

});

// Función para listar artículos
function listArticles() {
    let table = $("#table-articulos").DataTable();
    if (table) {
        table.clear().destroy();
    }

    let tableBody = $("#table-articulos tbody");
    tableBody.empty();

    $.ajax({
        url: "./modules/inventory/controller/products/list_Products.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener los artículos:", response.error);
                return;
            }

            let data = response.data;

            $.each(data, function (index, articulo) {
                tableBody.append(`
                    <tr>
                        <td>${articulo.NombreArticulo}</td>
                        <td>${articulo.DescripcionArticulo}</td>
                   
                     
                        <td>${articulo.PrecioCompra}</td>
                        <td>${articulo.PrecioVenta}</td>
                        <td class="text-center">
                            <span class="badge ${articulo.Estado == 1 ? 'bg-success' : 'bg-danger'}">
                                ${articulo.Estado == 1 ? 'Activo' : 'Inactivo'}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm edit-btn" data-id="${articulo.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${articulo.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            // Inicializa DataTable nuevamente
            $("#table-articulos").DataTable({
                ordering: false
            });

            // Eliminar eventos previos antes de agregar nuevos
            $("#table-articulos").off("click", ".edit-btn").on("click", ".edit-btn", function () {
                let id = $(this).data("id");
                loadArticleForEdit(id);
            });

            $("#table-articulos").off("click", ".delete-btn").on("click", ".delete-btn", function () {
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
                        $.post("modules/inventory/controller/products/disable_Products.php", { action: "eliminar", id: id }, function (response) {
                            let data = JSON.parse(response);
                            if (data.success) {
                                listArticles();
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
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al cargar los artículos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function loadProviders() {
    $.ajax({
        url: "./modules/inventory/controller/products/list_Providers.php" ,
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener los proveedores:", response.error);
                return;
            }

            let proveedor = response.data; // Extraer la data correctamente
            let select = $("#proveedorArticulo");
            select.empty();
            select.append('<option value="">Ninguno</option>');

            if (proveedor.length === 0) {
                select.append('<option disabled>No hay proveedores disponibles</option>');
            } else {
                $.each(proveedor, function (index, proveedor) {
                    select.append(`<option value="${proveedor.id}">${proveedor.Nombre} - ${proveedor.NombreContacto}</option>`);
                });
            }
        },
        error: function () {
            alert("Error al cargar los proveedores.");
        }
    });
}



function loadCategories() {
    $.ajax({
        url: "./modules/inventory/controller/categories/list_Categories.php" ,
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener los proveedores:", response.error);
                return;
            }

            let proveedor = response.data; // Extraer la data correctamente
            let select = $("#categoria");
            select.empty();
            select.append('<option value="">Ninguno</option>');

            if (proveedor.length === 0) {
                select.append('<option disabled>No hay proveedores disponibles</option>');
            } else {
                $.each(proveedor, function (index, categoria) {
                    select.append(`<option value="${categoria.id}">${categoria.nombreCategoria} </option>`);
                });
            }
        },
        error: function () {
            alert("Error al cargar los proveedores.");
        }
    });
}


function loadUnitMeasures() {
    $.ajax({
        url: "./modules/inventory/controller/products/list_unit_measures.php" ,
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success || !response.data) {
                console.error("No hay datos:", response.error || "Datos no válidos");
                return;
            }

            let select = $("#unidadMedida");
            select.empty();
            select.append('<option value="">Ninguno</option>');

            let subcategorias = response.data;

            // Convertir a array si es un objeto único
            if (!Array.isArray(subcategorias)) {
                subcategorias = [subcategorias];
            }

            if (subcategorias.length === 0) {
                select.append('<option disabled>No hay unidades de medida disponibles</option>');
            } else {
                $.each(subcategorias, function (index, categoria) {
                
                        select.append(`<option value="${categoria.id}">${categoria.nombre}</option>`);
                  
                });
            }
        },
        error: function () {
            alert("Error al cargar las unidades de medida.");
        }
    });
}


function loadSubCategories(id) {
    if (!id) {
        console.error("ID no válido:", id);
        return;
    }

    $.ajax({
        url: "./modules/inventory/controller/subCategories/list_SubcategoriesById.php?id=" + id,
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success || !response.data) {
                console.error("No hay datos:", response.error || "Datos no válidos");
                return;
            }

            let select = $("#subcategoria");
            select.empty();
            select.append('<option value="">Ninguno</option>');

            let subcategorias = response.data;

            // Convertir a array si es un objeto único
            if (!Array.isArray(subcategorias)) {
                subcategorias = [subcategorias];
            }

            if (subcategorias.length === 0) {
                select.append('<option disabled>No hay subcategorías disponibles</option>');
            } else {
                $.each(subcategorias, function (index, categoria) {
                    if (categoria && categoria.id && categoria.nombreSubCategoria) {
                        select.append(`<option value="${categoria.id}">${categoria.nombreSubCategoria}</option>`);
                    } else {
                        console.warn("Datos inválidos en índice", index, categoria);
                    }
                });
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la petición AJAX:", status, error);
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la subcategoría.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}


// Guardar nuevo artículo
function save_Article() {
    $('#modalArticulo').modal('hide');
    var name = $('#nombreArticulo').val();
    var idSubCategoria = $('#subcategoria').val();
    var descripcion = $('#descripcionArticulo').val();
    //var proveedor = $('#proveedorArticulo').val();
    var cantidad = $('#cantidadInicialArticulo').val();
    var precioCompra = $('#precioCompraArticulo').val();
    var precioVenta = $('#precioVentaArticulo').val();
    var estado = $('#estadoArticulo').val();
    var idUnidadMedida = $('#unidadMedida').val();

    $.ajax({
        url: 'modules/inventory/controller/products/save_Products.php',
        type: 'POST',
        data: {
            idSubCategoria:idSubCategoria,
            NombreArticulo: name,
            DescripcionArticulo: descripcion,
            //ProveedorID: proveedor,
            CantidadInicial: cantidad,
            PrecioCompra: precioCompra,
            PrecioVenta: precioVenta,
            Estado: estado,
            idUnidadMedida:idUnidadMedida
        },
        dataType: "json",
        success: function (response) {
            $("#formArticulo")[0].reset();
            if (response.success) {
                Swal.fire({
                    title: 'Artículo guardado',
                    text: 'El artículo ha sido guardado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $("#modalArticulo").modal("hide");
                    listArticles();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo guardar el artículo.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al guardar el artículo.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function loadArticleForEdit(id) {
    $.ajax({
        url: "./modules/inventory/controller/products/list_Product_For_Update.php?id=" + id,
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (response.success) {
                let articulo = response.data;

                $("#articulo_id").val(articulo.id);
                $("#nombreArticulo").val(articulo.NombreArticulo);
                $("#descripcionArticulo").val(articulo.DescripcionArticulo);
                $("#cantidadInicialArticulo").val(articulo.CantidadInicial);
                $("#precioCompraArticulo").val(articulo.PrecioCompra);
                $("#precioVentaArticulo").val(articulo.PrecioVenta);
                $("#estadoArticulo").val(articulo.Estado);
                $('#unidadMedida').val(articulo.idUnidadMedida);

          
                $('#categoria').val(articulo.idCategoria);
                loadSubCategories(articulo.idCategoria); // Cargar subcategorías según la categoría seleccionada
                // Esperar a que se carguen las subcategorías antes de establecer el valor  
                setTimeout(function() {  
                    $('#subcategoria').val(articulo.idSubCategoria);
                 }, 1000)
           

                $("#modalArticulo").modal("show");
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar el artículo para editar.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}



// Actualizar artículo
function update_Article(id) {
    $('#modalArticulo').modal('hide');
    var idSubCategoria = $('#subcategoria').val();
    var name = $('#nombreArticulo').val();
    var descripcion = $('#descripcionArticulo').val();
    //var proveedor = $('#proveedorArticulo').val();
    var cantidad = $('#cantidadInicialArticulo').val();
    var precioCompra = $('#precioCompraArticulo').val();
    var precioVenta = $('#precioVentaArticulo').val();
    var estado = $('#estadoArticulo').val();
    var idUnidadMedida = $('#unidadMedida').val();
    $.ajax({
        url: 'modules/inventory/controller/products/update_Products.php',
        type: 'POST',
        data: {
            id: id,
            idSubCategoria:idSubCategoria,
            NombreArticulo: name,
            DescripcionArticulo: descripcion,
            //ProveedorID: proveedor,
            CantidadInicial: cantidad,
            PrecioCompra: precioCompra,
            PrecioVenta: precioVenta,
            Estado: estado,
            idUnidadMedida:idUnidadMedida
        },
        dataType: "json",
        success: function (response) {
            $("#formArticulo")[0].reset();
            if (response.success) {
                Swal.fire({
                    title: 'Artículo actualizado',
                    text: 'El artículo ha sido actualizado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    $("#articulo_id").val("");
                    $("#modalArticulo").modal("hide");
                    listArticles();
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.error || 'No se pudo actualizar el artículo.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al actualizar el artículo.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

// Manejar eliminación de artículo
function handleDeleteArticle(id) {
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
            $.post("modules/inventory/controller/articles/disable_Article.php", { action: "eliminar", id: id }, function (response) {
                let data = JSON.parse(response);
                if (data.success) {
                    Swal.fire({
                        title: 'Eliminado',
                        text: 'El artículo ha sido eliminado.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        listArticles();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo eliminar el artículo.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    });
}