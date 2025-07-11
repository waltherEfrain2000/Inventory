<style>
#placaImg {
    background-image: url(./src/assets/images/transporte/placaAsset.png);
    background-repeat: no-repeat;
    background-size: 150px 75px;
    background-position-y: center;
    background-position-x: center;
    font-weight: bold;
    text-align: center;
    align-content: center;
    font-size: x-large;
    font-family: sans-serif;
}
</style>

<?php 
 date_default_timezone_set('America/Tegucigalpa');
$fecha_actual = date('Y-m-d'); 
?>
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Nuevo Mantenimiento</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="?module=mantenimientos">Listado Mantenimientos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Nuevo Mantenimiento</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <form id="formVehiculo">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="vehiculo">Vehículo</label>
                                    <input class="form-control"  id="vehiculo" name="vehiculo" disabled>
                                </div>                            
                                <div class="mb-3 col-md-2 infoVehiculo" id="placaImg" style="height: 90px;">
                                    <span id="imgPlaca">_______</span>
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="estado">Estado Actual de Vehículo</label>

                                    <input type="text" class="form-control" id="estado" name="estado"
                                        placeholder="Estado del vehículo" disabled>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="tipoMantenimiento">Tipo de Mantenimiento a Realizar</label>
                                    <input class="form-control"  id="tipoMantenimiento" name="tipoMantenimiento" disabled>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="tipoServicio">Tipo de Servicio</label>
                                    <input class="form-control"  id="tipoServicio" name="tipoServicio" disabled>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="txtKilometrajeActual">Kilometraje Actual del Vehículo</label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="1" class="form-control" disabled
                                            id="txtKilometrajeActual" name="txtKilometrajeActual"
                                            placeholder="Ingrese el kilometraje actual" aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">km</span>
                                    </div>

                                </div>

                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="fechaInicio">Fecha de Inicio</label>

                                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" disabled>
                                </div>

                                <div class="mb-3 col-md-8">
                                    <label class="form-label" for="comentarios">Comentarios</label>
                                    <textarea class="form-control" disabled id="comentarios" name="comentarios" placeholder="Ingrese comentarios del mantenimiento" rows="3"></textarea>
                                </div>
                                
                                <div class="my-2 col-md-12" hidden>
                                    <input id="pics" name="pics" type="file" class="filepond" multiple>
                                </div>

                                

                                <hr>
                                <div class="col-md-12 mb-3">
                                <div class=" col-md-6 taller" hidden>
                                    <label class="form-label" for="taller">Taller Encargado del Mantenimiento Tercerizado:</label>
                                    <input class="form-control"  id="taller" name="taller" disabled>
                                </div>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <div class="dt-responsive table-responsive">
                                        <table  class="table table-hover table-bordered nowrap" id="tablaTrabajos">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>No.</th>
                                                    <th>Nombre de Trabajo a Realizar</th>
                                                    <th>Costo</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot class="totalesFooter" hidden>
                                                <tr>

                                                    <td colspan="1"></td>
                                                    <td style="text-align:end!important;"><b>Total: </b></td>
                                                    <td class="table-secondary"> <span id="totales"></span></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mx-0 px-0 insumosOT" hidden>
                                    <hr>

                                    <h5 class="mb-3">Insumos para Orden de Trabajo</h5>

                                    <div class="col-md-12 mb-3">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="bodega">Bodega Origen:</label>
                                            <input class="form-control" id="bodega" name="bodega" disabled>
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-12">
                                        <div class="dt-responsive table-responsive">
                                            <table  class="table table-hover table-bordered nowrap" id="tablaInsumos">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>No.</th>
                                                        <th>Insumo</th>
                                                        <th>Unidad</th>
                                                        <th>Cantidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
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