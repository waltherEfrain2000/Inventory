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

<?php  date_default_timezone_set('America/Tegucigalpa'); $anio_actual = date("Y"); ?>
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Agregar Vehículo</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="?module=vehicles">Listado vehículos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Agregar Vehículo</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <form id="formVehiculo">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="marca">Marca</label>
                                    <select class="form-select select2" id="marca" name="marca" required>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="modelo">Modelo</label>
                                    <select class="form-select select2" id="modelo" name="modelo" required>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="placa">Placa</label>
                                    <input type="text" class="form-control" id="placa" name="placa"
                                        oninput="this.value = this.value.toUpperCase()" placeholder="Ingrese la placa"
                                        required maxlength="7">
                                </div>
                                <div class="mb-3 col-md-2 infoVehiculo" id="placaImg" style="height: 90px;">
                                    <span id="imgPlaca">_______</span>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label" for="anio">Año</label>

                                    <input type="number" class="form-control" id="anio" name="anio"
                                        placeholder="Ingrese el año" min="1980" max="<?php echo $anio_actual; ?>"
                                        required>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label" for="color">Color</label>

                                    <input type="text" class="form-control" id="color" name="color"
                                        placeholder="Ingrese el color" required>
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label class="form-label" for="tipo">Tipo de Vehículo</label>
                                    <select class="form-select select2" id="tipo" name="tipo" required>
                                    </select>
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label class="form-label" for="pertenencia">Pertenencia</label>
                                    <select class="form-select select2" id="pertenencia" name="pertenencia" required>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="txtKilometrajeActual">Kilometraje Actual</label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="1" class="form-control"
                                            id="txtKilometrajeActual" name="txtKilometrajeActual"
                                            placeholder="Ingrese el kilometraje" aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">km</span>
                                    </div>

                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="txtIntervaloMantenimiento">Intérvalo de mantenimiento</label>
                                    <div class="input-group" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Intérvalo de cada cuántos kilómetros deberá realizarse el mantenimiento">
                                        <input type="number" min="0" step="1" class="form-control" value="5000"
                                            id="txtIntervaloMantenimiento" name="txtIntervaloMantenimiento"
                                            placeholder="Ingrese el kilometraje" aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">km</span>
                                    </div>

                                </div>

                                <div class="my-4 col-md-12" hidden>
                                    <input id="pics" name="pics" type="file" class="filepond" multiple>
                                </div>


                                <div class="mb-2 col-md-12 d-flex justify-content-center">
                                    <button type="button" class="btn btn-shadow btn-success" id="btnGuardar">Guardar Vehículo</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>