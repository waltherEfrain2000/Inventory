<div class="pc-container">
    <div class="pc-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Gestión de categorías y subcategorías</h5>
                </div>
                <div class="card-body">
           
                    <ul class="nav nav-tabs" id="tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="categoria-tab" data-bs-toggle="tab" href="#categorias" role="tab" aria-controls="categorias" aria-selected="true">Categorías</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="subcategoria-tab" data-bs-toggle="tab" href="#subcategorias" role="tab" aria-controls="subcategorias" aria-selected="false">Subcategorías</a>
                        </li>
                    </ul>

              
                    <div class="tab-content" id="myTabContent">
                   
                        <div class="tab-pane fade show active" id="categorias" role="tabpanel" aria-labelledby="categoria-tab">
                            <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4" >
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategoria">Agregar Categoría</button>
                            </div>
                            <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                    <table id="table-style-hover" class="table table-striped table-hover table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>Nombre categoría</th>
                                                <th>Descripción</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Nombre categoría</th>
                                                <th>Descripción</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Subcategoría -->
                        <div class="tab-pane fade" id="subcategorias" role="tabpanel" aria-labelledby="subcategoria-tab">
                            <div class="col-md-12 form-group d-flex align-items-end justify-content-end  pt-4">
                                <button class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#modalSubcategoria">Agregar Subcategoría</button>
                            </div>
                            <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                    <table id="table-subcategoria" class="table table-striped table-hover table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>Categoría</th>
                                                <th>Nombre subcategoría</th>
                                                <th>Descripción</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                 <th>Categoría</th>
                                                <th>Nombre subcategoría</th>
                                                <th>Descripción</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar categoría -->
    <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCategoriaLabel">Administración de Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCategoria">
                        <input type="hidden" id="category_id" name="id">
                        <div class="mb-3">
                            <label for="categoriaNombre" class="form-label">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="categoriaNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoriaDescripcion" class="form-label">Descripción de la Categoría</label>
                            <input type="text" class="form-control" id="categoriaDescripcion" required>
                        </div>      
                        <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4" >
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar subcategoría -->
    <div class="modal fade" id="modalSubcategoria" tabindex="-1" aria-labelledby="modalSubcategoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubcategoriaLabel">Administración de subcategoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formSubcategoria">
                        <input type="hidden" id="subcategory_id" name="id">
                        <div class="mb-3">
                            <label for="subcategoriaNombre" class="form-label">Categoria a la que pertenece</label>
                            <select class="form-select" id="subcategoriaIdCategoria" required>
                                <option value="">Seleccione una categoría</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subcategoriaNombre" class="form-label">Nombre de la Subcategoría</label>
                            <input type="text" class="form-control" id="subcategoriaNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="subcategoriaDescripcion" class="form-label">Descripción de la Subcategoría</label>
                            <input type="text" class="form-control" id="subcategoriaDescripcion" required>
                        </div>
                        <div class="col-md-12 form-group d-flex align-items-end justify-content-end pt-4" >
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
