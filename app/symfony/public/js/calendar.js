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
        url: '/employee/getUsers',
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
                    console.log(value['initialdate'].date);
                    event.push({
                        id: 'festives',
                        groupId: 'festives',
                        title: value['name'],
                        start: value['initialdate'].date,
                        end: value['finaldate'].date,
                        backgroundColor: "#5F9D72", //orange
                        borderColor: "#5F9D72", //orange
                        allDay: true
                    })

                }

                for (var key in data['festivo_usuario']) {

                    var value = data['festivo_usuario'][key];
                    event.push({
                        id: 'vacances',
                        groupId: 'vacances', // recurrent events in this group move together
                        title: value['name'],
                        start: value['initialdate'].date,
                        end: value['finaldate'].date,
                        backgroundColor: "#ECB011",
                        borderColor: "#ECB011", //orange
                        allDay: true
                    })

                }
                for (var key in data['absence_user']) {
                    var value = data['absence_user'][key];
                    event.push({
                        id: 'absence',
                        groupId: 'absence', // recurrent events in this group move together
                        title: value['name'],
                        start: value['initialdate'].date,
                        end: value['finaldate'].date,
                        backgroundColor: "#1111EC",
                        borderColor: "#1111EC", //orange
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
