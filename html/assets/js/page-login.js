$(document).ready(function() {
    // alert("login.js")




    $('#customer_login').on('submit', function() {

        // alert("submit");
        $("i[name='btn_loader']").addClass("fa fa-spinner fa-spin");


        var fd = new FormData();

        fd = getFormDataRegister();

        stepSubmit(fd, api_link + "/api/user/login", function(return_response) {

            console.log(return_response[0]);

            if (return_response[0] == "success") {
                var u_id = return_response[1].success.data.user_id;
                // $("div[name='loader']").hide();
                set_session("user_id", u_id);
                // set_session("order_acess", return_response[1].success.data.order_acess);
                // set_session("purchase_order_acess", return_response[1].success.data.purchase_order_acess);
                // set_session("user_acess", return_response[1].success.data.user_acess);
                set_session("user_type", return_response[1].success.data.user_type);
                set_session("user_name", return_response[1].success.data.user_name);
                set_session("wallet_amt", return_response[1].success.data.wallet_amt);
                $("i[name='btn_loader']").removeClass();
                location.href = fe_link + "/index.html";

            }
            else{

                alert("User or password wrong");
            }


        });


    });

    function getFormDataRegister() {
        var fd = new FormData();
        // Personal Details
        fd.append('email', $("#signin-email").val());
        fd.append('password', $("#signin-password").val());
        return fd;
    }


    function set_session(key, value) {
        // alert(user_type);
        sessionStorage.setItem(key, value);

    }




});