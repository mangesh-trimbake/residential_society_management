    $(document).ready(function() {

    var url_string = window.location.href;
    var url = new URL(url_string);
    var society_id = 1;

    var user_type = sessionStorage.getItem("user_type");

    var wing_list = [];
    var wing_list_added = [];
    var wing_list_updated = [];
    var wing_list_deleted = [];

    

    onLoadGetMaster(function(master_dict) {

        console.log(master_dict);
        loadMasterValue(master_dict);
        onLoadShowSociety();

    });



    $("#update_society").on('submit', function() {
        // alert("btn click");
        fd = getFormDataUpdateSociety();

        stepSubmit(fd, api_link + "/api/society/update", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                // alert("inside success");

                var u_id = return_response[1].success.data.id;
                // $("div[name='loader']").hide();
                // set_session(u_id);
                $("i[name='btn_loader']").removeClass();
                // location.href = fe_link+"/society.html";
                location.reload();

            }

        });

    });

    $("#remove_society_btn").on('click', function() {

        // alert("remove_society_btn");
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

            stepSubmit(fd, api_link + "/api/society/delete", function(return_response) {

                console.log(return_response[0]);

                if (return_response[0] == "success") {
                    swal(
                        "Successfull",
                        "Society deleted Successfully",
                        'success'
                    ).then(function() {
                        window.location = fe_link + "/society.html"
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

        stepSubmit(fd, api_link + "/api/society/show", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.id;
                society_array = return_response[1].success.data;

                console.log(society_array);

                showSociety(society_array);
                societyAcess();


            }

        });
        // $("#update_society :input").prop("disabled", true); 
        // $("#edit_btn").prop("disabled", false); 
    }



    $("#edit_btn").on('change', function() {

        if ($("#edit_btn").is(':checked')) {
            $("#update_society :input").prop("disabled", false);
        } else {
            $("#update_society :input").prop("disabled", true);
            $("#edit_btn").prop("disabled", false);
        }

    });

    function showSociety(society_array) {
        // body...
        console.log(society_array);
        society_array = JSON.stringify(society_array).replace(/null/i, "\"\"");
        console.log(society_array);
        society_array = JSON.parse(society_array);

        console.log(society_array);

        $("#society_name").val(society_array.society_name);

        $("#society_short_name").val(society_array.society_short_name);
        $("#Address").val(society_array.Address);

        $("#city").val(society_array.city);
        $("#state").val(society_array.state);

        $("#country").val(society_array.country);

        $("#description").val(society_array.description);

        wing_list = society_array["wing_list"];

        showWingList();


    }

    function getFormDataShowSociety() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('society_id', society_id);
        return fd;
    }

    function getFormDataUpdateSociety() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('society_id', society_id);

        fd.append('society_name', $('#society_name').val());
        fd.append('society_short_name', $('#society_short_name').val());
        fd.append('Address', $('#Address').val());

        fd.append('city', $('#city').val());

        fd.append('state', $('#state').val());
        fd.append('country', $('#country').val());
        fd.append('description', $('#description').val());

        fd.append('wing_list_updated', JSON.stringify(wing_list_updated));
        
        fd.append('wing_list_added', JSON.stringify(wing_list_added));
        
        fd.append('wing_list_deleted', JSON.stringify(wing_list_deleted));
        

        return fd;
    }



    function getFormDataDeleteSociety() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('society_id', 1);

        return fd;
    }




    $("#add_wing_form").on('submit', function() {
        // alert("submit");
        var wing_detail = {};
        wing_detail["wing_name"] = $("#wing_name").val();
        wing_detail["remark"] = $("#remark").val();

        wing_list.push(wing_detail);
        wing_list_added.push(wing_detail);
        showWingList();
        $('#modalWingForm').modal('hide');

    });

    


    $("#update_wing_form").on('submit', function() {
        // alert($(this).val());


        // var wing_detail = {};
        wing_list[$(this).val()]["wing_name"] = $("#wing_name_u").val();
        wing_list[$(this).val()]["remark"] = $("#remark_u").val();
        // wing_list[$(this).val()] = wing_detail;

        wing_list_updated.push(wing_list[$(this).val()]);

        showWingList();

        $('#modalWingUpdateForm').modal('hide');

    });

    
    




    function showWingList() {
        // alert("empty");
        $("#wing_list").empty();
        for (var i in wing_list) {
            // alert(i);
            curr_wing = wing_list[i];
            $("#wing_list").append("\
                 <tr>\
                    <td>" + curr_wing['wing_name'] + "</td>\
                    <td>" + curr_wing['remark'] + "</td>\
                    <td><button type='button' id='wing_update_" + curr_wing['id'] + "' value=" + i + " name='update_wing_btn' >update</button></td>\
                    </tr>\
                ");


        }

    }

    

    $("body").on("click", "button[id='remove_wing_btn']", function() {

        wing_list_deleted.push(wing_list[$(this).val()]);
        wing_list.splice($(this).val(), 1)
        showWingList();
        $('#modalWingUpdateForm').modal('hide');

    });

    $("body").on("click", "button[name='update_wing_btn']", function() {

        // wing_list.splice($(this).val(), 1)
        // showWingList();
        var wing_n = $(this).attr("id").split("_");
        console.log(wing_n);
        // alert(wing_n[2]);
        // alert($(this).val());
        loadWingUpdate(wing_n[2]);
        $("#modalWingUpdateForm").modal('show');

    });



    

    


    function loadWingUpdate(wing_no) {

        for (var i in wing_list) {
            curr_wing = wing_list[i];
            if (curr_wing["id"] == wing_no) {
                // alert(curr_wing["category"]);

                $("#update_wing_form").val(i);
                $("#remove_wing_btn").val(i);

                $("#wing_name_u").val(curr_wing["wing_name"]);
                $("#remark_u").val(curr_wing["remark"]);
            }
        }

    }

    function societyAcess() {

        if(user_type == "member"){

            $("button[data-toggle='modal']").hide();
            $("#update_society_btn").hide();
            $("#remove_society_btn").hide();
            $("button[name='update_wing_btn']").attr("disabled",true);
        }
        // body...
    }
    
    

    



});