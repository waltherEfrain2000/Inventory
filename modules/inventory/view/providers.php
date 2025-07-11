<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Gestión de proveedores</h5>
                </div>
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="proveedor-tab" data-bs-toggle="tab" href="#proveedores" role="tab" aria-controls="proveedores" aria-selected="true">Proveedores</a>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Tab Proveedores -->
                        <div class="tab-pane fade show active" id="proveedores" role="tabpanel" aria-labelledby="proveedor-tab">
                            <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProveedor">Agregar Proveedor</button>
                            </div>
                            <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                    <table id="table-proveedores" class="table table-striped table-hover table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Estado</th>
                                                <th>Número Celular</th>
                                                <th>Dirección</th>
                                                <th>Nombre de Contacto</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Estado</th>
                                                <th>Número Celular</th>
                                                <th>Dirección</th>
                                                <th>Nombre de Contacto</th>
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

    <!-- Modal para agregar/editar proveedor -->
    <div class="modal fade" id="modalProveedor" tabindex="-1" aria-labelledby="modalProveedorLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProveedorLabel">Administración de Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formProveedor">
                        <input type="hidden" id="proveedor_id" name="id">
                        
                        <div class="mb-3">
                            <label for="nombreProveedor" class="form-label">Nombre del Proveedor</label>
                            <input type="text" class="form-control" id="nombreProveedor" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcionProveedor" class="form-label">Descripción del Proveedor</label>
                            <input type="text" class="form-control" id="descripcionProveedor" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estadoProveedor" class="form-label">Estado</label>
                            <select class="form-select" id="estadoProveedor" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="numeroCelular" class="form-label">Número de Celular</label>
                            <input type="text" class="form-control" id="numeroCelular" required>
                        </div>

                        <div class="mb-3">
                            <label for="direccionProveedor" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccionProveedor" required>
                        </div>

                        <div class="mb-3">
                            <label for="nombreContacto" class="form-label">Nombre de Contacto</label>
                            <input type="text" class="form-control" id="nombreContacto" required>
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
