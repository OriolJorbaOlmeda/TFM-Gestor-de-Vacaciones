const fechaInicio = document.getElementById("calendar_fecha_ini").innerText;
const fechaFin = document.getElementById("calendar_fecha_fin").innerText;
const fechaFestivo = document.getElementById("dateFestivo");


// COMPROBAR FECHA FESTIVO ENTRE EL RANGO DE FECHAS DEL CALENDARIO
fechaFestivo.addEventListener("change", event => {
    comprobarFechaFestivo()
});

function comprobarFechaFestivo() {
    let f1 = convertirFecha(fechaInicio)
    let f2 = convertirFecha(fechaFin)
    if (comprobarFechaMayorIgual(f1, fechaFestivo.value) && comprobarFechaMayorIgual(fechaFestivo.value, f2)) {
        fechaFestivo.classList.remove("is-invalid")
        document.getElementById("btn-añadir").disabled = false;
    } else {
        fechaFestivo.classList.add("is-invalid")
        document.getElementById("btn-añadir").disabled = true;
    }

}