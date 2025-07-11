<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="?module=inicio" class="b-brand text-primary">
                <img src="./dist/assets/images/logo.png" class="img-fluid logo-lg" alt="logo" />
            </a>
        </div>

        <style>
        .m-header {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            height: 100px !important;
            /* Ajusta la altura según sea necesario */
        }

        .b-brand {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }

        .logo-lg {
            max-width: 100% !important;
            /* Asegura que no sobrepase el contenedor */
            height: auto !important;
        }
        </style>

        <div class="navbar-content">
            <ul class="pc-navbar">

                <li class="pc-item">
                    <a href="?module=stat" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">insert_chart</i>
                        </span>
                        <span class="pc-mtext">Estadísticas</span>
                    </a>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">shopping_bag</i>
                        </span>
                        <span class="pc-mtext">Acopio</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="?module=comprasAcopio"
                                data-i18n="Ingreso de Fruta">Ingreso de Fruta</a></li>
                    </ul>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">shopping_cart</i>
                        </span>
                        <span class="pc-mtext">Control de Ventas</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="?module=historial" data-i18n="Ventas">Ventas</a>
                        </li>
                        <li class="pc-item"><a class="pc-link" href="?module=costomer" data-i18n="Clientes">Clientes</a>
                        </li>
                        <li class="pc-item"><a class="pc-link" href="?module=conductores" data-i18n="Conductores">Conductores</a>
                        </li>
                    </ul>
                </li>

                <li class="pc-item pc-hasmenu" id="navTransporte">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">directions_car</i>
                        </span>
                        <span class="pc-mtext">Control de Transporte</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="?module=vehicles" data-i18n="Vehículos"
                                id="navVehiculos">Vehículos</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=mantenimientos" data-i18n="Mantenimientos"
                                id="navMantenimientos">Mantenimientos</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=ordenesTrabajo"
                                data-i18n="Órdenes de Trabajo" id="navOrdenesTrabajo">Órdenes de Trabajo</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=inspecciones"
                                data-i18n="Inspecciones" id="navInspecciones">Inspecciones</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=odometros"
                                data-i18n="Odómetros" id="navOdometros">Odómetros</a></li>
                    </ul>
                </li>

                <li class="pc-item pc-hasmenu" hidden>
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">contacts</i>
                        </span>
                        <span class="pc-mtext">Control de Planillas</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="?module=employees"
                                data-i18n="Empleados">Empleados</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=payroll"
                                data-i18n="Planillas">Planillas</a></li>
                    </ul>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">monetization_on</i>
                        </span>
                        <span class="pc-mtext">Finanzas</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="?module=accounting"
                                data-i18n="Contabilidad">Contabilidad</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=accounts_receivable"
                                data-i18n="Cuentas por Cobrar">Cuentas por Cobrar</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=accounts_payable"
                                data-i18n="Cuentas por Pagar">Cuentas por Pagar</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=supplier"
                                data-i18n="Proveedores">Proveedores</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=financial_reports"
                                data-i18n="Reportes Financieros">Reportes Financieros</a></li>
                    </ul>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">insights</i>
                        </span>
                        <span class="pc-mtext">Trazabilidad</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="?module=georeference"
                                data-i18n="Georeferenciación">Georeferenciación</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=traceable_process"
                                data-i18n="Proceso Trazable">Proceso Trazable</a></li>
                    </ul>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">house</i>
                        </span>
                        <span class="pc-mtext">Control de Activos</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="?module=properties"
                                data-i18n="Propiedades">Propiedades</a></li>
                        <li class="pc-item"><a class="pc-link" href="?module=other_assets"
                                data-i18n="Otros activos">Otros activos</a></li>
                    </ul>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">store</i>
                        </span>
                        <span class="pc-mtext">Inventario</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <!-- <li class="pc-item"><a class="pc-link" href="?module=category" >Categorias</a></li>
            <li class="pc-item"><a class="pc-link" href="?module=che" > Gestión de Productos</a></li>
            <li class="pc-item"><a class="pc-link" href="?module=kardex" >Kardex</a></li>
            <li class="pc-item"><a class="pc-link" href="?module=inventoryEntry" >Entrada de inventario</a></li>
            <li class="pc-item"><a class="pc-link" href="?module=InventoryOut" >Salida de inventario</a></li>
            <li class="pc-item"><a class="pc-link" href="?module=InventoryReview" >Revisión ciclíca inventario</a></li>
            <li class="pc-item"><a class="pc-link" href="?module=asignacionContable" >Asignación Contable</a></li> -->
                        <li class="pc-item"><a class="pc-link" href="?module=InventoryConf">Configuración Inventario</a>
                        </li>
                    </ul>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">fact_check</i>
                        </span>
                        <span class="pc-mtext">Gestor de Reportes</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="?module=reports" data-i18n="Reportes">Reportes</a>
                        </li>
                    </ul>
                </li>

                <li class="pc-item pc-hasmenu" id="navParametrizacion">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon">
                            <i class="material-icons-two-tone">category</i>
                        </span>
                        <span class="pc-mtext">Parametrización</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="?module=settings">Configuraciones</a></li>
                        <li class="pc-item pc-hasmenu pc-trigger" id="navTransporteParametrizacion">
                            <a href="#!" class="pc-link">
                                <span data-i18n="Transporte">Transporte</span>
                                <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-chevron-right">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </span>
                            </a>
                            <ul class="pc-submenu" style="display: block; box-sizing: border-box;">
                                <li class="pc-item"><a class="pc-link" href="?module=brand" id="navMarca">Marcas</a>
                                </li>
                                <li class="pc-item"><a class="pc-link" href="?module=modelo" id="navModelo">Modelos</a>
                                </li>
                                <li class="pc-item"><a class="pc-link" href="?module=vehicle_type"
                                        id="navTipoVehiculo">Tipos Vehiculo</a></li>
                                <li class="pc-item"><a class="pc-link" href="?module=ownership"
                                        id="navPertenencia">Pertenencias</a></li>
                                <li class="pc-item"><a class="pc-link" href="?module=talleres"
                                        id="navTalleres">Talleres</a></li>
                                <li class="pc-item"><a class="pc-link" href="?module=catalogoMantenimiento"
                                        id="navCatalogoMantenimiento">Catálogo de Mantenimiento</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>