<?php $anio_actual = date("Y"); ?>

<style>
    
#resultados, #resultados2, #resultadosPlaca {
    max-height: 150px;  /* Ajusta la altura máxima según lo que necesites */
    overflow-y: auto;   /* Habilita el desplazamiento vertical */
    position: absolute; /* Evita que el <ul> mueva las demás etiquetas */
    z-index: 10;        /* Asegura que el <ul> quede por encima de otros elementos */
    width: 370px;        /* Ocupa todo el ancho del <input> */
    background-color: white; /* Fondo blanco para que se vean las opciones */
    padding-bottom: 10px;
}
body {
    padding-bottom: 150px; /* Espacio adicional al final de la página */
}

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
.badge.text-bg-warning {
        animation: blink 1s infinite;
    }
</style>
<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Agregar Venta</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="?module=historial">Listado ventas</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Agregar venta</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <div id="basicwizard" class="form-wizard row justify-content-center">

                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <ul class="nav nav-pills nav-justified">
                                            <li class="nav-item" data-target-form="#InfoGeneralForm">
                                                <a href="#InfoGeneral" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                                    <i class="ph-duotone ph-user-circle"></i>
                                                    <span class="d-none d-sm-inline">Inormación general</span>
                                                </a>
                                            </li>
                                            <!-- end nav item -->
                                            <li class="nav-item" data-target-form="#InfoDetalleForm">
                                                <a href="#InfoDetalle" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                                    <i class="ph-duotone ph-table"></i>
                                                    <span class="d-none d-sm-inline">Detalle</span>
                                                </a>
                                            </li>
                                            <!-- end nav item -->
                                            <li class="nav-item" data-target-form="#InfoTransporteForm">
                                                <a href="#InfoTransporte" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                                    <i class="ph-duotone ph-truck"></i>
                                                    <span class="d-none d-sm-inline">Inormación de transporte</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <!-- START: Define your progress bar here -->
                                    <div id="bar" class="progress mb-3" style="height: 7px">
                                        <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"></div>
                                    </div>
                                    <!-- END: Define your progress bar here -->
                                    <!-- START: Define your tab pans here -->
                                    <div class="tab-pane show active" id="InfoGeneral">
                                        <form id="contactForm" method="post" action="#">
                                            <div class="row mt-4">
                                                <div class="mb-3 col-md-6">
                                                    <label for="nremision" class="col-form-label pt-0">Guía de remisión:</label>
                                                    <input type="text" class="form-control nremision" id="nremision" data-mask="999-999-99-99999999" required autocomplete="off">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="fecha">Fecha</label>
                                                    <input type="date" class="form-control fechasfuturas" id="fecha" name="fecha" placeholder="Ingrese la fecha" required>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="remitente">Remitente:</label>
                                                    <select class="form-select" id="remitente" required disabled>
                                                        <option value="" disabled>Seleccione un remitente</option>
                                                        <option value=1 selected>Remitente 1</option>
                                                        <option value=2>Remitente 2</option>
                                                        <option value=3>Remitente 3</option>
                                                        <option value=4>Remitente 4</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="rtn1">RTN/DNI</label>
                                                    <input type="text" class="form-control" id="rtn1" name="rtn1" placeholder="0000-0000-000000" required disabled>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="destinatario">Destinatario:</label>
                                                    <select class="form-select" id="destinatario" required>
                                                        <option value="" disabled selected>Seleccione un destinatario</option>
                                                        <option value=1>Destinatario 1</option>
                                                        <option value=2>Destinatario 2</option>
                                                        <option value=3>Destinatario 3</option>
                                                        <option value=4>Destinatario 4</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="rtn2">RTN/DNI</label>
                                                    <input type="text" class="form-control" id="rtn2" name="rtn2" placeholder="0000-0000-000000" required disabled>
                                                </div>
                                                <div class="mb-3 col-md-6" hidden>
                                                    <label class="form-label" for="partida">Punto de partida:</label>
                                                    <select class="form-select" id="partida" required>
                                                        <option value="" disabled >Seleccione un punto de partida</option>
                                                        <option value=1 selected>Punto de partida 1</option>
                                                        <option value=2>Punto de partida 2</option>
                                                        <option value=3>Punto de partida 3</option>
                                                        <option value=4>Punto de partida 4</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="destino">Destino:</label>
                                                    <input type="text" class="form-control" id="destino" name="destino" required disabled>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- end contact detail tab pane -->
                                    <div class="tab-pane" id="InfoDetalle">
                                        <form id="formdetalle" method="post" action="#">
                                            <div class="row mt-4">
                                                <div class="mb-3 col-md-12">
                                                    <label class="form-label" for="traslado">Motivo de traslado:</label>
                                                    <select class="form-select" id="traslado" required>
                                                        <option value="" disabled selected>Seleccione un traslado</option>
                                                        <option value=1>Motivo 1</option>
                                                        <option value=2>Motivo 2</option>
                                                        <option value=3>Motivo 3</option>
                                                        <option value=4>Motivo 4</option>
                                                    </select>
                                                </div>

                                                <div class="row mt-4" id="divotrotraslado" style="display: none;">
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label" for="otrotraslado">Tipo</label>
                                                        <input type="text" class="form-control" id="otrotraslado" name="otrotraslado" required>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label" for="nAutorizacion">Numero de Autorización</label>
                                                        <input type="text" class="form-control" id="nAutorizacion" name="nAutorizacion" required>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label" for="numeracion">Numeración</label>
                                                        <input type="text" class="form-control" id="numeracion" name="numeracion" required>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label" for="fechaDocotrotraslado">Fecha del documento de importación</label>
                                                        <input type="date" class="form-control fechasfuturas" id="fechaDocotrotraslado" name="fechaDocotrotraslado" required>
                                                    </div>
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="fechainicio">Fecha de inicio de traslado</label>
                                                    <input type="date" class="form-control fechasfuturas" id="fechainicio" name="fechainicio" required>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="fechatermino">Fecha de finalización del traslado</label>
                                                    <input type="date" class="form-control" id="fechatermino" name="fechatermino" disabled>
                                                </div>
                                                <div class="text-center">
                                                    <div class="col-md-12 d-flex justify-content-end" style="padding: 10px;">
                                                        <a class="btn btn-shadow btn-primary" tabindex="0" id="nuevodetalle">
                                                            <span>Detalle de carga</span>
                                                        </a>
                                                    </div>
                                                </div>



                                                <div class="dt-responsive table-responsive">
                                                    <table id="tablaDetalle" class="table table-striped table-hover table-bordered nowrap" >
                                                        <thead>
                                                            <tr>
                                                                <th>N</th>
                                                                <th>Descripción</th>
                                                                <th>Cantidad(Toneladas)</th>
                                                                <th class="d-none">detalle</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                        <tfoot>

                                                        </tfoot>
                                                    </table>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="tipocertificacion">Tipo de certificación:</label>
                                                    <input type="text" class="form-control tiposc" id="tipocertificacion" name="tipocertificacion" required autocomplete="off">
                                                    <ul id="resultados" style="list-style: none; margin-top: 5px; padding: 0;"></ul>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="ncertificacion">N. certificación:</label>
                                                    <input type="text" class="form-control noc" id="ncertificacion" name="ncertificacion" required required autocomplete="off">
                                                    <ul id="resultados2" style="list-style: none; margin-top: 5px; padding: 0;"></ul>
                                                </div>
                                                <div class="mb-3 col-md-2">
                                                    <label class="form-label" for="emisiones">Emisiones</label>
                                                    <input type="text" class="form-control" id="emisiones" name="emisiones" required>
                                                </div>
                                                <div class="mb-3 col-md-2">
                                                    <label class="form-label" for="km">Km Recorridos</label>
                                                    <input type="text" class="form-control" id="km" name="km" required>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- end job detail tab pane -->
                                    <div class="tab-pane" id="InfoTransporte">
                                        <form id="formtransporte" method="post" action="#">
                                            <div class="row mt-4">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="transportista">Transportista:</label>
                                                    <select class="form-select" id="transportista" required>
                                                        <option value="" disabled selected>Seleccione un transportista</option>
                                                        <option value=1>Transportista 1</option>
                                                        <option value=2>Transportista 2</option>
                                                        <option value=3>Transportista 3</option>
                                                        <option value=4>Transportista 4</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="rtn3">RTN/DNI</label>
                                                    <input type="text" class="form-control" id="rtn3" name="rtn3" placeholder="0000-0000-000000" required disabled>
                                                </div>


                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="conductor">Conductor:</label>
                                                    <select class="form-select" id="conductor" required>
                                                        <option value="" disabled selected>Seleccione un conductor</option>
                                                        <option value=1>Conductor 1</option>
                                                        <option value=2>Conductor 2</option>
                                                        <option value=3>Conductor 3</option>
                                                        <option value=4>Conductor 4</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="rtn4">RTN/DNI</label>
                                                    <input type="text" class="form-control" id="rtn4" name="rtn4" placeholder="0000-0000-000000" required disabled>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="placa">Placa:</label>
                                                    <input type="text" class="form-control" id="placa" name="placa" placeholder="" required autocomplete="off" style="text-transform: uppercase">
                                                    <ul id="resultadosPlaca" style="list-style: none; margin-top: 5px; padding: 0;"></ul>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="marca">Marca:</label>
                                                    <input type="text" class="form-control" id="marca" name="marca" placeholder="" required disabled>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="licencia">Licencia de conducir:</label>
                                                    <input type="text" class="form-control" id="licencia" name="licencia" placeholder="" required>
                                                </div>
                                            </div>

                                            <div class="col-md-12 d-flex justify-content-end" style="padding: 10px;">
                                                <a class="btn btn-shadow btn-warning" id="guardaravance" tabindex="0">
                                                    <span>Guardar avance </span>
                                                </a>
                                                <a class="btn btn-shadow btn-success" id="procesarventa" tabindex="0">
                                                    <span>Procesar venta</span>
                                                </a>
                                            </div>
                                        </form>
                                    </div>


                                    <!-- END: Define your tab pans here -->
                                    <!-- START: Define your controller buttons here-->
                                    <div class="d-flex wizard justify-content-between flex-wrap gap-2 mt-3">
                                        <div class="first">
                                            <a href="javascript:void(0);" class="btn btn-secondary"> Inicio </a>
                                        </div>
                                        <div class="d-flex">
                                            <div class="previous me-2">
                                                <a href="javascript:void(0);" class="btn btn-secondary"> Anterior </a>
                                            </div>
                                            <div class="next">
                                                <a href="javascript:void(0);" class="btn btn-secondary"> Siguiente </a>
                                            </div>
                                        </div>
                                        <div class="last">
                                            <a href="javascript:void(0);" class="btn btn-secondary"> Final </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal nuevo cliente -->

<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formdetallemodal" method="post" action="#">
                    <div class="mb-3">
                        <label class="form-label" for="descripcion">Descripción:</label>
                        <select class="form-select" id="descripcion" required>
                            <option value="" disabled selected>Seleccione Descripción</option>
                            <option value=1>Palma</option>
                            <option value=2>Raqui</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="table-responsive dt-responsive">
                                <table id="disponibles" class="table table-striped table-bordered nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="d-none">ID</th>
                                            <th>Productor</th>
                                            <th>Lote</th>
                                            <th>Stock</th>
                                            <th>Usar</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="table-responsive dt-responsive">
                                <table id="seleccionados" class="table table-striped table-bordered nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="d-none">id</th>
                                            <th>Productor</th>
                                            <th>Lote</th>
                                            <th>Seleccionados</th>
                                            <th>Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="guardardetalle">Registrar item</button>
            </div>
        </div>
    </div>
</div>