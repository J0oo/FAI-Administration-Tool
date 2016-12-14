<?php require_once 'header.php';?>

 <script type="text/javascript" src="javascript/monitoring.js"></script>
</head>

<body>
<div class="container-fluid">
 <div class="row content">

  <!-- Sidebar -->
  <div class="col-sm-3 sidenav">
   <h4>FAI-Administration-Tool</h4>
   <ul class="nav nav-pills nav-stacked">
    <li>User: <?php echo $_SESSION['USER']; ?></li>
    <li><a href="mvc_index.php?action=default" >Formular</a></li>
    <li><a href="mvc_index.php?action=showClass" >Classes</a></li>
    <li class="active"><a href="mvc_index.php?action=monitor">Monitoring</a></li>
    <li><a href="mvc_index.php?action=showInstClients">Installclients</a></li>
    <li><a href="logout.php">Logout</a></li>
   </ul><br>
  </div>

  <!-- Center -->
  <div class="col-sm-9">
   <h4><small>Network Installation</small></h4>
   <hr>
   <h2>FAI-Monitoring</h2>
   <p>Monitor current installation</p>

<?php
$fai_pid=exec("pgrep fai-monitor");
if($fai_pid!="") echo "<div class=\"alert alert-success\"><strong>fai-monitor is running</strong></div>";
else echo "<div class=\"alert alert-danger\"><strong>fai-monitor is not running</strong></div>"; ?>

   <table class="table table-hover">
    <thead>
     <tr>
      <th>Hostname</th>
      <th>confdir</th>
      <th>defclass</th>
      <th>partition</th>
      <th>extrbase</th>
      <th>repository</th>
      <th>instsoft</th>
      <th>configure</th>
      <th>test</th>
      <th>savelog</th>
      <th>faiend</th>
      <th>reboot</th>
     </tr>
    </thead>
    <tbody id="monitor"></tbody>
   </table>
   <form role="form" method="post" class="form-vertical" action="mvc_index.php?action=controlMonitor">
    <button  type="submit" class="btn btn-success" name="status" value="start">Start</button>
    <button  type="submit" class="btn btn-info" name="status" value="refresh">Refresh</button>
   </form>
  </div>
 </div>
</div>

<?php require_once 'footer.php';?>
