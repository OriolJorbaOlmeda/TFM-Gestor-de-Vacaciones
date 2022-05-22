
// Convertir la fecha de 2022-12-20 a 20/12/2022
function convertirFechaPantalla(fecha) {
    return fecha.split("-")[2] + "/" + fecha.split("-")[1] + "/" + fecha.split("-")[0];
}

// Devolver fecha de hoy
function getFechaHoy(){
    let fecha_actual = new Date()
    let dia = fecha_actual.getDate()
    let mes = fecha_actual.getMonth() + 1;
    let ano = fecha_actual.getFullYear()
    if (dia < 10) {
        dia = "0" + dia
    }
    if (mes < 10) {
        mes = "0" + mes
    }
    return ano + "" + mes + "" + dia    //Ej.: 20220520
}

// Comprobar que la fecha introducida es mayor a la fecha actual
function comprobarFechaMayorFechaActual(fecha) {
    let fecha_hoy = getFechaHoy()
    fecha = fecha.split("-")[0] + "" + fecha.split("-")[1] + "" + fecha.split("-")[2];
    return fecha >= fecha_hoy;
}

// Comprobar que la fecha final es mayor a la fecha inicio
function comprobarFechaMayor(fechaInicio, fechaFin) {
    if (fechaInicio !== "" && fechaFin !== "") {
            fechaInicio = fechaInicio.split("-")[0] + "" + fechaInicio.split("-")[1] + "" + fechaInicio.split("-")[2];
            fechaFin = fechaFin.split("-")[0] + "" + fechaFin.split("-")[1] + "" + fechaFin.split("-")[2];
            return fechaInicio < fechaFin;
    } else {
        return true;
    }
}