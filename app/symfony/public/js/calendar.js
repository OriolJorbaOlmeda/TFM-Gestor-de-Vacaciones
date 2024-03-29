const selector = document.getElementById("search_by_department_department");
const selectorUser = document.getElementById("selector");
const checkeBox = document.getElementById("checkboxSuccess1");

selector.addEventListener("change", (e) => {
    let departmentId = e.target.value;

    addSelectUser(departmentId);
    addSelectCalendar(0);



});

selectorUser.addEventListener("change", (e) => {
    let userId = e.target.value;
    addSelectCalendar(userId);
});


function addSelectUser(departmentId) {

    $.ajax({
        type: 'POST',
        url: '/getUsers',
        async: true,
        data: ({id: departmentId}),
        datatype: 'json',
        success: function (data) {
            $("#selector option").remove();

            if (data['users'].length === 0) {
                $("#selector").prop('disabled', 'disabled');
            } else {
                $("#selector").prop('disabled', false);
                for (var key in data['users']) {
                    var value = data['users'][key];
                    $("#selector").append(new Option(value, key));
                }
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


                for (var key in data['festivo_depar']) {

                    var value = data['festivo_depar'][key];
                    event.push({
                        id: 'festives',
                        groupId: 'festives',
                        title: value['name'],
                        start: value['initialdate'].date,
                        end: value['finaldate'].date,
                        backgroundColor: "#3d9970", //olive
                        borderColor: "#3d9970", //olive
                        allDay: true
                    })

                }

                for (var key in data['festivo_usuario']) {

                    var value = data['festivo_usuario'][key];
                    var finalDate = new Date(value['finaldate'].date)
                    finalDate.setHours(20, 21, 22);

                    event.push({
                        id: 'vacances',
                        groupId: 'vacances', // recurrent events in this group move together
                        title: value['name'],
                        start: value['initialdate'].date,
                        end: finalDate,
                        backgroundColor: "#ff851b", //orange
                        borderColor: "#ff851b", //orange
                        allDay: false
                    })

                }
                for (var key in data['absence_user']) {
                    var value = data['absence_user'][key];
                    var finalDate = new Date(value['finaldate'].date)
                    finalDate.setHours(20, 21, 22);
                    event.push({
                        id: 'absence',
                        groupId: 'absence', // recurrent events in this group move together
                        title: value['name'],
                        start: value['initialdate'].date,
                        end: finalDate,
                        backgroundColor: "#6610f2", //indigo
                        borderColor: "#6610f2", //indigo
                        allDay: false
                    })

                }


                var calendar = new Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    nextDayThreshold: '00:00:00',
                    themeSystem: 'bootstrap',
                    events: event,
                    editable: true,
                    displayEventTime: false,


                });
                const checkeBox = document.querySelectorAll("input[type=checkbox]");
                checkeBox.forEach(function (checkbox) {
                    checkbox.checked = true;

                    checkbox.addEventListener("click", (e) => {
                        var target = e.target.id;
                        var event = calendar.getEventById(target);
                        if (checkbox.checked) {
                            event.setProp('display', 'auto') // return to normal
                        } else {
                            event.setProp("display", "none")
                        }
                    });
                });
                calendar.render();
            },
            error: function (data) {
                console.log(data);
            }
        });


};
