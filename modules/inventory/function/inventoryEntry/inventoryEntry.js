let productos = [];
    let bodegas = [];
  
    $(document).ready(function() {
        $("#table-IngresoInventario").DataTable({
            ordering: false
        });

        $('#table-HistorialIngresos').DataTable({
            dom: 'Bfrtip', // Define la posición de los botones
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print' // Botones de exportación
            ]
            
        });

        listEntries();
        listHistoricEntries();
 
 
    

    /**
     * !* Actualiza el subtotal y el total al cambiar la cantidad o el precio unitario
     *  
     */
       
    });


    


    function exportToExcel() {
 
        var table = document.getElementById("table-HistorialIngresos");

 
        var wb = XLSX.utils.table_to_book(table, { sheet: "Historial de Ingresos" });

   
        XLSX.writeFile(wb, "HistorialIngresos.xlsx");
    }

    function listHistoricEntries() {
        if ($.fn.DataTable.isDataTable("#table-HistorialIngresos")) {
            $("#table-HistorialIngresos").DataTable().destroy();
        }
    
        let tableBody = $("#table-HistorialIngresos tbody");
        tableBody.empty();
    
        $.ajax({
            url: "./modules/inventory/controller/inventoryEntry/list_HistoricEntry.php",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (!response.success) {
                    console.error("Error al obtener los ingresos:", response.error);
                    return;
                }
    
                let data = response.data;
    
                // Asegúrate de que los datos se agregan correctamente a la tabla
                $.each(data, function (index, ingreso) {
                    tableBody.append(`
                        <tr>
                            <td>${ingreso.NombreBodega}</td>
                            <td class="text-center">${ingreso.NombreArticulo}</td>
                            <td>${ingreso.Categoria}</td>
                            <td>${ingreso.SubCategoria}</td>
                            <td>${ingreso.Proveedor}</td>
                            <td>${ingreso.CantidadIngreso}</td>
                            <td>${new Date(ingreso.FechaIngreso).toLocaleString('es-ES', { dateStyle: 'short', timeStyle: 'short' })}</td>
                        </tr>
                    `);
                });
    
                // Inicializar DataTable DESPUES de agregar todos los datos
                $('#table-HistorialIngresos').DataTable({
                    ordering: false
                });
                
            },
            error: function (xhr, status, error) {
                console.error("Error al cargar los ingresos:", error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al cargar los ingresos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }
      
    
    function listEntries() {
    if ($.fn.DataTable.isDataTable("#table-IngresoInventario")) {
        $("#table-IngresoInventario").DataTable().destroy();
    }

    let tableBody = $("#table-IngresoInventario tbody");
    tableBody.empty();

    $.ajax({
        url: "./modules/inventory/controller/inventoryEntry/list_inventoryEntry.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                console.error("Error al obtener los ingresos:", response.error);
                return;
            }

            let data = response.data;

            $.each(data, function (index, ingreso) {
                tableBody.append(`
                    <tr>
                        <td>${ingreso.NumeroFactura ? ingreso.NumeroFactura : ""}</td>
                        <td class="text-center">
                            <span class="badge ${ingreso.Estado == 1 ? 'bg-success' : 'bg-danger'}">
                                ${ingreso.Estado == 1 ? 'Activo' : 'Inactivo'}
                            </span>
                        </td>
                   <td>${Number(ingreso.TotalFactura).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        <td>${ingreso.FechaCreacion}</td>
                        <td>${ingreso.ImpuestoFactura ? ingreso.ImpuestoFactura : '-' } % </td>
                        <td>${ingreso.Comentarios ? ingreso.Comentarios : "-"  }</td>
                      <td class="text-center">
                            <button class="btn btn-warning btn-sm edit-btn" onclick="window.location.href='?module=inventoryEntryDetail&id=${ingreso.Id}'">
                        <i class="fas fa-edit"></i>
                    </button>

                            <button class="btn btn-danger btn-sm delete-btn" data-id="${ingreso.Id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            $("#table-IngresoInventario").DataTable({
                ordering: false
            });

            $("#table-IngresoInventario").on("click", ".edit-btn", function () {
                let id = $(this).data("id");
                loadEntryForEdit(id);
            });

            $("#table-IngresoInventario").on("click", ".delete-btn", function () {
                let Id = $(this).data("id");
                handleDeleteEntry(Id);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los ingresos:", error);
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al cargar los ingresos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });


}
  



