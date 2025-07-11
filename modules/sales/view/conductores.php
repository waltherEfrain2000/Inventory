<style>
    /* CSS */
</style>
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-6">
                                <h5>Conductores</h5>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                
                                <a class="btn btn-shadow btn-primary" tabindex="0" aria-controls="custom-btn" id="nuevoconductor"><span>Agregar Conductor <i class="fas fa-user-plus"></i></span></a>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="tabla_conductores" class="table table-striped table-hover table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nombre</th>
                                        <th>DNI / RTN</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Cento de acopio</th>
                                        <th class="sticky-column" style="background-color: white;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nombre</th>
                                        <th>DNI / RTN</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Cento de acopio</th>
                                        <th class="sticky-column" style="background-color: white;">Acciones</th>
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


<!-- Modal nuevo conductor -->

<div class="modal fade" id="conductorModal" tabindex="-1" aria-labelledby="conductorModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="conductorModalLabel">Nuevo conductor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addConductorForm">
                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="nombre" class="col-form-label pt-0">Conductor:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required />
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="rtn" class="col-form-label pt-0">DNI / RTN:</label>
                            <input type="text" class="form-control rtn" id="rtn" name="rtn" data-mask="9999-9999-999999" required />
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="telefono" class="col-form-label pt-0">Teléfono:</label>
                            <input type="text" class="form-control telefono" id="telefono" name="telefono" data-mask="9999-9999" required />
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="direccion" class="col-form-label pt-0">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required />
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label class="form-label" for="acopio">Centro de acopio:</label>
                            <select class="form-select" id="acopio" name="acopio" required>
                                <option value="" disabled selected>Seleccione un centro de acopio</option>
                                <option value="1">Centro de acopio 1</option>
                                <option value="2">Centro de acopio 2</option>
                                <option value="3">Centro de acopio 3</option>
                                <option value="4">Centro de acopio 4</option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="btnguardar">Registrar Conductor</button>
            </div>
        </div>
    </div>
</div>