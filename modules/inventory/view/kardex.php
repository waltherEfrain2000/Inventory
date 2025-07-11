<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        padding: 16px;
    }

    .card-title {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .card-text {
        font-size: 18px;
        font-weight: 600;
        color: #212529;
    }

    .bg-white {
        background-color: #fff !important;
    }

    .border-success {
        border: 2px solid #28a745 !important;
    }

    .border-danger {
        border: 2px solid #dc3545 !important;
    }

    .border-light {
        border: 2px solid #dcdcdc !important;
    }

    .border-primary {
        border: 2px solid #007bff !important;
    }

    .border-info {
        border: 2px solid #17a2b8 !important;
    }

    .border-warning {
        border: 2px solid #ffc107 !important;
    }

    .text-dark {
        color: #333 !important;
    }

    .bi {
        font-size: 18px;
    }

    .dt-buttons .btn {
        margin-right: 5px;
    }


    .dt-button {
        color: #fff !important;
        border: none !important;
        padding: 6px 12px !important;
        border-radius: 4px !important;
        margin-right: 5px !important;
    }

    .buttons-excel {
        background-color: #1d6f42 !important;
    }

    .buttons-pdf {
        background-color: #d32f2f !important;
    }

    .buttons-print {
        background-color: #6c757d !important;
    }


    .date-range-filter {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .date-range-filter label {
        margin-bottom: 0;
        font-weight: 500;
    }

    .filter-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 15px;
    }

    @media (max-width: 768px) {
        .filter-container {
            flex-direction: column;
            align-items: stretch;
        }
    }


    #table-Kardex tbody tr {
        transition: background-color 0.3s ease;
    }


    #table-Kardex tbody tr.ingreso-row {
        background-color: #e8f5e9 !important;
    }

    #table-Kardex tbody tr.ingreso-row td {
        border-left: 4px solid #2e7d32 !important;
    }


    #table-Kardex tbody tr.salida-row {
        background-color: #ffebee !important;
    }

    #table-Kardex tbody tr.salida-row td {
        border-left: 4px solid #c62828 !important;
    }


    #table-Kardex tbody tr.saldo-final-row {
        font-weight: bold !important;
        background-color: #e3f2fd !important;
    }


    .dt-buttons .btn {
        margin-right: 5px;
        padding: 5px 10px;
        color: white !important;
    }

    .buttons-excel {
        background-color: #1d6f42 !important;
    }

    .buttons-pdf {
        background-color: #d32f2f !important;
    }

    .buttons-print {
        background-color: #6c757d !important;
    }


    .ingreso-row {
        background-color: #e8f5e9 !important;
    }

    .ingreso-row td {
        border-left: 4px solid #2e7d32 !important;
    }


    .salida-row {
        background-color: #ffebee !important;
    }

    .salida-row td {
        border-left: 4px solid #c62828 !important;
    }


    .saldo-final-row {
        font-weight: bold !important;
        background-color: #e3f2fd !important;
    }
</style>


<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">

<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Kardex por productos</h5>
                </div>
                <div class="card-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="kardex-tab" data-bs-toggle="tab" href="#kardex" role="tab" aria-controls="kardex" aria-selected="true">Kardex</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="historialTr-tab" data-bs-toggle="tab" href="#historialTr" role="tab" aria-controls="historialTr" aria-selected="false">Historial de transacciones</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade show active" id="kardex" role="tabpanel" aria-labelledby="kardex-tab">
                            <div class="filter-container">
                                <div style="flex-grow: 1;">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="selectProducto" class="form-label">Seleccionar Producto:</label>
                                            <select id="selectProducto" class="form-select"></select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="date-range-filter">
                                                <div class="form-group" style="flex-grow: 1;">
                                                    <label for="fechaInicio" class="form-label">Fecha Inicio:</label>
                                                    <input type="date" id="fechaInicio" class="form-control">
                                                </div>
                                                <div class="form-group" style="flex-grow: 1;">
                                                    <label for="fechaFin" class="form-label">Fecha Fin:</label>
                                                    <input type="date" id="fechaFin" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" onclick="buscarKardex()">
                                    <i class="bi bi-search"></i> Buscar Kardex
                                </button>
                            </div>


                            <div id="resumenKardex" class="mt-4" style="display: none;">
                                <div class="row justify-content-center"> <!-- Centrado horizontal -->
                                    <!-- Card 1 -->
                                    <div class="col-8 col-sm-5 col-md-3 col-lg-2 mb-3">
                                        <div class="card text-dark bg-white border-success h-100">
                                            <div class="card-body d-flex flex-column text-center"> <!-- Texto centrado -->
                                                <h6 class="card-title">
                                                    <i class="bi bi-box-arrow-in-down text-success"></i> Cant. Entrada
                                                </h6>
                                                <p id="totalEntrada" class="card-text mt-auto">0</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 2 -->
                                    <div class="col-8 col-sm-5 col-md-3 col-lg-2 mb-3">
                                        <div class="card text-dark bg-white border-success h-100">
                                            <div class="card-body d-flex flex-column text-center">
                                                <h6 class="card-title">
                                                    <i class="bi bi-currency-dollar text-success"></i> Total Entrada
                                                </h6>
                                                <p id="totalPrecioEntrada" class="card-text mt-auto">0</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 3 -->
                                    <div class="col-8 col-sm-5 col-md-3 col-lg-2 mb-3">
                                        <div class="card text-dark bg-white border-danger h-100">
                                            <div class="card-body d-flex flex-column text-center">
                                                <h6 class="card-title">
                                                    <i class="bi bi-box-arrow-up text-danger"></i> Cant. Salida
                                                </h6>
                                                <p id="totalSalida" class="card-text mt-auto">0</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 4 -->
                                    <div class="col-8 col-sm-5 col-md-3 col-lg-2 mb-3">
                                        <div class="card text-dark bg-white border-danger h-100">
                                            <div class="card-body d-flex flex-column text-center">
                                                <h6 class="card-title">
                                                    <i class="bi bi-currency-dollar text-danger"></i> Total Salida
                                                </h6>
                                                <p id="totalPrecioSalida" class="card-text mt-auto">0</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 5 -->
                                    <div class="col-8 col-sm-5 col-md-3 col-lg-2 mb-3">
                                        <div class="card text-dark bg-white border-light h-100">
                                            <div class="card-body d-flex flex-column text-center">
                                                <h6 class="card-title">
                                                    <i class="bi bi-calculator text-secondary"></i> Costo Prom.
                                                </h6>
                                                <p id="costoPromedioFinal" class="card-text mt-auto">0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="balanceResumen" class="row mt-4" style="display: none;">
                                <div class="col-md-4">
                                    <div class="card text-dark bg-white border-primary">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-cash-stack text-primary"></i> Balance Total
                                            </h6>
                                            <p id="balanceTotal" class="card-text">L 0.00</p>
                                            <small class="text-muted">(Entradas - Salidas)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card text-dark bg-white border-info">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-box-seam text-info"></i> Stock Actual
                                            </h6>
                                            <p id="stockActual" class="card-text">0 unidades</p>
                                            <small class="text-muted">(Saldo final)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card text-dark bg-white border-warning">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-graph-up text-warning"></i> Valoración Actual
                                            </h6>
                                            <p id="valoracionActual" class="card-text">L 0.00</p>
                                            <small class="text-muted">(Stock × Costo prom.)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

     <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4">
                                    <button class="btn btn-success" onclick="exportToExcel()">Exportar a Excel</button>
                                </div>
                            <div class="table-responsive mt-3">
                           
                                <table class="table table-bordered" id="table-Kardex" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Nombre Artículo</th>
                                            <th>Fecha Movimiento</th>
                                            <th>Tipo Movimiento</th>
                                            <th>Cantidad Entrada</th>
                                            <th>Precio Entrada</th>
                                            <th>Cantidad Salida</th>
                                            <th>Precio Salida</th>
                                            <th>Saldo Cantidad</th>
                                            <th>Costo Promedio</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align:right">Totales:</th>
                                            <th id="footerEntrada">0</th>
                                            <th id="footerPrecioEntrada">L 0.00</th>
                                            <th id="footerSalida">0</th>
                                            <th id="footerPrecioSalida">L 0.00</th>
                                            <th id="footerSaldo">0</th>
                                            <th id="footerCostoPromedio">L 0.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="historialTr" role="tabpanel" aria-labelledby="historialtr-tab">
                            <div class="row mt-4">


                                <div class="col-md-3">
                                    <div class="card text-dark bg-white border-success">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-cash-stack text-success"></i> Valor Total Entradas
                                            </h6>
                                            <p id="valorTotalEntradas" class="card-text">L 0.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-dark bg-white border-danger">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-cash text-danger"></i> Valor Total Salidas
                                            </h6>
                                            <p id="valorTotalSalidas" class="card-text">L 0.00</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-dark bg-white border-warning">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bi bi-box-arrow-in-down text-warning"></i> Diferencia
                                            </h6>
                                            <p id="totalDiferencias" class="card-text">L 0.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-container mb-3">
                                <div class="date-range-filter">
                                    <div class="form-group">
                                        <label for="fechaInicioHistorial" class="form-label">Fecha Inicio:</label>
                                        <input type="date" id="fechaInicioHistorial" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="fechaFinHistorial" class="form-label">Fecha Fin:</label>
                                        <input type="date" id="fechaFinHistorial" class="form-control">
                                    </div>
                                    <button class="btn btn-primary" onclick="filtrarHistorial()">
                                        <i class="bi bi-filter"></i> Filtrar
                                    </button>
                                    <button class="btn btn-secondary" onclick="limpiarFiltrosHistorial()">
                                        <i class="bi bi-arrow-counterclockwise"></i> Limpiar
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive mt-3">
                                <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4">
                                    <button class="btn btn-success" onclick="exportToExcel()">Exportar a Excel</button>
                                </div>
                                <table class="table table-bordered" id="table-Historial" style="width: 100%;">
                                    <thead>
                                        <tr>

                                            <th>Fecha Movimiento</th>
                                            <th>Tipo Movimiento</th>
                                            <th>Nombre Articulo</th>
                                            <th>Bodega</th>
                                            <th>Cantidad Ingreso</th>
                                            <th>Cantidad Salida</th>
                                            <th>Precio Ingreso</th>
                                            <th>Precio Salida</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">Totales:</th>
                                            <th id="footerCantidadHistorialIngreso">0</th>
                                            <th id="footerCantidadHistorialSalida">0</th>
                                            <th id="footerTotalHistorialIngreso">L 0.00</th>

                                            <th id="footerTotalSalida">L 0.00</th>
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


<script>
  
</script>