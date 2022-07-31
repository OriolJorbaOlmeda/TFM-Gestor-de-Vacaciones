const selector = document.getElementById("search_by_department_department");
const selectorUser = document.getElementById("selector");
const checkeBox = document.getElementById("checkboxSuccess1");

selector.addEventListener("change", (e) => {
    let departmentId = e.target.value;
    addSelectUser(departmentId);
});

selectorUser.addEventListener("change", (e) => {
    let userId = e.target.value;
    addSelectCalendar(userId);
});


checkeBox.addEventListener("click", (e) => {
    if (checkeBox.checked) {
        console.log("festivos marcado");
        $('.fc-daygrid-event').css("visibility", "visible");
    } else {
        console.log("festivos desmarcador")
        $('.fc-daygrid-event').css("visibility", "hidden");
    }
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

            var date = new Date()
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear()
            var Calendar = FullCalendar.Calendar;

            var calendarEl = document.getElementById('calendar');
            var event = [];

            console.log(data['festivo_depar']);

            for (var key in data['festivo_depar']) {

                var value = data['festivo_depar'][key];
                console.log(value['initialdate'].date);
                event.push({
                    title: value['name'],
                    start: value['initialdate'].date,
                    end: value['finaldate'].date,
                    backgroundColor: Math.floor(Math.random() * 16777215).toString(16), //orange
                    borderColor: Math.floor(Math.random() * 16777215).toString(16), //orange
                    allDay: true
                })

            }


            for (var key in data['festivo_usuario']) {

                var value = data['festivo_usuario'][key];
                console.log(value['initialdate'].date);
                event.push({
                    title: value['name'],
                    start: value['initialdate'].date,
                    end: value['finaldate'].date,
                    backgroundColor: "#F56547",
                    borderColor: "#F56547", //orange
                    allDay: true
                })

            }
            var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                //Random default events
                events: event,
                editable: true,
                droppable: true // this allows things to be dropped onto the calendar !!!
            });
            calendar.render();
        },
        error: function (data) {
            console.log(data);
        }
    });

};
