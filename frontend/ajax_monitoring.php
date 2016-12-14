<?php
// class for each host in fai-monitor.log
class clientMonitor{
	public $name = "";
	public $state = array(0,0,0,0,0,0,0,0,0,0,0);
}

$log_dir="/var/www/html/fai-monitor.log"; // fai-monitor log file
$file=fopen($log_dir, "r") or die("Can't open input file.");
$task=0; // current fai task
$line=array(); // whole lines of fai-monitor.log 
$line_array=array(); // current line of fai-monitor.log
$host_number=-1; // current host
$inst_counter=0; // counter for multiple installations

// get each line in fai-monitor.log
for($i=0;!feof($file);$i++) {
$line[$i] = fgets($file);
// create class clientMonitor for each host in fai-monitor.log
if(preg_match('/check/', $line[$i])) $host_monitor[]=new clientMonitor();;
}
fclose($file);

// analyse each line
for($i=0;$i<sizeof($line);$i++){
   // each part in one array
   $line[$i]=trim($line[$i]);
   $line_array=explode(" ", $line[$i]);
   if(preg_match('/check/', $line[$i])){
      // set new host
      $host_number++;
      $host_monitor[$host_number]->name=$line_array[0];
   }

   // if there is no next line, the installation is finished
   if(isset($line[$i+1])) $next=explode(" ", $line[$i+1]);
   if(isset($line_array[1])){
	// ignore some lines
	if(!(preg_match('/FAI/', $line[$i]))&& !(preg_match('/check/', $line[$i])) && !(preg_match('/Caught/', $line[$i]))){
	   // check multiple installation
           $temp=$line_array[0];
	   // search right host for current line
	   while($host_monitor[$host_number]->name!=$temp){
	      $host_number--;
	      $inst_counter++;
	   }

     	   if(isset($line_array[2])){
              switch($line_array[2]){
	        case 'confdir': $task=0; break;
		case 'defclass': $task=1; break;
		case 'partition': $task=2; break;
		case 'extrbase': $task=3; break;
		case 'repository': $task=4; break;
		case 'instsoft': $task=5; break;
		case 'configure': $task=6; break;
		case 'tests': $task=7; break;
		case 'savelog': $task=8; break;
		case 'faiend': $task=9; break;
		case 'reboot': $task=10; break;
		default: $task=-1;
              }
	      // set states for each task
	      if($task!=-1){
	         if($line_array[1]=='TASKBEGIN') $host_monitor[$host_number]->state[$task]='begin';
	         elseif($line_array[1]=='TASKEND'){
                    if($line_array[3]<300) $host_monitor[$host_number]->state[$task]='success';
		    elseif($line_array[3]<500) $host_monitor[$host_number]->state[$task]='warning';
		    elseif($line_array[3]<700) $host_monitor[$host_number]->state[$task]='minor';
		    else $host_monitor[$host_number]->state[$task]='fail';
                 }
	      }
      	   }

	   // set counter back to current host
	   while($inst_counter!=0){
	      $host_number++;
	      $inst_counter--;
	   }
	}
   }

}

// after refresh, the $host_monitor is not set
if(isset($host_monitor)) echo json_encode($host_monitor);

?>
