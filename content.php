<?php

/**
 * Descripction : Direcciones para el control de las rutas del sistema
 * Autor        : Douglas
 * Fecha        : 2025-01-02
 */

$eniguey = 1;
if ($eniguey != 1) {
    echo "No tienes permisos para acceder a este archivo";
} else {
    if (empty($_GET['module']) || $_GET['module'] == 'inicio') {
        include "./modules/dash.php";
    } else {
        
        /* Dashboard */
        $_GET['module'] == 'stat' ? include "./modules/dash.php" : false;
        
        /* Compras */
        $_GET['module'] == 'comprasAcopio' ? include "./modules/comprasAcopio/Views/acopioPrincipal.php" : false;
        $_GET['module'] == 'productores' ? include "./modules/comprasAcopio/Views/acopioProductores.php" : false;
        $_GET['module'] == 'transportes' ? include "./modules/comprasAcopio/Views/acopioTransportes.php" : false;

        /* Ventas */
        $_GET['module'] == 'costomer' ? include "./modules/sales/view/costomer.php" : false;
        $_GET['module'] == 'conductores' ? include "./modules/sales/view/conductores.php" : false;
        $_GET['module'] == 'sales' ? include "./modules/sales/view/sales.php" : false;
        $_GET['module'] == 'sales2' ? include "./modules/sales/view/sales2.php" : false;
        $_GET['module'] == 'historial' ? include "./modules/sales/view/historialventas.php" : false;

        /* Transporte */
        $_GET['module'] == 'vehicles' ? include "./modules/transport/view/vehicles.php" : false;
        $_GET['module'] == 'addVehicle' ? include "./modules/transport/view/addVehicle.php" : false;
        $_GET['module'] == 'editarVehiculo' ? include "./modules/transport/view/editarVehiculo.php" : false;
        $_GET['module'] == 'verVehiculo' ? include "./modules/transport/view/editarVehiculo.php" : false;
        $_GET['module'] == 'mantenimientos' ? include "./modules/transport/view/mantenimientos.php" : false;
        $_GET['module'] == 'nuevoMantenimiento' ? include "./modules/transport/view/nuevoMantenimiento.php" : false;
        $_GET['module'] == 'editarMantenimiento' ? include "./modules/transport/view/editarMantenimiento.php" : false;
        $_GET['module'] == 'verMantenimiento' ? include "./modules/transport/view/verMantenimiento.php" : false;
        $_GET['module'] == 'ordenesTrabajo' ? include "./modules/transport/view/ordenesTrabajo.php" : false;
        $_GET['module'] == 'inspecciones' ? include "./modules/transport/view/inspecciones.php" : false;
        $_GET['module'] == 'odometros' ? include "./modules/transport/view/odometros.php" : false;

        /* Planillas */
        $_GET['module'] == 'employees' ? include "./modules/payroll/view/employees.php" : false;
        $_GET['module'] == 'payroll' ? include "./modules/payroll/view/payroll.php" : false;

        /* Finanzas */
        $_GET['module'] == 'accounts_payable' ? include "./modules/finance/view/accounts_payable.php" : false;
        $_GET['module'] == 'accounts_receivable' ? include "./modules/finance/view/accounts_receivable.php" : false;
        $_GET['module'] == 'ar_details' ? include "./modules/finance/view/ar_details.php" : false;
        $_GET['module'] == 'accounting' ? include "./modules/finance/view/account.php" : false;
        $_GET['module'] == 'financial_reports' ? include "./modules/finance/view/financial_reports.php" : false;
        $_GET['module'] == 'ap_details' ? include "./modules/finance/view/ap_details.php" : false;
        $_GET['module'] == 'ap_details_supplier' ? include "./modules/finance/view/ap_details_supplier.php" : false;
        $_GET['module'] == 'ap_supplier' ? include "./modules/finance/view/ap_supplier.php" : false;
        $_GET['module'] == 'supplier' ? include "./modules/finance/view/supplier.php" : false;

        /* Trazabilidad */
        $_GET['module'] == 'georeference' ? include "./modules/tracing/view/georeference.php" : false;
        $_GET['module'] == 'traceable_process' ? include "./modules/tracing/view/traceable_process.php" : false;

        /* Activos */
        $_GET['module'] == 'properties' ? include "./modules/assets/view/properties.php" : false;
        $_GET['module'] == 'other_assets' ? include "./modules/assets/view/other_assets.php" : false;

        /* Reportes */
        $_GET['module'] == 'reports' ? include "./modules/reports/view/reports.php" : false;

        /*Inventory*/
        $_GET['module'] == 'category' ? include "./modules/inventory/view/category.php" : false;
        $_GET['module'] == 'products' ? include "./modules/inventory/view/productsManagement.php" : false;
        $_GET['module'] == 'kardex' ? include "./modules/inventory/view/kardex.php" : false;
        $_GET['module'] == 'inventoryEntry' ? include "./modules/inventory/view/inventoryEntry.php" : false;
        $_GET['module'] == 'inventoryEntryDetail' ? include "./modules/inventory/view/inventoryEntryDetail.php" : false;
        $_GET['module'] == 'InventoryReview' ? include "./modules/inventory/view/revisionInventario.php" : false;
        $_GET['module'] == 'InventoryConf' ? include "./modules/inventory/view/inventoryConfiguration.php" : false;
        $_GET['module'] == 'warehouses' ? include "./modules/inventory/view/warehouses.php" : false;
        $_GET['module'] == 'providers' ? include "./modules/inventory/view/providers.php" : false;
        $_GET['module'] == 'inventoryOut' ? include "./modules/inventory/view/inventoryOutFlow.php" : false;
        $_GET['module'] == 'inventoryOutFlowDetail' ? include "./modules/inventory/view/inventoryOutFlowDetail.php" : false;
        $_GET['module'] == 'inventory' ? include "./modules/inventory/view/inventory.php" : false;
        $_GET['module'] == 'inventoryCount' ? include "./modules/inventory/view/inventoryCount.php" : false;
        $_GET['module'] == 'inventoryReports' ? include "./modules/inventory/view/inventoryReports.php" : false;
        /* Parametrización */
        $_GET['module'] == 'settings' ? include "./modules/settings/view/settings.php" : false;
        $_GET['module'] == 'brand' ? include "./modules/settings/view/brand.php" : false;
        $_GET['module'] == 'modelo' ? include "./modules/settings/view/model.php" : false;
        $_GET['module'] == 'vehicle_type' ? include "./modules/settings/view/vehicle_type.php" : false;
        $_GET['module'] == 'ownership' ? include "./modules/settings/view/ownership.php" : false;
        $_GET['module'] == 'talleres' ? include "./modules/settings/view/talleres.php" : false;
        $_GET['module'] == 'catalogoMantenimiento' ? include "./modules/settings/view/catalogoMantenimiento.php" : false;
    }
}
?>
