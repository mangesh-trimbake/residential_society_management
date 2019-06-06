<?php
// echo "service.php";
class Service{
 
    // database connection and table name
    private $conn;
    private $table_name = "service";

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // create service
    function create(){
        // echo "create function";
        // sanitize
        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        
        
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

    function createbydict($service,$user_id,$maintenance_id){

        // echo "service createbydict function";

        $service_name=$service['service_name'];
        $monthly_charge=$service['monthly_charge'];
        $remark=$service['remark'];
        

        
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    service_name=:service_name,
                    monthly_charge=:monthly_charge,
                    maintenance_id=:maintenance_id,
                    remark=:remark,
                    created_at=:created;";
              
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);

            // // bind values
            $stmt->bindParam(":service_name", $service_name);    
            
            
            $stmt->bindParam(":monthly_charge", $monthly_charge);
            $stmt->bindParam(":maintenance_id", $maintenance_id);
            $stmt->bindParam(":remark", $remark);
            
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
        $service_id=htmlspecialchars(strip_tags($_POST['service_id']));


        

        foreach ($update_arr as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE id=".$service_id.";";
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
                                        "id" => $service_id
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



    function updatebydict($service,$user_id,$order_no){


        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_at=:updated_at";

        // $update_arr =  array();


        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        // $service_id=htmlspecialchars(strip_tags($_POST['service_id']));


        foreach ($service as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE id=".$service['id'].";";
        // print_r($query);    
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);
            foreach ($service as $key => $value)
            {
              // echo($key);
              // echo "\n".$update_arr[$key]."\n";
              $stmt->bindParam(":".$key, $service[$key]);
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

    function deletebydict($service,$user_id,$order_no){

        // echo "delete service ";
        
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted_at=:deleted_at";


        $query = $query." WHERE id=".$service['id'].";";    
        
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
     
            $row_service=array(
                "id" => $id,
                "service_id" => $service_id
            );
     
            array_push($rows_arr, $row_service);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "service detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }


    function show_self(){

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $service_id=htmlspecialchars(strip_tags($_POST['service_id']));

        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE id=:service_id and deleted_at IS NULL" ; // deleted_at IS NULL
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":service_id", $service_id);
        
        // execute query
        $stmt->execute();

        $rows_arr=array();
        // $users_arr["records"]=array();
     
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
     
            $row_service=array(
                "id" => $id,
                "service_id" => $service_id
            );
     
            array_push($rows_arr, $row_service);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "service detail fetched",
                            "data" => $rows_arr[0]
                            )
                        )
                ); 

        

    }

    function show_self_without_post($maintenance_id){

        // select all query

        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE maintenance_id=:maintenance_id and deleted_at IS NULL" ; // deleted_at IS NULL

        

        


        $stmt = $this->conn->prepare($query);
        
        
            $stmt->bindParam(":maintenance_id", $maintenance_id);
        

        
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_service=array(
                "id" => $id,                
                "service_name" => $service_name,
                "monthly_charge" => $monthly_charge,
                "remark" => $remark,
                "maintenance_id" => $maintenance_id,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );     
            array_push($rows_arr, $row_service);
        }

        return $rows_arr; 

        

    }

}

?>