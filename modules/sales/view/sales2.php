<?php $anio_actual = date("Y"); ?>

<style>
    .form-control2 {
        display: block;
        width: 100%;
        padding: 8px 12px;
        font-size: 1.2rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 8px;
        margin-top: 3px;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .swal-wide {
        width: 80% !important;
        /* Ajusta el ancho del modal */
        max-width: 1000px;
        /* Ancho máximo opcional */
    }
</style>

<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Venta directa</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="?module=historial">Listado ventas</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Venta directa</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <div id="basicwizard" class="form-wizard row justify-content-center">

                            <div class="col-12">

                                <form id="ventaterceros" method="post" action="#">
                                    <div class="row mt-4">


                                        <!-- Entidades -->

                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="fechalarga">Fecha:</label>
                                            <samp name="fechalarga" id="fechalarga" class="form-control2">04 de Abril. 2025</samp>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="proveedor">Proveedor:</label>
                                            <select type="text" class="form-control" id="proveedor" name="proveedor" placeholder="..." required autocomplete="off"></select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="cliente">Cliente:</label>
                                            <select type="text" class="form-control" id="cliente" name="cliente" placeholder="..." required autocomplete="off"></select>
                                        </div>

                                        <!-- Boleta -->

                                        <div class="mb-3 col-md-4">
                                            <label for="boleta" class="col-form-label pt-0">Boleta:</label>
                                            <input type="text" class="form-control boleta" id="boleta" data-mask="999-999-99-99999999" required autocomplete="off">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="fecha" class="col-form-label pt-0">Boleta:</label>
                                            <div class=" input-group">
                                                <input type="file" class="form-control" id="fileboleta" name="fileboleta" aria-describedby="verboleta" aria-label="Upload" required />
                                                <button class="btn btn-outline-secondary" type="button" id="verboleta"><i class="fas fa-eye"></i></button>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="peso">Toneladas:</label>
                                            <input type="text" class="form-control moneda" id="peso" name="peso" placeholder="0.00" required autocomplete="off">
                                        </div>

                                        <!-- Venta -->

                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="pcompra">Precio de compra:</label>
                                            <div class=" input-group">
                                                <span class="input-group-text ocultar">Lps.</span>
                                                <input type="text" class="form-control moneda" id="pcompra" placeholder="0.00" name="pcompra" disabled>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="pventa">Precio de venta:</label>
                                            <div class=" input-group">
                                                <span class="input-group-text ocultar">Lps.</span>
                                                <input type="text" class="form-control moneda" id="pventa" placeholder="0.00" name="pventa" disabled>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="totalp">Total a pagar:</label>
                                            <div class=" input-group">
                                                <span class="input-group-text ocultar">Lps.</span>
                                                <input type="text" class="form-control moneda" id="totalp" placeholder="0.00" name="totalp" required autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="ganancia">Ganancia:</label>
                                            <div class=" input-group">
                                                <span class="input-group-text ocultar">Lps.</span>
                                                <input type="text" class="form-control moneda" id="ganancia" placeholder="0.00" name="ganancia" disabled>
                                            </div>
                                        </div>

                                        <!-- Transporte -->

                                        <div class="mb-3 col-md-3" style="position: relative;">
                                            <label class="form-label" for="conductor">Conductor:</label>
                                            <input type="text" class="form-control" id="conductor" name="conductor" required autocomplete="off">
                                            <ul id="resultadosConductor" style="list-style: none; margin-top: 5px; padding: 0; position: absolute; z-index: 1000; background: white; border: 1px solid #ced4da; width: 90%;"></ul>
                                        </div>

                                        <div class="mb-3 col-md-3" style="position: relative;">
                                            <label class="form-label" for="placa">Placa:</label>
                                            <input type="text" class="form-control" id="placa" name="placa" placeholder="" required autocomplete="off" style="text-transform: uppercase">
                                            <ul id="resultadosPlaca" style="list-style: none; margin-top: 5px; padding: 0; position: absolute; z-index: 1000; background: white; border: 1px solid #ced4da; width: 90%;"></ul>
                                        </div>

                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="vehiculo_propio">Vehículo propio:</label>
                                            <div class="d-flex justify-content-center form-control2">
                                                <input type="radio" id="vehiculo_propio_si" name="vehiculo_propio" value="si" required>
                                                <label for="vehiculo_propio_si" class="ms-2 me-3">Sí</label>
                                                <input type="radio" id="vehiculo_propio_no" name="vehiculo_propio" value="no" required checked>
                                                <label for="vehiculo_propio_no" class="ms-2">No</label>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="pflete">Precio flete:</label>
                                            <input type="text" class="form-control moneda" id="pflete" name="pflete" placeholder="0.00" required autocomplete="off">
                                        </div>

                                        <!-- Observaciones -->


                                        <div class="mb-3 col-md-312">
                                            <label class="form-label" for="observacion">Observaciones:</label>
                                            <input type="text" class="form-control" id="observacion" name="observacion" autocomplete="off">
                                        </div>

                                        <div class="col-md-12 d-flex justify-content-end" style="padding: 10px;">
                                            <a class="btn btn-shadow btn-warning" id="guardaravance" tabindex="0">
                                                <span>Guardar avance</span>
                                            </a>
                                            <a class="btn btn-shadow btn-success" id="procesarventa" tabindex="0">
                                                <span>Procesar venta</span>
                                            </a>
                                        </div>
                                    </div>
                                </form>



                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
