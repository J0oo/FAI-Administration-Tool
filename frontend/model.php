<?php
// define MySQL data
define('DBHOST', 'localhost');
define('DBUSER','fai_user');
define('DBPASSWORD','fai');
define('DB','fai');

class Model{
   private $db;

   public function __construct(){
	// create database connection
	$this->db = new mysqli(DBHOST, DBUSER, DBPASSWORD);

	if(mysqli_connect_errno()) die("Error at connection to database: ". mysqli_connect_error());
	$this->db->select_db(DB);
	if($this->db->errno) die($this->db->error);
   }

   public function insertInstClient($data) {
	// insert client data to database
	$db_str = "INSERT INTO server (mac, fqdn, ip, netmask, gateway, fai_class) VALUES ('@mac', '@fqdn', '@ip', '@nm', '@gw', '@class');";
    	$db_str = str_replace("@mac",$data['MAC'],$db_str);
   	$db_str = str_replace("@fqdn",$data['hostname'], $db_str);

   	if($data['address']=="dynamic"){
           $db_str = str_replace("@ip", "dynamic", $db_str);
           $db_str = str_replace("@nm", "", $db_str);
           $db_str = str_replace("@gw", "", $db_str);
   	} else{
           $db_str = str_replace("@ip", $data['ip'], $db_str);
           $db_str = str_replace("@nm", $data['netmask'], $db_str);
           $db_str = str_replace("@gw", $data['gateway'], $db_str);
   	}

   	if(isset($data["faiclass"])) $faiclass=implode(",", $data["faiclass"]);
   	else $faiclass="";

   	$db_str = str_replace("@class", $faiclass, $db_str);
   	$result=$this->db->query($db_str);
	if(!$result) die("Database error: ". $this->db->error);
   }

   public function getInstClients() {
	// get client data from database
	$clients = array();
	$result = $this->db->query("SELECT mac,fqdn,ip,netmask,gateway,fai_class,date FROM server ORDER BY date;");
	if(!$result) die("Database error: ". $this->db->error);
	while($row = $result->fetch_assoc()) $clients[] = $row; // fetch to associative array

	return $clients;
   }

public function removeInstClient($data){
        $date=chunk_split($data['date'],10,' ');
	$mac=$data['mac'];
      	$result=$this->db->query("DELETE FROM server WHERE mac='$mac' AND date='$date';");
      	if(!$result) die("Database error: ". $this->db->error);
   }

   public function getClass(){
	// get classes from database
	$classes = array();
        $result = $this->db->query("SELECT class,description FROM classes ORDER BY class;");
        if(!$result) die("Database error: ". $this->db->error);
        while($row = $result->fetch_assoc()) $classes[] = $row; // fetch to associative array

        return $classes;
   }

   public function insertClass($data){
	// insert class to database
    	$db_str = "INSERT INTO classes (class, Description) VALUES ('@class', '@description');";
    	$db_str = str_replace("@class",$data['faiclass'],$db_str);
    	$db_str = str_replace("@description", $data['description'],$db_str);
    	$result=$this->db->query($db_str);
	if(!$result) die("Database error: ". $this->db->error);
   }

   public function removeClass($data){
	// remove class from database
	$class=$data['class'];
	$result=$this->db->query("DELETE FROM classes WHERE class = '$class';");
    	if(!$result) die("Database error: ". $this->db->error);
   }

   public function getCSV(){
	// get data from database and download as csv
	$result = $this->db->query("SELECT mac,fqdn,ip,netmask,gateway,fai_class,date FROM server ORDER BY date;") or die("Database error: ". $this->db->error);
	// get current date as filename
    	$timestamp = time();
    	$date = date("d.m.Y_H.i", $timestamp);
    	$filename="fai_$date.csv";

	// output headers so that the file is downloaded
	header('Content-Type: text/csv');
        header("Content-Disposition: attachment;filename=$filename");

	$output = fopen('php://output', 'w');
	// output database header
	fputcsv($output, array("MAC-Address", "FQ-Name", "IP", "Netmask", "Gateway", "FAI-Class", "Date"), ';');
	// output rows
	while($rows=$result->fetch_assoc()) fputcsv($output, $rows, ';');
	fclose($output);
   }
}
?>
