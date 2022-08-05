const email = document.getElementById("email");
const diasVac = document.getElementById("diasVac");
const postalCode = document.getElementById("postalCode");
const role = document.getElementById("role");
const department = document.getElementById("department");
const supervisor = document.getElementById("supervisor");


if (role != null && role.value !== "") {
    comprobarRole(role.value)
} else {
    if (role != null) {
        diasVac.disabled = true;
        supervisor.disabled = true;
        department.disabled = true;
    }
}

// VALIDAR EMAIL
if (email != null) {
    email.addEventListener("change", (event) => {
        validarEmail(event.target.value)
    });
}

function validarEmail(valor) {
    if (/^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/.test(valor)){
        email.classList.remove("is-invalid");
    } else {
        email.classList.add("is-invalid");
    }
}

// VALIDAR DIAS DE VACACIONES QUE SEA NUMÉRICO
if (diasVac != null) {
    diasVac.addEventListener("change", (e) => {
        validarDiasVac(e.target.value)
    })
}

function validarDiasVac(value) {
    if (/^[0-9]{1,2}$/.test(value)) {
        diasVac.classList.remove("is-invalid");
    } else {
        diasVac.classList.add("is-invalid");
    }
}


// VALIDACIÓN CÓDIGO POSTAL
if (postalCode != null) {
    postalCode.addEventListener("change", (e) => {
        let value = e.target.value;
        if (/^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$/.test(value)) {
            postalCode.classList.remove("is-invalid");
        } else {
            postalCode.classList.add("is-invalid");
        }
    })
}


// DEPENDIENDO DEL ROL, MOSTRAR O NO SUPERVISOR Y DIAS DE VACACIONES
if (role != null){
    role.addEventListener("change", (event) => {
        let $role = event.target.value;
        comprobarRole($role)

    });
}

function comprobarRole($role) {
    switch ($role) {
        case "ROLE_EMPLEADO":
            diasVac.disabled = false;
            supervisor.disabled = false;
            department.disabled = false;
            break;
        case "ROLE_SUPERVISOR":
            diasVac.disabled = false;
            supervisor.disabled = true;
            department.disabled = false;
            break;
        case "ROLE_ADMIN":
            diasVac.disabled = true;
            supervisor.disabled = true;
            department.disabled = false;
            diasVac.value = "";
            supervisor.value = "";
            department.value = "";
            break;
    }
}




// COMPORTAMIENTO SELECTORES
if (department != null) {
    department.addEventListener("change", (e) => {
        let departmentId = e.target.value;
        if (role.value === "ROLE_EMPLEADO"){
            addSupervisors(departmentId);
        }
    });
}

function addSupervisors(departmentId) {
    $.ajax({
        type: 'POST',
        url: '/admin/get_supervisors',
        async: true,
        data: ({department_id: departmentId}),
        datatype: 'json',
        success: function (data) {

            $("#supervisor option").remove();

            if (data['users'].length === 0) {
                $("#supervisor").prop('disabled', 'disabled');
            } else {
                $("#supervisor").prop('disabled', false);
                for (var key in data['users']) {
                    var value = data['users'][key];
                    $("#supervisor").append(new Option(value, key));
                }
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}