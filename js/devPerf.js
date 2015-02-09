var totalBudget = 2;
var liveSite = "mattshull.com"+location.pathname;
//Since this script will be on a dev site you'll need to add the hostname of the site manually
//This will fetch the live site statisitics from the database.

var html = '<section id="webperf-wrapper"><div id="webperf-client"><div class="webperf-label">Current</div><div class="webperf-value"><span id="webperf-clientBackend"></span> / <span id="webperf-clientFrontend"></span> / <span id="webperf-clientTotal"></span> secs</div></div><div id="webperf-server"><div class="webperf-label">Page Median</div><ul id="webperf-countries" class="webperf-hide"></ul><div class="webperf-value"><span id="webperf-serverBackend"></span> / <span id="webperf-serverFrontend"></span> / <span id="webperf-serverTotal"></span> secs</div></div><div id="webperf-resources"><div class="webperf-label">Resources</div><ul id="webperf-resourceInfo" class="webperf-hide"><li><span style="background-color:lightgray; font-weight:700;">File</span><span style="background-color:lightgray; font-weight:700;">Duration</span></li></ul></div><div id="webperf-webpagetest"><div class="webperf-label">WebPageTest.org (Cable)</div><ul id="webperf-webpagetestInfo" class="webperf-hide"><li><span><div style="background-color:lightgray; font-weight:700;">First Byte:</div><div style="background-color:lightgray; font-weight:700;">Start Render:</div><div style="background-color:lightgray; font-weight:700;">Speed Index:</div><div style="background-color:lightgray; font-weight:700;">Load Time:</div><div style="background-color:lightgray; font-weight:700;">Visually Complete:</div><div style="background-color:lightgray; font-weight:700;">DOM Elements:</div><div style="background-color:lightgray; font-weight:700;">Total Size:</div><div style="background-color:lightgray; font-weight:700;">WPT Results:</div></span><span id="webperf-wptResults"><div id="wpt-firstByte"></div><div id="wpt-startRender"></div><div id="wpt-speedIndex"></div><div id="wpt-loadTime"></div><div id="wpt-vComplete"></div><div id="wpt-domElements"></div><div id="wpt-totalSize"></div><div id="wpt-link"></div></span></li></ul></div><div id="webperf-wpt-images"><div class="webperf-label">WebPageTest.org Frames</div><ul id="webperf-wpt-images-content" class="webperf-hide"></ul></div></section>';
document.body.innerHTML += html;

   var navigationTiming = performance.timing;
   var now = new Date().getTime();
  
   //get client page speed
   var clientBackend = ((navigationTiming.responseEnd-navigationTiming.navigationStart)/1000).toFixed(2);
   var clientFrontend = ((now-navigationTiming.responseEnd)/1000).toFixed(2);
   var clientTotal = ((now-navigationTiming.navigationStart)/1000).toFixed(2);

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
    url : liveSite
  }
  var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
     	if(xhr.readyState == 4 && xhr.status == 200) {
		    var response = JSON.parse(xhr.responseText);
	        document.getElementById('webperf-serverBackend').innerHTML = response.overall.backend;
		    document.getElementById('webperf-serverFrontend').innerHTML = response.overall.frontend;
		    document.getElementById('webperf-serverTotal').innerHTML = response.overall.total;
		    if (response.total>totalBudget) {
		      	document.getElementById("webperf-serverTotal").className = "webperf-red";
		    }
		    else {
		        document.getElementById("webperf-serverTotal").className = "webperf-green";
		    }
		    var countries = response.countries;
		    var country;
			  var list = '';
			  for (i=0; i<countries.length; i++) {
				    for (country in countries[i]) {
					    var countryBackend = countries[i][country].backend;
					    var countryFrontend = countries[i][country].frontend;
      				var countryTotal = countries[i][country].total;
      				list += '<li><span class="webperf-country">'+country+'</span><span>'+countryBackend+' / '+countryFrontend+' / '+countryTotal+'</span>';
      			}
      	}
			  document.getElementById('webperf-countries').innerHTML += list;
      }
    }
    xhr.open('POST', './webperfSubmit.php', true);
    xhr.send(JSON.stringify(json));

    function toggleClass(element, className){
      if (!element || !className){
          return;
      }

      var classString = element.className, nameIndex = classString.indexOf(className);
      if (nameIndex == -1) {
          classString += ' ' + className;
      }
      else {
          classString = classString.substr(0, nameIndex) + classString.substr(nameIndex+className.length);
      }
      element.className = classString;
    }

    document.getElementById('webperf-resources').addEventListener('click', function() {
    toggleClass(document.getElementById('webperf-resourceInfo'), 'webperf-hide');
    });

    document.getElementById('webperf-webpagetest').addEventListener('click', function() {
    toggleClass(document.getElementById('webperf-webpagetestInfo'), 'webperf-hide');
    });

    document.getElementById('webperf-wpt-images').addEventListener('click', function() {
    toggleClass(document.getElementById('webperf-wpt-images-content'), 'webperf-hide');
    });

    document.getElementById('webperf-server').addEventListener('click', function() {
    toggleClass(document.getElementById('webperf-countries'), 'webperf-hide');
    });

    //Add webpage test results
    function getWebPageTestUrl() {
      document.getElementById('wpt-firstByte').innerHTML = "waiting...";
          document.getElementById('wpt-speedIndex').innerHTML = "waiting...";
          document.getElementById('wpt-loadTime').innerHTML = "waiting...";
          document.getElementById('wpt-startRender').innerHTML = "waiting...";
          document.getElementById('wpt-domElements').innerHTML = "waiting...";
          document.getElementById('wpt-vComplete').innerHTML = "waiting...";
          document.getElementById('wpt-totalSize').innerHTML = "waiting...";
          document.getElementById('wpt-link').innerHTML = "waiting...";
      
      var webpagetestxhr = new XMLHttpRequest();
      webpagetestxhr.onreadystatechange = function() {
          if (webpagetestxhr.readyState == 4 && webpagetestxhr.status == 200) {
              var resp = JSON.parse(webpagetestxhr.responseText);
              if (resp.statusCode==400) {
                console.log("Too many requests made for the day.");
              }
              else if (resp.statusCode==200) {
                data=resp.data.runs['1'].firstView;
                var firstByte = (data.TTFB/1000).toFixed(2);
                var speedIndex = (data.SpeedIndex/1000).toFixed(2);
                var loadTime = (data.loadTime/1000).toFixed(2);
                var startRender = (data.render/1000).toFixed(2);
                var domElements = data.domElements;
                var vComplete = (data.visualComplete/1000).toFixed(2);
                var totalSize = (data.bytesIn/1000).toFixed(2);;
                var link = data.pages.details;

                document.getElementById('wpt-firstByte').innerHTML = firstByte+" secs";
                document.getElementById('wpt-speedIndex').innerHTML = speedIndex+" secs";
                document.getElementById('wpt-loadTime').innerHTML = loadTime+" secs";
                document.getElementById('wpt-startRender').innerHTML = startRender+" secs";
                document.getElementById('wpt-vComplete').innerHTML = vComplete+" secs";
                document.getElementById('wpt-domElements').innerHTML = domElements;
                document.getElementById('wpt-totalSize').innerHTML = totalSize+" kb";
                document.getElementById('wpt-link').innerHTML = "<a href='"+link+"' target='_blank'>Link</a>";

                var frames = data.videoFrames;
          var list = '';

          for (i=0; i<frames.length; i++) {
            var complete = frames[i].VisuallyComplete;
            var image = frames[i].image;
            var time = (frames[i].time/1000).toFixed(2);
            list += '<li><div>' + complete + '%</div><div><a href="'+image+'" target="_blank"><img src="'+image+'"></a></div><div>'+time+' secs</div></li>';
          }
          document.getElementById('webperf-wpt-images-content').innerHTML += list;
              }
              else {
                console.log("Try again...");
                setTimeout(function(){getWebPageTestUrl()},5000);
              }
          }
      }
      webpagetestxhr.open("GET", "./devInfo.php?url="+liveSite+"", true);
      webpagetestxhr.send();
    }
  getWebPageTestUrl();

window.onload = function(){
  var resources = window.performance.getEntriesByType('resource');
  var resourcelist = '';

  for (i=0; i<resources.length; i++) {
    var resourcename = /[^/]*$/.exec(resources[i].name)[0];
    if (resourcename=="") {
      resourcename=resources[i].name;
    }
    var resourceduration = (resources[i].duration/1000).toFixed(2);
    resourcelist += '<li><span style="padding:5px 0px;">' + resourcename + '</span><span style="padding:5px 0px;">'+resourceduration+' secs</span></li>';
  }
  document.getElementById('webperf-resourceInfo').innerHTML += resourcelist;
}
