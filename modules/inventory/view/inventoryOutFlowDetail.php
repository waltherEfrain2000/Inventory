<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Salida de inventario</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="?module=inventoryEntry">Salidas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Registrar / Editar Salidas</li>
                        </ol>
                    </nav>
                </div>
                <div class="card-body">
                    <form id="formsalida">
                        <!-- Tipo de Salida -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tipoSalida" class="form-label">Tipo de Salida:</label>
                                <select id="tipoSalida" name="tipoSalida" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="3">Salida por Venta</option>
                                    <option value="1">Salida por Descargo</option>
                                </select>
                            </div>

                            <!-- Sección para Salida por Venta -->
                            <div class="col-md-4">
                                <div id="seccionVenta" style="display:none;">
                                <div class="mb-3">
                        <label for="cliente" class="form-label">Cliente:</label>
                    <select id="cliente" name="cliente" class="form-control" >
                    <option value="">Seleccione un cliente</option>
                    <option value="cliente1">Cliente 1</option>
                    <option value="cliente2">Cliente 2</option>
                     <option value="cliente3">Cliente 3</option>
                    <!-- Agrega más opciones si quieres -->
                    </select>
                    </div>

                                </div>

                                <!-- Sección para Salida por Descargo -->
                                <div id="seccionDescargo" style="display:none;">
                                    <div class="mb-3">
                                        <label for="motivoDescargo" class="form-label">Motivo del Descargo:</label>
                                        <textarea id="motivoDescargo" name="motivoDescargo" class="form-control" ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comentarios -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="comentarios" class="form-label">Comentarios</label>
                                <input type="text" id="comentarios" name="comentarios" class="form-control" required>
                            </div>
                        </div>

                        <!-- Detalle de Salida -->
                        <h5>Detalle de salida</h5>
                        <div id="loader" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                     <span class="visually-hidden">Loading...</span>
                    </div>
                    </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="detallesalida">
                                <thead>
                                    <tr>
                                        <th>Artículo</th>
                                        <th>Bodega</th>
                                        <th>Cantidad </th>
                                        <th> Cantidad en existencia</th>
                                       
                                        <th>Precio Venta</th>
                                        <th >Sub  total </th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Filas agregadas con JS -->
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-success" id="agregarLinea">Agregar Línea</button>

                        <!-- Impuesto y Totales -->
                        <div class="row mt-4">
                            <div class="col-md-4 offset-md-8">
                                <label for="impuesto" class="form-label">Impuesto (%)</label>
                                <select class="form-select" name="impuesto" id="impuesto">
                                    <option value="0.00">Exento</option>
                                    <option value="0.1">1%</option>
                                    <option value="0.10">10%</option>
                                    <option value="0.12">12%</option>
                                    <option value="0.125">12.5%</option>
                                    <option value="0.15">15%</option>
                                    <option value="0.18">18%</option>
                                    <option value="0.25">25%</option>
                                </select>
                            </div>
                            <div class="col-md-4 offset-md-8 mt-2">
                                <label class="form-label">Total</label>
                                <input type="text" id="totalsalida" class="form-control" readonly>
                            </div>
                        </div>

                        <!-- Botón Guardar -->
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- Script para manejar la lógica de los campos -->
<script>
    // Mostrar/ocultar secciones según el tipo de salida
 
</script>
