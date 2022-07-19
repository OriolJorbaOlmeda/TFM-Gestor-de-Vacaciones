const email = document.getElementById("email");
const password = document.getElementById("password");
const role = document.getElementById("role");

document.getElementById("groupSuper").style.display = "none";
document.getElementById("groupDiasVac").style.display = "none";

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
password.addEventListener("change", (event) => {
    validarPassword(event.target.value)
});

function validarPassword(valor) {
    if ( /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(valor)){
        password.classList.remove("is-invalid");
    } else {
        password.classList.add("is-invalid");
    }
}


// DEPENDIENDO DEL ROL, MOSTRAR O NO SUPERVISOR Y DIAS DE VACACIONES
role.addEventListener("change", (event) => {
    let $role = event.target.value;
    switch ($role) {
        case "ROLE_EMPLEADO":
            document.getElementById("groupDiasVac").style.display = "block";
            document.getElementById("groupSuper").style.display = "block";
            break;
        case "ROLE_SUPERVISOR":
            document.getElementById("groupDiasVac").style.display = "block";
            document.getElementById("groupSuper").style.display = "none";
            break;
        case "ROLE_ADMIN":
            document.getElementById("groupDiasVac").style.display = "none";
            document.getElementById("groupSuper").style.display = "none";
            break;
    }
});


