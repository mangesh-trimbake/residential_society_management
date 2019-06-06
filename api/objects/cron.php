





<?php

//  to cron
// crontab -e

// 0 0 * * * /usr/bin/php api/objects/cron.php

// echo "user.php";

$pro_dir = "/var/www/html/residential_society_management";

include_once $pro_dir.'/api/config/database.php';

include_once $pro_dir.'/api/objects/service.php';
include_once $pro_dir.'/api/objects/subscriptions.php';
include_once $pro_dir.'/api/objects/maintenance.php';




$database = new Database();
$db = $database->getConnection();

$maintenance = new Maintenance($db);
$subscription = new Subscription($db);

class MothlyCron{
 
    // database connection and table name
    private $conn;
    private $table_name = "user";

    

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // create user
    function create(){
        // echo "create function";
        // sanitize
        global $maintenance;
        global $subscription;

        $maintenance_arr = $maintenance->show_self_id(1);

        // print_r($maintenance_arr);

        $query = "SELECT user_id FROM 
                    " . $this->table_name . ";";           
        try{

            // prepare query
            $stmt = $this->conn->prepare($query);

                
            $stmt->execute();
            $rows_arr=array();
            // $users_arr["records"]=array();
         
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
         
                $row_subscription=array(
                    "user_id" => $user_id
                    
                );
         
                array_push($rows_arr, $row_subscription);
            }


            print_r($rows_arr);



            foreach ($rows_arr as $key => $val)
            {
                // print_r($val);
                $user_id_c = $val['user_id'];
               

                $subscription_flag = 0;
                
                $subscription_response = $subscription->createbydict($maintenance_arr,$user_id_c);
                // print_r($response);
                if($subscription_response == "mysql_error" || $subscription_response == "try_catch_error"){
                    $subscription_flag = 1;
                }
                
                if($subscription_flag == 1){
                    # code...
                    print_r("error");
                }
                else {
                    // code...
                    print_r("success");
                }
                




            }

            // execute query
            // if(){

            //     // $result = $stmt->lastInsertId();
            //     $result = $this->conn->lastInsertId();
            //     // $next = time()  + (60*60*24);
            //     // setcookie("cart_user", $email, $next,"/");
            //     $user_id_c = $result;
            //     $subscription_flag = 0;
                
            //     $subscription_response = $subscription->createbydict($maintenance_arr,$user_id_c);
            //     // print_r($response);
            //     if($subscription_response == "mysql_error" || $subscription_response == "try_catch_error"){
            //         $subscription_flag = 1;
            //     }
                
            //     if($subscription_flag == 1){
            //         # code...
            //         return json_encode(
            //                 array(
            //                     "error" => array(
            //                         "code" => "503",
            //                         "message" => "Something went wrong",
            //                         "data" => "id"
            //                         )
            //                     )
            //             );
            //     }
            //     else {
            //         // code...
            //         return json_encode(
            //                     array(
            //                         "success" => array(
            //                             "code" => "201",
            //                             "message" => "User was created.",
            //                             "data" => array(
            //                                 "id" => $result
            //                                 )
            //                             )
            //                         )
            //                 );
            //     }
                
            // }
            // else{
            //     return json_encode(
            //                 array(
            //                     "error" => array(
            //                         "code" => "503",
            //                         "message" => "Database Service unavailble",
            //                         "data" => "id"
            //                         )
            //                     )
            //             );

            // }
        }
        catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
                // return json_encode(
                //             array(
                //                 "error" => array(
                //                     "code" => "503",
                //                     "message" =>  $e->getMessage(),
                //                     "data" => "id"
                //                     )
                //                 )
                //         );
        }

         
    }

    

}






$cr = new MothlyCron($db);
$cr->create();



?>