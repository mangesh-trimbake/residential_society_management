$(document).ready(function() {


    var addr_id;
    var master_array;

    function onLoadShowAllMaster() {
        // alert("btn click");
        fd = getFormDataAddToMaster();

        stepSubmit(fd, api_link + "/api/master/showall", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.id;
                master_array = return_response[1].success.data;

                console.log(master_array);

                showAllMaster(master_array);


            }

        });

    }

    onLoadShowAllMaster();



    function getFormDataAddToMaster() {
        var fd = new FormData();
        // Personal Details
        // fd.append('product_id', 1);
        fd.append('user_id', sessionStorage.getItem("user_id"));
        return fd;
    }




    function showAllMaster(master_array) {
        // body...
        $("#master_list").empty();

        var subtotal = 0;


        master_array = JSON.stringify(master_array).replace(/null/i, "\"\"");
        // alert(master_array);
        master_array = JSON.parse(master_array);

        for (i in master_array) {

            var master_itme = master_array[i]
            var total_weight = 0;
            console.log(master_itme);
            for (var i in master_itme["item_list"]) {
                total_weight = total_weight + parseInt(master_itme["item_list"][i]["approx_weight"]);
            }

            $("#master_list").append("\
                        <tr>\
                            <td>" + master_itme.disp_text + "</td>\
                            <td>" + master_itme.description + "</td>\
                            <td><a href='master.html?master=" + master_itme.disp_text + "'><button>View</button></a></td>\
                        </tr>\
                ");

            // subtotal = subtotal + parseInt(master_itme.price);

        }
        // console.log("subtotal");
        // console.log(subtotal);
        // $("#subtotal").html("$"+subtotal+"");

    }

    $("#masterform").on('submit', function() {

        fd = getFormDataCheckout();

        stepSubmit(fd, "http://master.com/api/address/add", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                addr_id = return_response[1].success.data.id;
                // var master_array = return_response[1].success.data;

                console.log(addr_id);

                for (i in master_array) {
                    console.log(i);
                    console.log(master_array[i]);

                    createMaster(master_array[i]);
                }



            }

        });

    });

    function createMaster(master_itme) {

        fd = getFormDataCreateMaster(master_itme);

        console.log("master detail", fd);
        stepSubmit(fd, "http://master.com/api/inventory/create", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var master_id = return_response[1].success.data.id;
                // var master_array = return_response[1].success.data;

                console.log(master_id);

                // showAllMaster(master_array);


            }

        });

    }

    function getFormDataCheckout() {
        var fd = new FormData();
        // Personal Details
        fd.append('user_id', sessionStorage.getItem("user_id"));

        fd.append('full_name', $("#customer_full_name").val());
        fd.append('phone', $("#customer_phone").val());
        fd.append('country', $("#customer_country").val());
        fd.append('state', $("#customer_state").val());
        fd.append('city', $("#customer_city").val());
        fd.append('phone', $("#customer_phone").val());
        fd.append('address', $("#customer_address").val());
        fd.append('landmark', $("#customer_landmark").val());
        fd.append('pincode', $("#customer_pincode").val());
        fd.append('address_type', $("#customer_address_type").val());

        return fd;
    }

    function getFormDataCreateMaster(master_itme) {

        var fd = new FormData();

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('product_id', master_itme.product_id);
        fd.append('address', addr_id);
        fd.append('price', master_itme.price);
        fd.append('shiping_charge', "0");
        fd.append('total_price', master_itme.price);

        return fd;


    }


});