const email = document.getElementById("email");
const password = document.getElementById("password");
const role = document.getElementById("role");
const postalCode = document.getElementById("postalCode");
const department = document.getElementById("department");
const supervisor = document.getElementById("supervisor");
const diasVac = document.getElementById("diasVac");

diasVac.disabled = true;
supervisor.disabled = true;
department.disabled = true;

// VALIDAR EMAIL
email.addEventListener("change", (event) => {
   validarEmail(event.target.value)
});

function validarEmail(valor) {
    if (/^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/.test(valor)){
        email.classList.remove("is-invalid");
    } else {
        email.classList.add("is-invalid");
    }
}


// VALIDAR PASSWORD
/*password.addEventListener("change", (event) => {
    validarPassword(event.target.value)
});

function validarPassword(valor) {
    if ( /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(valor)){
        password.classList.remove("is-invalid");
    } else {
        password.classList.add("is-invalid");
    }
}*/


// DEPENDIENDO DEL ROL, MOSTRAR O NO SUPERVISOR Y DIAS DE VACACIONES
role.addEventListener("change", (event) => {
    let $role = event.target.value;

    switch ($role) {
        case "ROLE_EMPLEADO":
            diasVac.disabled = false;
            supervisor.disabled = false;
            department.disabled = false;
            break;
        case "ROLE_SUPERVISOR":
            diasVac.disabled = false;
            supervisor.disabled = false;
            department.disabled = false;
            break;
        case "ROLE_ADMIN":
            diasVac.disabled = true;
            supervisor.disabled = true;
            department.disabled = false;
            break;
    }
});

// VALIDACIÓN CÓDIGO POSTAL
postalCode.addEventListener("change", (e) => {
    let value = e.target.value;
    if (/^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$/.test(value)) {
        postalCode.classList.remove("is-invalid");
    } else {
        postalCode.classList.add("is-invalid");
    }
})




// COMPORTAMIENTO SELECTORES

department.addEventListener("change", (e) => {
    let departmentId = e.target.value;
    if (!role.value === "ROLE_ADMIN"){
        addSupervisors(departmentId);
    }
});

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
                    console.log(data['users'][key]);
                    var value = data['users'][key];
                    $("#supervisor").append(new Option(data['users'][key]));
                }
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}