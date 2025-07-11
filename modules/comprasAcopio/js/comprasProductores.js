
let editar = false;
$(document).ready(function () {

    listarProductores();


    $(document).ready(function () {
        $('#exampleModalCenter').on('hidden.bs.modal', function () {
          console.log('Modal cerrado, reiniciando formulario...');
          $(this).find('form')[0].reset();
        });
      });
$("#nuevoProductor").on("click",function(){
  $("#exampleModalCenter").modal("hide"); // Oculta el modal
  $("#cerrarModal").attr("hidden", true); // Oculta cerrarModal
  $("#completarDescarga").attr("hidden", true); // Oculta completarDescarga
  $("#guardarProductor").removeAttr("hidden"); // Muestra enviarDescarga
});

$("#guardarProductor").on("click", function () {
  // Obtén los valores de los inputs
  let nombre = $("#nombre").val();
  let identidad  = $("#identidad").val();
  let direccion = $("#direccion").val();
  // Validación para el campo "nombre"
  if (nombre.trim() === "") {
    Swal.fire({icon: "error", title: "Error",text: "Por favor, ingresa tu nombre.", });
    return false; // Detiene la ejecución si hay un error
  }

  // Validación para el campo "identidad"
  if (identidad.trim() === "") {
    Swal.fire({
      icon: "error",title: "Error",text: "Por favor, ingresa tu número de identidad.",});
    return false; // Detiene la ejecución si hay un error
  }

  // Validación para el campo "direccion"
  if (direccion.trim() === "") {
    Swal.fire({
        icon: "error",title: "Error",text: "Por favor, ingresa una dirección.",});
    return false; // Detiene la ejecución si hay un error
  }

  // Los datos están correctos, continúa con el envío
  let LosDatos = {
    nombre,
    identidad,
    direccion,
  };

  console.log(LosDatos);

  // Cierra el modal actual
  $("#exampleModalCenter").modal("hide");

  // Muestra una pantalla de carga
  guardarNuevoProductor(LosDatos);
  swal.fire({
      title: "Cargando...", text: "Por favor espera.",icon: "info",showConfirmButton: false,allowOutsideClick: false,timer: 3000, // Pantalla de carga por 1 segundo
      willOpen: () => {
          swal.showLoading();
      },
  });
});
  // Completar descarga y pagar y tal vez abonar a prestamo
  $("#actualizarProductor").on("click", function () {
    // Obtener los valores de los inputs
    let idProductor = $("#Na").val();
    let nombre = $("#nombre").val();
    let identidad = $("#identidad").val();
    let direccion = $("#direccion").val();

    // Crear el objeto con los datos
    let LosDatos = {
        idProductor,
        nombre,
        identidad,
        direccion,
    };

    // Mostrar los datos en la consola
    console.log(LosDatos);

    // Llamar a la función para completar el nuevo ingreso
    $("#exampleModalCenter").modal("hide");
    actualizarProductor(LosDatos);
    
});

  $("#complex-header").on("click", "button", function () {
    // Obtener el id que trae el botón
    let id = $(this).attr("data-id");

    // Obtener la acción a realizar
    let accion = $(this).attr("name");

    // Dependiendo del botón al que se le hace click
    // se realizará una acción transmitida por el atributo name
    if (accion == "eliminarProductor") {
      // Llamamos a la alerta para eliminar
      
      eliminarProductor(id);
    }
    if (accion == "editarProductor") {
      // Llamamos a la alerta para editar
      editarProductor(id);
    }
  });
});

/*INICIO DE FUNCIONES*/

function guardarNuevoProductor(losDatos) {
    $.ajax({
        type: "POST",
        url: "./modules/comprasAcopio/controllers/cp_agregarProductor.php",
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
                    text: "Productor guardado exitosamente",
                    icon: "success"
                  }).then(() => {
                    window.location.href = "?module=productores"; // Redirigir a la página indicada
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
function actualizarProductor(losDatos) {

  $.ajax({
      type: "POST",
      url: "./modules/comprasAcopio/controllers/cp_actualizarProductor.php",
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
                  window.location.href = "?module=productores"; // Redirigir a la página indicada
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
function editarProductor(id) {
  // Muestra el modal
  $("#exampleModalCenter").modal("show");
  $("#cerrarModal").removeAttr("hidden");
  $("#actualizarProductor").removeAttr("hidden");
  $("#guardarProductor").attr("hidden", true);
  cargarDatosEditar(id);
}
function listarProductores() {
  // Llamada AJAX para obtener los datos desde el backend
  $.ajax({
    type: "GET",
    url: "./modules/comprasAcopio/controllers/cp_listarProductores.php",
    data: {},
    // Error en la petición
    error: function (error) {
      console.error("Error al obtener las productores:", error);
    },
    // Petición exitosa
    success: function (respuesta) {
      // Verifica si la respuesta es válida y tiene éxito
      if (respuesta.success) {
        // Transforma los datos para asegurar que tienen los valores necesarios
        let datosTransformados = respuesta.data.map(item => ({
            idProductor: item.idProductor,
          id: item.id || "",
          nombre: item.nombre || "Sin nombre",
          identificacion: item.identificacion || "N/A",
          direccion: item.direccion || "N/A",
          estado: item.estado || "0",
        }));
        // Configuración de las columnas del DataTable
        console.log(datosTransformados);
        var columns = [
          { mDataProp: "nombre" },
          { mDataProp: "identificacion" },
          { mDataProp: "direccion" },
          {
            mDataProp: "estado",
            render: function (data, type, full, meta) {
              let estadoTexto = "";
              let estadoClase = "";

              switch (data) {
                case 1:
                  estadoTexto = "Activo";
                  estadoClase = "badge text-bg-success";
                  break;
                case 2:
                  estadoTexto = "Inactivo";
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
                case 1: // Activo
                btnAcciones = `
                  <button data-id="${full.idProductor}" data-identidad="${full.identificacion}" name="editarProductor" class="btn btn-icon btn-warning" type="button" data-toggle="tooltip" data-placement="top" title="Editar productor">
                    <i class="ti ti-edit-circle"></i>
                  </button>
                  <a href="?module=transportes&idProductor=${full.idProductor}" name="verTransportes" class="btn btn-icon btn-primary" data-toggle="tooltip" data-placement="top" title="Ver transportes">
                    <i class="fas fa-truck" style="color: white;"></i>
                  </a>
                  <button data-id="${full.idProductor}" name="eliminarProductor" class="btn btn-icon btn-danger" type="button" data-toggle="tooltip" data-placement="top" title="Eliminar productor">
                    <i class="ti ti-trash"></i>
                  </button>`;
                break;

                case 2: // Inactivo
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

function eliminarProductor(id) {
  Swal.fire({
    title: "¿Está seguro?",
    text: "Estas a punto de eliminar este productor",
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
        url: "./modules/comprasAcopio/controllers/cp_eliminarProductor.php",
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
              window.location.href = "?module=productores";
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
    console.log(id);
  $.ajax({
    type: "POST",
    url: "./modules/comprasAcopio/controllers/cp_cargarProductorEdicion.php",
    data: {
      id: id,
    },
    error: function (xhr, status, error) {
        console.error("Error en la solicitud AJAX:");
        console.log("Estado readyState:", xhr.readyState);
        console.log("Código de estado:", xhr.status);
        console.log("Texto del error:", error);
        console.log("Respuesta del servidor:", xhr.responseText);
    },
    success: function (respuesta) {
      const datos = respuesta.data;
      console.log('Datos para completar',respuesta);
      $("#Na").val(datos[0].idProductor);
      $("#nombre").val(datos[0].nombre);
      $("#direccion").val(datos[0].direccion);
      $("#identidad").val(datos[0].identificacion);
    },
  });
}

//FUNCIONES TRANSPORTES