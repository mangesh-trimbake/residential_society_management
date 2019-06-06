<?php
// echo "event.php";


$database = new Database();
$db = $database->getConnection();
 

class Event{
 
    // database connection and table name
    private $conn;
    private $table_name = "event";

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // create event
    function create(){
        // echo "create function";
        // sanitize
        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $society_id=htmlspecialchars(strip_tags($_POST['society_id']));

        $event_type=htmlspecialchars(strip_tags($_POST['event_type']));
        $event_title=htmlspecialchars(strip_tags($_POST['event_title']));
        $event_date=htmlspecialchars(strip_tags($_POST['event_date']));
        $event_time=htmlspecialchars(strip_tags($_POST['event_time']));
        $event_venue=htmlspecialchars(strip_tags($_POST['event_venue']));
        $event_description=htmlspecialchars(strip_tags($_POST['event_description']));
        
        
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    event_type=:event_type,
                    event_title=:event_title,
                    event_date=:event_date,
                    event_venue=:event_venue,
                    event_time=:event_time,
                    event_description=:event_description,
                    created_by=:created_by,
                    created_at=:created;"; 

       
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":event_type", $event_type);
            $stmt->bindParam(":event_title", $event_title);
            $stmt->bindParam(":event_date", $event_date);
            $stmt->bindParam(":event_time", $event_time);
            $stmt->bindParam(":event_venue", $event_venue);
            $stmt->bindParam(":event_description", $event_description);
            $stmt->bindParam(":created_by", $user_id);            
            $stmt->bindParam(":created", date('Y-m-d H:i:s'));

            $item_flag = 0;
            $payment_flag = 0;
            $vendor_status_flag = 0;
            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);

                $event_no = $result;

                // print_r($event_no);                    

                
                
                return json_encode(
                        array(
                            "success" => array(
                                "code" => "201",
                                "message" => "Event was created.",
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
        // echo "update PO function";
        // sanitize

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_at=:updated_at";

        $update_arr =  array();


        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $event_id=htmlspecialchars(strip_tags($_POST['event_id']));
        $event_no = $event_id;

        $event_type=htmlspecialchars(strip_tags($_POST['event_type']));
        if(!empty($event_type)){
            $update_arr["event_type"] = $event_type;
        }

        $event_title=htmlspecialchars(strip_tags($_POST['event_title']));
        if(!empty($event_title)){
            $update_arr["event_title"] = $event_title;
        }

        $event_date=htmlspecialchars(strip_tags($_POST['event_date']));
        if(!empty($event_date)){
            $update_arr["event_date"] = $event_date;
        }

        $event_time=htmlspecialchars(strip_tags($_POST['event_time']));
        if(!empty($event_time)){
            $update_arr["event_time"] = $event_time;
        }

        $event_description=htmlspecialchars(strip_tags($_POST['event_description']));
        if(!empty($event_description)){
            $update_arr["event_description"] = $event_description;
        }

        $event_venue=htmlspecialchars(strip_tags($_POST['event_venue']));
        if(!empty($event_venue)){
            $update_arr["event_venue"] = $event_venue;
        }
        

        foreach ($update_arr as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE id=".$event_id.";";
        // print_r($query);    
        

        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);
            // $stmt->bindParam(":updated_by", $user_id);
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
                                "message" => "Event was created.",
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
        $order_id=htmlspecialchars(strip_tags($_POST['event_id']));

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_by=:updated_by,
                    deleted_at=:deleted_at";

        // $update_arr =  array();
        $query = $query." WHERE po_no=".$order_id.";";
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
                                    "message" => "something went wrong order created",
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
                                    "message" => "Order was created.",
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
            WHERE deleted_at IS NULL ORDER BY created_at DESC" ; // deleted_at IS NULL
     
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
                "id" => $id,
                "event_type" => $event_type,
                "event_title" => $event_title,
                "event_date" => $event_date,
                "event_time" => $event_time,
                "event_venue" => $event_venue,
                "event_description" =>$event_description,
                "created_by" => $created_by,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );

     
            array_push($rows_arr, $row_item);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "event detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }


    function show_self(){

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $event_id=htmlspecialchars(strip_tags($_POST['event_id']));


        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE id=:event_id and deleted_at IS NULL" ; // deleted_at IS NULL
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":event_id", $event_id);
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_item=array(
                 "id" => $id,
                "event_type" => $event_type,
                "event_title" => $event_title,
                "event_date" => $event_date,
                "event_time" => $event_time,
                "event_venue" => $event_venue,
                "event_description" =>$event_description,
                "created_by" => $created_by,
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
                            "message" => "event detail fetched",
                            "data" => $rows_arr[0]
                            )
                        )
                ); 

        

    }

    function show_list_by_order_without_post($order_id){

        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE order_no=:order_id and deleted_at IS NULL" ; // deleted_at IS NULL
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_item=array(
                "po_no" => $po_no,                
                
            );     
            array_push($rows_arr, $row_item);
        }

        return $rows_arr; 

        

    }


    



}

?>