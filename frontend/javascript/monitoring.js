// invoke getInstallCycle every 2 seconds
setInterval(function(){getInstallCycle();}, 2000);

function getInstallCycle(){
   var url = "ajax_monitoring.php";
   var request = new XMLHttpRequest(); // create object vor XMLHttpRequest to exchange data from server

   request.onreadystatechange = function() { // use onreadystatechange-Event 
	// check readyState and status to get data from server
   	if(request.readyState == 4 && request.status == 200) updateMonitor(request.responseText); 
   };
   request.open("GET", url, true); // true for async
   request.send(null);  //request abschicken
}

function updateMonitor(responseText){ 
   var table = document.getElementById("monitor");

   // delete old rows in order to update
   while(table.rows.length > 0) table.deleteRow(0);
	var cycle=JSON.parse(responseText);// change JSON-String to JavaScript object

  	for(var i=0; i<cycle.length; i++){ 
	   // insert data from JavaScript object
	   var data = cycle[i];
	   var row = table.insertRow(0);
	   var state = new Array();
	   var cell1=row.insertCell(0);
	   state=data.state;
	   cell1.innerHTML=data.name;

	   for(var j=0; j<state.length; j++){
	      var cell=row.insertCell(j+1);
	      // set pictures appropriate to the state
	      if(state[j]=="success") cell.innerHTML="<img src=\"pictures/ok.gif\"/>";
	      else if(state[j]=="minor") cell.innerHTML="<img src=\"pictures/minor.gif\"/>";
	      else if(state[j]=="warning") cell.innerHTML="<img src=\"pictures/warning.gif\"/>";
	      else if(state[j]=="fail") cell.innerHTML="<img src=\"pictures/fail.gif\"/>";
	      else if(state[j]=="begin") cell.innerHTML="<img src=\"pictures/begin.gif\"/>";
	      else if(state[j]=="host") cell.innerHTML="<img src=\"pictures/bar.gif\"/>";
	      else cell.innerHTML="";
	   }
	} 
}

