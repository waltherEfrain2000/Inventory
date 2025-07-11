<style>
    td{
    align-content: center;

}
    td.tdPlaca{
    background-image: url(./src/assets/images/transporte/placaAsset.png);
    background-repeat: no-repeat;
    background-size: 7rem auto;
    background-position-y: center;
    background-position-x: center;
    font-weight: bold;
    padding: 1.5rem !important;
    /* height: 70px; */
    font-size: inherit;
}

.info-card {
    display: flex;
    align-items: center;
    justify-content: start;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    height: 90px;
}
.icon-container {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

</style>

<div class="pc-container">

    <div class="pc-content pt-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-6">
                                <h5>Inspecciones</h5>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <button class="btn btn-shadow btn-success" tabindex="0" aria-controls="custom-btn" id="btnAgregarInspeccion">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                                </svg>   
                                <span>Agregar Inspección</span></button>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table  class="table table-hover table-bordered nowrap" id="tabla">
                                <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Placa</th>
                                        <th>Información del Vehículo</th>
                                        <th>Fecha de la Inspección</th>
                                        <th>Kilometraje</th>
                                        <th>Observaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal agregar inspección -->
<div class="modal fade" id="modalInspeccion" tabindex="-1" aria-labelledby="modalInspeccionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInspeccionLabel">Agregar Inspección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formInspeccion">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="vehiculo">Vehículo</label>
                            <select class="form-select select2" id="vehiculo" name="vehiculo">
                            </select>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="kilometraje">Kilometraje Actual</label>
                            <div class="input-group">
                                <input type="number" min="0" step="1" class="form-control"
                                    id="kilometraje" name="kilometraje"
                                    placeholder="Ingrese el kilometraje" aria-describedby="basic-addon2">
                                <span class="input-group-text" id="basic-addon2">km</span>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="observaciones">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Ingrese las observaciones" required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnGuardar">Guardar Inspección</button>
            </div>
        </div>
    </div>
</div>