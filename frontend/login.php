<?php
# check session cookie
session_start();
$_SESSION['ERROR'] = '';

# ldap data
$server = 'ldap://';
$user = $_POST['user'].'@domain';
$password = $_POST['password'];
$searchbase = 'DC=,DC=';
$searchstring = "(sAMAccountName=".$_POST['user'].")";
/*
# try connect to ldap server
$connect = ldap_connect($server) or die("No connection to LDAP-Server");

# set ldap options
ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);

if($connect) {
  $bind = ldap_bind($connect, $user, $password);

  if ($bind) {
        $result = ldap_search($connect, $searchbase, $searchstring) or die ("Error in search query: ".ldap_error($connect));
        $data = ldap_get_entries($connect, $result);
        $_SESSION['ERROR'] = '';

	$groups=implode($data[0]['memberof']);
	$test_group=preg_match("/CN=,OU=,DC=/", $groups);
        if($test_group==1) $_SESSION['ERROR'] = '';
        else $_SESSION['ERROR'] = "You're not authorized";
  } else {
        $_SESSION['ERROR'] = ldap_error($connect);
  }
}

ldap_close($connect);

if (isset($_POST['user']) && ($_SESSION['ERROR'] == '')) {
  $_SESSION['USER'] = $_POST['user'];
  header('location: mvc_index.php');
}
else {
  header('location: index.php');
}
*/

$_SESSION['USER'] = 'fai';
header('location: mvc_index.php');
?>

