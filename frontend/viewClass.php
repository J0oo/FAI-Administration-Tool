<?php require_once 'header.php';?>

<script type="text/javascript" src="javascript/control.js"></script>
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
    <li class="active"><a href="mvc_index.php?action=showClass" >Classes</a></li>
    <li><a href="mvc_index.php?action=monitor">Monitoring</a></li>
    <li><a href="mvc_index.php?action=showInstClients">Installclients</a></li>
    <li><a href="logout.php">Logout</a></li>
   </ul><br>
  </div>

  <!-- Center -->
  <div class="col-sm-9">
   <h4><small>Network Installation</small></h4>
   <hr>
   <h2>FAI-Class</h2>
   <p>All available FAI-Classes</p>

   <table class="table table-hover">
    <thead>
     <tr>
      <th>Class</th>
      <th>Description</th>
      <th></th>
     </tr>
    </thead>
    <tbody >

<?php
foreach($classes as $value){
   echo "<tr>";
   foreach($value as $item) echo "<td>" . $item . "</td>";
   $class=$value["class"];
   echo "<td><a href=\"mvc_index.php?action=removeClass&class=$class\">delete</a></td>";
   echo "</tr>";
}
?>

    </tbody>
   </table>

   <form class="form-horizontal" role="form" name="formularAddClass" method="post" action="mvc_index.php?action=insertClass" onsubmit="return errorMessageClass();">
    <div class="form-group">
     <label class="col-sm-2 control-label">New Class</label>
     <div class="col-sm-10">
      <input style="max-width: 300px;" name="faiclass" class="form-control" id="focusedInput" type="text" placeholder="CLASSNAME">
     </div>
     <label class="col-sm-2 control-label">Description</label>
     <div class="col-sm-10">
      <input style="max-width: 300px;" name="description" class="form-control" id="focusedInput" type="text" placeholder="Description">
     </div>
    </div>
    <button type="submit" class="btn btn-success"  onclick="document.formularAddClass.faiclass.value=document.formularAddClass.faiclass.value.toUpperCase();">Submit</button>
   </form>
  </div>
 </div>
</div>

<?php require_once 'footer.php';?>
