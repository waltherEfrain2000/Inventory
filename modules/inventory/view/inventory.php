<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<style>
    .card-analytics {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        background-color: white;
    }

    .card-analytics:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .card-icon {
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .card-value {
        font-size: 1.6rem;
        font-weight: 700;
    }

    .card-title-analytics {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .border-inventory {
        border-left: 4px solid #FF7B00 !important;
    }

    .border-stock {
        border-left: 4px solid #4CAF50 !important;
    }

    .border-value {
        border-left: 4px solid #2196F3 !important;
    }

    .border-warehouse {
        border-left: 4px solid #9C27B0 !important;
    }

    .border-profit {
        border-left: 4px solid #FFC107 !important;
    }

    .border-projected {
        border-left: 4px solid #00BCD4 !important;
    }

    .filter-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .badge-stock {
        background-color: #4CAF50;
        color: white;
    }

    .badge-low {
        background-color: #FFC107;
        color: black;
    }

    .badge-out {
        background-color: #F44336;
        color: white;
    }

    .badge-profit {
        background-color: #9C27B0;
        color: white;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 1.5rem;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .dt-buttons .btn {
        margin-right: 5px;
    }
</style>

<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="bi bi-box-seam mr-2 text-orange"></i> Inventario General
                    </h5>
                  
                </div>
                <div class="card-body">
                    <!-- Cards de Análisis -->
                    <!-- Fila 1 -->
                    <div class="row mb-4">
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card h-100 card-analytics border-inventory">
                                <div class="card-body text-center py-2">
                                    <i class="bi bi-box-seam card-icon" style="color: #FF7B00;"></i>
                                    <div class="card-value" id="total-products">0</div>
                                    <div class="card-title-analytics">Productos en Inventario</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card h-100 card-analytics border-stock">
                                <div class="card-body text-center py-2">
                                    <i class="bi bi-check-circle card-icon" style="color: #4CAF50;"></i>
                                    <div class="card-value" id="total-stock">0</div>
                                    <div class="card-title-analytics">Unidades en Stock</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card h-100 card-analytics border-value">
                                <div class="card-body text-center py-2">
                                    <i class="bi bi-currency-dollar card-icon" style="color: #2196F3;"></i>
                                    <div class="card-value" id="total-value">L 0</div>
                                    <div class="card-title-analytics">Valor Total</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fila 2 -->
                    <div class="row mb-4">
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card h-100 card-analytics border-warehouse">
                                <div class="card-body text-center py-2">
                                    <i class="bi bi-building card-icon" style="color: #9C27B0;"></i>
                                    <div class="card-value" id="total-warehouses">0</div>
                                    <div class="card-title-analytics">Bodegas</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card h-100 card-analytics border-profit">
                                <div class="card-body text-center py-2">
                                    <i class="bi bi-graph-up card-icon" style="color: #FFC107;"></i>
                                    <div class="card-value" id="total-profit">0%</div>
                                    <div class="card-title-analytics">Ganancia Promedio</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card h-100 card-analytics border-projected">
                                <div class="card-body text-center py-2">
                                    <i class="bi bi-cash-stack card-icon" style="color: #00BCD4;"></i>
                                    <div class="card-value" id="total-projected">L 0</div>
                                    <div class="card-title-analytics">Proyección de Ventas</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Filtros -->
                    <div class="filter-section mb-4">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Bodega</label>
                                <select class="form-select" id="filter-warehouse">
                                    <option value="">Todas las bodegas</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-2 d-flex align-items-end">
                                <button class="btn btn-primary me-2" id="btn-filter">
                                    <i class="bi bi-funnel"></i> Filtrar
                                </button>
                                <button class="btn btn-outline-secondary me-2" id="btn-reset">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reiniciar
                                </button>
                                    <button class="btn btn-success ms-auto" id="btn-export" onclick="exportToExcel()">
                                    <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Inventario -->
                    <div class="dt-responsive table-responsive">
                        <table id="table-inventory"  class="table table-hover table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Bodega</th>
                                    <th>Unidad</th>
                                    <th>Stock</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>% Ganancia</th>
                                    <th>Valor Total</th>
                                    <th>Proyección</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Datos se llenarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script> 

<script>
    

</script>