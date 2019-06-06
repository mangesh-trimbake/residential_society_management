$(document).ready(function() {


    var addr_id;
    var users_array;
    var user_type = sessionStorage.getItem("user_type");

    function onLoadShowAllUser() {
        // alert("btn click");
        fd = getFormDataAddToUser();

        stepSubmit(fd, api_link + "/api/user/showall", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.id;
                users_array = return_response[1].success.data;

                console.log(users_array);

                showAllUsers(users_array);

                userAcess();

            }

        });

    }

    onLoadShowAllUser();



    function getFormDataAddToUser() {
        var fd = new FormData();
        // Personal Details
        // fd.append('product_id', 1);
        fd.append('user_id', sessionStorage.getItem("user_id"));
        return fd;
    }


    function showAllUsers(users_array) {
        // body...
        $("#user_list").empty();

        var subtotal = 0;


        users_array = JSON.stringify(users_array).replace(/null/i, "\"\"");
        // alert(users_array);
        users_array = JSON.parse(users_array);

        for (i in users_array) {

            var user_itme = users_array[i]
            var total_weight = 0;
            console.log(user_itme);
            

            $("#user_list").append("\
                        <tr>\
                            <td>" + user_itme.user_id + "</td>\
                            <td>" + user_itme.user_type + "</td>\
                            <td>" + user_itme.user_name + "</td>\
                            <td>" + user_itme.mobile + "</td>\
                            <td>" + user_itme.email + "</td>\
                            <td><a href='user.html?user_id=" + user_itme.user_id + "'><button>View</button></a></td>\
                        </tr>\
                ");


        }
        

    }

    
    function getFormDataCreateUser(user_itme) {

        var fd = new FormData();

        fd.append('user_id', sessionStorage.getItem("user_id"));
        fd.append('product_id', user_itme.product_id);
        fd.append('address', addr_id);
        fd.append('price', user_itme.price);
        fd.append('shiping_charge', "0");
        fd.append('total_price', user_itme.price);

        return fd;


    }


    function userAcess() {
        
        if(user_type == "member"){

            $("#add_user").hide();
        }
    }




});