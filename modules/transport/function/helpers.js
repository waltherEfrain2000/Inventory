// Función utilitaria para hacer peticiones fetch al controlador
export async function apiFetch(url, method = 'GET', data = null, customHeaders = {}) {
    const options = { method, headers: {} };

    // Verifica si los datos son FormData para no agregar Content-Type manualmente (en caso de enviar archivos)
    if (data instanceof FormData) {
        options.body = data;
    } else if (data) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(data);
    }

    // Agrega headers personalizados si vienen en caso de que sean necesarios
    if (customHeaders && typeof customHeaders === 'object') Object.assign(options.headers, customHeaders);

    try {
        const response = await fetch(url, options);
        const result = await response.json();
        if (!response.ok) throw new Error(`${result.message}`);
        return { success: true, data: result };
    } catch (error) {
        console.error('Error en apiFetch: ', error);
        return { success: false, message: error.message };
    }
};

 // Función para cargar la tabla
export function cargarTabla(tableID, data, columns) {
    $(tableID).DataTable().destroy();
    var params = {
        aaData: data,
        aoColumns: columns,
        ordering: true,
        pageLength: 25,
        // "scrollY": "600px",
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron resultados",
            sEmptyTable: "Ningún dato disponible en esta tabla",
            sInfo:
                "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            sInfoPostFix: "",
            sSearch: "Buscar:",
            sUrl: "",
            sInfoThousands: ",",
            sLoadingRecords: "Cargando...",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior",
            },
            oAria: {
                sSortAscending:
                    ": Activar para ordenar la columna de manera ascendente",
                sSortDescending:
                    ": Activar para ordenar la columna de manera descendente",
            },
    
        },
        columnDefs: [
            {
                //   targets: 1,
                //  visible: false,
            }
        ],
        order: [[0, 'desc']]
    };

    $(tableID).DataTable(params);
}

export function getParamFromUrl(nombreParam) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const param = urlParams.get(nombreParam);
  
    if (param === null) return null;
  
    const numericParam = Number(param);
    if (!isNaN(numericParam)) {
      return numericParam;
    }
  
    return param;
}

export function generarSelect(data, optionValue, optionText, idDOM, dataset= {}){
    let html = '<option value=""></option>';
    data.forEach(registro => {
        let dataAttributes = '';
        // Recorre las claves del objeto dataset
        if (Object.keys(dataset).length > 0) {
            for (let key in dataset) {
                if (dataset.hasOwnProperty(key)) {
                    dataAttributes += ` data-${key}="${registro[dataset[key]]}"`;
                }
            }
        }
        html += `<option value="${registro[optionValue]}"${dataAttributes}>${registro[optionText]}</option>`;
    });

    document.getElementById(idDOM).innerHTML = html;
}


export async function obtenerNotificaciones() {

    try {

        const result = await apiFetch('./modules/transport/controller/vehiculoController.php?action=getAlertaMantenimientos');

        if (result.success && !result.data.error) {
            let alertas = result.data;
            mostrarNotificaciones(alertas)
        } else {
            throw new Error("Ha ocurrido un error al obtener las alertas de mantenimiento");
        }
    } catch (error) {
        console.log(error);
    }
}

export async function mostrarNotificaciones(vehiculos) {
    for (const vehiculo of vehiculos) {
        const estilos = obtenerEstilosSegunUrgencia(vehiculo.nivel_urgencia);
        await Swal.fire({
            title: `Mantenimiento requerido ${estilos.urgencia}`,
            html: 
                `
                    <div class="swal-clickable-content" style="display: flex; align-items: center;">
                        ${estilos.iconColorHtml}
                        <div style="text-align: left;">
                            El vehículo <b>${vehiculo.placa} - ${vehiculo.infoVehiculo}</b> requiere mantenimiento.
                            <br>
                            <br>
                            Kilómetros ${vehiculo.km_restantes < 1 ? `excedidos: <b>${Intl.NumberFormat("en-US", {minimumFractionDigits: 0,}).format(vehiculo.km_restantes * -1)}` : `restantes: <b>${Intl.NumberFormat("en-US", {minimumFractionDigits: 0,}).format(vehiculo.km_restantes)}`}  km.</b>
                        </div>
                    </div>
                `,
            toast: true,
            position: "top-end",
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: false,
            showCloseButton: true,
            background: estilos.background,
            backdrop: false,
            width: "600px",
            customClass: estilos.customClass,
            didOpen: () => {
                const clickable = document.querySelector('.swal-clickable-content');
                if (clickable) {
                    clickable.style.cursor = 'pointer';
                    clickable.addEventListener('click', () => {
                        window.location.href = `?module=nuevoMantenimiento&vehiculo=${vehiculo.id_vehiculo}`;
                    });
                }
            }
        });
    }
}

export function obtenerEstilosSegunUrgencia(nivel) {
    switch(nivel) {
        case 'ALTA':
            return {
                iconColorHtml: '<i class="material-icons-two-tone text-danger" style="font-size: 2.5rem; margin-right: 15px;">directions_car</i>',
                color: '#fff',
                urgencia: '<span style="color: #d32f2f;margin-left: 5px;"">(Urgencia Alta)</span>',
                background: '#d32f2f', // rojo intenso
                customClass: {
                    popup: 'alerta-alta mt-4'
                }
            };
        case 'MEDIA':
            return {
                iconColorHtml: '<i class="material-icons-two-tone text-warning" style="font-size: 2.5rem; margin-right: 15px;">directions_car</i>',
                urgencia: '<span style="color: #f57c00;margin-left: 5px;"">(Urgencia Media)</span>',
                background: '#f57c00', // naranja
                customClass: {
                    popup: 'alerta-media mt-4'
                }
            };
        default:
            return {
                iconColorHtml: '<i class="material-icons-two-tone text-info" style="font-size: 2.5rem; margin-right: 15px;">directions_car</i>',
                urgencia: '<span style="color: #0288d1;margin-left: 5px;"">(Urgencia Baja)</span>',
                background: '#0288d1', // azul
                customClass: {
                    popup: 'alerta-baja mt-4'
                }
            };
    }
}