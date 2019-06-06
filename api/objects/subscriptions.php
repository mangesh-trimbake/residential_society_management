<?php
// echo "subscription.php";
class Subscription{
 
    // database connection and table name
    private $conn;
    private $table_name = "subscriptions";

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // create subscription
    function create(){
        // echo "create function";
        // sanitize
        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));

        $jewellry_type=htmlspecialchars(strip_tags($_POST['jewellry_type']));
        $metal_col_purity=htmlspecialchars(strip_tags($_POST['metal_col_purity']));
        $category=htmlspecialchars(strip_tags($_POST['category']));
        $approx_quantity=htmlspecialchars(strip_tags($_POST['approx_quantity']));
        $approx_weight=htmlspecialchars(strip_tags($_POST['approx_weight']));
        
        
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    jewellry_type=:jewellry_type,
                    metal_col_purity=:metal_col_purity,
                    category=:category,
                    approx_quantity=:approx_quantity,
                    approx_weight=:approx_weight,
                    
                    created_by=:created_by,
                    created_at=:created;";       
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);

            // // bind values
            // $stmt->bindParam(":product_id", $product_id);
            // $stmt->bindParam(":user_id", $user_id);

            $stmt->bindParam(":jewellry_type", $jewellry_type);
            $stmt->bindParam(":metal_col_purity", $metal_col_purity);
            $stmt->bindParam(":category", $category);
            $stmt->bindParam(":approx_quantity", $approx_quantity);
            $stmt->bindParam(":approx_weight", $approx_weight);
            
            $stmt->bindParam(":created_by", $user_id);            
            $stmt->bindParam(":created", date('Y-m-d H:i:s'));

            print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);

                

                return json_encode(
                            array(
                                "success" => array(
                                    "code" => "201",
                                    "message" => "Order was created.",
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
                                    "data" => "id"
                                    )
                                )
                        );

            }
            // // echo "after excution";

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

        // return false;
         
    }

    function createbydict($maintenance_arr,$user_id_c){

        // echo "subscription createbydict function";
        // print_r($maintenance_arr);
        $maintenance_id=$maintenance_arr['maintenance_no'];

        $services = "";
        $total_amt_to_paid = 0;
        
        foreach ($maintenance_arr["service_list"] as $key => $val)
        {
            // print_r($val);
            $services = $services.",".$val['id'];
            $total_amt_to_paid = $total_amt_to_paid + $val['monthly_charge'];


        }
        
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    maintenance_id=:maintenance_id,
                    subscription_name=:subscription_name,
                    user_id=:user_id,
                    services=:services,
                    total_amt_to_paid=:total_amt_to_paid,
                    created_at=:created;";
              
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);

            // // bind values
            // $stmt->bindParam(":product_id", $product_id);
            // $stmt->bindParam(":user_id", $user_id);
            $now = new DateTime('now');
            $month = $now->format('F');
            $year = $now->format('Y');
            $subscription_name = $month."-".$year;
            $stmt->bindParam(":maintenance_id", $maintenance_id);
            $stmt->bindParam(":subscription_name", $subscription_name);
            $stmt->bindParam(":user_id", $user_id_c);
            $stmt->bindParam(":services", $services);
            $stmt->bindParam(":total_amt_to_paid", $total_amt_to_paid);
            
            $stmt->bindParam(":created", date('Y-m-d H:i:s'));

            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);

                return $result;

            }
            else{
                
                return "mysql_error";
            }
            // // echo "after excution";

        }
        catch(PDOException $e)
        {   
            
            return "try_catch_error";
        }

        // return false;
         
    }

    function update(){
        // echo "create function";
        // sanitize

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_by=:updated_by,
                    updated_at=:updated_at";

        $update_arr =  array();


        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $subscription_id=htmlspecialchars(strip_tags($_POST['subscription_id']));


        

        foreach ($update_arr as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE id=".$subscription_id.";";
        // print_r($query);    
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":updated_by", $user_id);
            $stmt->bindParam(":updated_at", date('Y-m-d H:i:s'));
            foreach ($update_arr as $key => $value)
            {
              // echo($key);
              // echo "\n".$update_arr[$key]."\n";
              $stmt->bindParam(":".$key, $update_arr[$key]);
            }

            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);

                return json_encode(
                            array(
                                "success" => array(
                                    "code" => "201",
                                    "message" => "Order was updated.",
                                    "data" => array(
                                        "id" => $subscription_id
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



    function updatebydict($subscription,$user_id,$subscription_no){

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_at=:updated_at";


        foreach ($subscription as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE id=".$subscription['id'].";";
        // print_r($query);    
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);
            foreach ($subscription as $key => $value)
            {
              // echo($key);
              // echo "\n".$update_arr[$key]."\n";
              $stmt->bindParam(":".$key, $subscription[$key]);
            }
            $stmt->bindParam(":updated_at", date('Y-m-d H:i:s'));
            

            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);

                return $result;
            }
            else{
                
                return "mysql_error";
            }
            // echo "after excution";

        }
        catch(PDOException $e)
        {   
            
            return "try_catch_error";
        }

        return false;
         
    }

    function deletebydict($subscription,$user_id,$order_no){

        // echo "delete subscription ";
        
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted_at=:deleted_at";


        $query = $query." WHERE subscription_no=".$subscription['subscription_no'].";";    
        
        try{

            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":deleted_at", date('Y-m-d H:i:s'));
            

            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);

                return $result;
            }
            else{
                
                return "mysql_error";
            }
            // echo "after excution";

        }
        catch(PDOException $e)
        {   
            
            return "try_catch_error";
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
     
            $row_subscription=array(
                "id" => $id,
                "subscription_id" => $subscription_id,
                
            );
     
            array_push($rows_arr, $row_subscription);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "subscription detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }


    function show_self(){

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $subscription_id=htmlspecialchars(strip_tags($_POST['subscription_id']));

        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE id=:subscription_id and deleted_at IS NULL" ; // deleted_at IS NULL
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":subscription_id", $subscription_id);
        
        // execute query
        $stmt->execute();

        $rows_arr=array();
        // $users_arr["records"]=array();
     
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
     
            $row_subscription=array(
                "id" => $id,
                "subscription_id" => $subscription_id
            );
     
            array_push($rows_arr, $row_subscription);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "subscription detail fetched",
                            "data" => $rows_arr[0]
                            )
                        )
                ); 

        

    }

    function show_self_without_post($user_id){

        // select all query      

        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE user_id=:user_id and deleted_at IS NULL and (total_amt_to_paid != paid_amt OR paid_amt IS NULL)" ; // deleted_at IS NULL

        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":user_id", $user_id);
        

        
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_subscription=array(
                "id" => $id,                
                "subscription_name" => $subscription_name,
                "maintenance_id" => $maintenance_id,
                "services" => $services,
                "user_id" => $user_id,
                "total_amt_to_paid" => $total_amt_to_paid,
                "paid_amt" => $paid_amt,
                "completed_at" => $completed_at,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );     
            array_push($rows_arr, $row_subscription);
        }

        return $rows_arr; 

        

    }

}

?>