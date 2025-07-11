<style>
    .swal-wide {
        max-width: 70%;
        /* Ajusta el ancho del modal */
        width: 70%;
        /* Ancho relativo al viewport */
    }
</style>
<div class="pc-container">
    <div class="pc-content pt-0">

        <div class="row g-3 align-items-stretch">

            <!-- Tarjeta 1 -->
            <div class="col">
                <div class="card selecttodas">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar avtar-s bg-light-primary">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Ventas Procesadas</h6>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 todas" style="color:cornflowerblue">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2 -->
            <div class="col">
                <div class="card selectpendientes">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar avtar-s bg-light-securondary">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Ventas Pendientes</h6>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 pendientes" style="color:cornflowerblue">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3 -->
            <div class="col">
                <div class="card selectprocesadas">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar avtar-s bg-light-warning">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Ventas En proceso</h6>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 procesadas" style="color:cornflowerblue">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 4 -->
            <div class="col">
                <div class="card selectcobrando">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar avtar-s bg-light-info">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Pendientes de Cobro</h6>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 cobrando" style="color:cornflowerblue">0</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 5 -->
            <div class="col">
                <div class="card selectcompletadas">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar avtar-s bg-light-success">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Ventas Completadas</h6>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 completadas" style="color:cornflowerblue">0</h6>
                            </div>
                        </div>
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
                                <h5>Listado de ventas</h5>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <a class="btn btn-shadow btn-primary" href="?module=sales2"><span>Nueva venta directa</span></a>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="tablaventasdirectas" class="table table-striped table-hover table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>N.</th>
                                        <th>Cliente</th>
                                        <th>Boleta</th>
                                        <th>Peso de venta</th>
                                        <th>Monto</th>
                                        <th>Flete</th>
                                        <th>Placa</th>
                                        <th>estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>N.</th>
                                        <th>Cliente</th>
                                        <th>Boleta</th>
                                        <th>Peso de venta</th>
                                        <th>Monto</th>
                                        <th>Flete</th>
                                        <th>Placa</th>
                                        <th>estado</th>
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

<!-- Modal -->

<div class="modal fade" id="comprobanteModal" tabindex="-1" aria-labelledby="comprobanteModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="comprobanteModalLabel">Finalizar venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addNotarecepcionForm">
                    <div class="row">
                        <!-- No. Recepción -->
                        <div class="mb-3 col-sm-6">
                            <label for="nrecepcion" class="col-form-label pt-0">No. Recepción:</label>
                            <input type="text" class="form-control rtn" id="nrecepcion" name="nrecepcion" data-mask="9999-9999-999999" required autocomplete="off" />
                        </div>

                        <!-- Archivo Recepción con botón -->
                        <div class="mb-3 col-sm-6">
                            <label class="col-form-label pt-0">Archivo Recepción:</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="filerecepcion" name="filerecepcion" aria-describedby="verrecepcion" aria-label="Upload" required />
                                <button class="btn btn-outline-secondary" type="button" id="verrecepcion"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        </br>
                        <div class="mb-3 col-sm-12">
                            <div class="form-group d-flex align-items-center">
                                <label class="mr-3">Raqui:&nbsp;&nbsp;&nbsp;</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="traeraqui" id="raquisi" value="1" checked>
                                    <label class="form-check-label" for="raquisi">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="traeraqui" id="raquino" value="2">
                                    <label class="form-check-label" for="raquino">No</label>
                                </div>
                            </div>
                        </div>

                        <div id="traeraqui">
                            <div class="row">
                                <!-- No. Recepción raqui -->
                                <div class="mb-3 col-sm-6">
                                    <label for="nrecepcionraqui" class="col-form-label pt-0">No. Recepción raqui:</label>
                                    <input type="text" class="form-control rtn" id="nrecepcionraqui" name="nrecepcionraqui" required autocomplete="off" />
                                </div>

                                <!-- Archivo + botón -->
                                <div class="mb-3 col-sm-6">
                                    <label class="col-form-label pt-0">Archivo Recepción Raqui:</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="filerecepcionraqui" name="filerecepcionraqui" aria-describedby="verrecepcionraqui" aria-label="Upload" required />
                                        <button class="btn btn-outline-secondary" type="button" id="verrecepcionraqui"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>


                            </br>
                            <div class="mb-3 col-sm-12">
                                <div class="form-group d-flex align-items-center">
                                    <label class="mr-3">Acciones:&nbsp;&nbsp;&nbsp;</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="accionraqui" id="accionraqui1" value="1" checked>
                                        <label class="form-check-label" for="accionraqui1">Solo flete</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="accionraqui" id="accionraqui2" value="2">
                                        <label class="form-check-label" for="accionraqui2">Compra y venta de raqui</label>
                                    </div>
                                </div>
                            </div>

                            <div id="soloflete">
                                <div class="row">
                                    <div class="mb-3 col-sm-6">
                                        <label for="fleteraqui" class="col-form-label pt-0">Precio del flete:</label>
                                        <input type="text" class="form-control moneda" id="fleteraqui" name="fleteraqui" required autocomplete="off" />
                                    </div>
                                </div>
                            </div>

                            <div id="compraventa">
                                <div class="row">
                                    <div class="mb-3 col-sm-3">
                                        <label for="nrecepcionraqui" class="col-form-label pt-0">Cantidad raqui:</label>
                                        <input type="text" class="form-control moneda" id="cantidadRaqui" name="cantidadRaqui" required autocomplete="off" />
                                    </div>

                                    <div class="mb-3 col-sm-3">
                                        <label for="nrecepcionraqui" class="col-form-label pt-0">Precio campra del raqui:</label>
                                        <input type="text" class="form-control moneda" id="precioCompraRaqui" name="precioCompraRaqui" required autocomplete="off" />
                                    </div>

                                    <div class="mb-3 col-sm-3">
                                        <label for="nrecepcionraqui" class="col-form-label pt-0">Precio venta del raqui:</label>
                                        <input type="text" class="form-control moneda" id="precioVentaRaqui" name="precioVentaRaqui" required autocomplete="off" />
                                    </div>

                                    <div class="mb-3 col-sm-3">
                                        <label for="nrecepcionraqui" class="col-form-label pt-0">Ganancia:</label>
                                        <input type="text" class="form-control moneda" id="gananciaRaqui" name="gananciaRaqui" disabled />
                                    </div>

                                </div>
                            </div>

                        </div>



                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="btnguardarrecepcion">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="PagoModal" tabindex="-1" aria-labelledby="PagoModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-m" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PagoModalLabel">Finalizar venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPagoForm">
                    <div class="row">
                        <input type="hidden" id="idventa" name="idventa" />
                        <input type="hidden" id="montototal" name="montototal" />
                        <div class="mb-3 col-sm-12">
                            <div class="form-group d-flex align-items-center">
                                <label class="mr-3">Pagara la totalidad:&nbsp;&nbsp;&nbsp;</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pagototal" id="todosi" value="1" checked>
                                    <label class="form-check-label" for="todosi">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="pagototal" id="todono" value="2">
                                    <label class="form-check-label" for="todono">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 col-sm-12" id="pagototal">
                            <label for="abono" class="col-form-label pt-0" id="labelmontoabono">Monto a abonar:</label>
                            <input type="text" class="form-control moneda" id="abono" name="abono" required autocomplete="off" />
                        </div>
                        <div class="mb-3 col-sm-12" id="pagoparcial">
                            <label class="col-form-label pt-0" id="labelmonto"></label>
                        </div>

                        <div class="mb-3 col-sm-12">
                            <label for="metodoPago" class="col-form-label pt-0">Metodo de pago:</label>
                            <select id="metodoPago" class="swal2-select form-control" name="metodoPago" required>
                                <option value="" disabled selected>Seleccione</option>
                                <option value="1">Efectivo</option>
                                <option value="2">Tarjeta de Crédito</option>
                                <option value="3">Tarjeta de Débito</option>
                                <option value="4">Transferencia Bancaria</option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="btnguarabono">Guardar</button>
            </div>
        </div>
    </div>
</div>