<?php

// start page of the MVC-Presentationlayer

// include MVC-Classes
include('controller.php');
include('model.php');
include('view.php');

// put $_GET and $_POST together
$request = array_merge($_GET, $_POST);

// create controller and commit the request
$controller = new Controller($request);

// show content of the webapplication
echo $controller->display();

?>
