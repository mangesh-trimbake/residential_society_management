<?php
// echo "wing.php";
class Wing{
 
    // database connection and table name
    private $conn;
    private $table_name = "wing";

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // create wing
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

    function createbydict($wing,$user_id,$society_id){

        // echo "wing createbydict function";

        $wing_name=$wing['wing_name'];
        $remark=$wing['remark'];
        
        

        
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    wing_name=:wing_name,
                    remark=:remark,
                    society_id=:society_id,
                    created_at=:created;";
          
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);

            // // bind values
            // $stmt->bindParam(":product_id", $product_id);
            // $stmt->bindParam(":user_id", $user_id);
            
            $stmt->bindParam(":wing_name", $wing_name);
            $stmt->bindParam(":remark", $remark);
            $stmt->bindParam(":society_id", $society_id);
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
        $wing_id=htmlspecialchars(strip_tags($_POST['wing_id']));


        
        foreach ($update_arr as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE id=".$wing_id.";";
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
                                        "id" => $wing_id
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



    function updatebydict($wing,$user_id,$order_no){
        

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_at=:updated_at";

        foreach ($wing as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE id=".$wing['id'].";";
        // print_r($query);    
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);
            foreach ($wing as $key => $value)
            {
              // echo($key);
              // echo "\n".$update_arr[$key]."\n";
              $stmt->bindParam(":".$key, $wing[$key]);
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

    function deletebydict($wing,$user_id,$order_no){

        // echo "delete wing ";
        
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    deleted_at=:deleted_at";


        $query = $query." WHERE id=".$wing['id'].";";    
        
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
     
            $row_wing=array(
                "id" => $id,
                "wing_id" => $wing_id
                
            );
     
            array_push($rows_arr, $row_wing);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "wing detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }


    function show_self(){

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $wing_id=htmlspecialchars(strip_tags($_POST['wing_id']));

        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE id=:wing_id and deleted_at IS NULL" ; // deleted_at IS NULL
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":wing_id", $wing_id);
        
        // execute query
        $stmt->execute();

        $rows_arr=array();
        // $users_arr["records"]=array();
     
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
     
            $row_wing=array(
                "id" => $id,
                "wing_id" => $wing_id
            );
     
            array_push($rows_arr, $row_wing);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "wing detail fetched",
                            "data" => $rows_arr[0]
                            )
                        )
                ); 

        

    }

    function show_self_without_post($society_id){

        // select all query

        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE society_id=:society_id and deleted_at IS NULL" ; // deleted_at IS NULL

        

        


        $stmt = $this->conn->prepare($query);
        
        
        $stmt->bindParam(":society_id", $society_id);
        

        
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_wing=array(
                "id" => $id,                
                "wing_name" => $wing_name,
                "society_id" => $society_id,
                "society_short_name" => $society_short_name,
                "remark" => $remark,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );     
            array_push($rows_arr, $row_wing);
        }

        return $rows_arr; 

        

    }

}

?>