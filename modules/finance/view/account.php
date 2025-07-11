<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-6">
                                <h5>CATALAGO CONTABLE</h5>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <a class="btn btn-shadow btn-secondary text-white" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                                    <span>Agregar Nuevo</span>
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="table-style-hover" class="table table-striped table-hover table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Nombre</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Nombre</th>
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


<!-- Modal para Agregar Cuenta Contable -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccountLabel">Agregar Nueva Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="addAccountForm">
                    <input type="hidden" id="account_id" name="id">

                    <div class="mb-3">
                        <label class="form-label">Código:</label>
                        <input type="text" class="form-control" name="codigo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Cuenta:</label>
                        <select class="form-select" name="tipo_id" required>
                            <option value="1">Activo</option>
                            <option value="2">Pasivo</option>
                            <option value="3">Patrimonio</option>
                            <option value="4">Ingresos</option>
                            <option value="5">Gastos</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nivel:</label>
                        <input type="number" class="form-control" id="nivel" name="nivel" min="1" max="5" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cuenta Padre:</label>
                        <select class="form-select" id="padre_id" name="padre_id">
                            <option value="">Ninguna</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>