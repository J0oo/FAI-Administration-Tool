<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <meta name="description" content="Administration tool for FAI">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FAI-Administration-Tool</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
    .row.content {height: 900px}

    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }

  </style>
 </head>
 <body>

  <div class="container-fluid">
   <div class="row content">
    <h4><big>FAI-Installation</big></h4>
    <hr>
    <center>
     <h2>Login</h2>
     <form role="form" name="loginForm" action="login.php" method="POST">

<?php
if (isset($_SESSION['ERROR']) && $_SESSION['ERROR']!=''){
echo '<script> alert("'.$_SESSION['ERROR'].'"); </script>';
$_SESSION['ERROR']='';
}
else
  echo '';
?>

       <div class="form-inline">
        <div class="form-group">
         <div class="col-sm-10">
          <input style="width: 300px;" class="form-control" id="focusedInput" type="text" placeholder="Name" name="user">
         </div>
        </div>
        <div class="form-group">
         <div class="col-sm-10">
         <input style="width: 300px;" class="form-control" id="focusedInput" type="password" placeholder="Password" name="password">
        </div>
       </div>
      </div>
      <button style="margin-top: 20px;" type="submit" class="btn btn-success">Submit</button>
     </form>
    </center>
   </div>
  </div>

<?php require_once 'footer.php';?>
