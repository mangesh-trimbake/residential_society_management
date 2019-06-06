<?php
// echo "handlers.php";

include_once 'restframe/web.php';

// instantiate headers
// include_once 'api/config/headers.php';

// // get database connection
// include_once 'api/config/database.php';

// instantiate order object
// include_once 'api/objects/order.php';
foreach (glob("api/config/*.php") as $filename)
{
    include_once $filename;
}

// echo "handlers.php = ";

include_once 'api/objects/wing.php';
include_once 'api/objects/service.php';
include_once 'api/objects/subscriptions.php';


include_once 'api/objects/society.php';
include_once 'api/objects/maintenance.php';

include_once 'api/objects/user.php';
include_once 'api/objects/payment.php';
include_once 'api/objects/event.php';

include_once 'api/objects/master.php';




class indexHandler  
{
	function get(){
		# code...
		// echo "Hello Wolrd";
		// echo file_get_contents("index.html");
		header('Location: index.html');
	}

	function post(){
		# code...
		echo "Hello Wolrd";
	}
}

	// user
class userAdd  {

	function get(){
		echo "userAdd class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new User($db);

		$response_data = $user->create();


		echo $response_data;	

	}
	
}



class userCreate  {

		function get(){
		echo "userCreate class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new User($db);

		$response_data = $user->create();


		echo $response_data;

	}
	
}


class userDelete  {

		function get(){
		echo "userDelete class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new User($db);

		$response_data = $user->delete();


		echo $response_data;

	}
	
}

class userLogin  {

		function get(){
		echo "userLogin class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new User($db);

		$response_data = $user->login();


		echo $response_data;

	}
	
}

class userLogout  {

		function get(){
		echo "userLogin class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new User($db);

		$response_data = $user->logout();


		echo $response_data;

	}
	
}

class userRead  {

		function get(){
		echo "userRead class get method" ;
	}

	function post(){
	
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new User($db);

		$response_data = $user->delete();


		echo $response_data;

	}
	
}

class userShow  {

		function get(){
		echo "userShow class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new User($db);

		$response_data = $user->show_self();


		echo $response_data;


	}
	
}

class userShowAll  {

		function get(){
		echo "userShowAll class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new User($db);

		$response_data = $user->showall_self();


		echo $response_data;

	}
	
}

class userUpdate  {

		function get(){
		echo "userUpdate class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new User($db);

		$response_data = $user->update();


		echo $response_data;

	}
	
}



// society

// society

class  societyAdd  {

		function get(){
		echo "societyAdd class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$society = new Society($db);

		$response_data = $society->create();


		echo $response_data;


	}
	
}

class  societyDelete  {

		function get(){
		echo "societyDelete class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$society = new Society($db);

		$response_data = $society->delete();


		echo $response_data;


	}
	
}

class  societyShow  {

		function get(){
		echo "societyShow class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new Society($db);

		$response_data = $user->show_self();


		echo $response_data;


	}
	
}

class  societyShowAll  {

		function get(){
		echo "societyShowAll class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new Society($db);

		$response_data = $user->showall_self();


		echo $response_data;


	}
	
}



class  societyUpdate  {

		function get(){
		echo "societyUpdate class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$society = new Society($db);

		$response_data = $society->update();


		echo $response_data;


	}
	
}


// maintenance

class  maintenanceAdd  {

		function get(){
		echo "maintenanceAdd class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$maintenance = new Maintenance($db);

		$response_data = $maintenance->create();


		echo $response_data;


	}
	
}

class  maintenanceDelete  {

		function get(){
		echo "maintenanceDelete class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$maintenance = new Maintenance($db);

		$response_data = $maintenance->delete();


		echo $response_data;


	}
	
}

class  maintenanceShow  {

		function get(){
		echo "maintenanceShow class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new Maintenance($db);

		$response_data = $user->show_self();


		echo $response_data;


	}
	
}

class  maintenanceShowAll  {

		function get(){
		echo "maintenanceShowAll class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new Maintenance($db);

		$response_data = $user->showall_self();


		echo $response_data;


	}
	
}



class  maintenanceUpdate  {

		function get(){
		echo "maintenanceUpdate class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$maintenance = new Maintenance($db);

		$response_data = $maintenance->update();


		echo $response_data;


	}
	
}





// event

class  eventAdd  {

		function get(){
		echo "eventAdd class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$event = new Event($db);

		$response_data = $event->create();


		echo $response_data;

	}
	
}

class  eventDelete  {

		function get(){
		echo "eventDelete class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$order = new Event($db);

		$response_data = $order->delete();


		echo $response_data;


	}
	
}

class  eventShow  {

		function get(){
		echo "eventShow class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new Event($db);

		$response_data = $user->show_self();


		echo $response_data;



	}
	
}

class  eventShowAll  {

		function get(){
		echo "eventShowAll class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$user = new Event($db);

		$response_data = $user->showall_self();


		echo $response_data;


	}
	
}

class  eventUpdate  {

		function get(){
		echo "orderUpdate class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$order = new Event($db);

		$response_data = $order->update();


		echo $response_data;



	}
	
}




// master
class  masterAdd  {

		function get(){
		echo "masterAdd class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$order = new Order($db);

		$response_data = $order->create();


		echo $response_data;



	}
	
}

class masterDelete  {

		function get(){
		echo "masterDelete class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$order = new Order($db);

		$response_data = $order->delete();


		echo $response_data;



	}
	
}

class  masterShow  {

		function get(){
		echo "masterShow class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$master = new Master($db);

		$response_data = $master->show_self();


		echo $response_data;



	}
	
}

class  masterShowAll  {

		function get(){
		echo "masterShowAll class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$master = new Master($db);

		$response_data = $master->showall_self();


		echo $response_data;



	}
	
}

class  masterShowe  {

		function get(){
		echo "masterShowe class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$master = new Master($db);

		$response_data = $master->show_self_e();


		echo $response_data;



	}
	
}

class  masterUpdate  {

		function get(){
		echo "masterUpdate class get method" ;
	}

	function post(){
		// echo "userAdd class" ;
		$database = new Database();
		$db = $database->getConnection();
		 
		$master = new Master($db);

		$response_data = $master->update();


		echo $response_data;



	}
	
}



// payment

class paymentAdd  {

		function get(){
		echo "paymentAdd class get method" ;
	}

	function post(){
		
		$database = new Database();
		$db = $database->getConnection();
		 
		$payment = new Payment($db);

		$response_data = $payment->create();


		echo $response_data;


	}
	
}




// echo "end of handlers";
?>

