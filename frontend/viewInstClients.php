<?php require_once 'header.php';?>

</head>

<body>
<div class="container-fluid">
 <div class="row content">

  <!-- Sidebar -->
  <div class="col-sm-3 sidenav">
   <h4>FAI-Administration-Tool</h4>
   <ul class="nav nav-pills nav-stacked">
    <li>User: <?php echo $_SESSION['USER']; ?></li>
    <li><a href="mvc_index.php?action=default">Formular</a></li>
    <li><a href="mvc_index.php?action=showClass">Classes</a></li>
    <li><a href="mvc_index.php?action=monitor">Monitoring</a></li>
    <li class="active"><a href="mvc_index.php?action=showInstClients">Installclients</a></li>
    <li><a href="logout.php">Logout</a></li>
   </ul><br>
  </div>

  <!-- Center -->
  <div class="col-sm-9">
   <h4><small>Network Installation</small></h4>
   <hr>
   <h2>Installclients</h2>
   <p>All server with network installation configuration</p>

   <table class="table table-hover">
    <thead>
     <tr>
      <th>MAC-Address</th>
      <th>FQDN</th>
      <th>IP</th>
      <th>Netmask</th>
      <th>Gateway</th>
      <th>FAI-Class</th>
      <th>Date</th>
      <th></th>
     </tr>
    </thead>
   <tbody>

<?php
foreach($server as $value){
   echo "<tr>";
   foreach($value as $item) echo "<td>" . $item . "</td>";
   $mac=$value['mac'];
   $date=str_replace(' ', '', $value['date']);
   echo "<td><a href=\"mvc_index.php?action=removeServer&mac=$mac&date=$date\">delete</a></td>";
   echo "</tr>";
}

?>
    </tbody>
   </table>
   <div class="form-group">
    <button type="submit" class="btn btn-success" onclick="window.location.href = 'mvc_index.php?action=generateCSV';">Download</button>
   </div>
  </div>
 </div>
</div>
<?php require_once 'footer.php';?>
