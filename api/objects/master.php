<?php
// echo "master.php";

$database = new Database();
$db = $database->getConnection();

class Master{
 
    // database connection and table name
    private $conn;
    private $table_name = "master";

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // $item = new Item($this->conn);

    // create master
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

                $master_no = $result;

                // print_r($master_no);
                return json_encode(
                        array(
                            "success" => array(
                                "code" => "201",
                                "message" => "Master was created.",
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
        
        $insert_flag = 0;
        $delete_flag = 0;
        $update_flag = 0;

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $master_disp_text=htmlspecialchars(strip_tags($_POST['master_disp_text']));

        $master_entity_list_added = (array)json_decode($_POST['master_entity_list_added'],true);

        $master_entity_list_updated = (array)json_decode($_POST['master_entity_list_updated'],true);        

        $master_entity_list_deleted = (array)json_decode($_POST['master_entity_list_deleted'],true);
        // $master_no = $master_id;
         $query_i = "INSERT INTO
                    " . $master_disp_text . "
                SET
                    created_at=:created_at";

        $query_u = "UPDATE
                    " . $master_disp_text . "
                SET
                    updated_at=:updated_at";

        $query_d = "UPDATE
                    " . $master_disp_text . "
                SET
                    deleted_at=:deleted_at";


        foreach ($master_entity_list_added as $key => $val)
        {
            // echo "".$val."\n";
            // print_r($val);

            foreach ($val as $key => $value)
            {
              // echo($key);
              $query_i = $query_i.", ".$key."=:".$key;

            }

            try{

                $stmt = $this->conn->prepare($query_i);
                $stmt->bindParam(":created_at", date('Y-m-d H:i:s'));

                foreach ($val as $key => $value)
                {
                  $stmt->bindParam(":".$key, $val[$key]);
                }
                // print_r($stmt);
                if($stmt->execute()){
                    
                    $result = $this->conn->lastInsertId();
                                
                }
                else{
                    
                    $insert_flag = 1;

                }
                // echo "after excution";

            }
            catch(PDOException $e)
            {   
                // echo "excpetion";
                $insert_flag = 1;
            }

        }

        foreach ($master_entity_list_updated as $key => $val)
        {
            // echo "".$val."\n";
            // print_r($val);

            foreach ($val as $key => $value)
            {
              if($key == "disp_text" || $key == "description"){
                $query_u = $query_u.", ".$key."=:".$key;
              }
              

            }

            $query_u = $query_u." WHERE id=".$val['id'].";";
            try{

                $stmt = $this->conn->prepare($query_u);
                $stmt->bindParam(":updated_at", date('Y-m-d H:i:s'));

                foreach ($val as $key => $value)
                {

                     if($key == "disp_text" || $key == "description"){
                         $stmt->bindParam(":".$key, $val[$key]);
                     }
                 
                }


                // print_r($stmt);
                if($stmt->execute()){
                    
                    $result = $this->conn->lastInsertId();
                                
                }
                else{
                    
                    $update_flag = 1;

                }
                // echo "after excution";

            }
            catch(PDOException $e)
            {   
                // echo "excpetion";
                $update_flag = 1;
            }


        }

        foreach ($master_entity_list_deleted as $key => $val)
        {
            // echo "".$val."\n";
            // print_r($val);

            $query_d = $query_d." WHERE id=".$val['id'].";";
            try{

                $stmt = $this->conn->prepare($query_d);
                $stmt->bindParam(":deleted_at", date('Y-m-d H:i:s'));

                // print_r($stmt);
                if($stmt->execute()){
                    
                    $result = $this->conn->lastInsertId();
                                
                }
                else{
                    
                    $update_flag = 1;

                }
                // echo "after excution";

            }
            catch(PDOException $e)
            {   
                // echo "excpetion";
                $update_flag = 1;
            }


        }


        if($insert_flag == "1" || $update_flag == "1" || $delete_flag =="1"){

            return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "something went wrong master created",
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
                            "message" => "Master was created.",
                            "data" => array(
                                "id" => $result
                                )
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
        $master_id=htmlspecialchars(strip_tags($_POST['master_id']));

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_by=:updated_by,
                    deleted_at=:deleted_at";

        // $update_arr =  array();
        $query = $query." WHERE master_no=".$master_id.";";
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
                                    "message" => "something went wrong master created",
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
                                    "message" => "Master was created.",
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

        global $item;
        global $payment;
        global $dispatch;


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
                "disp_text" => $disp_text,
                "description" => $description,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );           

            
            // $rows_arr[$disp_text] = $this->getMasterTableRow($disp_text);
            array_push($rows_arr, $row_item);

        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "master detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }

    function getMasterTableRow($table_name){

            $query_m = "SELECT * FROM
                " . $table_name. "
                WHERE deleted_at IS NULL" ; // deleted_at IS NULL
         
            // prepare query statement
            $stmt_m = $this->conn->prepare($query_m);
            $stmt_m->execute();
            $rows_arr_m=array();
                     
            while ($row_m = $stmt_m->fetch(PDO::FETCH_ASSOC)){
                extract($row_m);
         
                $row_item_m=array(
                    "id" => $id,
                    "disp_text" => $disp_text,
                    "description" => $description,
                    "created_at" => $created_at,
                    "updated_at" => $updated_at,
                    "deleted_at" => $deleted_at
                );
         
                array_push($rows_arr_m, $row_item_m);
            }

            return $rows_arr_m;
    }


    function show_self_e(){

        global $item;
        global $payment;
        global $dispatch;


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
                "disp_text" => $disp_text,
                "description" => $description,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );           

            
            $rows_arr[$disp_text] = $this->getMasterTableRow($disp_text);


        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "master detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }
    function show_self(){

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $master_disp_text=htmlspecialchars(strip_tags($_POST['master_disp_text']));

        global $item;
        global $payment;
        global $dispatch;

        // select all query
        $query = "SELECT * FROM
            " . $master_disp_text . "
            WHERE deleted_at IS NULL" ; // deleted_at IS NULL
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":master_id", $master_id);
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_item=array(
                "id" => $id,
                "disp_text" => $disp_text,
                "description" => $description,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );     
            array_push($rows_arr, $row_item);
        }

        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "master detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }

}

?>