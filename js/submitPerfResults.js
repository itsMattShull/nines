function init() {
   var navigationTiming = performance.timing;
   var now = new Date().getTime();
  
   //get client page speed
   var clientBackend = ((navigationTiming.responseEnd-navigationTiming.requestStart)/1000).toFixed(2);
   var clientFrontend = ((now-navigationTiming.domLoading)/1000).toFixed(2);
   var clientTotal = ((now-navigationTiming.navigationStart)/1000).toFixed(2);
   var url = window.location.host + window.location.pathname; 

   document.getElementById('webperf-clientBackend').innerHTML = clientBackend;
   document.getElementById('webperf-clientFrontend').innerHTML = clientFrontend;
   document.getElementById('webperf-clientTotal').innerHTML = clientTotal;
   if (clientTotal>totalBudget) {
     document.getElementById("webperf-clientTotal").className = "webperf-red";
   }
   else {
     document.getElementById("webperf-clientTotal").className = "webperf-green";
   }
   
   //send client page speed to server & get average page speed back
   var json = {
      backend : clientBackend,
      frontend : clientFrontend,
      total : clientTotal,
      url : url
    }
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if(xhr.readyState == 4 && xhr.status == 200) {
        var response = JSON.parse(xhr.responseText);
        document.getElementById('webperf-serverBackend').innerHTML = response.backend;
      document.getElementById('webperf-serverFrontend').innerHTML = response.frontend;
      document.getElementById('webperf-serverTotal').innerHTML = response.total;
      if (response.total>totalBudget) {
        document.getElementById("webperf-serverTotal").className = "webperf-red";
      }
     else {
        document.getElementById("webperf-serverTotal").className = "webperf-green";
      }
      }
    }
    xhr.open('POST', './webperfSubmit.php', true);
    xhr.send(JSON.stringify(json));
}

window.onload = init;
