
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Detalles de cuenta por cobrar</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="?module=accounts_receivable">Cuentas por cobrar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Detalles de cuenta</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <form id="formCuentaPagar">
                            <div class="row">
                                <!-- cliente -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="cliente">Cliente</label>
                                    <select class="form-select select2" id="cliente" name="cliente" required>
                                      
                                    </select>
                                </div>

                                <!-- Número de Documento -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="numeroDocumento">Número de Documento</label>
                                    <input type="text" class="form-control" id="numeroDocumento" name="numeroDocumento"
                                        placeholder="Ingrese el número de documento" required>
                                </div>

                                <!-- Fecha de Emisión -->
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="fechaEmision">Fecha de Emisión</label>
                                    <input type="date" class="form-control" id="fechaEmision" name="fechaEmision" required>
                                </div>

                                <!-- Fecha de Vencimiento -->
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="fechaVencimiento">Fecha de Vencimiento</label>
                                    <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento" required>
                                </div>

                                <!-- Estado de la Documento -->
                                <div class="mb-3 col-md-4">
                                    <label class="form-label" for="estadoDocumento">Estado</label>
                                    <select class="form-select select2" id="estadoDocumento" name="estadoDocumento" required>
                                       
                                    </select>
                                </div>

                                <!-- Monto Total -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="montoTotal">Monto Total</label>
                                    <div class="input-group">
                                        <span class="input-group-text">L</span>
                                        <input type="number" class="form-control" id="montoTotal" name="montoTotal" min="0" step="0.01"
                                            placeholder="Ingrese el monto total" required>
                                    </div>
                                </div>

                                <!-- Saldo Pendiente -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="saldoPendiente">Saldo Pendiente</label>
                                    <div class="input-group">
                                        <span class="input-group-text">L</span>
                                        <input type="number" class="form-control" id="saldoPendiente" name="saldoPendiente" min="0"
                                            step="0.01" placeholder="Saldo pendiente" required>
                                    </div>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="comentarios">Observaciones/Comentarios</label>
                                    <div class="input-group">
                                        <input type="input" class="form-control" id="comentarios" name="comentarios" min="0"
                                            placeholder="Observaciones / Comentarios" required>
                                    </div>
                                </div>

                                <!-- Historial de Pagos -->
                                <div class="mb-4 col-md-12 mt-5" id="contenedor_historial">
                                    <h5>Historial de Pagos</h5>

                                    <div class="d-flex justify-content-end mb-2">
                                        <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistrarAbono">
                                            <i class="fa fa-plus"></i> Registrar Abono
                                        </a>
                                    </div>

                                    <table class="table table-bordered" id="abonosTabla">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha</th>
                                                <th>Monto</th>
                                                <th>Método de Pago</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="historialPagos">
                                            <tr>
                                                <td colspan="4" class="text-center">No hay pagos registrados</td>
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
                        <a  class="btn btn-success" id="btnGuardarAbono">Guardar Abono</a>
                        <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>