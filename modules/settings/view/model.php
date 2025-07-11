<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Modelos de Vehículos</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="?module=settings">Parametrización</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Agregar Modelo</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <form id="formVehiculo">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label" for="marca">Marca</label>
                                            <select class="form-select select2" id="marca" name="marca" >
                                                <!-- <option value="">Seleccione...</option> -->
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label" for="nombre">Nombre de Modelo</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el modelo" >
                                        </div>
                                        <div class="mb-2 col-md-12 d-flex justify-content-center">
                                            <button type="button" id="guardar" class="btn btn-shadow btn-success">Guardar Modelo</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="dt-responsive table-responsive">
                                        <table id="tabla" class="table table-striped table-hover table-bordered nowrap">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>ID</th>
                                                    <th>Marca</th>
                                                    <th>Modelo</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarModalLabel">Editar Modelo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarMarca">
                <div class="mb-3">
                                            <label class="form-label" for="marcaEditar">Marca</label>
                                            <select class="form-select select2" id="marcaEditar" name="marcaEditar">
                                                <!-- <option value="">Seleccione...</option> -->
                                            </select>
                                        </div>
                    <div class="mb-3">
                        <label for="editarNombre" class="form-label">Nombre del Modelo</label>
                        <input type="text" class="form-control" id="editarNombre" name="editarNombre" placeholder="Ingrese el nombre">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="guardarCambios">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>