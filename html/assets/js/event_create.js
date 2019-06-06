$(document).ready(function() {

    var society_id = 1;


    onLoadGetMaster(function(master_dict) {

        console.log(master_dict);
        loadMasterValue(master_dict);

    });


    $("#add_to_event").on('submit', function() {
        // alert("btn click");
        fd = getFormDataAddToEvent();

        stepSubmit(fd, api_link + "/api/event/add", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                // alert("inside success");

                var u_id = return_response[1].success.data.id;
                // $("div[name='loader']").hide();
                // set_session(u_id);
                $("i[name='btn_loader']").removeClass();
                location.href = fe_link + "/events.html";

            }

        });

    });

    
    function getFormDataAddToEvent() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('society_id', society_id);

        fd.append('event_type', $('#event_type').val());
        fd.append('event_title', $('#event_title').val());
        fd.append('event_date', $('#event_date').val());

        fd.append('event_time', $('#event_time').val());

        fd.append('event_description',$('#event_description').val());

        fd.append('event_venue', $('#event_venue').val());

        


        return fd;
    }



    


});