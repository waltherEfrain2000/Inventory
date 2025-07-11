import { apiFetch, getParamFromUrl, cargarTabla } from "./helpers.js";

let idMantenimiento = 0;
let encabezado=[];
let detalle=[];
let insumos = [];

$(function () {
    $("#navTransporte").addClass("pc-trigger");
    $("#navTransporte ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navMantenimientos").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    idMantenimiento = getParamFromUrl('id');
    
    obtenerMantenimiento(idMantenimiento);

    function renderizarMantenimiento(data) {
        encabezado = data.encabezado;
        detalle = data.detalle;
        insumos = data.insumos;

        $("#vehiculo").val(encabezado.vehiculo);
        $("#imgPlaca").html(encabezado.placa);
        const textcolor = (()=> { switch(encabezado.estado_vehiculo){ case 1: return 'text-success'; case 3: return 'text-warning'; default: return 'text-danger';}})();
        $("#estado").prop("class",`form-control ${textcolor}`);
        $("#estado").val(encabezado.nombre_estado_vehiculo);
        $("#tipoMantenimiento").val(encabezado.tipo_mantenimiento);
        $("#tipoServicio").val(encabezado.tipo_servicio);
        $("#txtKilometrajeActual").val(encabezado.kilometraje_vehiculo);
        $("#comentarios").val(encabezado.comentarios);
        $("#fechaInicio").val(encabezado.fecha_inicio_sin_formato);

        cargarTrabajos();
        if(encabezado.id_tipo_servicio == 2){
            $(".taller").prop("hidden", false);
            $("#taller").val(encabezado.taller);
        } else {
            $("#bodega").val(encabezado.bodega);
            $(".insumosOT").prop("hidden", false);
            cargarInsumos();
        }
    }

    function cargarTrabajos() {
        var columns = [
            {
                className: "text-center",
                render: function (data, type, full, meta) {
                    return meta.row + 1;
                },
            },
            {
                className: "text-center",
                mDataProp: "nombre_catalogo",
            },
            {
                className: "text-center",
                mDataProp: "costo",
                render: function (data, types, full, meta){
                    return `L. ${Intl.NumberFormat("en-US", {minimumFractionDigits: 2,}).format(full.costo)}`;
                }
            },
            
        ];

        // Llamado a la función para crear la tabla con los datos
        cargarTabla("#tablaTrabajos", detalle, columns);

        const total = detalle.reduce(
            (accumulator, currentValue) => accumulator + currentValue.costo,
            0
        );
        
        $("#totales").html(
            "L. " +
            Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(total)
        );

        detalle.length > 0 ? $(".totalesFooter").prop("hidden", false) : $(".totalesFooter").prop("hidden", true);
        
    }

    function cargarInsumos() {
        var columns = [
            {
                className: "text-center",
                render: function (data, type, full, meta) {
                    return meta.row + 1;
                },
            },
            {
                className: "text-center",
                mDataProp: "articulo",
            },
            {
                className: "text-center",
                mDataProp: "unidad",
            },
            {
                className: "text-center",
                mDataProp: "cantidad",
            },
        ];

        // Llamado a la función para crear la tabla con los datos
        cargarTabla("#tablaInsumos", insumos, columns);
    }

    async function obtenerMantenimiento(idMantenimiento){
        try {

            const result = await apiFetch(`./modules/transport/controller/mantenimientoController.php?action=getMantenimientoPorId&id=${idMantenimiento}`);

            if (result.success && !result.data.error) {
                let mantenimiento = result.data;
                
                renderizarMantenimiento(mantenimiento)
            } else {
                throw new Error("Ha ocurrido un error al obtener los mantenimientos");
            }
        } catch (error) {
            console.log(error);
        }
    }
    
});