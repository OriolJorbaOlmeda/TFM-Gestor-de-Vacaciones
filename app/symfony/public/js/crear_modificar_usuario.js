const email = document.getElementById("email");
const password = document.getElementById("password");
const role = document.getElementById("role");
const selector = document.getElementById("select_user_department");

/*document.getElementById("groupSuper").style.display = "none";
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
*/

if(selector!=null) {
    selector.addEventListener("change", (e) => {
        let departmentId = e.target.value;
        addSelectUser(departmentId);
    });
}


function addSelectUser(departmentId) {

    $.ajax({
        type: 'POST',
        url: '/admin/prueba',
        async: true,
        data: ({id: departmentId}),
        datatype: 'json',
        success: function (data) {
            $("#select_user_user option").remove();
            for (var key in data['users']) {
                var value = data['users'][key];
                $("#select_user_user").append(new Option(value, key));
            }
        },
        error: function (data) {
            console.log(data);
        }
    });

};



