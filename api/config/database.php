<?php
// echo "database.php";
// error_reporting(0);
class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "residential_society_management";
    private $username = "development";
    private $password = "12345";

    //database credentials for production
    // private $host = "localhost";
    // private $db_name = "navan3i2_invent";
    // private $username = "navan3i2_invent";
    // private $password = "12345";

    public $conn;
 
    // get the database connection
    public function getConnection(){
        // echo "getConnection called";
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>
