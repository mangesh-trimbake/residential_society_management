$(document).ready(function() {


    var addr_id;
    var events_array;

    function onLoadShowAllEvent() {
        // alert("btn click");
        fd = getFormDataAddToEvent();

        stepSubmit(fd, api_link + "/api/event/showall", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.id;
                events_array = return_response[1].success.data;

                console.log(events_array);

                showAllEvents(events_array);
                // eventsAcess();


            }

        });

    }

    onLoadShowAllEvent();



    function getFormDataAddToEvent() {
        var fd = new FormData();
        // Personal Details
        // fd.append('product_id', 1);
        fd.append('user_id', sessionStorage.getItem("user_id"));
        return fd;
    }

    function showAllEvents(events_array) {
        // body...
        $("#event_list").empty();

        var subtotal = 0;

        events_array = JSON.stringify(events_array).replace(/null/i, "\"\"");
        // alert(events_array);
        events_array = JSON.parse(events_array);

        for (i in events_array) {

            var event_itme = events_array[i]

            console.log(event_itme);

            $("#event_list").append("\
                        <tr>\
                            <td>" + event_itme.event_type + "</td>\
                            <td>" + event_itme.event_title + "</td>\
                            <td>" + event_itme.event_date + "</td>\
                            <td><a href='event.html?event_id=" + event_itme.id + "'><button>View</button></a></td>\
                        </tr>\
                ");


        }
        

    }

    function eventsAcess() {
        // alert(sessionStorage.getItem("event_acess"));
        if (sessionStorage.getItem("event_acess") == 1) {
            $('#add_event_btn').hide();
        } else if (sessionStorage.getItem("event_acess") == 3) {
            $('#add_event_btn').show();
        }
    }



});