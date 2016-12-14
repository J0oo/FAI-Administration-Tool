<?php
# check callback parameter
if (isset($_GET["callback"]) && !empty($_GET["callback"])) {
   $callback = $_GET["callback"];

   # build response
   $dir="/srv/tftp/fai/pxelinux.cfg";
   # check whether the directory exists
   if($handle = opendir($dir)){
	# query the content from the directory
      	while(($file = readdir($handle))!== false){
           if($file!="." and $file!=".."){
	      # build file content to a string
	      $content=implode("", file("/srv/tftp/fai/pxelinux.cfg/$file"));
	      # associative array to query the content
              $file_array[$file]=$content;
	   }
        }
      	closedir($handle);
   }

  # create a JSON-String from array
  $json_data=json_encode($file_array);

  # send response
  echo $callback."($json_data);";
}
?>
