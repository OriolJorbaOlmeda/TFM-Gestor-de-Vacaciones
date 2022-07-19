const fecha_festivo = document.getElementById("nuevoFestivo");
const descripcion_festivo = document.getElementById("descNuevoFestivo");
const btn_anadir = document.getElementById("btn-añadir");
const lista_festivos = document.getElementById("listaFestivos");

// CONTROL BOTÓN AÑADIR - SE DEBEN RELLENAR TANTO LA FECHA COMO LA DESCRIPCIÓN DEL FESTIVO PARA HABILITAR EL BOTÓN
btn_anadir.disabled = true;

fecha_festivo.addEventListener("change", () => {
    controlBotonAnadir();
});

descripcion_festivo.addEventListener("keyup", () => {
    controlBotonAnadir();
});

function controlBotonAnadir() {
    btn_anadir.disabled = !(fecha_festivo.value !== "" && descripcion_festivo.value !== "");
}

// AÑADIR LINEA CON EL NUEVO FESTIVO
btn_anadir.addEventListener("click",  ()=>  {
    crearNuevoFestivo(fecha_festivo.value, descripcion_festivo.value)
    fecha_festivo.value = "";
    descripcion_festivo.value = "";
    btn_anadir.disabled = true;



});

function crearNuevoFestivo(fecha, descripcion) {
    let nuevo_festivo = document.createElement("li");
    nuevo_festivo.className = "list-group-item d-flex justify-content-between align-items-center";

    let icono = document.createElement("i");
    icono.className = "bi bi-x-lg";

    let span = document.createElement("span");
    span.className = "badge badge-primary badge-pill";
    span.appendChild(icono);

    let link = document.createElement("a");
    link.setAttribute('type', 'button');
    link.appendChild(span);
    link.addEventListener('click', () => {
        lista_festivos.removeChild(link.parentElement);

    })

    fecha = convertirFechaPantalla(fecha)   // de utils.js
    nuevo_festivo.innerText = fecha + " - " + descripcion
    nuevo_festivo.appendChild(link);
    lista_festivos.appendChild(nuevo_festivo);
    //crearFes("prueba","pruebad")
}


function crearFes(fecha, descripcion) {
    $.ajax({
        url : '/create-festive',
        type: "POST",
        data: {
            'date':'fecha',
            'desc':'descripcion'}
        ,
        success : function (data) {
            console.log("SUCCESS" +data);
        },
        error   : function () {
            console.log("ERROR");
        }
    });
}


// LISTENER BORRAR FESTIVOS
document.querySelectorAll('.eliminar-festivo').forEach(elem => {
    elem.addEventListener('click', () => {
        lista_festivos.removeChild(elem.parentElement);
    })
})