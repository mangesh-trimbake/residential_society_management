$(document).ready(function() {

    $('#user_name').html(sessionStorage.getItem("user_name").split(" ")[0]);

    if(sessionStorage.getItem("user_type") == "member"){

        $("#user_menu").hide();
    }

    $('#logout_btn').on('click', function() {

        // alert("submit");
        $("i[name='btn_loader']").addClass("fa fa-spinner fa-spin");

        // alert("login.js");
        var fd = new FormData();

        // fd = getFormDataRegister(); 

        stepSubmit(fd, api_link + "/api/user/logout", function(return_response) {
            // alert("login.js")
            console.log(return_response[0]);

            if (return_response[0] == "success") {
                sessionStorage.clear();
                location.reload();

            }

        });


    });




});