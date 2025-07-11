<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Ingreso de inventario</h5>
                    <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="?module=inventoryEntry">Ingresos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Registrar / Editar Ingresos</li>
                            </ol>
                        </nav>
                </div>
                <div class="card-body">
                    <form id="formIngreso">
                        <!-- Toggle Ingreso sin factura -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="sinFactura">
                            <label class="form-check-label" for="sinFactura">Ingreso sin factura</label>
                        </div>
                     
                        <!-- Datos generales de la factura -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="proveedor" class="form-label">Proveedor</label>
                                <select id="proveedor" name="proveedor" class="form-form-select select2 factura-field" required>
                                    <option value="">Seleccione un proveedor</option>
                                    <!-- Proveedores dinámicos aquí -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="fechaFactura" class="form-label">Fecha de Factura</label>
                                <input type="date" id="fechaFactura" name="fechaFactura" class="form-control factura-field" required>
                            </div>
                            <div class="col-md-4">
                                <label for="numeroFactura" class="form-label">Número de Factura</label>
                                <input type="text" id="numeroFactura" name="numeroFactura" class="form-control factura-field" required >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="comentarios" class="form-label">Comentarios</label>
                                <input type="text" id="comentarios" name="comentarios" class="form-control" required>
                            </div>
                        </div>

                        <!-- Detalle de ingreso -->
                        <h5>Detalle de Ingreso</h5>
                        <div class="table-responsive">
                        <table class="table table-bordered" id="detalleIngreso">
                            <thead>
                                <tr>
                                    <th>Artículo</th>
                                    <th>Bodega</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Filas agregadas con JS -->
                            </tbody>
                        </table>
                        </div>
                        <button type="button" class="btn btn-success" id="agregarLinea">Agregar Línea</button>

                        <!-- Impuesto y totales -->
                        <div class="row mt-4">
                            <div class="col-md-4 offset-md-8">
                                <label for="impuesto" class="form-label">Impuesto (%)</label>
                                <select class="form-select" name="impuesto" id="impuesto">
                                    <option value="0">Exento</option>
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
                                <input type="text" id="totalIngreso" class="form-control" readonly>
                            </div>
                        </div>

                        <!-- Botón guardar -->
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables JS y otros -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- Script para desactivar campos si es sin factura -->
<script>
    $(document).ready(function() {
        $('#sinFactura').change(function() {
            const isChecked = $(this).is(':checked');
            $('.factura-field').prop('disabled', isChecked);
        });
    });
</script>
