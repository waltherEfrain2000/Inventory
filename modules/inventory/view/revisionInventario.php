
<div class="pc-container">
    <div class="pc-content">
        
    <div class="container mt-5">

    <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Revisión Cíclica de Inventario</h2>
                    </div>
                    <div class="card-body">
        <form action="procesarRevision.php" method="post">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="producto">Producto:</label>
                <select class="form-control" id="producto" name="producto">
                    <option value="producto1">Producto 1</option>
                    <option value="producto2">Producto 2</option>
                    <option value="producto3">Producto 3</option>
                  
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="cantidadExistente">Cantidad Existente:</label>
                <input type="number" class="form-control" id="cantidadExistente" name="cantidadExistente" required>
            </div>
            <div class="col-md-4 form-group">
                <label for="cantidadSistema">Cantidad en Sistema:</label>
                <input type="number" class="form-control" id="cantidadSistema" name="cantidadSistema" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
            </div>
            <div class="col-md-4 form-group">
                <label for="fechaRevision">Fecha de Revisión:</label>
                <input type="date" class="form-control" id="fechaRevision" name="fechaRevision" required>
            </div>
            <div class="col-md-4 form-group">
                <label for="observaciones">Observaciones:</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="autorizacion">Autorización:</label>
                <input type="password" class="form-control" id="autorizacion" name="autorizacion" required>
            </div>
            <div class="col-md-4 form-group">
                <label for="firma">Firma:</label>
                <input type="file" class="form-control" id="firma" name="firma" required>
            </div>
            <div class="col-md-4 form-group d-flex align-items-end justify-content-end">
                <button type="submit" class="btn btn-shadow btn-success">Enviar</button>
            </div>
        </div>
        </form>
        <div class="mt-4">
            <table id="revisionTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad Existente</th>
                        <th>Cantidad en Sistema</th>
                        <th>Ubicación</th>
                        <th>Fecha de Revisión</th>
                        <th>Observaciones</th>
                        <th>Autorización</th>
                        <th>Firma</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí puedes agregar filas de ejemplo o dinámicamente con PHP -->
                </tbody>
            </table>
        </div>
                    </div>
                    </div>
                    </div>
                    </div>
       
    </div>
    </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#revisionTable').DataTable();
        });
    </script>
</body>
</html>
