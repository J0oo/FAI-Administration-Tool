<?php
class View{

   // show input formular
   public function displayFormular($classes, $bootconf) {
	// create a buffer to save the output from the included .php and return to controller
	ob_start(); // activate output buffer
      	include 'viewFormular.php';
      	$output = ob_get_contents(); // write contents of output buffer to $output
      	ob_end_clean();

      	return $output;
   }


   // show all client data
   public function displayInstClients($server) {
     	ob_start();
      	include 'viewInstClients.php';
      	$output = ob_get_contents();
      	ob_end_clean();

      	return $output;
   }

   // show page with all classes
   public function displayClass($classes) {
	ob_start();
      	include 'viewClass.php';
      	$output = ob_get_contents();
      	ob_end_clean();

      	return $output;
   }

   // show monitoring page
   public function displayMonitor() {
      	ob_start();
      	include 'viewMonitor.php';
      	$output = ob_get_contents();
      	ob_end_clean();

      	return $output;
   }
}
?>

