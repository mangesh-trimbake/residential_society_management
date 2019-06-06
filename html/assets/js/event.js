$(document).ready(function() {

    var url_string = window.location.href;
    var url = new URL(url_string);
    var event_id = url.searchParams.get("event_id");
    var society_id = 1;
    var created_by;


    onLoadGetMaster(function(master_dict) {

        console.log(master_dict);
        loadMasterValue(master_dict);
        onLoadShowEvent();

    });


    $("#update_event").on('submit', function() {
        // alert("btn click");
        fd = getFormDataUpdateOrder();

        stepSubmit(fd, api_link + "/api/event/update", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                // alert("inside success");

                var u_id = return_response[1].success.data.id;
                // $("div[name='loader']").hide();
                // set_session(u_id);
                $("i[name='btn_loader']").removeClass();
                // location.href = fe_link+"/orders.html";
                location.reload();

            }

        });

    });

    $("#remove_event_btn").on('click', function() {

        // alert("remove_order_btn");
        swal({
            title: "Delete Event",
            // text: "You won't be able to revert this!",
            // type: 'warning',
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: '#41B314',
            cancelButtonColor: '#F9354C',
            confirmButtonText: "Yes Delete it!"
        }).then(function() {
            fd = getFormDataDeleteOrder();

            stepSubmit(fd, api_link + "/api/event/delete", function(return_response) {

                console.log(return_response[0]);

                if (return_response[0] == "success") {
                    swal(
                        "Successfull",
                        "Event deleted Successfully",
                        'success'
                    ).then(function() {
                        window.location = fe_link + "/events.html"
                    });

                }
            });

            // }

            //   });

        }, function(dismiss) {
            if (dismiss === 'cancel') {
                swal(
                    'Cancelled',
                    'Your Cancelled the deleting.',
                    'info'
                ).catch(swal.noop);
            }
        });

    });




    function onLoadShowEvent() {
        // alert("btn click");
        fd = getFormDataShowOrder();

        stepSubmit(fd, api_link + "/api/event/show", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.id;
                orders_array = return_response[1].success.data;

                console.log(orders_array);

                showOrder(orders_array);

                eventAcess();


            }

        });
        // $("#update_order :input").prop("disabled", true); 
        // $("#edit_btn").prop("disabled", false); 
    }



    $("#edit_btn").on('change', function() {

        if ($("#edit_btn").is(':checked')) {
            $("#update_order :input").prop("disabled", false);
        } else {
            $("#update_order :input").prop("disabled", true);
            $("#edit_btn").prop("disabled", false);
        }

    });

    function showOrder(orders_array) {
        // body...
        console.log(orders_array);
        orders_array = JSON.stringify(orders_array).replace(/null/i, "\"\"");
        console.log(orders_array);
        orders_array = JSON.parse(orders_array);

        console.log(orders_array);

        created_by = orders_array.created_by;

        $("#event_type").val(orders_array.event_type);

        $("#event_title").val(orders_array.event_title);
        // $("#order_no").val(orders_array.order_no);

        $("#event_date").val(orders_array.event_date);
        $("#event_time").val(orders_array.event_time);

        $("#event_venue").val(orders_array.event_venue);
        $("#event_description").val(orders_array.event_description);
        
    }

    function getFormDataShowOrder() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('event_id', event_id);
        return fd;
    }

    function getFormDataUpdateOrder() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('event_id', event_id);

        fd.append('society_id', society_id);

        fd.append('event_type', $('#event_type').val());
        fd.append('event_title', $('#event_title').val());
        fd.append('event_date', $('#event_date').val());

        fd.append('event_time', $('#event_time').val());

        // fd.append('order_delivery_date',$('#order_delivery_date').val());
        // fd.append('po_no',$('#po_no').val());
        fd.append('event_description',$('#event_description').val());

        fd.append('event_venue', $('#event_venue').val());


        return fd;
    }

    function getFormDataDeleteOrder() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('event_id', event_id);


        return fd;
    }




    




    
    function eventAcess(){
        // body...
        if(sessionStorage.getItem("user_type") == "member"){

            $("#update_event_btn").hide();
            $("#remove_event_btn").hide();
        }
        if(sessionStorage.getItem("user_id") == created_by){
            $("#update_event_btn").show();
            $("#remove_event_btn").show();   
        }
    }

    



});