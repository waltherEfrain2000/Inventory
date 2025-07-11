<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Detalles de cuenta por pagar de proveedores</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="?module=accounts_payable">Cuentas por Pagar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Detalles de cuenta de proveedor</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <form id="formCuentaPagar">
                            <div class="row">
                                <!-- proveedor -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="proveedor">Proveedor</label>
                                    <select class="form-select select2" id="proveedor" name="proveedor" required>

                                    </select>
                                </div>

                                <!-- Saldo Pendiente -->
                                <div class="mb-3 col-md">
                                    <label class="form-label" for="anticipado">Anticipos</label>
                                    <div class="input-group">
                                        <span class="input-group-text">L</span>
                                        <input type="number" class="form-control" id="anticipado" name="anticipado" min="0"
                                            step="0.01" placeholder="Saldo pendiente" required>
                                    </div>
                                </div>


                                <!-- Monto Total -->
                                <div class="mb-3 col-md">
                                    <label class="form-label" for="montoPagado">Monto Pagado</label>
                                    <div class="input-group">
                                        <span class="input-group-text">L</span>
                                        <input type="number" class="form-control" id="montoPagado" name="montoPagado" min="0" step="0.01"
                                            placeholder="Ingrese el monto total" required>
                                    </div>
                                </div>

                                <!-- Monto Total -->
                                <div class="mb-3 col-md">
                                    <label class="form-label" for="disponible">Disponible</label>
                                    <div class="input-group">
                                        <span class="input-group-text">L</span>
                                        <input type="number" class="form-control" id="disponible" name="disponible" min="0" step="0.01"
                                            placeholder="Ingrese el monto total" required>
                                    </div>
                                </div>


                                <!-- Historial de Anticipos -->
                                <div class="mb-4 col-md-12 mt-5" id="contenedor_historial">
                                    <h5>Historial de Anticipos</h5>

                                    <table class="table table-bordered" id="anticiposTabla">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha</th>
                                                <th>Referencia</th>
                                                <th>Monto</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="historialPagos">
                                            <tr>
                                                <td colspan="5" class="text-center">No hay pagos registrados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                 <!-- Historial de Pagos -->
                                 <div class="mb-4 col-md-12 mt-5" id="contenedor_historial">
                                    <h5>Historial de Pagos</h5>

                                    <table class="table table-bordered" id="anticiposTabla">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha</th>
                                                <th>Referencia</th>
                                                <th>Monto</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="historialPagos">
                                            <tr>
                                                <td colspan="5" class="text-center">No hay pagos registrados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>



                                <!-- Botón Guardar -->
                                <div class="mb-2 col-md-12 d-flex justify-content-center">
                                    <button type="button" class="btn btn-shadow btn-success" id="btnGuardarPago">Guardar Cuenta por Pagar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalRegistrarAbono" tabindex="-1" aria-labelledby="modalRegistrarAbonoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarAbonoLabel">Registrar Abono</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistrarAbono">
                    <div class="mb-3">
                        <label for="fechaAbono" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fechaAbono" name="fechaAbono" required>
                    </div>
                    <div class="mb-3">
                        <label for="montoAbono" class="form-label">Monto del Abono</label>
                        <div class="input-group">
                            <span class="input-group-text">L</span>
                            <input type="number" class="form-control" id="montoAbono" name="montoAbono" min="1" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="metodoPago" class="form-label">Método de Pago</label>
                        <select class="form-select" id="metodoPago" name="metodoPago" required>

                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a class="btn btn-success" id="btnGuardarAbono">Guardar Abono</a>
                        <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>