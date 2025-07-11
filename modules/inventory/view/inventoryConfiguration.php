<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<div class="pc-container">
    <div class="pc-content">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="bi bi-gear mr-2 text-primary"></i> Configuración de Inventarios
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center py-4">

                                    <i class="bi bi-box-seam mb-3" style="font-size: 2rem; color: #FF7B00;"></i>
                                    <h6 class="card-title font-weight-bold text-dark">Inventario</h6>
                                    <p class="card-text text-muted small mb-4">Ver artículos en existencia</p>

                                    <a href="?module=inventory" class="btn btn-sm text-white" style="background-color: #FF7B00;">
                                        Ir a Inventario
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-building icon-blue mb-3" style="font-size: 2rem;"></i>
                                    <h6 class="card-title font-weight-bold text-dark">Bodegas</h6>
                                    <p class="card-text text-muted small mb-4">Crea y edita bodegas para organizar tu inventario físico</p>
                                    <a href="?module=warehouses" class="btn btn-outline-primary btn-sm">
                                        Ir a Bodegas
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Categorías -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-tags icon-green mb-3" style="font-size: 2rem;"></i>
                                    <h6 class="card-title font-weight-bold text-dark">Categorías</h6>
                                    <p class="card-text text-muted small mb-4">Organiza tus productos en categorías y subcategorías</p>
                                    <a href="?module=category" class="btn btn-outline-success btn-sm">
                                        Ir a Categorías
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Gestión de Productos -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-box-seam icon-purple mb-3" style="font-size: 2rem;"></i>
                                    <h6 class="card-title font-weight-bold text-dark">Gestión de Productos</h6>
                                    <p class="card-text text-muted small mb-4">Administra todos los productos en tu inventario</p>
                                    <a href="?module=products" class="btn btn-outline-purple btn-sm">
                                        Ir a Productos
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Kardex -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-clock-history icon-orange mb-3" style="font-size: 2rem;"></i>
                                    <h6 class="card-title font-weight-bold text-dark">Kardex</h6>
                                    <p class="card-text text-muted small mb-4">Registro completo de entradas y salidas de inventario</p>
                                    <a href="?module=kardex" class="btn btn-outline-warning btn-sm">
                                        Ir a Kardex
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Entradas de Inventario -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-box-arrow-in-down icon-teal mb-3" style="font-size: 2rem;"></i>
                                    <h6 class="card-title font-weight-bold text-dark">Entradas de Inventario</h6>
                                    <p class="card-text text-muted small mb-4">Registra nuevas entradas de productos al inventario</p>
                                    <a href="?module=inventoryEntry" class="btn btn-outline-info btn-sm">
                                        Ir a Entradas
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Salidas de Inventario -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-box-arrow-up icon-red mb-3" style="font-size: 2rem;"></i>
                                    <h6 class="card-title font-weight-bold text-dark">Salidas de Inventario</h6>
                                    <p class="card-text text-muted small mb-4">Registra las salidas de productos del inventario</p>
                                    <a href="?module=inventoryOut" class="btn btn-outline-danger btn-sm">
                                        Ir a Salidas
                                    </a>
                                </div>
                            </div>
                        </div>
                <!-- Tomas de inventario -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-clipboard2-check mb-3" style="font-size: 2rem; color: #4B7BEC;"></i>
                                    <h6 class="card-title font-weight-bold text-dark">Tomas de Inventario</h6>
                                    <p class="card-text text-muted small mb-4">Realiza revisiones físicas y ajustes de stock</p>
                                    <a href="?module=inventoryCount" class="btn btn-sm  btn-outline-primary " >
                                        Ir a Tomas
                                    </a>
                                </div>
                            </div>
                        </div>

                                   <!-- Reportes de inventarios-->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-book mb-3" style="font-size: 2rem; color:rgb(97, 83, 5);"></i>
                                    <h6 class="card-title font-weight-bold text-dark">Reportes de Inventario</h6>
                                    <p class="card-text text-muted small mb-4">Reportes para el modulo de inventario</p>
                                    <a href="?module=inventoryReports" class="btn btn-sm  btn-outline-coffee" >
                                        Ir a Reportes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Efecto hover para las tarjetas */
    .hover-effect {
        transition: all 0.3s ease;
    }

    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* Colores para iconos */
    .icon-blue {
        color: #0d6efd;
    }

    .icon-green {
        color: #198754;
    }

    .icon-purple {
        color: #6f42c1;
    }

    .icon-orange {
        color: #fd7e14;
    }

    .icon-teal {
        color: #20c997;
    }

    .icon-red {
        color: #dc3545;
    }

    /* Botón morado personalizado */
    .btn-outline-purple {
        color: #6f42c1;
        border-color: #6f42c1;
    }

    .btn-outline-purple:hover {
        color: #fff;
        background-color: #6f42c1;
        border-color: #6f42c1;
    }

    .bg-custom-orange {
        background-color: #FFF3E0;
        border-left: 4px solid #FF7B00 !important;
    }

    .btn-custom-orange {
        background-color: #FF7B00;
        color: white;
    }

        .btn-outline-coffee {
        color:#583119;
        border-color:#583119;
    }

    .btn-outline-coffee:hover {
        color: #fff;
        background-color:#583119;
        border-color: #583119;
    }
</style>