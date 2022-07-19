const fechaInicio = document.getElementById("fechaInicio");
const fechaFin = document.getElementById("fechaFin");

// COMPROBACIONES FECHA INICIO Y FECHA FIN
fechaInicio.addEventListener("change", event => {
    comprobarFechaInicio()
});


fechaFin.addEventListener("change", event => {
    comprobarFechaFin()
});


function comprobarFechaInicio() {
    let fecha = fechaInicio.value;
    if (comprobarFechaMayorFechaActual(fecha)) {
        if (comprobarFechaMayorFechaActual(fechaFin.value) && comprobarFechaMayor(fechaInicio.value, fechaFin.value)){
            fechaInicio.classList.remove("is-invalid")
            fechaFin.classList.remove("is-invalid")
        } else {
            fechaInicio.classList.add("is-invalid")
        }
    } else {
        fechaInicio.classList.add("is-invalid")
    }
}

function comprobarFechaFin() {
    let fecha = fechaFin.value;
    if (comprobarFechaMayorFechaActual(fecha)) {
        if (comprobarFechaMayorFechaActual(fechaInicio.value) && comprobarFechaMayor(fechaInicio.value, fechaFin.value)){
            fechaInicio.classList.remove("is-invalid")
            fechaFin.classList.remove("is-invalid")
        } else {
            fechaFin.classList.add("is-invalid")
        }
    } else {
        fechaFin.classList.add("is-invalid")
    }
}