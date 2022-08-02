const selector = document.getElementById("select_user_department");
const department = document.getElementById("department");
const role = document.getElementById("role");
const supervisor = document.getElementById("supervisor");
const diasVac = document.getElementById("diasVac");

if(selector!=null) {
    selector.addEventListener("change", (e) => {
        let departmentId = e.target.value;
        addSelectUser(departmentId);
    });
}


function addSelectUser(departmentId) {

    $.ajax({
        type: 'POST',
        url: '/admin/getUsers',
        async: true,
        data: ({id: departmentId}),
        datatype: 'json',
        success: function (data) {

            if (data['users'].length === 0) {
                $("#select_user_user option").remove();

                $("#select_user_user").prop('disabled', 'disabled');
            } else {
                $("#select_user_user").prop('disabled', false);

                $("#select_user_user option").remove();

                for (var key in data['users']) {

                    var value = data['users'][key];

                    $("#select_user_user").append(new Option(value, key));
                }
            }

        },
        error: function (data) {
            console.log(data);
        }
    });

};


// DEPENDIENDO DEL ROL, MOSTRAR O NO SUPERVISOR Y DIAS DE VACACIONES
role.addEventListener("change", (event) => {
    let $role = event.target.value;
    comprobarRole($role)

});


function comprobarRole($role) {
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
            diasVac.value = "";
            supervisor.value = "";
            department.value = "";
            break;
    }
}
// COMPORTAMIENTO SELECTORES

department.addEventListener("change", (e) => {
    let departmentId = e.target.value;
    console.log(role.value)
    if (role.value !== "ROLE_ADMIN"){
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
                    $("#supervisor").append(new Option(value,key));
                }
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

