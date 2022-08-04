const selector = document.getElementById("select_user_department");

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

}
