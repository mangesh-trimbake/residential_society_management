<?php
// echo "maintenance.php";


$database = new Database();
$db = $database->getConnection();
 
$service = new Service($db);
$subscription = new Subscription($db);




class Maintenance{
 
    // database connection and table name
    private $conn;
    private $table_name = "maintenance";

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }


    // create maintenance
    function create(){
        // echo "create function";
        // sanitize
        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));

        $maintenance_date=htmlspecialchars(strip_tags($_POST['maintenance_date']));
        $maintenance_type=htmlspecialchars(strip_tags($_POST['maintenance_type']));
        $client_name=htmlspecialchars(strip_tags($_POST['client_name']));
        $other_refrance_no=htmlspecialchars(strip_tags($_POST['other_refrance_no']));
        $maintenance_delivery_date=htmlspecialchars(strip_tags($_POST['maintenance_delivery_date']));
        $po_no=htmlspecialchars(strip_tags($_POST['po_no']));
        $current_status=htmlspecialchars(strip_tags($_POST['current_status']));
        $remark=htmlspecialchars(strip_tags($_POST['remark']));

        $item_list = (array)json_decode($_POST['item_list'],true);
        // $item_list=htmlspecialchars(strip_tags($_POST['item_list']));
        $payment_list = (array)json_decode($_POST['payment_list'],true);
        // $payment_list=htmlspecialchars(strip_tags($_POST['payment_list']));
        $dispatch_list = (array)json_decode($_POST['dispatch_list'],true);
        // $dispatch_list=htmlspecialchars(strip_tags($_POST['dispatch_list']));
        global $item;
        global $payment;
        global $dispatch;
        
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    maintenance_date=:maintenance_date,
                    maintenance_type=:maintenance_type,
                    client_name=:client_name,
                    other_refrance_no=:other_refrance_no,
                    maintenance_delivery_date=:maintenance_delivery_date,
                    po_no=:po_no,
                    current_status=:current_status,
                    remark=:remark,
                    created_by=:created_by,
                    created_at=:created;"; 

       
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":maintenance_date", $maintenance_date);
            $stmt->bindParam(":maintenance_type", $maintenance_type);
            $stmt->bindParam(":client_name", $client_name);
            $stmt->bindParam(":other_refrance_no", $other_refrance_no);
            $stmt->bindParam(":maintenance_delivery_date", $maintenance_delivery_date);
            if($po_no == ""){
                $po_no = -1;
            }
            $stmt->bindParam(":po_no", $po_no);
            $stmt->bindParam(":current_status", $current_status);
            $stmt->bindParam(":remark", $remark);
            
            $stmt->bindParam(":created_by", $user_id);            
            $stmt->bindParam(":created", date('Y-m-d H:i:s'));

            $item_flag = 0;
            $payment_flag = 0;
            $dispatch_flag = 0;
            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);

                $maintenance_no = $result;

                
                
                return json_encode(
                        array(
                            "success" => array(
                                "code" => "201",
                                "message" => "Maintenance was created.",
                                "data" => array(
                                    "id" => $result
                                    )
                                )
                            )
                    );
                

                
            }
            else{
                return json_encode(
                            array(
                                "error" => array(
                                    "code" => "504",
                                    "message" => "Database Service unavailble",
                                    "data" => "sql excution error"
                                    )
                                )
                        );

            }
            // // echo "after excution";

        }
        catch(PDOException $e)
        {   
            echo "excpetion".$e;
            echo "Error: " . $e->getMessage();
                return json_encode(
                            array(
                                "error" => array(
                                    "code" => "503",
                                    "message" =>  $e->getMessage(),
                                    "data" => "try sql"
                                    )
                                )
                        );
        }

        // return false;
         
    }

    function update(){
        // echo "create function";
        // sanitize

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_at=:updated_at";

        $update_arr =  array();


        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $maintenance_id=htmlspecialchars(strip_tags($_POST['maintenance_id']));
        $maintenance_no = $maintenance_id;
    
        $service_list_added = (array)json_decode($_POST['service_list_added'],true);
        

        $service_list_updated = (array)json_decode($_POST['service_list_updated'],true);
        

        $service_list_deleted = (array)json_decode($_POST['service_list_deleted'],true);
        

        foreach ($update_arr as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE id=".$maintenance_id.";";
        // print_r($query);    
        
        global $service;

        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":updated_at", date('Y-m-d H:i:s'));
            foreach ($update_arr as $key => $value)
            {
              // echo($key);
              // echo "\n".$update_arr[$key]."\n";
              $stmt->bindParam(":".$key, $update_arr[$key]);
            }

            $service_flag = 0;
            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);


                foreach ($service_list_added as $key => $val)
                {
                    $service_response = $service->createbydict($val,$user_id,$maintenance_no);
                    // print_r($response);
                    if($service_response == "mysql_error" || $service_response == "try_catch_error"){
                        $service_flag = 1;
                    }
                }
                        

                foreach ($service_list_updated as $key => $val)
                {
                    $service_response = $service->updatebydict($val,$user_id,$maintenance_no);
                    // print_r($response);
                    if($service_response == "mysql_error" || $service_response == "try_catch_error"){
                        $service_flag = 1;
                    }
                } 


                foreach ($service_list_deleted as $key => $val)
                {
                    $service_response = $service->deletebydict($val,$user_id,$maintenance_no);
                    // print_r($response);
                    if($service_response == "mysql_error" || $service_response == "try_catch_error"){
                        $service_flag = 1;
                    }
                }
                                

                
                if($service_flag == "1"){

                    return json_encode(
                            array(
                                "success" => array(
                                    "code" => "201",
                                    "message" => "something went wrong maintenance created",
                                    "data" => array(
                                        "id" => $result
                                        )
                                    )
                                )
                        );
                }
                else{
                    return json_encode(
                            array(
                                "success" => array(
                                    "code" => "201",
                                    "message" => "Maintenance was created.",
                                    "data" => array(
                                        "id" => $result
                                        )
                                    )
                                )
                        );
                }

                
            }
            else{
                return json_encode(
                            array(
                                "error" => array(
                                    "code" => "504",
                                    "message" => "Database Service unavailble",
                                    "data" => "id"
                                    )
                                )
                        );

            }
            // echo "after excution";

        }
        catch(PDOException $e)
        {   
            // echo "excpetion";
            echo "Error: " . $e->getMessage();
                return json_encode(
                            array(
                                "error" => array(
                                    "code" => "503",
                                    "message" =>  $e->getMessage(),
                                    "data" => "id"
                                    )
                                )
                        );
        }

        return false;
         
    }

    function delete(){
        // echo "deleted function";
        // sanitize

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $maintenance_id=htmlspecialchars(strip_tags($_POST['maintenance_id']));

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_by=:updated_by,
                    deleted_at=:deleted_at";

        // $update_arr =  array();
        $query = $query." WHERE maintenance_no=".$maintenance_id.";";
        // print_r($query);    
    

        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":updated_by", $user_id);
            $stmt->bindParam(":deleted_at", date('Y-m-d H:i:s'));
            

            $item_flag = 0;
            $payment_flag = 0;
            $dispatch_flag = 0;
            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                          

                
                if($item_flag == "1" || $payment_flag == "1" || $dispatch_flag =="1"){

                    return json_encode(
                            array(
                                "success" => array(
                                    "code" => "201",
                                    "message" => "something went wrong maintenance created",
                                    "data" => array(
                                        "id" => $result
                                        )
                                    )
                                )
                        );
                }
                else{
                    return json_encode(
                            array(
                                "success" => array(
                                    "code" => "201",
                                    "message" => "Maintenance was created.",
                                    "data" => array(
                                        "id" => $result
                                        )
                                    )
                                )
                        );
                }

                
            }
            else{
                return json_encode(
                            array(
                                "error" => array(
                                    "code" => "504",
                                    "message" => "Database Service unavailble",
                                    "data" => "id"
                                    )
                                )
                        );

            }
            // echo "after excution";

        }
        catch(PDOException $e)
        {   
            // echo "excpetion";
            echo "Error: " . $e->getMessage();
                return json_encode(
                            array(
                                "error" => array(
                                    "code" => "503",
                                    "message" =>  $e->getMessage(),
                                    "data" => "id"
                                    )
                                )
                        );
        }

        return false;
         
    }


    function showall_self(){

        


        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));

        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE deleted_at IS NULL" ; // deleted_at IS NULL
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind values
        // $stmt->bindParam(":user_id", $user_id);
        
        // execute query
        $stmt->execute();

        $rows_arr=array();
        // $users_arr["records"]=array();
     
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
     
            $row_item=array(
                "maintenance_no" => $maintenance_no,
                "maintenance_date" => $maintenance_date,
                "maintenance_type" => $maintenance_type,
                "other_refrance_no" => $other_refrance_no,
                "client_name" => $client_name,
                "maintenance_delivery_date" => $maintenance_delivery_date,
                "current_status" => $current_status,
                "remark" => $remark,
                "created_by" => $created_by,
                "updated_by" => $updated_by,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );
     
            array_push($rows_arr, $row_item);
            // print_r($rows_arr);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "maintenance detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }


    function show_self(){

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $maintenance_id=htmlspecialchars(strip_tags($_POST['maintenance_id']));

        global $service;
        global $subscription;
        global $dispatch;

        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE id=:maintenance_id and deleted_at IS NULL" ; // deleted_at IS NULL
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":maintenance_id", $maintenance_id);
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_item=array(
                "maintenance_no" => $id,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );     
            array_push($rows_arr, $row_item);
        }
        $service_list = $service->show_self_without_post($maintenance_id);
        $rows_arr[0]["service_list"] = $service_list; 

        $subscription_list = $subscription->show_self_without_post($user_id);
        $rows_arr[0]["subscription_list"] = $subscription_list; 

        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "maintenance detail fetched",
                            "data" => $rows_arr[0]
                            )
                        )
                ); 

        

    }

    function show_self_id($maintenance_id){

        // $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        // $maintenance_id=htmlspecialchars(strip_tags($_POST['maintenance_id']));

        global $service;

        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE id=:maintenance_id and deleted_at IS NULL" ; // deleted_at IS NULL
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":maintenance_id", $maintenance_id);
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_item=array(
                "maintenance_no" => $id,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );     
            array_push($rows_arr, $row_item);
        }
        $service_list = $service->show_self_without_post($maintenance_id);
        $rows_arr[0]["service_list"] = $service_list; 

        return $rows_arr[0];

        

    }



    

}

?>