// var api_link = "http://localhost/residential_society_management";
var api_link = "http://137.116.117.172/rsm"

// var fe_link = "http://localhost/residential_society_management/html";
var fe_link = "http://137.116.117.172/rsm";

function stepSubmit(formData, api_url, callback) {

    console.log(formData);
    var return_response = [];
    $.ajax({
        type: "POST",
        url: api_url,
        contentType: false,
        processData: false,
        data: formData,
        xhrFields: {
            withCredentials: true
        },
        success: function(response) {
            if (response.success) {

                return_response[0] = "success";
                return_response[1] = response;
                // return return_response;
                callback(return_response);

            } else if (response.error) {
                return_response[0] = "error";
                return_response[1] = response;
                // return return_response;
                $("div[name='loader']").hide();
                $("i[name='btn_loader']").removeClass();
                // alert("loader ended");
                // swal(
                //     'Error!',
                //     response.error.message,
                //     'error'
                // ).catch(swal.noop);
                callback(return_response);

            };

        },
        error: function(error) {

            return_response[0] = "error";
            return_response[1] = error;
            $("div[name='loader']").hide();
            $("i[name='btn_loader']").removeClass();
            // return return_response;
            callback(return_response);
        }
    });
}