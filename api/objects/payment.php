<?php
// echo "payment.php";



$database = new Database();
$db = $database->getConnection();

$subscription = new Subscription($db);
$user = new User($db);


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Payment{
 
    // database connection and table name
    private $conn;
    private $table_name = "payment_transaction";

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // $item = new Item($this->conn);

    // create payment
    function create(){
        // echo "create function";
        // sanitize
        $user_id=htmlspecialchars(strip_tags($_POST['user_id']));

        $maintenance_id=htmlspecialchars(strip_tags($_POST['maintenance_id']));
        $wallet_amt=htmlspecialchars(strip_tags($_POST['wallet_amt']));
        $remain_amt=htmlspecialchars(strip_tags($_POST['remain_amt']));
        $enter_amount=htmlspecialchars(strip_tags($_POST['enter_amount']));
        $wallet_chbx=htmlspecialchars(strip_tags($_POST['wallet_chbx']));

        $subscription_list = (array)json_decode($_POST['subscription_list'],true);
        // print_r($subscription_list);
        $total_amt_have = $enter_amount;
        global $item;
        global $subscription;
        global $user;

        if($wallet_chbx == 1){
            $total_amt_have = $total_amt_have + $wallet_amt;
            $wallet_amt = 0;
        }

        foreach ($subscription_list as $key => $val)
        {
            // print_r($val);
            $pending = $val["total_amt_to_paid"] - $val["paid_amt"];

            if($total_amt_have!=0){

                $val["paid_amt"] = $val["paid_amt"] + $total_amt_have;

                $total_amt_have = 0;
                if($val["paid_amt"]>$val["total_amt_to_paid"]){

                    $total_amt_have = $val["paid_amt"] - $val["total_amt_to_paid"];
                    $val["paid_amt"] = $val["total_amt_to_paid"];
                }


                $subscription_response = $subscription->updatebydict($val,$user_id,$val["id"]);
            }
            
        }

        if($total_amt_have >0){
            $wallet_amt = $wallet_amt + $total_amt_have;
            $user_response = $user->updatebydict(array('wallet_amt'=>$wallet_amt),$user_id);
            
        }

        return json_encode(
                        array(
                            "success" => array(
                                "code" => "201",
                                "message" => "Payment was created.",
                                "data" => array(
                                    "id" => "",
                                    "wallet_amt" => $wallet_amt
                                    )
                                )
                            )
                    );

        
        
         
    }

    



    

}

?>