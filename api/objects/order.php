<?php
// echo "order.php";

// get database connection
// include_once '../config/database.php';

// instantiate order item
// include_once '../objects/item.php';
// include_once '../objects/payment.php';
// include_once '../objects/dispatch.php';

// require_once('../Psr/autoloader.php');
// require_once('../PhpSpreadsheet/autoloader.php');


$database = new Database();
$db = $database->getConnection();
 
$item = new Item($db);
$payment = new Payment($db);
$dispatch = new Dispatch($db);

$po = new PurchaseOrder($db);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Order{
 
    // database connection and table name
    private $conn;
    private $table_name = "orders";

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // $item = new Item($this->conn);

    // create order
    function create(){
        // echo "create function";
        // sanitize
        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));

        $order_date=htmlspecialchars(strip_tags($_POST['order_date']));
        $order_type=htmlspecialchars(strip_tags($_POST['order_type']));
        $client_name=htmlspecialchars(strip_tags($_POST['client_name']));
        $other_refrance_no=htmlspecialchars(strip_tags($_POST['other_refrance_no']));
        $order_delivery_date=htmlspecialchars(strip_tags($_POST['order_delivery_date']));
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
                    order_date=:order_date,
                    order_type=:order_type,
                    client_name=:client_name,
                    other_refrance_no=:other_refrance_no,
                    order_delivery_date=:order_delivery_date,
                    po_no=:po_no,
                    current_status=:current_status,
                    remark=:remark,
                    created_by=:created_by,
                    created_at=:created;"; 

       
        
        try{

            // echo "inside try";
            // prepare query
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":order_date", $order_date);
            $stmt->bindParam(":order_type", $order_type);
            $stmt->bindParam(":client_name", $client_name);
            $stmt->bindParam(":other_refrance_no", $other_refrance_no);
            $stmt->bindParam(":order_delivery_date", $order_delivery_date);
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

                $order_no = $result;

                // print_r($order_no);

                foreach ($item_list as $key => $val)
                {
                    $item_response = $item->createbydict($val,$user_id,$order_no,"so");
                    // print_r($response);
                    if($item_response == "mysql_error" || $item_response == "try_catch_error"){
                        $item_flag = 1;
                    }
                }
                foreach ($payment_list as $key => $val)
                {
                    $payment_response = $payment->createbydict($val,$user_id,$order_no);
                    // print_r($response);
                    if($payment_response == "mysql_error" || $payment_response == "try_catch_error"){
                        $paymentflag = 1;
                    }
                }
                foreach ($dispatch_list as $key => $val)
                {
                    $dispatch_response = $dispatch->createbydict($val,$user_id,$order_no);
                    // print_r($response);
                    if($dispatch_response == "mysql_error" || $dispatch_response == "try_catch_error"){
                        $dispatch_flag = 1;
                    }
                }         

                
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
                    updated_by=:updated_by,
                    updated_at=:updated_at";

        $update_arr =  array();


        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $order_id=htmlspecialchars(strip_tags($_POST['order_id']));
        $order_no = $order_id;

        $order_date=htmlspecialchars(strip_tags($_POST['order_date']));
        if(!empty($order_date)){
            $update_arr["order_date"] = $order_date;
        }

        $order_type=htmlspecialchars(strip_tags($_POST['order_type']));
        if(!empty($order_type)){
            $update_arr["order_type"] = $order_type;
        }

        $other_refrance_no=htmlspecialchars(strip_tags($_POST['other_refrance_no']));
        if(!empty($other_refrance_no)){
            $update_arr["other_refrance_no"] = $other_refrance_no;
        }

        $client_name=htmlspecialchars(strip_tags($_POST['client_name']));
        if(!empty($client_name)){
            $update_arr["client_name"] = $client_name;
        }

        $order_delivery_date=htmlspecialchars(strip_tags($_POST['order_delivery_date']));
        if(!empty($order_delivery_date)){
            $update_arr["order_delivery_date"] = $order_delivery_date;
        }

        $current_status=htmlspecialchars(strip_tags($_POST['current_status']));
        if(!empty($current_status)){
            $update_arr["current_status"] = $current_status;
        }
        $remark=htmlspecialchars(strip_tags($_POST['remark']));
        if(!empty($remark)){
            $update_arr["remark"] = $remark;
        }
    
        $item_list_added = (array)json_decode($_POST['item_list_added'],true);
        $payment_list_added = (array)json_decode($_POST['payment_list_added'],true);
        $dispatch_list_added = (array)json_decode($_POST['dispatch_list_added'],true);

        $item_list_updated = (array)json_decode($_POST['item_list_updated'],true);
        $payment_list_updated = (array)json_decode($_POST['payment_list_updated'],true);
        $dispatch_list_updated = (array)json_decode($_POST['dispatch_list_updated'],true);

        $item_list_deleted = (array)json_decode($_POST['item_list_deleted'],true);
        $payment_list_deleted = (array)json_decode($_POST['payment_list_deleted'],true);
        $dispatch_list_deleted = (array)json_decode($_POST['dispatch_list_deleted'],true);

        foreach ($update_arr as $key => $value)
        {
          // echo($key);
          $query = $query.", ".$key."=:".$key;

        }
        $query = $query." WHERE order_no=".$order_id.";";
        // print_r($query);    
        
        global $item;
        global $payment;
        global $dispatch;

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

            $item_flag = 0;
            $payment_flag = 0;
            $dispatch_flag = 0;
            // print_r($stmt);
            if($stmt->execute()){
                // echo "inside if";
                // $result = $stmt->lastInsertId();
                $result = $this->conn->lastInsertId();
                // $next = time()  + (60*60*24);


                foreach ($item_list_added as $key => $val)
                {
                    $item_response = $item->createbydict($val,$user_id,$order_no,"so");
                    // print_r($response);
                    if($item_response == "mysql_error" || $item_response == "try_catch_error"){
                        $item_flag = 1;
                    }
                }
                foreach ($payment_list_added as $key => $val)
                {
                    $payment_response = $payment->createbydict($val,$user_id,$order_no);
                    // print_r($response);
                    if($payment_response == "mysql_error" || $payment_response == "try_catch_error"){
                        $paymentflag = 1;
                    }
                }
                foreach ($dispatch_list_added as $key => $val)
                {
                    $dispatch_response = $dispatch->createbydict($val,$user_id,$order_no);
                    // print_r($response);
                    if($dispatch_response == "mysql_error" || $dispatch_response == "try_catch_error"){
                        $dispatch_flag = 1;
                    }
                }         

                foreach ($item_list_updated as $key => $val)
                {
                    $item_response = $item->updatebydict($val,$user_id,$order_no);
                    // print_r($response);
                    if($item_response == "mysql_error" || $item_response == "try_catch_error"){
                        $item_flag = 1;
                    }
                }

                foreach ($payment_list_updated as $key => $val)
                {
                    $payment_response = $payment->updatebydict($val,$user_id,$order_no);
                    // print_r($response);
                    if($payment_response == "mysql_error" || $payment_response == "try_catch_error"){
                        $payment_flag = 1;
                    }
                }

                foreach ($dispatch_list_updated as $key => $val)
                {
                    $dispatch_response = $dispatch->updatebydict($val,$user_id,$order_no);
                    // print_r($response);
                    if($dispatch_response == "mysql_error" || $dispatch_response == "try_catch_error"){
                        $dispatch_flag = 1;
                    }
                }





                foreach ($item_list_deleted as $key => $val)
                {
                    $item_response = $item->deletebydict($val,$user_id,$order_no);
                    // print_r($response);
                    if($item_response == "mysql_error" || $item_response == "try_catch_error"){
                        $item_flag = 1;
                    }
                }

                foreach ($payment_list_deleted as $key => $val)
                {
                    $payment_response = $payment->deletebydict($val,$user_id,$order_no);
                    // print_r($response);
                    if($payment_response == "mysql_error" || $payment_response == "try_catch_error"){
                        $payment_flag = 1;
                    }
                }

                foreach ($dispatch_list_deleted as $key => $val)
                {
                    $dispatch_response = $dispatch->deletebydict($val,$user_id,$order_no);
                    // print_r($response);
                    if($dispatch_response == "mysql_error" || $dispatch_response == "try_catch_error"){
                        $dispatch_flag = 1;
                    }
                }
                                

                
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

    function delete(){
        // echo "deleted function";
        // sanitize

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $order_id=htmlspecialchars(strip_tags($_POST['order_id']));

        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    updated_by=:updated_by,
                    deleted_at=:deleted_at";

        // $update_arr =  array();
        $query = $query." WHERE order_no=".$order_id.";";
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

        global $item;
        global $payment;
        global $dispatch;
        global $po;


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
                "order_no" => $order_no,
                "order_date" => $order_date,
                "order_type" => $order_type,
                "other_refrance_no" => $other_refrance_no,
                "client_name" => $client_name,
                "order_delivery_date" => $order_delivery_date,
                "current_status" => $current_status,
                "remark" => $remark,
                "created_by" => $created_by,
                "updated_by" => $updated_by,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );

            $item_list = $item->show_self_without_post($order_no,"so");
            $row_item["item_list"] = $item_list;  

            $payment_list = $payment->show_self_without_post($order_no);
            $row_item["payment_list"] = $payment_list;  

            $dispatch_list = $dispatch->show_self_without_post($order_no);
            $row_item["dispatch_list"] = $dispatch_list;

            $po_list = $po->show_list_by_order_without_post($order_no);
            $row_item["po_list"] = $po_list;
             
     
            array_push($rows_arr, $row_item);
            // print_r($rows_arr);
        }

        // print_r($rows_arr);
        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "order detail fetched",
                            "data" => $rows_arr
                            )
                        )
                ); 

        

    }


    function show_self(){

        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));
        $order_id=htmlspecialchars(strip_tags($_POST['order_id']));

        global $item;
        global $payment;
        global $dispatch;
        global $po;

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
                "order_no" => $order_no,
                "order_date" => $order_date,
                "order_type" => $order_type,
                "other_refrance_no" => $other_refrance_no,
                "client_name" => $client_name,
                "order_delivery_date" => $order_delivery_date,
                "current_status" => $current_status,
                "remark" => $remark,
                "created_by" => $created_by,
                "updated_by" => $updated_by,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "deleted_at" => $deleted_at
            );     
            array_push($rows_arr, $row_item);
        }
        $item_list = $item->show_self_without_post($order_id,"so");
        $rows_arr[0]["item_list"] = $item_list;  

        $payment_list = $payment->show_self_without_post($order_id);
        $rows_arr[0]["payment_list"] = $payment_list;  

        $dispatch_list = $dispatch->show_self_without_post($order_id);
        $rows_arr[0]["dispatch_list"] = $dispatch_list;  


        $po_list = $po->show_list_by_order_without_post($order_no);
        $rows_arr[0]["po_list"] = $po_list;

        return json_encode(
                    array(
                        "success" => array(
                            "code" => "201",
                            "message" => "order detail fetched",
                            "data" => $rows_arr[0]
                            )
                        )
                ); 

        

    }



    function getXLSX($order_arr){


        // print_r($order_arr);

        $xls_rows = array();
        $item_xls_rows = array();
        $payment_xls_rows = array();
        $dispatch_xls_rows = array();
        
        foreach ($order_arr as $order) {
            // print_r($order);
            $xls_row = array();
            
            array_push($xls_row, $order["order_no"]);
            array_push($xls_row, $order["order_date"]);
            array_push($xls_row, $order["order_type"]);
            array_push($xls_row, $order["other_refrance_no"]);
            array_push($xls_row, $order["client_name"]);
            array_push($xls_row, $order["order_delivery_date"]);

            $po_arr = array();

            foreach ($order["po_list"] as $po) {
                # code...
                array_push($po_arr, $po["po_no"]);
            }
            $po_str = join(",",$po_arr);
            // print_r($po_arr);
            array_push($xls_row, $po_str);
            array_push($xls_row, $order["current_status"]);
            array_push($xls_row, $order["remark"]);
            // array_push($xls_row, $order["order_no"]);

            $curr_item_xls_rows = array();
            foreach ($order["item_list"] as $item) {
                # code...
                $curr_item_xls_row = array();
                array_push($curr_item_xls_row, $item["jewellry_type"]);
                array_push($curr_item_xls_row, $item["metal_col_purity"]);
                array_push($curr_item_xls_row, $item["category"]);
                array_push($curr_item_xls_row, $item["approx_quantity"]);
                array_push($curr_item_xls_row, $item["approx_weight"]);
            
                array_push($curr_item_xls_rows, $curr_item_xls_row);

            }
            $curr_payment_xls_rows = array();
            foreach ($order["payment_list"] as $item) {
                # code...
                // print_r($item);
                $curr_payment_xls_row = array();
                array_push($curr_payment_xls_row, $item["payment_date"]);
                array_push($curr_payment_xls_row, $item["payment_mode"]);
                array_push($curr_payment_xls_row, $item["payment_received"]);

                array_push($curr_payment_xls_rows, $curr_payment_xls_row);

            }
            $curr_dispatch_xls_rows = array();
            foreach ($order["dispatch_list"] as $item) {
                # code...
                // print_r($item);
                $curr_dispatch_xls_row = array();
                array_push($curr_dispatch_xls_row, $item["invoice_no"]);
                array_push($curr_dispatch_xls_row, $item["dispatch_date"]);
                array_push($curr_dispatch_xls_row, $item["courier_name"]);
                array_push($curr_dispatch_xls_row, $item["pod_no"]);
                array_push($curr_dispatch_xls_row, $item["remark"]);
                
                array_push($curr_dispatch_xls_rows, $curr_dispatch_xls_row);

            }

            array_push($xls_rows, $xls_row);
            array_push($item_xls_rows, $curr_item_xls_rows);
            array_push($payment_xls_rows, $curr_payment_xls_rows);
            array_push($dispatch_xls_rows, $curr_dispatch_xls_rows);


        }

        // print_r($item_xls_rows);


        $mydate=getdate(date("U"));
        // print_r($mydate) ;
        $eof = $mydate["mday"]."_".$mydate["month"]."_".$mydate["year"].".xlsx";
        // print_r($eof);
        $editFile = dirname(__DIR__)."/sheets/OrderBook".$eof;


        copy("api/template/OrderBook.xlsx",$editFile);
        chmod($editFile, 0777); 


        $sSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $editFile );
        //working on something with the spreadsheet/worksheet
        $worksheet = $sSheet->getSheetByName('Sales Order');
        // $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($colLine, $rowLine, $value);
        //this is how to write directly using loaded spreadsheet data

        $rs = 5;
        $i = 0;
        foreach ($xls_rows as $xls_row) {
            # code...
            $worksheet->fromArray([$xls_row], NULL, 'A'.$rs);
            $rs = $rs + 1;
            $rsi = $rs;
            $rsp = $rs;
            $rsd = $rs;
            foreach ($item_xls_rows[$i] as $item_row) {
                # code...
                // print_r($item_row);
                $worksheet->fromArray([$item_row], NULL, 'J'.$rsi);
                $rsi = $rsi + 1;
            }
            foreach ($payment_xls_rows[$i] as $item_row) {
                # code...
                // print_r($item_row);
                $worksheet->fromArray([$item_row], NULL, 'O'.$rsp);
                $rsp = $rsp + 1;
            }
            foreach ($dispatch_xls_rows[$i] as $item_row) {
                # code...
                // print_r($item_row);
                $worksheet->fromArray([$item_row], NULL, 'R'.$rsd);
                $rsd = $rsd + 1;
            }

            
            $rs = $rs + max(sizeof($item_xls_rows[$i]),sizeof($payment_xls_rows[$i]),sizeof($dispatch_xls_rows[$i]));
            $i = $i + 1;

        }


        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sSheet);
        $writer->save( $editFile );

        $url =  $home_url."/api/sheets/OrderBook".$eof ;

        if(file_exists($editFile)){

            // return json_encode(
            //         array(
            //             "success" => array(
            //                 "code" => "201",
            //                 "message" => "xlsx file built",
            //                 "data" => array('url' =>$url)
            //                 )
            //             )
            //     ); 
            return "OrderBook".$eof;
        }
        else{

            // return json_encode(
            //         array(
            //             "error" => array(
            //                 "code" => "503",
            //                 "message" =>  "error while file creating",
            //                 "data" => ""
            //                 )
            //             )
            //     );
            return "error";
        }



    }

}

?>