$(document).ready(function() {

    var url_string = window.location.href;
    var url = new URL(url_string);
    var master_disp_text = url.searchParams.get("master");

    var master_entity_list = [];
    var master_entity_list_added = [];
    var master_entity_list_updated = [];
    var master_entity_list_deleted = [];



    $("#update_master").on('submit', function() {
        // alert("btn click");
        fd = getFormDataUpdateMaster();

        stepSubmit(fd, api_link + "/api/master/update", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                // alert("inside success");

                var u_id = return_response[1].success.data.id;
                // $("div[name='loader']").hide();
                // set_session(u_id);
                $("i[name='btn_loader']").removeClass();
                // location.href = fe_link+"/master.html";
                location.reload();

            }

        });

    });




    function onLoadShowMaster() {
        // alert("btn click");
        fd = getFormDataShowMaster();

        stepSubmit(fd, api_link + "/api/master/show", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.id;
                master_array = return_response[1].success.data;

                console.log(master_array);

                master_entity_list = master_array;
                showMasterEntityList();
                loadmasterdiv();


            }

        });
    }

    onLoadShowMaster();

    $("#edit_btn").on('change', function() {

        if ($("#edit_btn").is(':checked')) {
            $("#update_master :input").prop("disabled", false);
        } else {
            $("#update_master :input").prop("disabled", true);
            $("#edit_btn").prop("disabled", false);
        }

    });



    function getFormDataShowMaster() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('master_disp_text', master_disp_text);
        return fd;
    }

    function getFormDataUpdateMaster() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('master_disp_text', master_disp_text);

        fd.append('master_entity_list_updated', JSON.stringify(master_entity_list_updated));

        fd.append('master_entity_list_added', JSON.stringify(master_entity_list_added));

        fd.append('master_entity_list_deleted', JSON.stringify(master_entity_list_deleted));



        return fd;
    }

    function getFormDataDeleteMaster() {
        var fd = new FormData();
        // Personal Details

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('master_id', master_id);

        return fd;
    }




    $("#add_master_entity_form").on('submit', function() {
        // alert("submit");
        var item_detail = {};
        item_detail["disp_text"] = $("#disp_text").val();
        item_detail["description"] = $("#description").val();

        master_entity_list.push(item_detail);
        master_entity_list_added.push(item_detail);
        showMasterEntityList();
        $('#modalMasterEntityForm').modal('hide');

    });



    $("#update_master_entity_form").on('submit', function() {
        // alert($(this).val());


        // var item_detail = {};
        master_entity_list[$(this).val()]["disp_text"] = $("#disp_text_u").val();
        master_entity_list[$(this).val()]["description"] = $("#description_u").val();

        // master_entity_list[$(this).val()] = item_detail;

        master_entity_list_updated.push(master_entity_list[$(this).val()]);

        showMasterEntityList();

        $('#modalMasterEntityUpdateForm').modal('hide');

    });




    function showMasterEntityList() {
        // alert("empty");
        $("#master_entity_list").empty();
        for (var i in master_entity_list) {
            // alert(i);
            curr_item = master_entity_list[i];
            $("#master_entity_list").append("\
                 <tr>\
                    <td>" + curr_item['disp_text'] + "</td>\
                    <td>" + curr_item['description'] + "</td>\
                    <td><button type='button' id='" + curr_item['item_no'] + "' value='" + curr_item['disp_text'] + "' name='update_master_entity_btn' >update</button></td>\
                    </tr>\
                ");


        }

    }


    $("body").on("click", "button[id='remove_master_entity_btn']", function() {

        master_entity_list_deleted.push(master_entity_list[$(this).val()]);
        master_entity_list.splice($(this).val(), 1)
        showMasterEntityList();
        $('#modalMasterEntityUpdateForm').modal('hide');

    });

    $("body").on("click", "button[name='update_master_entity_btn']", function() {

        loadMasterEntityUpdate($(this).val());
        $("#modalMasterEntityUpdateForm").modal('show');

    });


    function loadMasterEntityUpdate(disp_text) {

        for (var i in master_entity_list) {
            curr_item = master_entity_list[i];
            if (curr_item["disp_text"] == disp_text) {
                // alert(curr_item["category"]);

                $("#update_master_entity_form").val(i);
                $("#remove_master_entity_btn").val(i);

                $("#disp_text_u").val(curr_item["disp_text"]);
                $("#description_u").val(curr_item["description"]);
                //     $("#category_u").val(curr_item["category"]);
                //     $("#approx_quantity_u").val(curr_item["approx_quantity"]);
                //     $("#approx_weight_u").val(curr_item["approx_weight"]);
            }
        }

    }


    function loadmasterdiv() {
        if (sessionStorage.getItem("user_type") == "User") {
            // $('#load_masters_table').hide();
            $('button[data-target="#modalMasterEntityForm"]').hide();
            $('#update_master_btn').hide();
            // alert("hiii");
            // $('button[name=""]').attr("disabled", true);
            $('button[name=update_master_entity_btn]').attr("disabled", true);

        } else {
            $('button[data-target="#modalMasterEntityForm"]').show();
            $('#update_master_btn').show();
            // $('button[name=update_dispatch_btn]').removeAttr("disabled");
            $('button[name=update_master_entity_btn]').removeAttr("disabled");
        }

    }


});