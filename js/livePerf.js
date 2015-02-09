function init() {
   var navigationTiming = performance.timing;
   var now = new Date().getTime();
   var url = "mattshull.com"+location.pathname;
   var clientBackend = ((navigationTiming.responseEnd-navigationTiming.requestStart)/1000).toFixed(2);
   var clientFrontend = ((now-navigationTiming.domLoading)/1000).toFixed(2);
   var clientTotal = ((now-navigationTiming.navigationStart)/1000).toFixed(2);
   
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
      		console.log(xhr.responseText);
      	}
    }
    xhr.open('POST', './webperfSubmit.php', true);
    xhr.send(JSON.stringify(json));
}
window.onload = init;
