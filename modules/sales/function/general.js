$(document).ready(function () {

    /* ========================================== Formatos de los los inputs ========================================== */
    $('#rtn, #telefono, #nremision, #cantidad, #emisiones, #km, #boleta').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    $('#precio').on('input', function () {
        this.value = this.value.replace(/[^0-9.-]/g, '');
    });
    $('.moneda').on('input', function () {
        this.value = this.value.replace(/[^0-9.]/g, '');
    });
    IMask($('#rtn')[0], { mask: '0000-0000-000000' });
    IMask($('#telefono')[0], { mask: '0000-0000' });
    IMask($('#nremision')[0], { mask: '000-000-00-00000000' });
});

document.querySelectorAll('input[type="date"]').forEach(function (input) {
    input.addEventListener('keydown', function (e) {
        e.preventDefault(); // Evita cualquier acción predeterminada al presionar una tecla
    });
});

//========================= Fechas largas =========================

const fecha = new Date(); // Fecha actual

const opciones = {
    weekday: 'long',
    day: '2-digit',
    month: 'long',
    year: 'numeric'
};

let fechaFormateada = fecha.toLocaleDateString('es-ES', opciones);

// Capitalizar primera letra (día de la semana)
fechaFormateada = fechaFormateada.charAt(0).toUpperCase() + fechaFormateada.slice(1);

// Agregar punto después del mes
fechaFormateada = fechaFormateada.replace(/de ([a-záéíóúñ]+) /i, (match, mes) => {
    const mesCapitalizado = mes.charAt(0).toUpperCase() + mes.slice(1);
    return `de ${mesCapitalizado}. `;
});

$('#fechalarga').html(fechaFormateada);


//========================= Botones de accion =========================


$('.seleccionar').on('click', function () {

    Swal.fire({
        target: document.getElementById('modalDetalle'),
        title: 'Cantidad',
        html: '<p>Coloque la cantidad que desea usar</p>' +
            '<input id="swal-input1" class="swal2-input">',
        focusConfirm: false,
        preConfirm: () => {
            return [document.getElementById('swal-input1').value];
        }
    });
});


//========================= codigo botones wizard ========================= hoy me comi 2 baleadas :v

document.addEventListener('DOMContentLoaded', function () {
    const wizard = new Wizard('#basicwizard', {
        validate: true,
        progress: true
    });

    document.querySelector('.first a').addEventListener('click', function () {
        wizard.first();
    });

    document.querySelector('.previous a').addEventListener('click', function () {
        wizard.previous();
    });

    document.querySelector('.next a').addEventListener('click', function () {
        wizard.next();
    });

    document.querySelector('.last a').addEventListener('click', function () {
        wizard.last();
    });
});



//========================= validaciones de fecha =========================

// Obtener la fecha actual en formato YYYY-MM-DD
let hoy = new Date().toISOString().split("T")[0];
// Establecer el atributo max para bloquear fechas futuras
$(".fechasfuturas").attr("max", hoy);

//========================= RAQUI ========================= 

$(document).ready(function () {
    toggleTraeRaqui();

    $('input[name="traeraqui"]').change(function () {
        toggleTraeRaqui();
    });

    function toggleTraeRaqui() {
        if ($("#raquisi").is(":checked")) {
            $("#traeraqui").slideDown();
            $("#nrecepcionraqui").attr("required", true);
            $("#filerecepcionraqui").attr("required", true);
        } else {
            $("#traeraqui").slideUp();
            $("#nrecepcionraqui").removeAttr("required");
            $("#filerecepcionraqui").removeAttr("required");
        }
    }

    togglePagototal();

    $('input[name="pagototal"]').change(function () {
        togglePagototal();
    });

    function togglePagototal() {
        if ($("#todosi").is(":checked")) {
            $("#pagototal").slideUp();
            $("#pagoparcial").slideDown();
            $("#abono").attr("required", true);
        } else {
            $("#pagototal").slideDown();
            $("#pagoparcial").slideUp();
            $("#abono").removeAttr("required");
        }
    }


    //========================= ACCIONES RAQUI ========================= 

    toggleAccionRaqui();

    $('input[name="accionraqui"]').change(function () {
        toggleAccionRaqui();
    });

    function toggleAccionRaqui() {
        if ($("#accionraqui1").is(":checked")) {
            $("#soloflete").slideDown();
            $("#compraventa").slideUp();
            $("#fleteraqui").attr("required", true);
            $("#compraventa input").removeAttr("required");
        } else if ($("#accionraqui2").is(":checked")) {
            $("#compraventa").slideDown();
            $("#soloflete").slideUp();
            $("#compraventa input").attr("required", true);
            $("#fleteraqui").removeAttr("required");
        }
    }
});





//========================= codigo datatables ========================= pero ya tengo hambre :v
/*       // [ DOM/jquery ]
      var total, pageTotal;
      var table = $('#disponibles, #seleccionados').DataTable();
      // [ column Rendering ]
      $('#colum-render').DataTable({
        columnDefs: [
          {
            render: function (data, type, row) {
              return data + ' (' + row[3] + ')';
            },
            targets: 0
          },
          {
            visible: false,
            targets: [3]
          }
        ]
      }); */