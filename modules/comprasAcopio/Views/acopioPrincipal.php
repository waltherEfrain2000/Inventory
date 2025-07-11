<style>
    @keyframes blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .swal-popup-custom {
        z-index: 2000 !important;
        /* Asegúrate de que sea más alto que el modal del formulario */
    }

    .badge.text-bg-warning {
        animation: blink 1s infinite;
    }

    td {
        align-content: center;
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

    .hover-scale:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
</style>
<div class="pc-container">
    <div class="pc-content pt-0">

        <div class="row g-3 align-items-stretch">

            <!-- Tarjeta 1 -->
            <div class="col-md-6 col-xxl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar avtar-s bg-light-primary">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4" d="M13 9H7" stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M22.0002 10.9702V13.0302C22.0002 13.5802 21.5602 14.0302 21.0002 14.0502H19.0402C17.9602 14.0502 16.9702 13.2602 16.8802 12.1802C16.8202 11.5502 17.0602 10.9602 17.4802 10.5502C17.8502 10.1702 18.3602 9.9502 18.9202 9.9502H21.0002C21.5602 9.9702 22.0002 10.4202 22.0002 10.9702Z"
                                            stroke="#4680FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M17.48 10.55C17.06 10.96 16.82 11.55 16.88 12.18C16.97 13.26 17.96 14.05 19.04 14.05H21V15.5C21 18.5 19 20.5 16 20.5H7C4 20.5 2 18.5 2 15.5V8.5C2 5.78 3.64 3.88 6.19 3.56C6.45 3.52 6.72 3.5 7 3.5H16C16.26 3.5 16.51 3.50999 16.75 3.54999C19.33 3.84999 21 5.76 21 8.5V9.95001H18.92C18.36 9.95001 17.85 10.17 17.48 10.55Z"
                                            stroke="#4680FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="?module=productores">
                                    <h6 class="mb-0">Ver productores</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tarjeta 1 -->
            <div class="col-md-6 col-xxl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar avtar-s bg-light-primary">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4" d="M13 9H7" stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M22.0002 10.9702V13.0302C22.0002 13.5802 21.5602 14.0302 21.0002 14.0502H19.0402C17.9602 14.0502 16.9702 13.2602 16.8802 12.1802C16.8202 11.5502 17.0602 10.9602 17.4802 10.5502C17.8502 10.1702 18.3602 9.9502 18.9202 9.9502H21.0002C21.5602 9.9702 22.0002 10.4202 22.0002 10.9702Z"
                                            stroke="#4680FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M17.48 10.55C17.06 10.96 16.82 11.55 16.88 12.18C16.97 13.26 17.96 14.05 19.04 14.05H21V15.5C21 18.5 19 20.5 16 20.5H7C4 20.5 2 18.5 2 15.5V8.5C2 5.78 3.64 3.88 6.19 3.56C6.45 3.52 6.72 3.5 7 3.5H16C16.26 3.5 16.51 3.50999 16.75 3.54999C19.33 3.84999 21 5.76 21 8.5V9.95001H18.92C18.36 9.95001 17.85 10.17 17.48 10.55Z"
                                            stroke="#4680FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="">
                                    <h6 class="mb-0">Realizar abono</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tarjeta 1 -->
            <div class="col-md-6 col-xxl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar avtar-s bg-light-primary">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4" d="M13 9H7" stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M22.0002 10.9702V13.0302C22.0002 13.5802 21.5602 14.0302 21.0002 14.0502H19.0402C17.9602 14.0502 16.9702 13.2602 16.8802 12.1802C16.8202 11.5502 17.0602 10.9602 17.4802 10.5502C17.8502 10.1702 18.3602 9.9502 18.9202 9.9502H21.0002C21.5602 9.9702 22.0002 10.4202 22.0002 10.9702Z"
                                            stroke="#4680FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M17.48 10.55C17.06 10.96 16.82 11.55 16.88 12.18C16.97 13.26 17.96 14.05 19.04 14.05H21V15.5C21 18.5 19 20.5 16 20.5H7C4 20.5 2 18.5 2 15.5V8.5C2 5.78 3.64 3.88 6.19 3.56C6.45 3.52 6.72 3.5 7 3.5H16C16.26 3.5 16.51 3.50999 16.75 3.54999C19.33 3.84999 21 5.76 21 8.5V9.95001H18.92C18.36 9.95001 17.85 10.17 17.48 10.55Z"
                                            stroke="#4680FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="">
                                    <h6 class="mb-0">Ver abono</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tarjeta 1 -->
            <div class="col-md-6 col-xxl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avtar avtar-s bg-light-primary">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4" d="M13 9H7" stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M22.0002 10.9702V13.0302C22.0002 13.5802 21.5602 14.0302 21.0002 14.0502H19.0402C17.9602 14.0502 16.9702 13.2602 16.8802 12.1802C16.8202 11.5502 17.0602 10.9602 17.4802 10.5502C17.8502 10.1702 18.3602 9.9502 18.9202 9.9502H21.0002C21.5602 9.9702 22.0002 10.4202 22.0002 10.9702Z"
                                            stroke="#4680FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M17.48 10.55C17.06 10.96 16.82 11.55 16.88 12.18C16.97 13.26 17.96 14.05 19.04 14.05H21V15.5C21 18.5 19 20.5 16 20.5H7C4 20.5 2 18.5 2 15.5V8.5C2 5.78 3.64 3.88 6.19 3.56C6.45 3.52 6.72 3.5 7 3.5H16C16.26 3.5 16.51 3.50999 16.75 3.54999C19.33 3.84999 21 5.76 21 8.5V9.95001H18.92C18.36 9.95001 17.85 10.17 17.48 10.55Z"
                                            stroke="#4680FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="">
                                    <h6 class="mb-0">Ver prestamos</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pc-content">
        <div class="row">
            <!-- Complex Headers With Column Visibility table start -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-6">
                                <h5>Ingreso de Fruta</h5>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" id="nuevaCarga"><i class="fas fa-truck"></i> Nueva carga</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dt-responsive">
                            <table id="complex-header" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <!-- <tr>
                                        <th colspan="3">Productor</th>
                                        <th colspan="5">Datos de Descarga de fruta</th>
                                        <th rowspan="1">Acciones</th>
                                    </tr> -->
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Vehiculo</th>
                                        <th>Fecha Peso Bruto</th>
                                        <th>Peso Bruto (Lbs)</th>
                                        <th>Peso Tara (Lbs)</th>
                                        <th>Peso Total (Lbs)</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>NOMBRE</th>
                                        <th>VEHICULO</th>
                                        <th>FECHA PESO BRUTO</th>
                                        <th>PESO BRUTO (LBS)</th>
                                        <th>PESO TARA (LBS)</th>
                                        <th>PESO TOTAL (LBS)</th>
                                        <th>ESTADO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Complex Headers With Column Visibility table end -->
        </div>
    </div>
</div>

<div
    id="exampleModalCenter"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Registro de Carga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cargaForm">
                    <div class="row">
                        <div class="mb-3  col-md-6" hidden>
                            <label class="form-label" for="exampleInputPassword1">Si miras esto es un error</label>
                            <input type="text" class="form-control" id="Na" placeholder="No borrar input" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlSelect1">Productor</label>
                            <select class="form-select" id="productorSelect">
                                <option value="-1">Selecciona un productor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlSelect1">Placa / Identificador</label>
                            <select class="form-select" id="trasnporteSelect">
                                <option value="-1">Selecciona un transporte</option>
                            </select>
                        </div>
                        <div class="mb-3  col-md-6">
                            <label class="form-label" for="exampleInputPassword1">Peso Bruto</label>
                            <input type="text" class="form-control" id="pesoBruto" placeholder="Ingresa el peso bruto en libras" />
                        </div>
                        <div class="mb-3 col-md-6 hidden" hidden>
                            <label class="form-label" for="exampleInputPassword1">Peso Tara</label>
                            <input type="text" class="form-control" id="pesoTara" placeholder="Ingresa el peso tara en libras" />
                        </div>
                        <div class="mb-3 col-md-6 hidden" hidden>
                            <label class="form-label" for="exampleInputPassword1">Peso Neto</label>
                            <input disabled type="text" class="form-control" id="pesoNeto" placeholder="Esperando datos...." />
                        </div>


                        <div class="mb-3 col-md-6 hidden" hidden>
                            <label class="form-label" for="exampleInputPassword1">Precio de compra</label>
                            <input type="text" class="form-control" id="precioCompra" placeholder="Ingresa el precio de compra" />
                        </div>
                        <div class="mb-3 col-md-12" hidden>
                            <label class="form-label" for="exampleInputPassword1">Total a pagar</label>
                            <input disabled type="text" class="form-control" id="totalPagar" placeholder="Esperando datos...." />
                        </div>
                        <div class="form-check mb-3 hidden" hidden>
                            <input class="form-check-input" type="checkbox" id="abonarPrestamo" name="abonarPrestamo">
                            <label class="form-check-label" for="abonarPrestamo">Abonar a préstamo pendiente</label>
                        </div>
                        <div id="prestamoFields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label" for="exampleFormControlSelect1">Metodo de pago</label>
                                <select class="form-select" id="metodoPago">
                                    <option value="-1">Selecciona una opción</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="valorAportar" class="form-label">Valor a Aportar</label>
                                <input type="number" class="form-control" id="valorAportar" name="valorAportar">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary hidden" hidden data-bs-dismiss="modal" id="cerrarModal">Cerrar</button>
                <button type="button" class="btn btn-primary hidden" hidden id="completarDescarga">Completar</button>
                <button type="button" class="btn btn-warning" id="enviarDescarga">Enviar a descarga</button>
            </div>
        </div>
    </div>
</div>