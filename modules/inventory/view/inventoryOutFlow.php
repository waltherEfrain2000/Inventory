<style>
    /* Estilos para el contenedor de filtros */
#historial .filter-container {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

#historial .date-range-filter {
    display: flex;
    gap: 10px;
    align-items: flex-end;
    flex-wrap: wrap;
}

/* Estilos para las cards */
#historial .card {
    transition: transform 0.3s ease;
}

#historial .card:hover {
    transform: translateY(-5px);
}

#historial .card-title {
    font-size: 14px;
    margin-bottom: 8px;
}

#historial .card-text {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 4px;
}

/* Estilos para la tabla */

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}


</style>
<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Salida de Inventarios</h5>
                </div>
                <div class="card-body">

                    <!-- Pestañas -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="ingreso-tab" data-bs-toggle="tab" href="#ingreso" role="tab" aria-controls="ingreso" aria-selected="true">Salidas de inventario</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="historial-tab" data-bs-toggle="tab" href="#historial" role="tab" aria-controls="historial" aria-selected="false">Historial de salidas</a>
                        </li>
                    </ul>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Ingreso de Inventario Tab -->
                        <div class="tab-pane fade show active" id="ingreso" role="tabpanel" aria-labelledby="ingreso-tab">
                            <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4">
                                <button class="btn btn-primary" onclick="window.location.href='?module=inventoryOutFlowDetail'">Nueva Salida</button>

                            </div>
                            <div class="table-responsive mt-3">
                                <input type="hidden" id="ingreso_id" name="id">
                                <table class="table table-bordered" id="table-IngresoInventario">
                                    <thead>
                                        <tr>
                                        <th style="display:none;">Id</th>
                                            <th>Cliente</th>
                                            <th>Estado</th>
                                            <th>Total Factura</th>
                                            <th>Fecha Creación</th>
                                            <th>Financiado</th>
                                            <th>Saldo Pendiente</th>
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
                            <div class="row mt-4">
                                <!-- Card 1: Total Salidas -->
                                <div class="col-md-3">
                                    <div class="card text-dark bg-white border-danger">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-box-arrow-up text-danger"></i> Total Salidas
                                            </h6>
                                            <p id="totalSalidasCard" class="card-text">0</p>
                                            <small class="text-muted">Unidades totales</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 2: Valor Total -->
                                <div class="col-md-3">
                                    <div class="card text-dark bg-white border-warning">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-cash-stack text-warning"></i> Valor Total
                                            </h6>
                                            <p id="valorTotalCard" class="card-text">L 0.00</p>
                                            <small class="text-muted">Valor acumulado</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 3: Salida Promedio -->
                                <div class="col-md-3">
                                    <div class="card text-dark bg-white border-info">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-graph-up text-info"></i> Salida Promedio
                                            </h6>
                                            <p id="salidaPromedioCard" class="card-text">0</p>
                                            <small class="text-muted">Unidades por transacción</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 4: Artículo Más Vendido -->
                                <div class="col-md-3">
                                    <div class="card text-dark bg-white border-success">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-star-fill text-success"></i> Artículo Top
                                            </h6>
                                            <p id="articuloTopCard" class="card-text">-</p>
                                            <small class="text-muted">Más unidades vendidas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-container mb-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="date-range-filter">
                                            <div class="form-group" style="flex-grow: 1;">
                                                <label for="fechaInicioSalidas" class="form-label">Fecha Inicio:</label>
                                                <input type="date" id="fechaInicioSalidas" class="form-control">
                                            </div>
                                            <div class="form-group" style="flex-grow: 1;">
                                                <label for="fechaFinSalidas" class="form-label">Fecha Fin:</label>
                                                <input type="date" id="fechaFinSalidas" class="form-control">
                                            </div>
                                            <button class="btn btn-primary" onclick="filtrarSalidas()" style="align-self: flex-end;">
                                                <i class="bi bi-filter"></i> Filtrar
                                            </button>
                                            <button class="btn btn-secondary" onclick="limpiarFiltrosSalidas()" style="align-self: flex-end;">
                                                <i class="bi bi-arrow-counterclockwise"></i> Limpiar
                                            </button>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                            <div class="table-responsive mt-6">
                                <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4">
                                    <button class="btn btn-success" onclick="exportToExcel()">Exportar a Excel</button>
                                </div>
                                <input type="hidden" id="historial_id" name="id">
                                <table id="tablaHistorialSalidas" class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                             <th>Tipo salida</th>
                                            <th>Fecha</th>
                                            <th>Bodega</th>
                                            <th>Artículo</th>
                                            <th>Categoría</th>
                                            <th>Subcategoría</th>

                                            <th>Cantidad</th>
                                            <th>Precio Unitario</th>

                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aquí se cargan los datos dinámicamente con JS/AJAX -->
                                    </tbody>
                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td colspan="6" class="text-end">Totales:</td>
                                            <td id="footerCantidadHistorialSalida">0.00</td>
                                            <td></td>
                                            <td id="footerTotalSalida">L. 0.00</td>
                                        </tr>
                                    </tfoot>
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