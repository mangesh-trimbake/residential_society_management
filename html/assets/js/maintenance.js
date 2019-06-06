    $(document).ready(function() {

    var url_string = window.location.href;
    var url = new URL(url_string);
    var maintenance_id = 1;

    var user_type = sessionStorage.getItem("user_type");

    var service_list = [];
    var service_list_added = [];
    var service_list_updated = [];
    var service_list_deleted = [];

    var subscription_list = [];

    var remain_amt;

    

    onLoadGetMaster(function(master_dict) {

        console.log(master_dict);
        loadMasterValue(master_dict);
        onLoadShowSociety();

    });



    $("#update_maintenance").on('submit', function() {
        // alert("btn click");
        fd = getFormDataUpdateSociety();

        stepSubmit(fd, api_link + "/api/maintenance/update", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                // alert("inside success");

                var u_id = return_response[1].success.data.id;
                // $("div[name='loader']").hide();
                // set_session(u_id);
                $("i[name='btn_loader']").removeClass();
                // location.href = fe_link+"/maintenance.html";
                location.reload();

            }

        });

    });

    $("#remove_maintenance_btn").on('click', function() {

        // alert("remove_maintenance_btn");
        swal({
            title: "Delete Society",
            // text: "You won't be able to revert this!",
            // type: 'warning',
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: '#41B314',
            cancelButtonColor: '#F9354C',
            confirmButtonText: "Yes Delete it!"
        }).then(function() {
            fd = getFormDataDeleteSociety();

            stepSubmit(fd, api_link + "/api/maintenance/delete", function(return_response) {

                console.log(return_response[0]);

                if (return_response[0] == "success") {
                    swal(
                        "Successfull",
                        "Society deleted Successfully",
                        'success'
                    ).then(function() {
                        window.location = fe_link + "/maintenance.html"
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




    function onLoadShowSociety() {
        // alert("btn click");
        fd = getFormDataShowSociety();

        stepSubmit(fd, api_link + "/api/maintenance/show", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.id;
                maintenance_array = return_response[1].success.data;

                console.log(maintenance_array);

                showSociety(maintenance_array);
                maintenanceAcess();


            }

        });
        // $("#update_maintenance :input").prop("disabled", true); 
        // $("#edit_btn").prop("disabled", false); 
    }



    $("#edit_btn").on('change', function() {

        if ($("#edit_btn").is(':checked')) {
            $("#update_maintenance :input").prop("disabled", false);
        } else {
            $("#update_maintenance :input").prop("disabled", true);
            $("#edit_btn").prop("disabled", false);
        }

    });

    function showSociety(maintenance_array) {
        // body...
        console.log(maintenance_array);
        maintenance_array = JSON.stringify(maintenance_array).replace(/null/i, "\"\"");
        console.log(maintenance_array);
        maintenance_array = JSON.parse(maintenance_array);

        console.log(maintenance_array);

        $("#maintenance_name").val(maintenance_array.maintenance_name);

        $("#maintenance_short_name").val(maintenance_array.maintenance_short_name);
        $("#Address").val(maintenance_array.Address);

        $("#city").val(maintenance_array.city);
        $("#state").val(maintenance_array.state);

        $("#country").val(maintenance_array.country);

        $("#description").val(maintenance_array.description);

        service_list = maintenance_array["service_list"];
        subscription_list = maintenance_array["subscription_list"];

        showServiceList();
        showSubscriptions();


    }

    function getFormDataShowSociety() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('maintenance_id', maintenance_id);
        return fd;
    }
    

    function getFormDataUpdateSociety() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('maintenance_id', maintenance_id);


        fd.append('service_list_updated', JSON.stringify(service_list_updated));
        
        fd.append('service_list_added', JSON.stringify(service_list_added));
        
        fd.append('service_list_deleted', JSON.stringify(service_list_deleted));
        

        return fd;
    }



    function getFormDataDeleteSociety() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('maintenance_id', 1);

        return fd;
    }

    function getFormDataPayment() {
        var fd = new FormData();
        // Personal Details
        console.log(subscription_list);
        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('maintenance_id', maintenance_id);
        fd.append('wallet_amt', sessionStorage.getItem("wallet_amt"));
        fd.append('subscription_list',JSON.stringify(subscription_list));
        fd.append('remain_amt',$("#remain_amt").val())
        fd.append('enter_amount',$("#enter_amount").val())
        fd.append('wallet_chbx',$("#wallet_chbx").is(":checked") ? 1 : 0)
        // alert($("#wallet_chbx").is(":checked") ? 1 : 0);
        return fd;
    }



    $("#add_service_form").on('submit', function() {
        // alert("submit");
        var service_detail = {};
        service_detail["service_name"] = $("#service_name").val();
        service_detail["monthly_charge"] = $("#monthly_charge").val();
        service_detail["remark"] = $("#remark").val();

        service_list.push(service_detail);
        service_list_added.push(service_detail);
        showServiceList();
        $('#modalServiceForm').modal('hide');

    });

    


    $("#update_service_form").on('submit', function() {
        // alert($(this).val());


        // var service_detail = {};
        service_list[$(this).val()]["service_name"] = $("#service_name_u").val();
        service_list[$(this).val()]["monthly_charge"] = $("#monthly_charge_u").val();
        service_list[$(this).val()]["remark"] = $("#remark_u").val();

        // service_list[$(this).val()] = service_detail;

        service_list_updated.push(service_list[$(this).val()]);

        showServiceList();

        $('#modalServiceUpdateForm').modal('hide');

    });

    $("#payment_form").on('submit', function() {
        // alert($(this).val());


        fd = getFormDataPayment();

        stepSubmit(fd, api_link + "/api/payment/add", function(return_response) {

            console.log(return_response);

            if (return_response[0] == "success") {
                // alert("inside success");

                var wallet_amt = return_response[1].success.data.wallet_amt;
                // $("div[name='loader']").hide();
                // set_session(u_id);
                // $("i[name='btn_loader']").removeClass();
                // location.href = fe_link+"/maintenance.html";
                // alert(wallet_amt);
                sessionStorage.setItem("wallet_amt", wallet_amt);
                location.reload();

            }

        });      

        

        $('#modalPaymentForm').modal('hide');

    });

    




    function showServiceList() {
        // alert("empty");
        $("#service_list").empty();
        var total_monthly_charge = 0;
        for (var i in service_list) {
            // alert(i);
            curr_service = service_list[i];
            $("#service_list").append("\
                 <tr>\
                    <td>" + curr_service['service_name'] + "</td>\
                    \
                    <td>" + curr_service['monthly_charge'] + "</td>\
                    <td>" + curr_service['remark'] + "</td>\
                    <td><button type='button' id='service_update_" + curr_service['id'] + "' value=" + i + " name='update_service_btn' >update</button></td>\
                    </tr>\
                ");


            total_monthly_charge = total_monthly_charge + parseInt(curr_service['monthly_charge']);
        }

        $("#service_list").append("\
                 <tr>\
                    <td>Total monthly charge</td>\
                    \
                    <td>" + total_monthly_charge + "</td>\
                    <td></td>\
                    </tr>\
                ");

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
                        <button type='button' class='btn btn-primary' id='payment_btn'>Pay</button>\
                    </div>\
                 </div>\
                 ");

    }





    

    $("body").on("click", "button[id='remove_service_btn']", function() {

        service_list_deleted.push(service_list[$(this).val()]);
        service_list.splice($(this).val(), 1)
        showServiceList();
        $('#modalServiceUpdateForm').modal('hide');

    });

    $("body").on("click", "button[name='update_service_btn']", function() {

        // service_list.splice($(this).val(), 1)
        // showServiceList();
        var service_n = $(this).attr("id").split("_");
        console.log(service_n);
        // alert(service_n[2]);
        // alert($(this).val());
        loadServiceUpdate(service_n[2]);
        $("#modalServiceUpdateForm").modal('show');

    });

    $("body").on("click", "button[id='payment_btn']", function() {

        // alert("payment_btn");
        $("#remain_amt").val(remain_amt);
        $("#wallet_amt_u").val(sessionStorage.getItem("wallet_amt"));

        $("#modalPaymentForm").modal('show');

    });

    $("body").on("change", "input[id='wallet_chbx']", function() {

        // alert("payment_btn");
        if(sessionStorage.getItem("wallet_amt") == 0){
            alert("dont have balance");
            $("#wallet_chbx").attr("checked",false);
        }

    });



    


    function loadServiceUpdate(service_no) {

        for (var i in service_list) {
            curr_service = service_list[i];
            if (curr_service["id"] == service_no) {
                // alert(curr_service["category"]);

                $("#update_service_form").val(i);
                $("#remove_service_btn").val(i);

                $("#service_name_u").val(curr_service["service_name"]);
                $("#monthly_charge_u").val(curr_service["monthly_charge"]);
                $("#remark_u").val(curr_service["remark"]);
            }
        }

    }

    function maintenanceAcess() {

        if(user_type == "member"){

            $("button[data-toggle='modal']").hide();
            $("#update_maintenance_btn").hide();
            $("#remove_maintenance_btn").hide();
            $("button[name='update_service_btn']").attr("disabled",true);
        }
    }

    

    



});