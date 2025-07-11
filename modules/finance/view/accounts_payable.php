<div class="pc-container">

    <div class="pc-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-6">
                                <h5>CUENTAS POR PAGAR</h5>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <a class="btn btn-shadow btn-success" tabindex="0" aria-controls="custom-btn" href="?module=ap_details">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                    </svg>
                                    <span>Agregar nuevo</span></a>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mt-1">
                            <div class="col-3">
                                <div class="bg-body p-3 rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-shrink-0">
                                            <span class="p-1 d-block bg-primary rounded-circle">
                                                <span class="visually-hidden">New alerts</span>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0">Total cuentas por pagar</p>
                                        </div>
                                    </div>
                                    <h6 class="mb-0 text-center" id="pendienteCobro"></h6>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="bg-body p-3 rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-shrink-0">
                                            <span class="p-1 d-block bg-warning rounded-circle">
                                                <span class="visually-hidden">New alerts</span>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0">Cuentas proximas a vencer</p>
                                        </div>
                                    </div>
                                    <h6 class="mb-0 text-center" id="proximoVencer"></h6>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="bg-body p-3 rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-shrink-0">
                                            <span class="p-1 d-block bg-danger rounded-circle">
                                                <span class="visually-hidden">New alerts</span>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0">Cuentas Vencidas</p>
                                        </div>
                                    </div>
                                    <h6 class="mb-0 text-center" id="vencidas"></h6>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="bg-body p-3 rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-shrink-0">
                                            <span class="p-1 d-block bg-success rounded-circle">
                                                <span class="visually-hidden">New alerts</span>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0">Pagos realizados este mes</p>
                                        </div>
                                    </div>
                                    <h6 class="mb-0 text-center" id="pagosMes"></h6>
                                </div>
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
                            <div class="col-md">
                                <h5>CUENTAS POR PROVEEDOR</h5>
                            </div>


                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mt-1">
                            <div class="mb-3 col-mD">
                                <label class="form-label" for="proveedor">Proveedor</label>
                                <select class="form-select select2" id="proveedor" name="proveedor" required>

                                </select>
                            </div>
                            <div class="mb-2 col-md-12 d-flex justify-content-center">
                                <button type="button" class="btn btn-shadow btn-success" id="btnConsultarProveedor">Ver Detalles</button>
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
                            <div class="col-md">
                                <h5>LISTADO DE ANTICIPOS</h5>
                            </div>


                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table class="table table-hover table-bordered nowrap" id="cuentasPorPagarTabla">
                                <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Proveedor</th>
                                        <th>No. de Factura</th>
                                        <th>Fecha de Emisión</th>
                                        <th>Monto total</th>
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