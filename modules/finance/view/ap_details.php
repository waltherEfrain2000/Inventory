
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Detalles de cuenta por pagar</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="?module=accounts_payable">Cuentas por pagar</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Detalles de cuenta</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <form id="formCuentaPagar">
                            <div class="row">
                                <!-- proveedor -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="proveedor">Proveedor</label>
                                    <select class="form-select select2" id="proveedor" name="proveedor" required>
                                      
                                    </select>
                                </div>

                                <!-- Número de Documento -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="numeroDocumento">Número de Referencia</label>
                                    <input type="text" class="form-control" id="numeroDocumento" name="numeroDocumento"
                                        placeholder="Ingrese el número de documento" required>
                                </div>

                                <!-- Fecha de Emisión -->
                                <div class="mb-3 col-md">
                                    <label class="form-label" for="fechaEmision">Fecha de Emisión</label>
                                    <input type="date" class="form-control" id="fechaEmision" name="fechaEmision" required>
                                </div>
                           
                                <!-- Monto Total -->
                                <div class="mb-3 col-md">
                                    <label class="form-label" for="montoTotal">Monto Total anticipo</label>
                                    <div class="input-group">
                                        <span class="input-group-text">L</span>
                                        <input type="number" class="form-control" id="montoTotal" name="montoTotal" min="0" step="0.01"
                                            placeholder="Ingrese el monto total" required>
                                    </div>
                                </div>

                                <!-- Saldo Pendiente -->
                                <div class="mb-3 col-md-4 saldoPendienteContenedor">
                                    <label class="form-label" for="saldoPendiente">Saldo Disponible</label>
                                    <div class="input-group">
                                        <span class="input-group-text">L</span>
                                        <input type="number" class="form-control" id="saldoPendiente" name="saldoPendiente" min="0"
                                            step="0.01" placeholder="Saldo pendiente" disabled>
                                    </div>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="comentarios">Observaciones/Comentarios</label>
                                    <div class="input-group">
                                        <input type="input" class="form-control" id="comentarios" name="comentarios" min="0"
                                            placeholder="Observaciones / Comentarios" required>
                                    </div>
                                </div>




                                <!-- Botón Guardar -->
                                <div class="mb-2 col-md-12 d-flex justify-content-center">
                                    <button type="button" class="btn btn-shadow btn-success" id="btnGuardarAbono">Guardar Cuenta por Pagar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


