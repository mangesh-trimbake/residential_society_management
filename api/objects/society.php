<?php
// echo "society.php";


$database = new Database();
$db = $database->getConnection();
 
$wing = new Wing($db);



class Society{
 
    // database connection and table name
    private $conn;
    private $table_name = "society";

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // $item = new Item($this->conn);

    // create society
    function create(){
        // echo "create function";
        // sanitize
        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));

        $society_date=htmlspecialchars(strip_tags($_POST['society_date']));
        $society_type=htmlspecialchars(strip_tags($_POST['society_type']));
        $client_name=htmlspecialchars(strip_tags($_POST['client_name']));
        $other_refrance_no=htmlspecialchars(strip_tags($_POST['other_refrance_no']));
        $society_delivery_date=htmlspecialchars(strip_tags($_POST['society_delivery_date']));
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
                    created_by=:created_by,
                    created_at=:created;"; 

       
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);

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

                $society_no = $result;

                
                return json_encode(
                        array(
                            "success" => array(
                                "code" => "201",
                                "message" => "Society was created.",
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
        $society_id=htmlspecialchars(strip_tags($_POST['society_id']));
        $society_no = $society_id;

        $society_name=htmlspecialchars(strip_tags($_POST['society_name']));
        if(!empty($society_name)){
            $update_arr["society_name"] = $society_name;
        }

        $society_short_name=htmlspecialchars(strip_tags($_POST['society_short_name']));
        if(!empty($society_short_name)){
            $update_arr["society_short_name"] = $society_short_name;
        }

        $Address=htmlspecialchars(strip_tags($_POST['Address']));
        if(!empty($Address)){
            $update_arr["Address"] = $Address;
        }

        $city=htmlspecialchars(strip_tags($_POST['city']));
        if(!empty($city)){
            $update_arr["city"] = $city;
        }

        $state=htmlspecialchars(strip_tags($_POST['state']));
        if(!empty($state)){
            $update_arr["state"] = $state;
        }

        $country=htmlspecialchars(strip_tags($_POST['country']));
        if(!empty($country)){
            $update_arr["country"] = $country;
        }
        $description=htmlspecialchars(strip_tags($_POST['description']));
        if(!empty($description)){
            $update_arr["description"] = $description;
        }
    
        $wing_list_added = (array)json_decode($_POST['wing_list_added'],true);
        

        $wing_list_updated = (array)json_decode($_POST['wing_list_updated'],true);
        

        $wing_list_deleted = (array)json_decode($_POST['wing_list_deleted'],true);
        

        foreach ($update_arr as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE id=".$society_id.";";
        // print_r($query);    
        
        global $wing;

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

            $wing_flag = 0;
            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);


                foreach ($wing_list_added as $key => $val)
                {
                    $wing_response = $wing->createbydict($val,$user_id,$society_no);
                    // print_r($response);
                    if($wing_response == "mysql_error" || $wing_response == "try_catch_error"){
                        $wing_flag = 1;
                    }
                }
                        

                foreach ($wing_list_updated as $key => $val)
                {
                    $wing_response = $wing->updatebydict($val,$user_id,$society_no);
                    // print_r($response);
                    if($wing_response == "mysql_error" || $wing_response == "try_catch_error"){
                        $wing_flag = 1;
                    }
                } 


                foreach ($wing_list_deleted as $key => $val)
                {
                    $wing_response = $wing->deletebydict($val,$user_id,$society_no);
                    // print_r($response);
                    if($wing_response == "mysql_error" || $wing_response == "try_catch_error"){
                        $wing_flag = 1;
                    }
                }
                                

                
                if($wing_flag == "1"){

                    return json_encode(
                            array(
                                "success" => array(
                                    "code" => "201",
                                    "message" => "something went wrong society created",
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
                                    "message" => "Society was created.",
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
        $society_id=htmlspecialchars(strip_tags($_POST['society_id']));

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_by=:updated_by,
                    deleted_at=:deleted_at";

        // $update_arr =  array();
        $query = $query." WHERE society_no=".$society_id.";";
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
                                    "message" => "something went wrong society created",
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
                                    "message" => "Society was created.",
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
                "society_no" => $society_no,
            );
     
            array_push($rows_arr, $row_item);
            // print_r($rows_arr);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "society detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }


    function show_self(){

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $society_id=htmlspecialchars(strip_tags($_POST['society_id']));

        global $wing;
        global $payment;
        global $dispatch;
        global $po;

        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE id=:society_id and deleted_at IS NULL" ; // deleted_at IS NULL
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":society_id", $society_id);
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_item=array(
                "society_no" => $id,
                "society_name" => $society_name,
                "society_short_name" => $society_short_name,
                "Address" => $Address,
                "city" => $city,
                "state" => $state,
                "country" => $country,
                "pincode" => $pincode,
                "description" => $description,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );     
            array_push($rows_arr, $row_item);
        }
        $wing_list = $wing->show_self_without_post($society_id);
        $rows_arr[0]["wing_list"] = $wing_list;

        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "society detail fetched",
                            "data" => $rows_arr[0]
                            )
                        )
                ); 

        

    }


    function show_by_id($society_id){

        global $wing;

        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE id=:society_id and deleted_at IS NULL" ; // deleted_at IS NULL
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":society_id", $society_id);
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_item=array(
                "society_no" => $id,
                "society_name" => $society_name,
                "society_short_name" => $society_short_name,
                "Address" => $Address,
                "city" => $city,
                "state" => $state,
                "country" => $country,
                "pincode" => $pincode,
                "description" => $description,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );     
            array_push($rows_arr, $row_item);
        }
        $wing_list = $wing->show_self_without_post($society_id);
        $rows_arr[0]["wing_list"] = $wing_list;

        return $rows_arr[0];

        

    }


}

?>