<?php
class Controller{

   private $request = null;
   private $view = null;
   private $output = '';

   public function __construct($request){			
	$this->request = $request;
	// select action either from request or from default action
        if(empty($request['action'])) $this->action = 'default'; 
        else $this->action = $request['action'] ;
   }

   // method to show the view and control the model
   public function display(){
	$view = new View();
        $model = new Model();
        $entries = array();

        switch($this->action){
	     	// show all server from database, by call the method in the model
         	case 'showInstClients':
            	   $server = $model->getInstClients();
  	           $this->output = $view->displayInstClients($server);
	        break;	
	
	     	// insert new server in the database		
	     	case 'insertInstClient':
	           // call accept method to add configuration files
	           $this->accept();			  
	           $result= $model->insertInstClient($this->request);	
		   // get FAI-classes from database, to display in formular
		   $classes=$model->getClass();
		   $bootconf = $this->getBootconf();
	           $this->output=$view->displayFormular($classes, $bootconf);
	        break;

		case 'removeServer':
                   $model -> removeInstClient($this->request);
        	   $server = $model->getInstClients();
                   $this->output = $view->displayInstClients($server);
                break; 

		case 'showClass':
		   $classes = $model->getClass();		
		   $this->output=$view->displayClass($classes);                
		break;

		// add a new FAI-Class
	     	case 'insertClass':
	           $result=$model->insertClass($this->request);
		   // get FAI-classes from database, to display in formular
		   $classes = $model->getClass();
	           $this->output=$view->displayClass($classes);
	        break;

		// remove a FAI-Class
		case 'removeClass':
		   $model->removeClass($this->request);	
		   // get FAI-classes from database, to display in formular
		   $classes = $model->getClass();
            	   $this->output=$view->displayClass($classes);
		break;

		case 'removeConfig':
		   // remove old configurations
                   $this->remove_config();
                   // get FAI-classes from database, to display in formular
                   $classes = $model->getClass();
		   $bootconf = $this->getBootconf();
                   $this->output=$view->displayFormular($classes, $bootconf);
                break;

	     	// generate CSV-file from database
	     	case 'generateCSV':
	           $model -> getCSV();
	        break;  

		case 'monitor':
                   $this->output=$view->displayMonitor();
		break;

		case 'controlMonitor':
		   $this->controlMonitor(); 
		   $this->output=$view->displayMonitor();
		break;
 
		default:
	  	   // get FAI-classes from database, to display in formular
		   $classes = $model->getClass();
		   $bootconf = $this->getBootconf();
		   $this->output=$view->displayFormular($classes, $bootconf);
		break;
        }
		
	// return view output to index.php
	return $this->output;
   }

/**********************
 ** private functions**
 **********************/

   private function accept (){
	// Build additional classes for FAI
	if(isset($_POST["faiclass"])){
	   // check FAI-Classes
	   $faiclass=implode(",", $_POST["faiclass"]);
	   $faiarray=$_POST["faiclass"];
	   // check Debian version
	   for($i=0; $i<sizeof($_POST["faiclass"]); $i++){
	      if($faiarray[$i]=="DEBIAN7") {$debian="DEBIAN7"; $nfsroot="nfsroot"; $kernel = "vmlinuz-3.2.0-4-amd64"; $initrd = "initrd.img-3.2.0-4-amd64"; break;}
	      else {$debian="DEBIAN8"; $nfsroot="nfsroot"; $kernel = "vmlinuz-3.16.0-4-amd64"; $initrd = "initrd.img-3.16.0-4-amd64";}
	   }
	}
if(!isset($debian)){
$faiclass="";
$debian="DEBIAN8";
$nfsroot="nfsroot"; $kernel = "vmlinuz-3.16.0-4-amd64"; $initrd = "initrd.img-3.16.0-4-amd64";
}

	// Build hostname + full qualified domain name
	$host=explode(".", $_POST['hostname']);
	$hostname=$host[0];
	$branch=$host[1];

	// remove separator and add the separator "-"
	$mac=$this->removeSeparator($_POST['MAC']);
	$mac=$this->addSeparator($mac);

	// remove old configurations
	$this->remove_file("/srv/fai/config/files/etc/network/interfaces/$hostname");
	$this->remove_file("/srv/fai/config/files/etc/hosts/$hostname");
	$this->remove_file("/srv/tftp/fai/pxelinux.cfg/01-$mac");

	/* pxe configuration */
	$config = file_get_contents("templates/pxe_conf");
	$config = str_replace("%KERNEL", $kernel, $config);
	$config = str_replace("%INITRD", $initrd, $config);
	$config = str_replace("%NFSROOT", $nfsroot, $config);
	$config = str_replace("%HOSTNAME", $hostname, $config);
	if($debian=="DEBIAN8") $faiclass=$debian ."," .$faiclass;
	$config = str_replace("%ADDCLASSES", $faiclass, $config);
	
	file_put_contents("/srv/tftp/fai/pxelinux.cfg/01-$mac", $config);

	if($_POST['address']=="static"){
		/* interface configuration */
		$config = file_get_contents("templates/interfaces");
		$config = str_replace("%TYPE", "static", $config);
		$config = str_replace("%IP", $_POST['ip'], $config);
		$config = str_replace("%NETMASK", $_POST['netmask'], $config);
		$config = str_replace("%GATEWAY", $_POST['gateway'], $config);
		file_put_contents("/srv/fai/config/files/etc/network/interfaces/$hostname", $config);

		/* host configuration */
		$config = file_get_contents("templates/hosts");
		$config = str_replace("%IP", $_POST['ip'], $config);
		$config = str_replace("%FQDN", $_POST['hostname'], $config);
		$config = str_replace("%HOSTNAME", $hostname, $config);
	    file_put_contents("/srv/fai/config/files/etc/hosts/$hostname", $config);
	}

	// create password file in /class with $hostname.var 
	if($_POST['psw']!=""){
	   // generate random salt
	   $salt=mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
	   $salt=substr(base64_encode($salt), 1, 15);
	   // new password encrypted with SHA512
	   $password="# password hash for $hostname\nROOTPW='" .crypt($_POST['psw'], "$6$$salt$") ."'";
	   file_put_contents("/srv/fai/config/class/$hostname.var", $password);
	}
   }

   private function removeSeparator($mac, $separator = array(':', '-')){  	
	return str_replace($separator, '', $mac);
   }

   private function addSeparator($mac, $separator = '-'){
	return join($separator, str_split($mac, 2));
   }

   private function remove_config(){
	// get the hostname from the configuration
	$inhalt=implode("", file("/srv/tftp/fai/pxelinux.cfg/$_POST[bootConf]"));
	preg_match("/hostname=(?P<name>[A-Za-z0-9-]+)/", $inhalt, $hostname);
	// remove ip configuration
	$this->remove_file("/srv/fai/config/files/etc/network/interfaces/$hostname[name]");
	// remove hosts configuration
	$this->remove_file("/srv/fai/config/files/etc/hosts/$hostname[name]");
	// remove resolv.conf
	$this->remove_file("/srv/fai/config/files/etc/resolv.conf/$hostname[name]");
	// remove pxelinux.cfg configuration
	$this->remove_file("/srv/tftp/fai/pxelinux.cfg/$_POST[bootConf]");
	// remove password file
	$this->remove_file("/srv/fai/config/class/$hostname[name].var");
   }

   private function remove_file($directory){
	if(file_exists($directory)) unlink($directory);
   }

   private function controlMonitor(){
	$log_path="/var/www/html/fai-monitor.log";
	if($_POST['status']=="start"){
	   // start daemon if it works
	   $fai_pid=exec("pgrep fai-monitor");
 	   if($fai_pid=="") exec("fai-monitor -l $log_path ");
	}
	elseif($_POST['status']=="refresh") file_put_contents("$log_path", "");
   }

   private function getBootconf(){
        // read boot configuration directory
        $dir="/srv/tftp/fai/pxelinux.cfg";
        // build json data
        $json_data = '[';

        // get all pxe configurations
        $files=glob("/srv/tftp/fai/pxelinux.cfg/01-*");
        usort($files, function($a,$b){ // sort by modified time
           return filemtime($a)<filemtime($b);
        });

        // get hostname from each configuration and build json data
        foreach($files as $file){
           $content=implode("", file($file));
           preg_match("/hostname=(?P<name>[A-Za-z0-9-]+)/", $content, $hostname);
           $host=$hostname['name'];
           $json_data .= '{"file":"' . substr($file,27) . '","hostname":"' . $hostname['name'] .'"},';
        }
        $json_data=substr($json_data, 0, -1); // delete last comma
        $json_data .= ']';
        return $json_data;
   }

}
?>
