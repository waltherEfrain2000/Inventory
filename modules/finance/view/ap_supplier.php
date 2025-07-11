<div class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Ingreso de Proveedor</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="?module=stat">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="?module=supplier">Proveedores</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Registrar / Editar Proveedores</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <form id="formProveedor">
                            <input type="hidden" id="proveedor_id" name="proveedor_id">

                            <div class="row">
                                <!-- Nombre del proveedor -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="nombreProveedor">Nombre</label>
                                    <input type="text" class="form-control" id="nombreProveedor" name="nombreProveedor"
                                        placeholder="Ingrese el nombre del proveedor" required>
                                </div>

                                <!-- RTN -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="rtn">RTN</label>
                                    <input type="text" class="form-control" id="rtn" name="rtn"
                                        placeholder="Número de RTN (opcional)">
                                </div>

                                <!-- Dirección -->
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="direccion">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion"
                                        placeholder="Dirección del proveedor">
                                </div>

                                <!-- Teléfono -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="telefono">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono"
                                        placeholder="Teléfono del proveedor">
                                </div>

                                <!-- Correo -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="correo">Correo electrónico</label>
                                    <input type="email" class="form-control" id="correo" name="correo"
                                        placeholder="Correo del proveedor">
                                </div>

                              

                                <!-- Botón Guardar -->
                                <div class="mb-2 col-md-12 d-flex justify-content-center">
                                    <button type="button" class="btn btn-shadow btn-primary" id="guardarRegistro">Guardar Proveedor</button>
                                </div>
                            </div>
                        </form>
                    </div> <!-- card-body -->
                </div>
            </div>
        </div>
    </div>
</div>
