<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Gestión de Inventarios</h5>
                </div>
                <div class="card-body">
                    <!-- Pestañas -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="ingreso-tab" data-bs-toggle="tab" href="#ingreso" role="tab" aria-controls="ingreso" aria-selected="true">Ingreso de Inventario</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="historial-tab" data-bs-toggle="tab" href="#historial" role="tab" aria-controls="historial" aria-selected="false">Historial de Ingresos</a>
                        </li>
                    </ul>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Ingreso de Inventario Tab -->
                        <div class="tab-pane fade show active" id="ingreso" role="tabpanel" aria-labelledby="ingreso-tab">
                            <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4">
                            <button class="btn btn-primary" onclick="window.location.href='?module=inventoryEntryDetail'">Nuevo Ingreso</button>

                            </div>
                            <div class="table-responsive mt-3">
                                <input type="hidden" id="ingreso_id" name="id">
                                <table class="table table-bordered" id="table-IngresoInventario">
                                    <thead>
                                        <tr>
                                            <th>Numero Factura</th>
                                            <th>Estado</th>
                                            <th>Total Factura</th>
                                            <th>Fecha Creación</th>
                                            <th>Impuesto</th>
                                            <th>Comentarios</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Historial de Ingresos Tab -->
                        <div class="tab-pane fade" id="historial" role="tabpanel" aria-labelledby="historial-tab">
                            <div class="table-responsive mt-6">
                            <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4">
                            <button class="btn btn-primary" onclick="exportToExcel()">Exportar a Excel</button>
                            </div>
                                <input type="hidden" id="historial_id" name="id">
                                <table class="table table-bordered" id="table-HistorialIngresos">
                                    <thead>
                                        <tr>
                                            <th>Nombre Bodega</th>
                                            <th>Nombre Articulo</th>
                                            <th>Categoria</th>
                                            <th>SubCategoria</th>
                                            <th>Proveedor</th>
                                            <th>Cantidad Ingreso</th>
                                            <th>Fecha Ingreso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 
</div>

</div>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>

<!-- Botones de DataTables -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>

<!-- JSZip para exportar a Excel -->
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!-- pdfMake para exportar a PDF -->
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script>
    // Función para exportar la tabla a Excel
 


</script>

