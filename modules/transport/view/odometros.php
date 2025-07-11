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
                                <h5>Odómetros</h5>
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
                                        <th>Tipo de Registro</th>
                                        <th>Kilometraje</th>
                                        <th>Fecha del Registro</th>
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