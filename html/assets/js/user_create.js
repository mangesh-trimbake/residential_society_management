$(document).ready(function() {
    var order_acess = 0;
    var purchase_order_acess = 0;
    var user_acess = 0;
    var society_id = 1;

    var wing_list = [];
    // var payment_list = [];
    // var dispatch_list = [];

    // var master_dict = 
    onLoadGetMaster(function(master_dict) {

        console.log(master_dict);
        loadMasterValue(master_dict);
        onLoadShowSociety();

    });


    function onLoadShowSociety() {
        // alert("btn click");
        fd = getFormDataShowSociety();

        stepSubmit(fd, api_link + "/api/society/show", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.id;
                society_array = return_response[1].success.data;

                console.log(society_array);

                // showSociety(society_array);

                console.log(society_array);
                society_array = JSON.stringify(society_array).replace(/null/i, "\"\"");
                console.log(society_array);
                society_array = JSON.parse(society_array);

                wing_list = society_array["wing_list"];

                $("#wing_name").empty();
                $("#wing_name").append("<option value=''>-Select-</option>")
                for (var i in wing_list) {
                    // alert(i);
                    curr_wing = wing_list[i];
                    $("#wing_name").append("\
                        <option value='"+curr_wing['wing_name']+"'>"+curr_wing['wing_name']+"</option>\
                        ");


                }


                // societyAcess();


            }

        }); 
    }


    $("#add_to_user").on('submit', function() {
        // alert("btn click");
        fd = getFormDataAddToUser();

        stepSubmit(fd, api_link + "/api/user/add", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                // alert("inside success");

                var u_id = return_response[1].success.data.id;
                // $("div[name='loader']").hide();
                // set_session(u_id);
                $("i[name='btn_loader']").removeClass();
                location.href = fe_link + "/users.html";

            }

        });

    });

    $("#user_type").on('change', function() {

        // alert(user_type.value);
        if (user_type.value == "Admin") {
            $('input[type=checkbox]').prop('checked', true);
        } else {
            $('input[type=checkbox]').prop('checked', false);
        }



    });


    

    function getFormDataAddToUser() {
        var fd = new FormData();
        // Personal Details
        
        fd.append('user_id', sessionStorage.getItem("user_id"));

        fd.append('user_type', $('#user_type').val());
        fd.append('user_name', $('#user_name_c').val());
        fd.append('first_name', $('#first_name').val());
        fd.append('last_name', $('#last_name').val());
        fd.append('mobile', $('#mobile').val());
        fd.append('email', $('#email').val());

        fd.append('wing_name', $('#wing_name').val());
        fd.append('flat_no', $('#flat_no').val());
        fd.append('password', $('#password').val());




        return fd;
    }

    function getFormDataShowSociety() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('society_id', society_id);
        return fd;
    }

    


});