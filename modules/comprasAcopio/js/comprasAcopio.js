let editar = false;
$(document).ready(function () {

  listarDescargas();  
  listarProductor();
  $(document).ready(function () {
    $('#exampleModalCenter').on('hidden.bs.modal', function () {
      console.log('Modal cerrado, reiniciando formulario...');
      $(this).find('form')[0].reset();
    });
  });
  
  
    $('#productorSelect').change(function () {
        // Obtener el valor del select actual usando this
        const productorSelect = $(this).val();
        // Mostrar valor en la consola
        console.log('Valor del select:', productorSelect);
        listarTipoTransporte(productorSelect);
    });
    $('#trasnporteSelect').change(function () {
      // Obtener el valor del select actual usando this
      const trasnporteSelect = $(this).val();
      // Mostrar valor en la consola
      console.log('Valor del select placa:', trasnporteSelect);
  });

  $("#pesoBruto, #pesoTara").on("input", function () {
    let pesoBruto = parseFloat($("#pesoBruto").val()) || 0;
    let pesoTara = parseFloat($("#pesoTara").val()) || 0;
    let pesoNeto = pesoBruto - pesoTara;
    // Si el resultado es negativo, mostrar "Revisar", de lo contrario mostrar el valor
    $("#pesoNeto").val(pesoNeto < 0 ? "Ups, revisa bien tus pesos" : pesoNeto);
});

$("#pesoNeto, #precioCompra").on("input", function () {
    let pesoNeto = parseFloat($("#pesoNeto").val()) || 0;
    let precioCompra = parseFloat($("#precioCompra").val()) || 0;
    let totalPagar = pesoNeto * precioCompra;
    // Si el resultado es negativo, mostrar "Revisar", de lo contrario mostrar el valor
    $("#totalPagar").val(totalPagar < 0 ? "Ups, revisa bien tus numeros" : totalPagar);
});


$("#nuevaCarga").on("click",function(){
  $("#exampleModalCenter").modal("hide"); // Oculta el modal
  $("div.mb-3.col-md-6:has(#pesoTara)").attr("hidden", true); // Oculta pesoTara
  $("div.mb-3.col-md-6:has(#pesoNeto)").attr("hidden", true); // Oculta pesoNeto
  $("div.mb-3.col-md-6:has(#precioCompra)").attr("hidden", true); // Oculta precioCompra
  $("div.mb-3.col-md-12:has(#totalPagar)").attr("hidden", true); // Oculta totalPagar
  $("#cerrarModal").attr("hidden", true); // Oculta cerrarModal
  $("#completarDescarga").attr("hidden", true); // Oculta completarDescarga
  $("#enviarDescarga").removeAttr("hidden"); // Muestra enviarDescarga
  $("#productorSelect").removeAttr("disabled");
  $("#trasnporteSelect").removeAttr("disabled");
  $("div.mb-3.col-md-6:has(#pesoBruto) input").removeAttr("disabled");
});
$("#enviarDescarga").on("click", function () {
  // Obtén los valores de los inputs
  let id_transporte = $("#trasnporteSelect").val();
  let peso_bruto = parseFloat($("#pesoBruto").val()) || 0;

  // Validaciones
  if (!id_transporte || id_transporte === "") {
      swal.fire({
          title: "Error",
          text: "Por favor selecciona un transporte.",
          icon: "error",
          confirmButtonText: "Aceptar",
      });
      return; // Detenemos la ejecución
  }

  if (peso_bruto <= 0) {
      swal.fire({
          title: "Error",
          text: "El peso bruto debe ser mayor a 0.",
          icon: "error",
          confirmButtonText: "Aceptar",
      });
      return; // Detenemos la ejecución
  }

  // Los datos están correctos, continúa con el envío
  let LosDatos = {
      id_transporte,
      peso_bruto,
  };

  console.log(LosDatos);

  // Cierra el modal actual
  $("#exampleModalCenter").modal("hide");

  // Muestra una pantalla de carga
  guardarNuevoIngreso(LosDatos);
  swal.fire({
      title: "Cargando...", text: "Por favor espera.",icon: "info",showConfirmButton: false,allowOutsideClick: false,timer: 3000, // Pantalla de carga por 1 segundo
      willOpen: () => {
          swal.showLoading();
      },
  });
});
  // Completar descarga y pagar y tal vez abonar a prestamo
  $("#completarDescarga").on("click", function () {
    // Obtener los valores de los inputs
    let peso_tara = $("#pesoTara").val();
    let monto = $("#precioCompra").val();
    let id_pesaje = $("#Na").val();

    // Validaciones de los campos
    if (!id_pesaje) {
        alert("El ID del pesaje es obligatorio.");
        return;
    }

    if (!peso_tara || isNaN(peso_tara) || parseFloat(peso_tara) <= 0) {
        alert("El peso tara debe ser un número válido mayor a 0.");
        return;
    }

    if (!monto || isNaN(monto) || parseFloat(monto) <= 0) {
        alert("El monto debe ser un número válido mayor a 0.");
        return;
    }

    // Crear el objeto con los datos
    let LosDatos = {
        id_pesaje,
        peso_tara,
        monto,
    };

    // Mostrar los datos en la consola
    console.log(LosDatos);

    // Llamar a la función para completar el nuevo ingreso
    $("#exampleModalCenter").modal("hide");
    completarNuevoIngreso(LosDatos);
    
});


  $("#complex-header").on("click", "button", function () {
    // Obtener el id que trae el botón
    let id = $(this).attr("data-id");
    // Obtener la acción a realizar
    let accion = $(this).attr("name");

    // Dependiendo del botón al que se le hace click
    // se realizará una acción transmitida por el atributo name
    if (accion == "cancelarDescarga") {
      // Llamamos a la alerta para eliminar
      eliminarPesaje(id);
    }
    if (accion == "completarDescarga") {
      // Llamamos a la alerta para editar
      console.log(id);
      completarDescarga(id);
    }
    if(accion == "imprimir"){
      mostrarFicha(datosEjemplo);
    }
  });
});

/*INICIO DE FUNCIONES*/

function listarProductor() {
  // POST  // GET   POST -Envia Recibe   | GET RECEPCIÓ
  $.ajax({
    type: "GET",
    url: "./modules/comprasAcopio/controllers/listarProductor.php",
    data: {},
    // Error en la petición
    error: function (error) {
      console.log(error);
    },
    // Petición exitosa
    success: function (datos) {
      console.log(datos);
      datos.data.forEach((e) => {
        $("#productorSelect").append(
          `<option value="${e.idProductor}">${e.nombre}</option>`
        );
      });
      
    },
  });
}
function listarTipoTransporte(id) {
    // POST  // GET   POST -Envia Recibe   | GET RECEPCIÓ
    $.ajax({
      type: "POST",
      url: "./modules/comprasAcopio/controllers/listarTipoTransporte.php",
      data: {
        id : id,
      },
      // Error en la petición
      error: function (error) {
        console.log(error);
      },
      // Petición exitosa
      success: function (datos) {

          // Limpiar el contenido del select
          $("#trasnporteSelect").empty();

          // Agregar opción inicial (opcional)
          $("#trasnporteSelect").append(`<option value="-1">Seleccione un transporte</option>`);

        datos.data.forEach((e) => {
          $("#trasnporteSelect").append(
            `<option value="${e.id}">${e.descripcion} - ${e.identificador}</option>`
          );
        });
        
      },
    });
}
function guardarNuevoIngreso(losDatos) {
    $.ajax({
        type: "POST",
        url: "./modules/comprasAcopio/controllers/agregarDescarga.php",
        data: {
            losDatos: JSON.stringify(losDatos), // Convertir a JSON antes de enviar
        },
        error: function (error) {
            console.error("Error en la solicitud AJAX:", error);
        },
        success: function (respuesta) {
            console.log("Respuesta del servidor:", respuesta);
            try {
                const resp = typeof respuesta === "string" ? JSON.parse(respuesta) : respuesta;

                if (resp.success) {
                  Swal.fire({
                    title: "Excelente",
                    text: "Enviado a descarga",
                    icon: "success"
                  }).then(() => {
                    window.location.href = "?module=comprasAcopio"; // Redirigir a la página indicada
                  });
                
                } else {
                    swal.fire("Ups", resp.data || "Error desconocido", "warning");
                }
            } catch (e) {
                console.error("Error al procesar la respuesta JSON:", e.message);
                swal.fire("Error", "Respuesta no válida del servidor", "error");
            }
        },
    });
}
function completarNuevoIngreso(losDatos) {
  $.ajax({
      type: "POST",
      url: "./modules/comprasAcopio/controllers/completarDescarga.php",
      data: {
          losDatos: JSON.stringify(losDatos), // Convertir a JSON antes de enviar
      },
      error: function (xhr, status, error) {
        console.error("Error en la solicitud AJAX:");
        console.log("Estado readyState:", xhr.readyState);
        console.log("Código de estado:", xhr.status);
        console.log("Texto del error:", error);
        console.log("Respuesta del servidor:", xhr.responseText);
    },
      success: function (respuesta) {
          console.log("Respuesta del servidor:", respuesta);
          try {
              const resp = typeof respuesta === "string" ? JSON.parse(respuesta) : respuesta;

              if (resp.success) {
                Swal.fire({
                  title: "Excelente",
                  text: "Proceso completo",
                  icon: "success"
                }).then(() => {
                  window.location.href = "?module=comprasAcopio"; // Redirigir a la página indicada
                });
              
              } else {
                  swal.fire("Ups", resp.data || "Error desconocido", "warning");
              }
          } catch (e) {
              console.error("Error al procesar la respuesta JSON:", e.message);
              swal.fire("Error", "Respuesta no válida del servidor", "error");
          }
      },
  });
}
function completarDescarga(id) {
  // Muestra el modal
  $("#exampleModalCenter").modal("show");
  $("#productorSelect").attr("disabled", true);
  $("#trasnporteSelect").attr("disabled", true);
  $("div.mb-3.col-md-6:has(#pesoBruto) input").attr("disabled", true);
  $("div.mb-3.col-md-6:has(#pesoTara)").removeAttr("hidden");
  $("div.mb-3.col-md-6:has(#pesoNeto)").removeAttr("hidden");
  $("div.mb-3.col-md-6:has(#precioCompra)").removeAttr("hidden");
  $("div.mb-3.col-md-12:has(#totalPagar)").removeAttr("hidden");
  $("#cerrarModal").removeAttr("hidden");
  $("#completarDescarga").removeAttr("hidden");
  $("#enviarDescarga").attr("hidden", true);
  cargarDatosEditar(id);
}
function listarDescargas() {
  // Llamada AJAX para obtener los datos desde el backend
  $.ajax({
    type: "GET",
    url: "./modules/comprasAcopio/controllers/listarDescargas.php",
    data: {},
    // Error en la petición
    error: function (error) {
      console.error("Error al obtener las descargas:", error);
    },
    // Petición exitosa
    success: function (respuesta) {
      console.log(respuesta);

      // Verifica si la respuesta es válida y tiene éxito
      if (respuesta.success) {
        // Transforma los datos para asegurar que tienen los valores necesarios
        let datosTransformados = respuesta.data.map(item => ({
          id: item.id || "",
          nombre: item.nombre || "Sin nombre",
          identificador: item.identificador || "N/A",
          fecha_pesaje: item.fecha_pesaje || "Sin fecha",
          peso_bruto: item.peso_bruto || "0",
          peso_tara: item.peso_tara || "0",
          peso_neto: item.peso_neto || "0",
          estado: item.estado || "0",
        }));

        // Configuración de las columnas del DataTable
        var columns = [
          { mDataProp: "id" },
          { mDataProp: "nombre" },
          { mDataProp: "identificador" },
          { mDataProp: "fecha_pesaje" },
          { mDataProp: "peso_bruto" },
          { mDataProp: "peso_tara" },
          { mDataProp: "peso_neto" },
          {
            mDataProp: "estado",
            render: function (data, type, full, meta) {
              let estadoTexto = "";
              let estadoClase = "";

              switch (data) {
                case "1":
                  estadoTexto = "Completado";
                  estadoClase = "badge text-bg-success";
                  break;
                case "2":
                  estadoTexto = "Descarga";
                  estadoClase = "badge text-bg-warning";
                  break;
                case "3":
                  estadoTexto = "Cancelado";
                  estadoClase = "badge text-bg-danger";
                  break;
                default:
                  estadoTexto = "Desconocido";
                  estadoClase = "badge text-bg-secondary";
              }

              return `<span class="${estadoClase} d-inline-flex align-items-center gap-2">
                        ${estadoTexto}
                      </span>`;
            },
          },
          {
            className: "text-left",
            render: function (data, type, full, meta) {
              let btnAcciones = "";

              // Condicionar los botones según el estado
              switch (full.estado) {
                case "1": // Completado
                  btnAcciones = `
                    <button data-id="${full.id}" name="imprimir" class="btn btn-icon btn-info" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir descarga">
                      <i class="ti ti-printer"></i>
                    </button>`;
                  break;
                case "2": // Descarga
                  btnAcciones = `
                    <button data-id="${full.id}" name="cancelarDescarga" class="btn btn-icon btn-danger" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar descarga">
                      <i class="ti ti-trash"></i>
                    </button>
                    <button data-id="${full.id}" name="completarDescarga" class="btn btn-icon btn-warning" type="button" data-toggle="tooltip" data-placement="top" title="Completar descarga">
                      <i class="ti ti-edit-circle"></i>
                    </button>`;
                  break;
                case "3": // Cancelado
                  // No hay botones de acción cuando está cancelado
                  btnAcciones = "";
                  break;
                default:
                  btnAcciones = "";
              }

              return btnAcciones;
            },
          },
        ];

        // Llamado a la función para cargar la tabla con los datos transformados
        cargarTabla("#complex-header", datosTransformados, columns);
      } else {
        console.error("La respuesta no fue exitosa:", respuesta.data);
      }
    },
  });
}

function cargarTabla(tableID, data, columns) {
  console.log(data);

  if ($.fn.DataTable.isDataTable(tableID)) {
    var table = $(tableID).DataTable();
    table.clear();
    table.rows.add(data);
    table.draw();
  } else {
    $(tableID).DataTable({
      data: data,
      columns: columns,
      ordering: false,
      language: {
        sProcessing: "Procesando...",
        sLengthMenu: "Mostrar _MENU_ registros",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sInfo:
          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
        sSearch: "Buscar:",
        oPaginate: {
          sFirst: "Primero",
          sLast: "Último",
          sNext: "Siguiente",
          sPrevious: "Anterior",
        },
      },
    });
  }
}

function eliminarPesaje(id) {
  Swal.fire({
    title: "¿Está seguro?",
    text: "Estas a punto de cancelar la descarga",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085D6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "POST",
        url: "./modules/comprasAcopio/controllers/eliminarDescarga.php",
        data: {
          id: id, // Enviando el id como está
        },
        dataType: "json", // Aquí especificamos que esperamos una respuesta en formato JSON
        // Error en la petición
        error: function (error) {
          console.log("Error:", error);
          Swal.fire({
            title: "Error",
            icon: "error",
            text: error.responseJSON.data, // Mostramos el mensaje de error desde PHP
            confirmButtonColor: "#3085d6",
          });
        },
        // Petición exitosa
        success: function (respuesta) {
          console.log("Respuesta del servidor:", respuesta); // Verifica el contenido de la respuesta
          
          if (respuesta.success) {
            Swal.fire({
              title: "Listo",
              icon: "success",
              text: `${respuesta.data.mensaje}`, // Mensaje desde PHP
              confirmButtonColor: "#3085d6",
            }).then(() => {
              window.location.href = "?module=comprasAcopio";
            });
          } else {
            Swal.fire({
              title: "Algo anda mal",
              icon: "error",
              text: "Ocurrió un error al intentar cancelar la descarga.",
              confirmButtonColor: "#3085d6",
            });
          }
        },
      });
    }
  });
}

function cargarDatosEditar(id) {
  $.ajax({
    type: "POST",
    url: "./modules/comprasAcopio/controllers/listarDescargas.php",
    data: {
      id: id,
    },
    error: function (error) {
      console.log("Error");
    },
    success: function (respuesta) {
      const datos = respuesta.data;
      console.log('Datos para completar',respuesta);
      $("#Na").val(datos[0].id);
      $("#productorSelect").val(datos[0].idProductor).trigger("change");
      $("#trasnporteSelect").val(datos[0].identificador).trigger("change");
      $("#pesoBruto").val(datos[0].peso_bruto);
      $("#pesoTara").val(datos[0].peso_tara);
      $("#pesoNeto").val(datos[0].peso_neto); 
    },
  });
}
function mostrarFicha(datos) {
  // Desestructuramos los datos para facilidad
  const { nombreProductor, vehiculo, pesoBruto, pesoTara, precioCompra } = datos;

  // Calculamos peso neto y total
  const pesoNeto = pesoBruto - pesoTara;
  const total = pesoNeto * precioCompra;

  // Generamos el contenido HTML de la tabla
  const tablaHTML = `
    <table class="table table-bordered" style="width: 100%; text-align: left;">
      <thead>
        <tr>
          <th>Campo</th>
          <th>Valor</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Nombre Productor</td>
          <td>${nombreProductor}</td>
        </tr>
        <tr>
          <td>Vehículo</td>
          <td>${vehiculo}</td>
        </tr>
        <tr>
          <td>Peso Bruto</td>
          <td>${pesoBruto} kg</td>
        </tr>
        <tr>
          <td>Peso Tara</td>
          <td>${pesoTara} kg</td>
        </tr>
        <tr>
          <td>Peso Neto</td>
          <td>${pesoNeto} kg</td>
        </tr>
        <tr>
          <td>Precio de Compra</td>
          <td>L ${precioCompra.toFixed(2)}</td>
        </tr>
        <tr>
          <td>Total</td>
          <td>L ${total.toFixed(2)}</td>
        </tr>
      </tbody>
    </table>
  `;

  // Usamos SweetAlert2 para mostrar el modal
  Swal.fire({
    title: 'Ficha del Productor',
    html: tablaHTML,
    icon: 'info',
    showCloseButton: true,
    focusConfirm: false,
    confirmButtonText: 'Cerrar',
    customClass: {
      popup: 'swal-wide', // Puedes agregar estilos personalizados si lo necesitas
    },
  });
}

// Ejemplo de uso de la función
const datosEjemplo = {
  nombreProductor: 'Juan Pérez',
  vehiculo: 'Toyota Hilux',
  pesoBruto: 12000,
  pesoTara: 4000,
  precioCompra: 5.25,
};
