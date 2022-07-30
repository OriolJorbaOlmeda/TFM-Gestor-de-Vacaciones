const fechaInicio = document.getElementById("fechaInicio");
const fechaFin = document.getElementById("fechaFin");
const totalDays = document.getElementById("duracion")
let twigArray = document.querySelector('.js-data-festive');
let festives = twigArray.dataset.festives;

window.onload = function() {
    comprobarFechaInicio();
    comprobarFechaFin();
};

// COMPROBACIONES FECHA INICIO Y FECHA FIN
fechaInicio.addEventListener("change", event => {
    comprobarFechaInicio()
    getBusinessDatesCount()
});


fechaFin.addEventListener("change", event => {
    comprobarFechaFin()
    getBusinessDatesCount()
});

function comprobarFechaInicio() {
    let fecha = fechaInicio.value;
    if (comprobarFechaMayorFechaActual(fecha)) {
        if (comprobarFechaMayorFechaActual(fechaFin.value) && comprobarFechaMayorIgual(fechaInicio.value, fechaFin.value)){
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
        if (comprobarFechaMayorFechaActual(fechaInicio.value) && comprobarFechaMayorIgual(fechaInicio.value, fechaFin.value)){
            fechaInicio.classList.remove("is-invalid")
            fechaFin.classList.remove("is-invalid")
        } else {
            fechaFin.classList.add("is-invalid")
        }
    } else {
        fechaFin.classList.add("is-invalid")
    }
}

function getBusinessDatesCount() {
    let count = 0;
    const curDate = new Date(fechaInicio.value);
    const fin = new Date(fechaFin.value)
    while (curDate <= fin) {
        const dayOfWeek = curDate.getDay();
        if (dayOfWeek !== 0 && dayOfWeek !== 6 && !festives.includes(curDate.toISOString().split('T')[0])) count++;
        curDate.setDate(curDate.getDate() + 1);
        console.log(count)
    }
    totalDays.value = count;
}