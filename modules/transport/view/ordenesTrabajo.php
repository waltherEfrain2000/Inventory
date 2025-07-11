<style>
    td {
        align-content: center;
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

    .swal2-popup.alerta-alta {
        border: 1px solid #ff1744;
        box-shadow: 0 0 4px red;
    }

    .swal2-popup.alerta-media {
        border: 3px solid #ffa000;
    }

    .swal2-popup.alerta-baja {
        border: 2px solid #0288d1;
    }
</style>

<div class="pc-container">

    <div class="pc-content pt-0">

        <div class="row g-3 align-items-stretch">

            <!-- Tarjeta 1 -->
            <div class="col">
                <div class="info-card bg-white d-flex">
                    <div class="icon-container bg-primary text-white me-3">
                        <i class="fas fa-hammer fa-2x"></i>
                    </div>
                    <div id="card_estado_1">
                        
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2 -->
            <div class="col">
                <div class="info-card bg-white d-flex">
                    <div class="icon-container bg-info text-white me-3">
                        <i class="fas fa-hammer fa-2x"></i>
                    </div>
                    <div id="card_estado_2">
                        
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3 -->
            <div class="col">
                <div class="info-card bg-white d-flex">
                    <div class="icon-container bg-warning text-white me-3">
                        <i class="fas fa-hammer fa-2x"></i>
                    </div>
                    <div id="card_estado_3">
                        
                    </div>
                </div>
            </div>

            <!-- Tarjeta 4 -->
            <div class="col">
                <div class="info-card bg-white d-flex">
                    <div class="icon-container bg-success text-white me-3">
                        <i class="fas fa-hammer fa-2x"></i>
                    </div>
                    <div id="card_estado_4">
                        
                    </div>
                </div>
            </div>

            <!-- Tarjeta 5 -->
            <div class="col">
                <div class="info-card bg-white d-flex">
                    <div class="icon-container bg-danger text-white me-3">
                        <i class="fas fa-hammer fa-2x"></i>
                    </div>
                    <div id="card_estado_5">

                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="pc-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-6">
                                <h5>Órdenes de Trabajo</h5>
                            </div>
                            <!-- <div class="col-md-6 d-flex justify-content-end">
                                <a class="btn btn-shadow btn-success" tabindex="0" aria-controls="custom-btn" href="?module=nuevoMantenimiento">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                                </svg>   
                                <span>Agregar nuevo mantenimiento</span></a>
                            </div> -->

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table class="table table-hover table-bordered nowrap" id="tablaMantenimientos">
                                <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Vehículo</th>
                                        <th>Estado</th>
                                        <th>Tipo de Mantenimiento</th>
                                        <th>Tipo de Servicio</th>
                                        <th>Taller</th>
                                        <th>Fecha Generado</th>
                                        <th>Fecha de Inicio</th>
                                        <th>Fecha de Finalización</th>
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

<!-- modal para agregar un motivo de rechazo -->
<div class="modal fade" id="modalMotivoRechazo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rechazar mantenimiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label" for="motivo_rechazo">Motivo del Rechazo:</label>
                <textarea id="motivo_rechazo" name="motivo_rechazo" rows="4" class="form-control" placeholder="Ingrese el motivo del rechazo"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnRechazarMantenimiento" class="btn btn-danger">Rechazar</button>
            </div>
        </div>
    </div>
</div>