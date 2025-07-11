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

@keyframes blink {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.12;
    }

    100% {
        opacity: 1;
    }
}

.badge.bg-danger {
    animation: blink 1.4s infinite;
}

.remision{
    font-size: 0.7rem;
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
            <div class="col-md-3">
                <div class="info-card bg-white d-flex">
                    <div class="icon-container bg-success text-white me-3">
                        <i class="fas fa-car fa-2x"></i>
                    </div>
                    <div id="card_estado_1">
                        
                    </div>
                </div>
            </div>
            <!-- Tarjeta 2 -->
            <div class="col-md-3">
                <div class="info-card bg-white d-flex">
                    <div class="icon-container bg-danger text-white me-3">
                        <i class="fas fa-car fa-2x"></i>
                    </div>
                    <div id="card_estado_2">
                        
                    </div>
                </div>
            </div>
            <!-- Tarjeta 3 -->
            <div class="col-md-3">
                <div class="info-card bg-white d-flex">
                    <div class="icon-container bg-warning text-white me-3">
                        <i class="fas fa-car fa-2x"></i>
                    </div>
                    <div id="card_estado_3">
                        
                    </div>
                </div>
            </div>
            <!-- Tarjeta 4 -->
            <div class="col-md-3">
                <div class="info-card bg-white d-flex">
                    <div class="icon-container bg-danger-subtle text-white me-3">
                        <i class="fas fa-car fa-2x"></i>
                    </div>
                    <div id="card_estado_4">
                        
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
                                <h5>Vehículos</h5>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <a class="btn btn-shadow btn-success" tabindex="0" aria-controls="custom-btn" href="?module=addVehicle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
                                </svg>   
                                <span>Agregar nuevo</span></a>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table  class="table table-hover table-bordered nowrap" id="ListadoVehiculosTabla">
                                <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Placa</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Año</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Pertenencia</th>
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