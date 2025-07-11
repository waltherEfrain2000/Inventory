<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Toma de Inventario</h5>
                </div>
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="historial-tab" data-bs-toggle="tab" href="#historial" role="tab" aria-controls="historial" aria-selected="true">Historial</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="toma-tab" data-bs-toggle="tab" href="#toma" role="tab" aria-controls="toma" aria-selected="false">Toma de Inventario</a>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Tab Historial (ahora primero y activo) -->
                        <div class="tab-pane fade show active" id="historial" role="tabpanel" aria-labelledby="historial-tab">
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-hover" id="tablaHistorial">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha de Corte</th>
                                            <th>Usuario</th>
                                            <th>Comentarios</th>
                                            <th>Estado</th>
                                            <th>Ver Detalle</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Tab Toma de Inventario (ahora segundo) -->
                        <div class="tab-pane fade" id="toma" role="tabpanel" aria-labelledby="toma-tab">
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="bodegaSelect">Bodega:</label>
                                    <select id="bodegaSelect" class="form-select">
                                        <option value="">Seleccione una bodega</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-end justify-content-end">
                                    <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#modalToma">Agregar Producto</button>
                                </div>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-striped" id="tablaToma">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Existencia</th>
                                            <th>Contado</th>
                                            <th>Diferencia</th>
                                            <th>Motivo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                <label for="comentariosToma" class="form-label">Comentarios:</label>
                                <textarea id="comentariosToma" class="form-control" rows="2" placeholder="Ej. Toma de inventario mensual"></textarea>
                            </div>
                            <div class="mt-3 text-end">
                                <button id="btnGuardarToma" class="btn btn-primary">Guardar Toma</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar producto -->
    <div class="modal fade" id="modalToma" tabindex="-1" aria-labelledby="modalTomaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formToma">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTomaLabel">Agregar Producto a la Toma</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label for="productoSelect" class="form-label">Producto</label>
                            <select id="productoSelect" class="form-select" required>
                                <option value="">Seleccione un producto</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="existenciaActual" class="form-label">Existencia</label>
                            <input type="text" id="existenciaActual" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="cantidadContada" class="form-label">Cantidad Contada</label>
                            <input type="number" step="0.01" min="0" id="cantidadContada" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label for="motivoAjuste" class="form-label">Motivo</label>
                            <textarea id="motivoAjuste" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Agregar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Detalle de Toma -->
    <div class="modal fade" id="modalDetalleToma" tabindex="-1" aria-labelledby="modalDetalleTomaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de Toma de Inventario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                         <div class="table-responsive mt-4"></div>
                    <table class="table table-bordered" id="tablaDetalleToma">
                        <thead>
                            <tr>
                                <th>Fecha de corte</th>
                                <th>Producto</th>
                                <th>Existencia</th>
                                <th>Contado</th>
                                <th>Diferencia</th>
                                <th>Motivo</th>
                                <th>Movimiento</th> <!-- NUEVA COLUMNA -->
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

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
</script>