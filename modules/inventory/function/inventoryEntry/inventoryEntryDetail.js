let productos = [];
let bodegas = [];
let idModificar ;

$(document).ready(function() {
    console.log("Document ready");

    const params = new URLSearchParams(window.location.search);
const id = params.get('id');


    $("#table-IngresoInventario").DataTable();


    loadProviders();
    cargarProductos();
    cargarBodegas();
    if (id) {

        loadEntryForEdit(id);
        idModificar = id;
    }
    $('#formIngreso').submit(function(event) {
        event.preventDefault();
        id ? update_ProductEntry(id) : save_ProductEntry(); 
    });

  $('#agregarLinea').click(function() {
    let productoOptions = `<option value="">Seleccione un artículo</option>` +
        productos.map(p => `<option value="${p.id}">${p.NombreArticulo}</option>`).join('');
    
    let bodegaOptions = `<option value="">Seleccione una bodega</option>` +
        bodegas.map(b => `<option value="${b.id}" ${window.bodegaGeneralId && b.id === window.bodegaGeneralId ? 'selected' : ''}>${b.NombreBodega}</option>`).join('');
    
    let nuevaFila = `
    <tr>
        <td>
            <select class="form-select select2" name="articulo[]" required>
                ${productoOptions}
            </select>
        </td>
        <td>
            <select class="form-select select2" name="bodega[]" required>
                ${bodegaOptions}
            </select>
        </td>
        <td><input type="number" class="form-control cantidad" name="cantidad[]" required step="0.01" min="0.01"></td>
        <td><input type="number" class="form-control precioUnitario" name="precioUnitario[]" required step="0.01" min="0.01"></td>
        <td><input type="number" class="form-control subTotal" name="subTotal[]" required step="0.01" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm eliminar">Eliminar</button></td>
    </tr>`;

    $('#detalleIngreso tbody').append(nuevaFila);

    // Inicializamos Select2 en los nuevos select agregados
    $('#detalleIngreso tbody .select2').select2({
        width: '100%'  // Ajusta al ancho del input
    });
});

    $('#detalleIngreso').on('input', '.cantidad, .precioUnitario', function () {
        let fila = $(this).closest('tr');
        let cantidad = parseFloat(fila.find('.cantidad').val()) || 0;
        let precio = parseFloat(fila.find('.precioUnitario').val()) || 0;
        let subtotal = cantidad * precio;
        fila.find('.subTotal').val(subtotal.toFixed(2));

        let total = 0;
        $('#detalleIngreso tbody tr').each(function() {
            let subtotalFila = parseFloat($(this).find('.subTotal').val()) || 0;
            total += subtotalFila;
        });

        let totalImpuesto = ( total * parseFloat($('#impuesto').val())) + total ; 
        $('#totalIngreso').val(totalImpuesto.toFixed(2));

    });
    

    $(document).on('click', '.eliminar', function() {
        $(this).closest('tr').remove();
    });

    $('#modalIngreso').on('hidden.bs.modal', function() {
        $('#detalleIngreso tbody').empty();
    });
})


$('#detalleIngreso').on('input', '.cantidad, .precioUnitario', function () {
    recalcularTotales();
});

// Recalcular totales cuando se cambie el impuesto
$('#impuesto').on('change', function () {
    recalcularTotales();
});
//funcion para cargar los proveedores   
function loadProviders() {
    $.ajax({
        url: "./modules/inventory/controller/providers/list_providers.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener los proveedores:", response.error);
                return;
            }

            // Vaciar el select primero y agregar la opción por defecto
            let $select = $('#proveedor');
            $select.empty().append('<option value="">Seleccione un proveedor</option>');

            // Agregar proveedores dinámicamente
            response.data.forEach(function (proveedor) {
                $select.append(`<option value="${proveedor.id}">${proveedor.nombre}</option>`);
            });
        },
        error: function () {
            alert("Error al cargar los proveedores.");
        }
    });
}


function cargarProductos() {
    $.ajax({
    url: "./modules/inventory/controller/inventoryEntry/list_ActiveArticles.php" ,
    type: "GET",
    dataType: "json",
    success: function (response) {
        if (!response.success) {
            console.error("Error al obtener los proveedores:", response.error);
            return;
        }
        productos = response.data;
    },
    error: function () {
        alert("Error al cargar los proveedores.");
    }
});
}


function cargarBodegas() {
    $.ajax({
        url: "./modules/inventory/controller/inventoryEntry/list_ActiveWarehouses.php",
        type: "GET",
        dataType: "json",
        success: function(response) {
            if (!response.success) {
                console.error("Error al obtener las bodegas:", response.error);
                return;
            }

            bodegas = response.data;
            
            // Buscar la bodega general para seleccionarla por defecto
            let bodegaGeneral = bodegas.find(b => 
                b.NombreBodega.toLowerCase().includes('gen') || 
                b.NombreBodega.toLowerCase().includes('general')
            );
            
            // Guardar el ID de la bodega general si existe
            if (bodegaGeneral) {
                window.bodegaGeneralId = bodegaGeneral.id;
            }
        },
        error: function() {
            alert("Error al cargar las bodegas.");
        }
    });
}

    // Función para recalcular totales
    function recalcularTotales() {
        let total = 0;
    
        $('#detalleIngreso tbody tr').each(function () {
            let fila = $(this);
            let cantidad = parseFloat(fila.find('.cantidad').val()) || 0;
            let precio = parseFloat(fila.find('.precioUnitario').val()) || 0;
            let subtotal = cantidad * precio;
    
            fila.find('.subTotal').val(subtotal.toFixed(2));
            total += subtotal;
        });
    
        let impuesto = parseFloat($('#impuesto').val()) || 0;
        let totalConImpuesto = total + (total * impuesto);
        $('#totalIngreso').val(totalConImpuesto.toFixed(2));
    }


    function save_ProductEntry() {

        // Recopilar los datos antes de cerrar el modal
        let detalles = [];
        $('#detalleIngreso tbody tr').each(function() {
            let articulo = $(this).find('[name="articulo[]"]').val();
            let bodega = $(this).find('[name="bodega[]"]').val();
            let cantidad = $(this).find('[name="cantidad[]"]').val();
            let PrecioUnitario = $(this).find('[name="precioUnitario[]"]').val();
            let SubTotal = $(this).find('[name="subTotal[]"]').val();
  
            if (articulo && bodega && cantidad) {
                detalles.push({
                    idArticulo: articulo,
                    idBodega: bodega,
                    cantidad: cantidad,
                      PrecioUnitario: PrecioUnitario,
                      SubTotal: SubTotal
                });
            }
  
            if (articulo == "" || bodega == "" || cantidad == "") {
                Swal.fire("Error", "Todos los campos son obligatorios.", "error");
                return;       
  
            }
        });
  
        // Verificar que haya al menos un detalle
        if (detalles.length === 0) {
            Swal.fire("Error", "Debe agregar al menos un artículo.", "error");
            return;
        }
  
        // Preparar los datos para enviar
        let data = {
          IdProveedor : $('#proveedor').val(), 
          NumeroFactura : $('#numeroFactura').val(),
           FechaFactura : $('#fechaFactura').val(),
            Comentarios : $('#comentarios').val(),
            TotalFactura : $('#totalIngreso').val(),
          ImpuestoFactura : $('#impuesto').val(),
            detalles: detalles
        };
  
        // Cerrar el modal después de recopilar los datos
      $('#modalIngreso').modal('hide');
  
      Swal.fire({
          title: '¿Está seguro?',
          html: '¿Desea guardar este ingreso?<br><br>' +
          ($('#sinFactura').is(':checked') ? '' : 'Número de factura: <strong>' + $('#numeroFactura').val() + '</strong><br>') +
          ($('#sinFactura').is(':checked') ?  '' : 'Proveedor: <strong>' + $('#proveedor option:selected').text() + '</strong><br>' ) + // Proveedor opcional solo si sinFactura
          'Total: <strong>' + Number($('#totalIngreso').val()).toLocaleString('en-US', { minimumFractionDigits: 2 }) + '</strong><br>' +
          'Impuesto: <strong>' + $('#impuesto option:selected').text() + '</strong>',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, guardar',
          cancelButtonText: 'Cancelar'
      }).then((result) => {
          if (result.isConfirmed) {
            
  
              // Enviar los datos al servidor
              $.ajax({
                  url: 'modules/inventory/controller/inventoryEntry/save_inventoryEntry.php',
                  type: 'POST',
                  data: JSON.stringify(data),
                  dataType: "json",
                  success: function (response) {
                      $("#formIngreso")[0].reset();
                      if (response.success) {
                          Swal.fire({
                              title: 'Ingreso guardado',
                              text: 'El ingreso ha sido guardado correctamente.',
                              icon: 'success',
                              confirmButtonText: 'Aceptar'
                          }).then(() => {
                            window.location.href = '?module=inventoryEntry';
                          });
                      } else {
                          Swal.fire({
                              title: 'Error',
                              text: response.error || 'No se pudo guardar el ingreso.',
                              icon: 'error',
                              confirmButtonText: 'Aceptar'
                          });
                      }
                  },
                  error: function () {
                      Swal.fire({
                          title: 'Error',
                          text: 'Hubo un problema al guardar el ingreso.',
                          icon: 'error',
                          confirmButtonText: 'Aceptar'
                      });
                  }
              });
          }
      });
  }
  
  function update_ProductEntry(id) {
  
      // Recopilar los datos antes de cerrar el modal
      let detalles = [];
      $('#detalleIngreso tbody tr').each(function() {
          let articulo = $(this).find('[name="articulo[]"]').val();
          let bodega = $(this).find('[name="bodega[]"]').val();
          let cantidad = $(this).find('[name="cantidad[]"]').val();
          let PrecioUnitario = $(this).find('[name="precioUnitario[]"]').val();
            let SubTotal = $(this).find('[name="subTotal[]"]').val();
  
            if (articulo && bodega && cantidad) {
                detalles.push({
                    idArticulo: articulo,
                    idBodega: bodega,
                    cantidad: cantidad,
                    PrecioUnitario: PrecioUnitario,
                    SubTotal: SubTotal
                });
            }
      });
  
      // Verificar que haya al menos un detalle
      if (detalles.length === 0) {
          Swal.fire("Error", "Debe agregar al menos un artículo.", "error");
          return;
      }
  
      // Preparar los datos para enviar
      let data = {
          idIngreso: id,
          IdProveedor : $('#proveedor').val(), 
          NumeroFactura : $('#numeroFactura').val(),
          FechaFactura : $('#fechaFactura').val(),
          Comentarios : $('#comentarios').val(),
          TotalFactura : $('#totalIngreso').val(),
          ImpuestoFactura : $('#impuesto').val(),
          detalles: detalles
      };
  
      // Cerrar el modal después de recopilar los datos
      $('#modalIngreso').modal('hide');
  
      Swal.fire({
          title: '¿Está seguro?',
          html: '¿Desea actualizar este ingreso?<br><br>' +
          ($('#sinFactura').is(':checked') ? '' : 'Número de factura: <strong>' + $('#numeroFactura').val() + '</strong><br>') +
          ($('#sinFactura').is(':checked') ?  '' : 'Proveedor: <strong>' + $('#proveedor option:selected').text() + '</strong><br>' ) + // Proveedor opcional solo si sinFactura
          'Total: <strong>' + Number($('#totalIngreso').val()).toLocaleString('en-US', { minimumFractionDigits: 2 }) + '</strong><br>' +
          'Impuesto: <strong>' + $('#impuesto option:selected').text() + '</strong>',
          text: '¿Desea actualizar este ingreso?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, actualizar',
          cancelButtonText: 'Cancelar'
      }).then((result) => {
          if (result.isConfirmed) {
              
              $.ajax({
                  url: 'modules/inventory/controller/inventoryEntry/update_ProductEntry.php',
                  type: 'POST',
                  data: JSON.stringify(data),
                  dataType: "json",
                  success: function (response) {
                      $("#ingreso_id").val("");
                      $("#formIngreso")[0].reset();
                      if (response.success) {
                          Swal.fire({
                              title: 'Ingreso actualizado',
                              text: 'El ingreso ha sido actualizado correctamente.',
                              icon: 'success',
                              confirmButtonText: 'Aceptar'
                          }).then(() => {
                               window.location.href = '?module=inventoryEntry';
                          });
                      } else {
                          Swal.fire({
                              title: 'Error',
                              text: response.error || 'No se pudo actualizar el ingreso.',
                              icon: 'error',
                              confirmButtonText: 'Aceptar'
                          });
                      }
                  },
                  error: function () {
                      Swal.fire({
                          title: 'Error',
                          text: 'Hubo un problema al actualizar el ingreso.',
                          icon: 'error',
                          confirmButtonText: 'Aceptar'
                      });
                  }
              });
          }
      });
  }
  

 function loadEntryForEdit(id) {
    $.ajax({
        url: './modules/inventory/controller/inventoryEntry/list_EntriesForUpdate.php?id=' + id,
        type: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (!response.success) {
                console.error("Error al obtener la entrada:", response.error);
                return;
            }

            let detalles = response.data;  
            $('#detalleIngreso tbody').empty();
            $("#ingreso_id").val(id);
            
            detalles.forEach(function(detalle) {
                let productoOptions = productos.map(p => 
                    `<option value="${p.id}" ${p.id === detalle.idArticulo ? 'selected' : ''}>${p.NombreArticulo}</option>`
                ).join('');
                
                let bodegaOptions = bodegas.map(b => 
                    `<option value="${b.id}" ${b.id === detalle.idBodega ? 'selected' : ''}>${b.NombreBodega}</option>`
                ).join('');

                let nuevaFila = `
                    <tr>
                        <td>
                            <select class="form-select" name="articulo[]" required>
                                ${productoOptions}
                            </select>
                        </td>
                        <td>
                            <select class="form-select" name="bodega[]" required>
                                ${bodegaOptions}
                            </select>
                        </td>
                        <td><input type="number" class="form-control cantidad" name="cantidad[]" required value="${detalle.CantidadIngreso}" step="0.01"></td>
                        <td><input type="number" class="form-control precioUnitario" name="precioUnitario[]" required value="${detalle.PrecioUnitario}" step="0.01"></td>
                        <td><input type="number" class="form-control subTotal" name="subTotal[]" required value="${detalle.SubTotal}" step="0.01"></td>
                        <td><button type="button" class="btn btn-danger btn-sm eliminar">Eliminar</button></td>
                    </tr>`;
                
                $('#detalleIngreso tbody').append(nuevaFila);
            });

            $("#modalIngreso").modal("show");
        },
        error: function() {
            alert("Error al cargar los detalles de la entrada.");
        }
    });


    $.ajax({
        url: './modules/inventory/controller/inventoryEntry/list_inventoryHeader.php?id=' + id,
        type: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (!response.success) {
                console.error("Error al obtener la entrada:", response.error);
                return;
            }

            let detalles = response.data;  

            console.log(detalles);

            setTimeout(function() {
                $('#proveedor').val(detalles.IdProveedor);
                $('#proveedor').select2({
                    width: '100%',
                    placeholder: 'Seleccione un proveedor',
                    allowClear: true
                });
                $('#proveedor').trigger('change'); // Trigger change to update the select2 display
                
            }, 1000);
        
            $('#numeroFactura').val(detalles.NumeroFactura);
            $('#fechaFactura').val(detalles.FechaFactura);
            $('#comentarios').val(detalles.Comentarios);
            $('#impuesto').val(detalles.ImpuestoFactura);
            $('#totalIngreso').val(detalles.TotalFactura);
 
        },
        error: function() {
            alert("Error al cargar los detalles de la entrada.");
        }
    });
}

