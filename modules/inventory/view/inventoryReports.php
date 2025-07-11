<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Reportes de Inventario</h5>
                </div>
                <div class="card-body">

                    <ul class="nav nav-tabs" id="tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="inventario-tab" data-bs-toggle="tab" href="#inventario" role="tab" aria-controls="inventario" aria-selected="true">Inventario Ingresos</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="vehiculos-tab" data-bs-toggle="tab" href="#vehiculos" role="tab" aria-controls="vehiculos" aria-selected="false">Insumos Vehículos</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="semilla-tab" data-bs-toggle="tab" href="#semilla" role="tab" aria-controls="semilla" aria-selected="false">Inventario Semilla</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <!-- TAB 1: Inventario General -->
                        <div class="tab-pane fade show active" id="inventario" role="tabpanel" aria-labelledby="inventario-tab">
                            <form class="row g-2 align-items-end mb-3" id="filtro-inventario">
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Desde</label>
                                    <input type="date" class="form-control" id="fecha-desde-inventario">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Hasta</label>
                                    <input type="date" class="form-control" id="fecha-hasta-inventario">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Categoría</label>
                                    <select class="form-select" id="categoria-inventario">
                                        <option value="">Todas</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Producto</label>
                                    <select class="form-select" id="producto-inventario">
                                        <option value="">Todos</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Bodega</label>
                                    <select class="form-select" id="bodega-inventario">
                                        <option value="">Todas</option>
                                    </select>
                                </div>
                                <div class="col-12 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-primary" id="filtrar-inventario"><i class="bi bi-funnel"></i> Filtrar</button>
                                    <button type="button" class="btn btn-outline-secondary" id="reset-inventario"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</button>
                                    <button type="button" class="btn btn-success" id="export-inventario">Exportar Excel</button>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="tabla-inventario" class="table table-striped table-hover table-bordered nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Fecha Creación</th>
                                            <th>N° Factura</th>
                                            <th>Artículo</th>
                                            <th>Categoría</th>
                                            <th>Subcategoría</th>
                                            <th>Bodega</th>
                                            <th>Cantidad Ingreso</th>
                                            <th>Precio Compra</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 2: Insumos Vehículos -->
                        <div class="tab-pane fade" id="vehiculos" role="tabpanel" aria-labelledby="vehiculos-tab">
                            <form class="row g-2 align-items-end mb-3" id="filtro-vehiculos">
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Desde</label>
                                    <input type="date" class="form-control" id="fecha-desde-vehiculos">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Hasta</label>
                                    <input type="date" class="form-control" id="fecha-hasta-vehiculos">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Producto</label>
                                    <select class="form-select" id="producto-vehiculos">
                                        <option value="">Todos</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Placa</label>
                                    <select class="form-select" id="placa-vehiculos">
                                        <option value="">Todas</option>
                                    </select>
                                </div>
                                <div class="col-12 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-primary" id="filtrar-vehiculos"><i class="bi bi-funnel"></i> Filtrar</button>
                                    <button type="button" class="btn btn-outline-secondary" id="reset-vehiculos"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</button>
                                    <button type="button" class="btn btn-success" id="export-vehiculos"><i class="bi bi-file-earmark-excel"></i> Exportar Excel</button>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="tabla-vehiculos" class="table table-striped table-hover table-bordered nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Placa</th>
                                            <th>TipoVehiculo</th>

                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 3: Inventario Semilla -->
                        <div class="tab-pane fade" id="semilla" role="tabpanel" aria-labelledby="semilla-tab">
                            <form class="row g-2 align-items-end mb-3" id="filtro-semilla">
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Desde</label>
                                    <input type="date" class="form-control" id="fecha-desde-semilla">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Hasta</label>
                                    <input type="date" class="form-control" id="fecha-hasta-semilla">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Categoría</label>
                                    <select class="form-select" id="categoria-semilla">
                                        <option value="">Todas</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Producto</label>
                                    <select class="form-select" id="producto-semilla">
                                        <option value="">Todos</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Bodega</label>
                                    <select class="form-select" id="bodega-semilla">
                                        <option value="">Todas</option>
                                    </select>
                                </div>
                                <div class="col-12 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-primary" id="filtrar-semilla"><i class="bi bi-funnel"></i> Filtrar</button>
                                    <button type="button" class="btn btn-outline-secondary" id="reset-semilla"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</button>
                                    <button type="button" class="btn btn-success" id="export-semilla"><i class="bi bi-file-earmark-excel"></i> Exportar Excel</button>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="tabla-semilla" class="table table-striped table-hover table-bordered nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Cliente</th>
                                            <th>Forma de pago</th>
                                            <th>Categoría</th>
                                            <th>Producto</th>
                                            <th>Bodega</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />

<script>
    
</script>