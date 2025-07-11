<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Gestión de bodegas</h5>
                </div>
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="bodega-tab" data-bs-toggle="tab" href="#bodegas" role="tab" aria-controls="bodegas" aria-selected="true">Bodegas</a>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Tab Bodegas -->
                        <div class="tab-pane fade show active" id="bodegas" role="tabpanel" aria-labelledby="bodega-tab">
                            <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBodega">Agregar Bodega</button>
                            </div>
                            <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                    <table id="table-bodegas" class="table table-striped table-hover table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nombre Bodega</th>
                                                <th>Descripción</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Nombre Bodega</th>
                                                <th>Descripción</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
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

    <!-- Modal para agregar/editar bodega -->
    <div class="modal fade" id="modalBodega" tabindex="-1" aria-labelledby="modalBodegaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBodegaLabel">Administración de bodega</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formBodega">
                        <input type="hidden" id="bodega_id" name="id">
                        
                        <div class="mb-3">
                            <label for="nombreBodega" class="form-label">Nombre de la Bodega</label>
                            <input type="text" class="form-control" id="nombreBodega" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcionBodega" class="form-label">Descripción de la Bodega</label>
                            <input type="text" class="form-control" id="descripcionBodega" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estadoBodega" class="form-label">Estado</label>
                            <select class="form-select" id="estadoBodega" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
