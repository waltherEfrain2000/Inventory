import { apiFetch, cargarTabla } from './helpers.js';

let odometros = [];
$(function () {
    $("#navTransporte").addClass("pc-trigger");
    $("#navTransporte ul").prop("style", "display: block; box-sizing: border-box;");
    $("#navOdometros").prop("style", "background-color: rgb(225 224 223); border-radius: 8px;");

    obtenerOdometros();
    

    function listarOdometros() {

        var columns = [
            {
                className: "text-center",
                mDataProp: "id",
                width: '5%',
            },
            {
                className: "text-center tdPlaca",
                mDataProp: "placa",  
                width: '10%',
                render: function (data, types, full, meta){

                    // return `${full.placa.substr(0, 3) + "&nbsp;" + full.placa.substr(3, 4)}`;
                    return `${full.placa}`;
                }                      
            },
            {
                className: "text-center",
                mDataProp: "infoVehiculo",
            },
            {
                className: "text-center",
                mDataProp: "tipo_registro",
            },
            {
                className: "text-center",
                mDataProp: "kilometraje",
                render: function (data, types, full, meta){

                    // return `${full.placa.substr(0, 3) + "&nbsp;" + full.placa.substr(3, 4)}`;
                    return `${Intl.NumberFormat("en-US", {minimumFractionDigits: 0,}).format(full.kilometraje)} km`;
                } 
            },
            {
                className: "text-center",
                mDataProp: "fecha_registro",
            },
            
            
        ];

        // Llamado a la función para crear la tabla con los datos
        cargarTabla("#tabla", odometros, columns);
    }

    
    async function obtenerOdometros(){
        try {

            const result = await apiFetch('./modules/transport/controller/vehiculoController.php?action=getOdometros');

            if (result.success && !result.data.error) {
                odometros = result.data;
                listarOdometros();
            } else {
                throw new Error("Ha ocurrido un error al obtener los odómetros");
            }
        } catch (error) {
            console.log(error);
        }
    }

});