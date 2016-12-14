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
    <li class="active"><a href="mvc_index.php?action=default" >Formular</a></li>
    <li><a href="mvc_index.php?action=showClass" >Classes</a></li>
    <li><a href="mvc_index.php?action=monitor">Monitoring</a></li>
    <li><a href="mvc_index.php?action=showInstClients">Installclients</a></li>
    <li><a href="logout.php">Logout</a></li>
   </ul><br>
  </div>

  <!-- Center -->
  <div class="col-sm-9">
   <h4><small>Network Installation</small></h4>
   <hr>
   <h2>Formular</h2>
   <p>Create new configuration for an installation client</p>

   <form class="form-horizontal" role="form" method="post" name="formular" action="mvc_index.php?action=insertInstClient" onsubmit="return errorMessage();">
    <div class="form-group">
     <label class="col-sm-2 control-label">MAC-Address</label>
     <div class="col-sm-10">
      <input style="max-width: 300px;" name="MAC" maxlength=17 class="form-control" id="focusedInput" type="text" placeholder="aa-bb-cc-dd-ee-ff">
     </div>
     <label class="col-sm-2 control-label" >FQDN <img src="pictures/info.png" alt="Logo" data-toggle="tooltip" data-placement="top" title="Fully qualified domain name"/></label>
     <script>$(document).ready(function(){$('[data-toggle="tooltip"]').tooltip();});</script>
     <div class="col-sm-10">
      <input style="max-width: 300px;" name="hostname" class="form-control" id="focusedInput" type="text" placeholder="myhost.example.com">
     </div>
    </div>
    <div class="form-group">
     <fieldset name="fieldset" disabled>
      <label for="disabledTextInput" class="col-sm-2 control-label">IP</label>
      <div class="col-sm-10">
       <input style="max-width: 300px;" type="text" name="ip" id="disabledTextInput" class="form-control" placeholder="xx.xx.xx.xx" maxlength=15>
      </div>
      <label for="disabledTextInput" class="col-sm-2 control-label">Netmask</label>
      <div class="col-sm-10">
       <input style="max-width: 300px;" type="text" name="netmask" id="disabledTextInput" class="form-control" placeholder="xx.xx.xx.xx" maxlength=15>
      </div>
      <label for="disabledTextInput" class="col-sm-2 control-label">Gateway</label>
      <div class="col-sm-10">
       <input style="max-width: 300px;" type="text" name="gateway" id="disabledTextInput" class="form-control" placeholder="xx.xx.xx.xx" maxlength=15>
      </div>
     </fieldset>
     <label style="margin-left: 18%;" class="radio-inline">
      <input type="radio" name="address" onclick="document.formular.fieldset.disabled=true;" value="dynamic" checked>dynamic
     </label>
     <label class="radio-inline">
      <input type="radio" name="address" onclick="document.formular.fieldset.disabled=false;" value="static">static
     </label>
    </div>
    <div class="form-group">
     <label class="col-sm-2 control-label">Password</label>
     <div class="col-sm-10">
      <input style="max-width: 300px;" class="form-control" id="focusedInput" type="password" name="psw">
     </div>
    </div>
    <div class="form-group">
     <label class="col-sm-2 control-label">FAI-Class <img src="pictures/info.png" alt="Logo" data-toggle="tooltip" data-placement="top" title="Choose additional FAI-Class; see Documentation - multiple with Ctrl+Left-MouseClick"/></label>
     <div class="col-sm-10">
      <select name="faiclass[]" style="max-width: 300px;" multiple class="form-control" id="sel2">
	  <?php
foreach($classes as $value){
   if($value["class"]!="GRUB_PC" && $value["class"]!="LINUX" && $value["class"]!="FAIBASE" && $value["class"]!="DHCPC" && $value["class"]!="DEFAULT" && $value["class"]!="LAST" && $value["class"]!="AMD64" && $value["class"]!="I386" && $value["class"]!="DEBIAN" && $value["class"]!="DEBIAN8")
   echo "<option>" . $value["class"] . "</option>";
}
?>

      </select>
     </div>
    </div>
    <label class="col-sm-2 control-label"></label>
    <button  type="submit" class="btn btn-success" onclick="document.formular.MAC.value=document.formular.MAC.value.toLowerCase();">Submit</button>
   </form>

   <!-- Configuration -->
   <hr>
   <h2>Boot-Configurations</h2>
   <form name="formConf" method="post" action="mvc_index.php?action=removeConfig" class="form-horizontal" role="form">
    <div class="form-group">
     <label class="col-sm-2 control-label"></label>
     <div class="col-sm-10">
      <select style="max-width: 300px;" multiple class="form-control" id="sel2" name="bootConf" size=10>

<?php
// decode json data from pxelinux.cfg
$obj=json_decode($bootconf, true);
foreach($obj as $value) echo "<option value=" .$value['file'] .">" .$value['file'] ." : " .$value['hostname'] ."</option>";
?>

      </select>
     </div>
    </div>
    <label class="col-sm-2 control-label"></label>
    <button type="submit" class="btn btn-info" onclick="return configHandler();" data-toggle="modal" data-target="#myModal">View</button>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
     <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
       <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">pxelinux.cfg</h4>
       </div>
       <div class="modal-body">
        <p id="p1"></p>
       </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
     </div>
    </div>
   </div>
   <button type="submit" class="btn btn-danger" onclick="return removeConfiguration();" >Delete</button>
  </form>
 </div>
</div>

<?php require_once 'footer.php';?>

