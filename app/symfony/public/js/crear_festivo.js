const fechaInicio = document.getElementById("calendar_fecha_ini").innerText;
const fechaFin = document.getElementById("calendar_fecha_fin").innerText;
const fechaFestivo = document.getElementById("dateFestivo");


// COMPROBAR FECHA FESTIVO ENTRE EL RANGO DE FECHAS DEL CALENDARIO
fechaFestivo.addEventListener("change", event => {
    comprobarFechaFestivo()
});

function comprobarFechaFestivo() {
    let fechaInicio = convertirFecha(fechaInicio)
    let fechaFin = convertirFecha(fechaFin)
    console.log(fechaInicio)
    console.log(fechaFin)
}