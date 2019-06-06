<?php
// echo "user.php";

$database = new Database();
$db = $database->getConnection();

$maintenance = new Maintenance($db);
$subscription = new Subscription($db);

class User{
 
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

        $user_type=htmlspecialchars(strip_tags($_POST['user_type']));
        $user_name=htmlspecialchars(strip_tags($_POST['user_name']));
        $first_name=htmlspecialchars(strip_tags($_POST['first_name']));
        $last_name=htmlspecialchars(strip_tags($_POST['last_name']));
        $mobile=htmlspecialchars(strip_tags($_POST['mobile']));
        $email=htmlspecialchars(strip_tags($_POST['email']));
        $wing_name=htmlspecialchars(strip_tags($_POST['wing_name']));

        $flat_no=htmlspecialchars(strip_tags($_POST['flat_no']));
        $password=htmlspecialchars(strip_tags($_POST['password']));
        // echo "full_name: ".$full_name." email: ".$email." password: ".$password."\n";
        // echo "order_acess:".$order_acess."\n";
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    user_name=:user_name, first_name=:first_name, last_name=:last_name, mobile=:mobile, wing_name=:wing_name, flat_no=:flat_no, email=:email, password=:password, user_type=:user_type,created_at=:created";           
        try{

            // prepare query
            $stmt = $this->conn->prepare($query);

            // bind values
            $stmt->bindParam(":user_name", $user_name);
            $stmt->bindParam(":first_name", $first_name);
            $stmt->bindParam(":last_name", $last_name);
            $stmt->bindParam(":mobile", $mobile);
            $stmt->bindParam(":wing_name", $wing_name);
            $stmt->bindParam(":mobile", $mobile);
            $stmt->bindParam(":flat_no", $flat_no);


            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":user_type", $user_type);
            $stmt->bindParam(":created", date('Y-m-d H:i:s'));
            
            // execute query
            if($stmt->execute()){

                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);
                // setcookie("cart_user", $email, $next,"/");
                $user_id_c = $result;
                $subscription_flag = 0;
                
                $subscription_response = $subscription->createbydict($maintenance_arr,$user_id_c);
                // print_r($response);
                if($subscription_response == "mysql_error" || $subscription_response == "try_catch_error"){
                    $subscription_flag = 1;
                }
                
                if($subscription_flag == 1){
                    # code...
                    return json_encode(
                            array(
                                "error" => array(
                                    "code" => "503",
                                    "message" => "Something went wrong",
                                    "data" => "id"
                                    )
                                )
                        );
                }
                else {
                    // code...
                    return json_encode(
                                array(
                                    "success" => array(
                                        "code" => "201",
                                        "message" => "User was created.",
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
                                    "code" => "503",
                                    "message" => "Database Service unavailble",
                                    "data" => "id"
                                    )
                                )
                        );

            }
        }
        catch(PDOException $e)
        {
            // echo "Error: " . $e->getMessage();
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

    // login user
    function login(){
        // echo "\nlogin function\n";
        $stmt = $this->readLogin();
        $num = $stmt->rowCount();
        // echo "num ".$num;
        if($num>0){

            $users_arr=array();
            $users_arr["records"]=array();
         
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
         
                $user_item=array(
                    "user_id" => $user_id,
                    "user_type" => $user_type,
                    "user_name" => $user_name,
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "email" => $email,
                    "mobile" => $mobile,
                    "wing_id" => $wing_id,
                    "wing_name" =>$wing_name,
                    "flat_id" =>$flat_id,
                    "flat_no" =>$flat_no,
                    "wallet_amt" =>$wallet_amt
                    // "user_acess" =>$user_acess
                );
         
                array_push($users_arr["records"], $user_item);
            }
            $next = time()  + (60*60*24);
            // echo "email ".$users_arr["records"][0]["email"]."\n";
            setcookie("portal_user", $users_arr["records"][0]["email"], $next,"/");
            return json_encode(
                        array(
                            "success" => array(
                                "code" => "201",
                                "message" => "user successfully logged",
                                "data" => $users_arr["records"][0]
                                )
                            )
                    );            
            
        }
        else{
            return json_encode(
                        array(
                            "error" => array(
                                "code" => "503",
                                "message" => "User or Password Not correct.",
                                )
                            )
                    );

        }
        

        // return false;
         
    }


        function logout(){
        
        if(setcookie("portal_user", "", time() - 3600)){

            return json_encode(
                        array(
                            "success" => array(
                                "code" => "201",
                                "message" => "user successfully logout",
                                "data" =>  array()
                                )
                            )
                    );            
            
        }
        else{
            return json_encode(
                        array(
                            "error" => array(
                                "code" => "503",
                                "message" => "User or Password Not correct.",
                                )
                            )
                    );

        }
        

        return "logout function";
         
    }



    function readLogin(){
        // echo "\nreadLogin function called";
        $email=htmlspecialchars(strip_tags($_POST['email']));
        $password=htmlspecialchars(strip_tags($_POST['password']));
        // echo "full_name: ".$full_name." email: ".$email." password: ".$password."\n";
 
        // select all query
        $query = "SELECT  * FROM " . $this->table_name . " 
                    WHERE  user_name=:email and password=:password";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind values
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);

        // echo "\nquery ".$stmt->fullQuery;
        // echo "\n query ".$stmt;
        // execute query
        $stmt->execute();
     
        return $stmt;
                
    }




  function update(){
        // echo "create function";
        // sanitize

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_at=:updated_at";

        $update_arr =  array();


        $user_req_id=htmlspecialchars(strip_tags($_POST['user_req_id']));
        // $order_id=htmlspecialchars(strip_tags($_POST['order_id']));
        $id = $user_req_id;


        
        if(array_key_exists ( "user_name" , $_POST)){
            $user_name=htmlspecialchars(strip_tags($_POST['user_name']));
            $update_arr["user_name"] = $user_name;
        }

        
        if(array_key_exists ( "email" , $_POST)){
            $email=htmlspecialchars(strip_tags($_POST['email']));
            $update_arr["email"] = $email;
        }

        if(array_key_exists ( "mobile" , $_POST)){
            $mobile=htmlspecialchars(strip_tags($_POST['mobile']));
            $update_arr["mobile"] = $mobile;
        }
       
        if(array_key_exists ( "user_type" , $_POST)){
             $user_type=htmlspecialchars(strip_tags($_POST['user_type']));
            $update_arr["user_type"] = $user_type;
        }
        
        if(array_key_exists ( "first_name" , $_POST)){
            $first_name=htmlspecialchars(strip_tags($_POST['first_name']));
            $update_arr["first_name"] = $first_name;
        }
        
        if(array_key_exists ( "last_name" , $_POST)){
            $last_name=htmlspecialchars(strip_tags($_POST['last_name']));
            $update_arr["last_name"] = $last_name;
        }
        
        if(array_key_exists ( "wing_name" , $_POST)){
            $wing_name=htmlspecialchars(strip_tags($_POST['wing_name']));
            $update_arr["wing_name"] = $wing_name;
        }

        if(array_key_exists ( "flat_no" , $_POST)){
            $flat_no=htmlspecialchars(strip_tags($_POST['flat_no']));
            $update_arr["flat_no"] = $flat_no;
        }
        
        if(array_key_exists ( "wallet_amt" , $_POST)){
            $wallet_amt=htmlspecialchars(strip_tags($_POST['wallet_amt']));
            $update_arr["wallet_amt"] = $wallet_amt;
        }

        if(array_key_exists ( "password" , $_POST)){
            $password=htmlspecialchars(strip_tags($_POST['password']));
            $update_arr["password"] = $password;
        }

        foreach ($update_arr as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE user_id=".$user_req_id.";";
       
        global $pass;
        
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
            // $password_flag= 0;
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();                 


            return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "user detail updated",
                            "data" => "id"
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

    function updatebydict($user,$user_id){

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_at=:updated_at";



        foreach ($user as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE user_id=".$user_id.";";
        // print_r($query);    
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);
            foreach ($user as $key => $value)
            {
              // echo($key);
              // echo "\n".$update_arr[$key]."\n";
              $stmt->bindParam(":".$key, $user[$key]);
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

    function showall_self(){

        // global $item;
        // global $payment;
        // global $dispatch;


        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));

        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE deleted_at IS NULL" ; // deleted_at IS NULL
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind values
        // $stmt->bindParam(":user_id", $user_id);
        // print_r($stmt);
        // execute query
        $stmt->execute();

        $rows_arr=array();
        // $users_arr["records"]=array();
     
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
            // echo $row;
            $row_item=array(
                "user_id" => $user_id,
                "first_name" => $user_id,
                "last_name" => $user_id,

                "user_name" => $user_name,
                "mobile" => $mobile,
                "email" => $email,
                "user_type" =>$user_type,
                "wing_name  " => $wing_name,
                "flat_no " => $flat_no,
                "wallet_amt" => $wallet_amt,
               
            
            );

     
            array_push($rows_arr, $row_item);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "user detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }

       function show_self(){

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $user_req_id=htmlspecialchars(strip_tags($_POST['user_req_id']));


        // select all query
        $query = "SELECT * FROM
            " . $this->table_name . "
            WHERE user_id=:user_req_id and deleted_at IS NULL" ; // deleted_at IS NULL
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_req_id", $user_req_id);
        $stmt->execute();

        $rows_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);     
            $row_item=array(
                "user_id" => $user_id,
                "first_name" => $first_name,
                "last_name" => $last_name,

                "user_name" => $user_name,
                "mobile" => $mobile,
                "email" => $email,
                "user_type" =>$user_type,
                "wing_name" => $wing_name,
                "flat_no" => $flat_no,
                "wallet_amt" => $wallet_amt
               
            );    
            array_push($rows_arr, $row_item);
            // print_r($rows_arr);
        }
       



        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "user detail fetched",
                            "data" => $rows_arr[0]
                            )
                        )
                ); 

        

    }

  function delete(){
        // echo "deleted function";
        // sanitize

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $user_req_id=htmlspecialchars(strip_tags($_POST['user_req_id']));

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                  
                    deleted_at=:deleted_at";

        // $update_arr =  array();
        $query = $query." WHERE id=".$user_req_id.";";
        // print_r($query);    
    

        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);
            // $stmt->bindParam(":updated_by", $user_id);
            $stmt->bindParam(":deleted_at", date('Y-m-d H:i:s'));
            

            // $item_flag = 0;
            // $payment_flag = 0;
            // $dispatch_flag = 0;
            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                          

                
                // if($item_flag == "1" || $payment_flag == "1" || $dispatch_flag =="1"){

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




}
?>