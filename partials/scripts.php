<!-- Required Js -->


<script src="./dist/assets/js/plugins/popper.min.js"></script>
<script src="./dist/assets/js/plugins/simplebar.min.js"></script>
<script src="./dist/assets/js/plugins/bootstrap.min.js"></script>


<script src="./dist/assets/js/plugins/i18next.min.js"></script>
<script src="./dist/assets/js/plugins/i18nextHttpBackend.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="./dist/assets/js/icon/custom-font.js"></script>
<script src="./dist/assets/js/script.js"></script>
<script src="./dist/assets/js/theme.js"></script>
<script src="./dist/assets/js/multi-lang.js"></script>
<script src="./dist/assets/js/plugins/feather.min.js"></script>
<script src="./dist/assets/js/plugins/sweetalert2.all.min.js"></script>
<script src="./dist/assets/js/plugins/dataTables.min.js"></script>
<script src="./dist/assets/js/plugins/dataTables.bootstrap5.min.js"></script>

<!-- Input mask Js -->
<script src="./dist/assets/js/plugins/imask.min.js"></script>

<!-- Sweet Alert -->
<!-- <script src="./dist/assets/js/plugins/sweetalert2.all.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<script>
layout_change('light');
</script>
<script>
change_box_container('false');
</script>
<script>
layout_caption_change('true');
</script>
<script>
layout_rtl_change('false');
</script>
<script>
preset_change('preset-1');
</script>
<script>
main_layout_change('vertical');
</script>

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

<!-- FILE POND -->
<script src="https://cdn.jsdelivr.net/npm/pixie-editor@3.0.4/dist/index.min.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.js"></script>
<!-- select2 js -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Parámetros por defecto del select2 -->
<script>
$('.select2').select2({
    placeholder: "Seleccione..."
});
$(document).on('select2:open', e => {
    const select2 = $(e.target).data('select2');
    if (!select2.options.get('multiple')) {
        select2.dropdown.$search.get(0).focus();
    }
});
</script>

<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<?php
if (isset($_GET['module'])) {
    

if ($_GET['module'] == 'stat') {
    echo '<script src="./dist/assets/js/widgets/all-earnings-graph.js"></script>';
    echo '<script src="./dist/assets/js/widgets/page-views-graph.js"></script>';
    echo '<script src="./dist/assets/js/plugins/apexcharts.min.js"></script>';
    echo '<script src="./dist/assets/js/widgets/total-task-graph.js"></script>';
    echo '<script src="./dist/assets/js/widgets/download-graph.js"></script>';
    echo '<script src="./dist/assets/js/widgets/customer-rate-graph.js"></script>';
    echo '<script src="./dist/assets/js/widgets/tasks-graph.js"></script>';
    echo '<script src="./dist/assets/js/widgets/total-income-graph.js"></script> ';
    echo '<script src="./modules/dashboard/function/dashboard.js"></script>';
}
elseif ($_GET['module'] == 'addVehicle') {
    echo '<script src="./modules/transport/function/agregarVehiculo.js"></script>';
}
elseif ($_GET['module'] == 'editarVehiculo') {
    echo '<script type="module" src="./modules/transport/function/editarVehiculo.js"></script>';
}
elseif ($_GET['module'] == 'verVehiculo') {
    echo '<script type="module" src="./modules/transport/function/editarVehiculo.js"></script>';
}
elseif ($_GET['module'] == 'vehicles') {
    echo '<script type="module" src="./modules/transport/function/vehicles.js"></script>';
}
//====================== Clientes ====================== // hola mundo :)
if ($_GET['module'] == 'costomer' || $_GET['module'] == 'sales' || $_GET['module'] == 'sales2' || $_GET['module'] == 'historial' || $_GET['module'] == 'supplier' || $_GET['module'] == 'conductores') {
    echo '<script src="./modules/sales/function/general.js"></script>';
}
if ($_GET['module'] == 'sales') {
    echo '<script src="./dist/assets/js/plugins/wizard.min.js"></script>';
    echo '<script src="./dist/assets/js/plugins/uppy.min.js"></script>';
    echo '<script src="./modules/sales/function/sales.js"></script>';
}
if ($_GET['module'] == 'sales2' || $_GET['module'] == 'historial') {
    echo '<script src="./dist/assets/js/plugins/wizard.min.js"></script>';
    echo '<script src="./dist/assets/js/plugins/uppy.min.js"></script>';
    echo '<script src="./modules/sales/function/sales2.js"></script>';
}
if ($_GET['module'] == 'costomer') {
    echo '<script src="./modules/sales/function/costomer.js"></script>';
}
if ($_GET['module'] == 'conductores') {
    echo '<script src="./modules/sales/function/conductores.js"></script>';
}


elseif ($_GET['module'] == 'mantenimientos') {
    echo '<script type="module" src="./modules/transport/function/mantenimientos.js"></script>';
}
elseif ($_GET['module'] == 'nuevoMantenimiento') {
    echo '<script type="module" src="./modules/transport/function/nuevoMantenimiento.js"></script>';
}
elseif ($_GET['module'] == 'editarMantenimiento') {
    echo '<script type="module" src="./modules/transport/function/editarMantenimiento.js"></script>';
}
elseif ($_GET['module'] == 'verMantenimiento') {
    echo '<script type="module" src="./modules/transport/function/verMantenimiento.js"></script>';
}
elseif ($_GET['module'] == 'ordenesTrabajo') {
    echo '<script type="module" src="./modules/transport/function/ordenesTrabajo.js"></script>';
}
elseif ($_GET['module'] == 'inspecciones') {
    echo '<script type="module" src="./modules/transport/function/inspecciones.js"></script>';
}
elseif ($_GET['module'] == 'odometros') {
    echo '<script type="module" src="./modules/transport/function/odometros.js"></script>';
}
/* Settings */
elseif ($_GET['module'] == 'brand') {
    echo '<script src="./modules/settings/function/brand.js"></script>';
}
elseif ($_GET['module'] == 'modelo') {
    echo '<script src="./modules/settings/function/model.js"></script>';
}
elseif ($_GET['module'] == 'vehicle_type') {
    echo '<script src="./modules/settings/function/vehicle_type.js"></script>';
}
elseif ($_GET['module'] == 'ownership') {
    echo '<script src="./modules/settings/function/ownership.js"></script>';
}
elseif ($_GET['module'] == 'talleres') {
    echo '<script src="./modules/settings/function/talleres.js"></script>';
}
elseif ($_GET['module'] == 'catalogoMantenimiento') {
    echo '<script src="./modules/settings/function/catalogoMantenimiento.js"></script>';
}elseif ($_GET['module'] == 'comprasAcopio') {
    echo '<script src="./modules/comprasAcopio/js/comprasAcopio.js"></script>';
}elseif ($_GET['module'] == 'productores') {
    echo '<script src="./modules/comprasAcopio/js/comprasProductores.js"></script>';
}elseif ($_GET['module'] == 'transportes') {
    echo '<script src="./modules/comprasAcopio/js/comprasTransportes.js"></script>';
}

/* Finance */
elseif ($_GET['module'] == 'accounting') {
    echo '<script src="./modules/finance/function/account.js"></script>';
}
elseif ($_GET['module'] == 'ar_details') {
    echo '<script src="./modules/finance/function/ar_details.js"></script>';
}
elseif ($_GET['module'] == 'accounts_receivable') {
    echo '<script src="./modules/finance/function/account_receivable.js"></script>';
}
elseif ($_GET['module'] == 'ap_details') {
    echo '<script src="./modules/finance/function/ap_details.js"></script>';
}
elseif ($_GET['module'] == 'accounts_payable') {
    echo '<script src="./modules/finance/function/account_payable.js"></script>';
}
elseif ($_GET['module'] == 'ap_supplier') {
    echo '<script src="./modules/finance/function/ap_supplier.js"></script>';
}
elseif ($_GET['module'] == 'supplier') {
    echo '<script src="./modules/finance/function/supplier.js"></script>';
}
elseif ($_GET['module'] == 'ap_details_supplier') {
    echo '<script src="./modules/finance/function/ap_details_supplier.js"></script>';
}



/* Inventory */

elseif ($_GET['module'] == 'category') {
    echo '<script src="./modules/inventory/function/categories/category.js"></script>';
}
elseif ($_GET['module'] == 'warehouses') {
    echo '<script src="./modules/inventory/function/warehouses/warehouse.js"></script>';
}
elseif ($_GET['module'] == 'products') {
    echo '<script src="./modules/inventory/function/products/products.js"></script>';
}
elseif ($_GET['module'] == 'inventoryEntry') {
    echo '<script src="./modules/inventory/function/inventoryEntry/inventoryEntry.js"></script>';
}
elseif ($_GET['module'] == 'inventoryEntryDetail') {
    echo '<script src="./modules/inventory/function/inventoryEntry/inventoryEntryDetail.js"></script>';
}
elseif ($_GET['module'] == 'providers') {
    echo '<script src="./modules/inventory/function/providers/provider.js"></script>';
}
elseif ($_GET['module'] == 'inventoryOutFlowDetail') {
    echo '<script src="./modules/inventory/function/inventoryOut/inventoryOut.js"></script>';
}
elseif ($_GET['module'] == 'inventoryOut') {
    echo '<script src="./modules/inventory/function/inventoryOut/inventoryOutHeader.js"></script>';
}
elseif ($_GET['module'] == 'inventory') {
    echo '<script src="./modules/inventory/function/inventory/inventory.js"></script>';
}
elseif ($_GET['module'] == 'inventoryCount') {
    echo '<script src="./modules/inventory/function/inventory/inventoryCount.js"></script>';
}
elseif ($_GET['module'] == 'inventoryReports') {
    echo '<script src="./modules/inventory/function/inventory/inventoryReports.js"></script>';
}
elseif ($_GET['module'] == 'kardex') {
    echo '<script src="./modules/inventory/function/kardex/kardex.js"></script>';
}
}
?>