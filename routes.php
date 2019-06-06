<?php


include_once 'restframe/web.php';

include_once 'handlers_class.php';

$handlers = array(
				'/' => new indexHandler(),

				'/api/user/add' => new userAdd(),
				'/api/user/create' => new userCreate(),
				'/api/user/delete' => new userDelete(),
				'/api/user/login' => new userLogin(),
				'/api/user/logout' => new userLogout(),
				// '/api/user/read' => new userRead(),
				'/api/user/show' => new userShow(),
				'/api/user/showall' => new userShowAll(),
				'/api/user/update' => new userUpdate(),


				'/api/society/add' => new societyAdd(),
				'/api/society/delete' => new societyDelete(),
				'/api/society/show' => new societyShow(),
				'/api/society/showall' => new societyShowAll(),
				'/api/society/update' => new societyUpdate(),



				'/api/event/add' => new eventAdd(),
				'/api/event/delete' => new eventDelete(),
				'/api/event/show' => new eventShow(),
				'/api/event/showall' => new eventShowAll(),
				'/api/event/update' => new eventUpdate(),

				'/api/maintenance/add' => new maintenanceAdd(),
				'/api/maintenance/delete' => new maintenanceDelete(),
				'/api/maintenance/show' => new maintenanceShow(),
				'/api/maintenance/showall' => new maintenanceShowAll(),
				'/api/maintenance/update' => new maintenanceUpdate(),


				'/api/master/add' => new masterAdd(),
				'/api/master/delete' => new masterDelete(),
				'/api/master/show' => new masterShow(),
				'/api/master/showall' => new masterShowAll(),
				'/api/master/showe' => new masterShowe(),
				'/api/master/update' => new masterUpdate(),


				'/api/payment/add' => new paymentAdd(),


				


			);

// echo "routes.php";
$app = new WebApplication($handlers);
$app->run();




?>