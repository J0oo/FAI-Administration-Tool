function errorMessage() {
   var mac=document.formular.MAC.value;
   var hostname=document.formular.hostname.value;
   var ip=document.formular.ip.value;
   var netmask=document.formular.netmask.value;
   var gateway=document.formular.gateway.value;
   var error="";
   var dhcp=document.formular.address[0].checked;
   var stat=document.formular.address[1].checked;
   var opts=getSelectedOptions(document.formular.elements['faiclass[]']);

   // check for errors in formular
   if(mac!="" && !macIsValid(mac)) error=error+"\n- MAC address is not correct";
   if(mac=="") error=error+"\n- MAC address must be specified";
   if(hostname=="") error=error+"\n- FQ name must be specified";
   if(stat && ip!="" && !AddressIsValid(ip)) error=error+"\n- IP address is not correct";
   if(stat && ip=="") error=error+"\n- IP address must be specified";
   if(stat && netmask!="" && !AddressIsValid(netmask)) error=error+"\n- Netmask is not correct";
   if(stat && netmask=="") error=error+"\n- Netmask must be specified";
   if(stat && gateway!="" && !AddressIsValid(gateway)) error=error+"\n- Gateway is not correct";
   if(stat && gateway=="") error=error+"\n- Gateway must be specified";

   if(error==""){
	if(stat) var r=confirm("Please check your entries again! \n\nMAC address: " +mac +"\nHostname: " +hostname +"\nIP address: " +ip +"\nNetmask: "+netmask +"\nGateway: " +gateway +"\nFAI-Class: " +opts.toString() );
	else var r=confirm("Please check your entries again! \n\nMAC address: " +mac +"\nHostname: " +hostname +"\nFAI-Class: " +opts.toString());
	// Check whether the configuration already exists and query whether you want to overwrite
	// seperator is different, therefore compare without seperator
	if(r==true){
	   var shortmac=mac.replace(/:|-/g, "");
	   for(i=0; i<document.formConf.bootConf.length; i++){
	      var shortkonf=document.formConf.bootConf.options[i].value.replace(/-/g, "");
	      if(shortkonf=="01"+shortmac){
	         var s=confirm("A configuration with the same name already exists. Do you really want to replace this?");
		 if(s==true) return true;
		 return false;
	      }
	   }
	   return true;
	} else return false;
    }
	
   // show error message
   error="Formular input is incomplete\n"+error;
   alert(error);
   return false;
}

function errorMessageClass(){
   var error="";
   var faiclass=document.formularAddClass.faiclass.value;
   var description=document.formularAddClass.description.value;

   // check for errors in class formular
   if(faiclass=="") error=error+"\n- Class name is not set";
   if(description=="") error=error+"\n- Description name is not set";
   if(error==""){
	return true;
   }
   
   // show error message
   error="Formular input is incomplete\n"+error;
   alert(error);
   return false;

}

function getSelectedOptions(sel){
   var opts=[], opt;
   
   // loop through options in select list
   for (var i=0, len=sel.options.length; i<len; i++) {
	opt = sel.options[i];
        // check if selected
        if(opt.selected) opts.push(opt.value); // add to array of option elements to return from this function
   }
   // return array containing references to selected option elements
   return opts;
}

// function to check validation for mac
function macIsValid(mac){
   var reg=/([a-fA-F0-9]{2}[:|\-]?){6}/;
   return reg.test(mac);
}

// function to check validation for ip address
function AddressIsValid(addr){
   var reg=/^((25[0-5]|2[0-4][0-9]|1?[0-9]?[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1?[0-9]?[0-9])$/;
   return reg.test(addr);
}

// delete message 
function removeConfiguration(){
   if(document.formConf.bootConf.value=="default"){
   alert("The \"default\" configuration can not delete!");
   return false;
   }

   var pxeFile=document.formConf.bootConf.value;
   var r=confirm("Do you really want to delete the configuration \"" +pxeFile +"\" ?");
   if(r==true) return true;
   else	return false;
}

// function to perform the configuration (pxelinux.cfg) with the contents to get from the server
function configHandler() {
   // URL to invoke the callback funktion
   var url = 'http://%FAI_SERVER/viewConfiguration.php?+"&callback=show';
   // paste a new script tag into the DOM tree
   var newScript = document.createElement("script");
   newScript.setAttribute("src", url);
   newScript.setAttribute("id", "jsonp");
   var oldScript = document.getElementById("jsonp");	
   // hang under head
   var head = document.getElementsByTagName("head")[0];
	
   if(oldScript==null) head.appendChild(newScript);
   else head.replaceChild(newScript, oldScript);

   return false;
}

// function to display the content of the desired configuration
function show(data){
   document.getElementById("p1").innerHTML=data[document.formConf.bootConf.value];
}
