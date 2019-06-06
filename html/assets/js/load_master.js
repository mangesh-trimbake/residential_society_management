function onLoadGetMaster(callback) {
    // alert("onLoadGetMaster");
    var fd = new FormData();

    fd.append('user_id', sessionStorage.getItem("user_id"));

    stepSubmit(fd, api_link + "/api/master/showe", function(return_response) {

        console.log(return_response[0]);

        if (return_response[0] == "success") {
            var master_dict = return_response[1].success.data;

            console.log(master_dict);
            callback(master_dict);


        }

    });

}



function loadMasterValue(master_dict) {
    console.log(master_dict);


    
}

function loadSelectOption(master_dict, id, dic_id) {


    



}