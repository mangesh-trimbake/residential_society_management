$(document).ready(function() {

    var url_string = window.location.href;
    var url = new URL(url_string);
    var user_req_id = url.searchParams.get("user_id");
    
    var user_acess = 0;
    var password = [];
    var users_array_g;

    var society_id = 1;

    var subscription_list = []

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
                onLoadShowUser();
                onLoadShowMaintenance();
                // societyAcess();


            }

        }); 
    }

    function onLoadShowMaintenance() {
        // alert("btn click");
        fd = getFormDataShowMaintenance();

        stepSubmit(fd, api_link + "/api/maintenance/show", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.id;
                maintenance_array = return_response[1].success.data;

                console.log(maintenance_array);

                showMaintenance(maintenance_array);
                maintenanceAcess();


            }

        });
        // $("#update_maintenance :input").prop("disabled", true); 
        // $("#edit_btn").prop("disabled", false); 
    }

    function showMaintenance(maintenance_array) {
        // body...
        console.log(maintenance_array);
        maintenance_array = JSON.stringify(maintenance_array).replace(/null/i, "\"\"");
        console.log(maintenance_array);
        maintenance_array = JSON.parse(maintenance_array);

        console.log(maintenance_array);

        subscription_list = maintenance_array["subscription_list"];

        showSubscriptions();


    }

    function showSubscriptions() {
        // alert("empty");
        $("#pay_board").empty();
        var total_monthly_charge = 0;
        remain_amt = 0;
        for (var i in subscription_list) {
            // alert(i);
            curr_service = subscription_list[i];
            var curr_remain_amt = (curr_service.total_amt_to_paid - curr_service.paid_amt);
            remain_amt = remain_amt + curr_remain_amt;

            $("#pay_board").append("\
            <div class='row' >\
                    <div class='col-md-10' style='background: #fff;padding-bottom:10px;border-bottom-width:2px;border-bottom-color:grey;border-bottom-style: solid;'>\
                        <h3> "+curr_service.subscription_name+"</h3>\
                        <h5> Total :"+curr_service.total_amt_to_paid+" &nbsp&nbsp&nbsp&nbsp Paid : "+curr_service.paid_amt+" &nbsp&nbsp&nbsp&nbsp To paid : "+curr_remain_amt+"</h5>\
                        <!--a href='event.html?event_id=" + curr_service.id + "'><button type='button' class='btn btn-primary'>View</button></a-->\
                    </div>\
                 </div>\
                 ");


            total_monthly_charge = total_monthly_charge + parseInt(curr_service['monthly_charge']);
        }

        $("#pay_board").append("\
            <div class='row' >\
                    <div class='col-md-10' style='background: #fff;padding-bottom:10px;'>\
                        <h3> Total amount To paid =  "+remain_amt+"</h3>\
                        <!--button type='button' class='btn btn-primary' id='payment_btn'>Pay</button-->\
                    </div>\
                 </div>\
                 ");

    }


    $("#update_user").on('submit', function() {
        // alert("btn click");
        fd = getFormDataUpdateUser();

        stepSubmit(fd, api_link + "/api/user/update", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                // alert("inside success");

                var u_id = return_response[1].success.data.id;
                // $("div[name='loader']").hide();
                // set_session(u_id);
                $("i[name='btn_loader']").removeClass();
                // location.href = fe_link+"/users.html";
                location.reload();

            }

        });

    });

    $("#remove_user_btn").on('click', function() {

        // alert("remove_user_btn");
        swal({
            title: "Delete User",
            // text: "You won't be able to revert this!",
            // type: 'warning',
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: '#41B314',
            cancelButtonColor: '#F9354C',
            confirmButtonText: "Yes Delete it!"
        }).then(function() {
            fd = getFormDataDeleteUser();

            stepSubmit(fd, api_link + "/api/user/delete", function(return_response) {

                console.log(return_response[0]);

                if (return_response[0] == "success") {
                    swal(
                        "Successfull",
                        "User deleted Successfully",
                        'success'
                    ).then(function() {
                        window.location = fe_link + "/users.html"
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


    $("#user_type").on('change', function() {

        // alert(user_type.value);
        if (user_type.value == "Admin") {
            $('input[type=checkbox]').prop('checked', true);
        } else {
            // alert("else");
            // alert(user_accessDetailLoadtype.value);
            $('input[type=checkbox]').prop('checked', false);
            
        }



    });



    $('#password, #renter_new_pswd').on('keyup', function() {

        if ($('#password').val() != "" || $('#renter_new_pswd').val() != "") {

            if ($('#password').val() == $('#renter_new_pswd').val()) {
                $('#message').html('Matching').css('color', 'green');
            } else {
                $('#message').html('Not Matching').css('color', 'red');
            }
        } else {
            $('#message').html('Please enter the value').css('color', 'red');
        }
    });



    function onLoadShowUser() {
        // alert("btn click");
        fd = getFormDataShowUser();
        // alert(user_req_id);


        stepSubmit(fd, api_link + "/api/user/show", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.id;
                users_array = return_response[1].success.data;
                console.log(users_array);
                showUser(users_array);
                // userAcess();



            }
        });


    }



    $("#edit_btn").on('change', function() {

        if ($("#edit_btn").is(':checked')) {
            $("#update_user :input").prop("disabled", false);
        } else {
            $("#update_user :input").prop("disabled", true);
            $("#edit_btn").prop("disabled", false);
        }

    });

    function showUser(users_array) {
        // body...
        console.log(users_array);
        users_array = JSON.stringify(users_array).replace(/null/i, "\"\"");
        console.log(users_array);
        users_array = JSON.parse(users_array);

        users_array_g = users_array;


        // console.log(users_array.full_name);
        $("#user_type").val(users_array.user_type);
        // $("#user_name").val(users_array.user_name);
        $("#first_name").val(users_array.first_name);
        $("#last_name").val(users_array.last_name);
        $("#mobile").val(users_array.mobile);
        $("#email").val(users_array.email);
        $("#wing_name").val(users_array.wing_name);
        $("#flat_no").val(users_array.flat_no);
        // alert(users_array.wing_name);

        $("#user_name_c").val(users_array.user_name);

        

        
    }

    


    function getFormDataShowUser() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('user_req_id', user_req_id);
        return fd;
    }

    function getFormDataUpdateUserPass() {
        var fd = new FormData();
        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('user_req_id', user_req_id);
        fd.append('password', $("#password").val());
        return fd;
    }

    function getFormDataUpdateUser() {
        var fd = new FormData();

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('user_req_id', user_req_id);


        fd.append('user_type', $('#user_type').val());
        fd.append('user_name', $('#user_name_c').val());
        fd.append('first_name', $('#first_name').val());
        fd.append('last_name', $('#last_name').val());
        fd.append('mobile', $('#mobile').val());
        fd.append('email', $('#email').val());

        fd.append('wing_name', $('#wing_name').val());
        fd.append('flat_no', $('#flat_no').val());
        // fd.append('password',JSON.stringify(password));


        return fd;
    }

    
    $("#chge_curr_pswd").on('submit', function() {
        // alert("btn click");
        fd = getFormDataUpdateUserPass();

        stepSubmit(fd, api_link + "/api/user/update", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                // alert("inside success");

                var u_id = return_response[1].success.data.id;
                // $("div[name='loader']").hide();
                // set_session(u_id);
                $("i[name='btn_loader']").removeClass();
                // location.href = fe_link+"/users.html";
                location.reload();

            }

        });

    });


    function getFormDataDeleteUser() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('user_req_id', user_req_id);

        return fd;
    }

    function getFormDataShowSociety() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('society_id', society_id);
        return fd;
    }

    function getFormDataShowMaintenance() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', user_req_id);
        fd.append('maintenance_id', 1);
        return fd;
    }

    



});