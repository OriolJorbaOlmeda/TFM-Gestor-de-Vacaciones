const selector = document.getElementById("search_by_department_department");
const selectorUser = document.getElementById("selector");

selector.addEventListener("change", (e) => {
    let departmentId = e.target.value;
    addSelectUser(departmentId);
});

selectorUser.addEventListener("change", (e) => {
    let userId = e.target.value;
    addSelectCalendar(userId);
});


function addSelectUser(departmentId) {

    $.ajax({
        type: 'POST',
        url: '/employee/getUsers',
        async: true,
        data: ({id: departmentId}),
        datatype: 'json',
        success: function (data) {
            $("#selector option").remove();
            for (var key in data['users']) {
                var value = data['users'][key];
                $("#selector").append(new Option(value, key));
            }
        },
        error: function (data) {
            console.log(data);
        }
    });

};

function addSelectCalendar(userId) {

    $.ajax({
        type: 'POST',
        url: '/employee/getCalendar',
        async: true,
        data: ({id: userId}),
        datatype: 'json',
        success: function (data) {
          /*  var date = new Date()
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear()
            var Calendar = FullCalendar.Calendar;

            var calendarEl=document.getElementById('calendar');*/
            console.log(data);
           /* var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                //Random default events
                events: [

                ],
                editable: true,
                droppable: true // this allows things to be dropped onto the calendar !!!
            });*/
        },
        error: function (data) {
            console.log(data);
        }
    });

};
