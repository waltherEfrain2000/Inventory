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


    </div>
    <div class="pc-content">
        <div class="row">
            <!-- Complex Headers With Column Visibility table start -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <!-- Título -->
                            <div class="col-md-6">
                                <h5>Control de Productores</h5>
                            </div>
                            <!-- Botones -->
                            <div class="col-md-6 d-flex justify-content-end gap-2">
                                <a class="btn btn-secondary" href="?module=comprasAcopio" id="btnRegresar">
                                    <i class="ti ti-arrow-left"></i> Regresar
                                </a>

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" id="btnAgregarProductor">
                                    <i class="ti ti-man"></i> Agregar Productor
                                </button>
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
                                        <th>Nombre</th>
                                        <th>identificación</th>
                                        <th>dirección</th>
                                        <th>estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>NOMBRE</th>
                                        <th>IDENTIFICACION</th>
                                        <th>DIRECCION</th>
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
            <!-- Complex Headers With Column Visibility table start -->
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
                <h5 class="modal-title" id="exampleModalCenterTitle">Registro de Productor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cargaForm">
                    <div class="row">
                        <div class="mb-3  col-md-6" hidden>
                            <label class="form-label" for="exampleInputPassword1">Si miras esto es un error</label>
                            <input type="text" class="form-control" id="Na" placeholder="No borrar input" />
                        </div>
                        <div class="mb-3  col-md-12">
                            <label class="form-label" for="exampleInputPassword1">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre y apellido" />
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="exampleInputPassword1">Identidad</label>
                            <input type="text" class="form-control" id="identidad" placeholder="0000-0000-00000" />
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="exampleInputPassword1">Dirección</label>
                            <input type="text" class="form-control" id="direccion" placeholder="" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary hidden" hidden data-bs-dismiss="modal" id="cerrarModal">Cerrar</button>
                <button type="button" class="btn btn-primary hidden" hidden id="actualizarProductor">Editar Productor</button>
                <button type="button" class="btn btn-warning" id="guardarProductor">Guardar productor</button>
            </div>
        </div>
    </div>
</div>