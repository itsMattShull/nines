# nines
A web performance tool aimed to help developers find critical performance issues.
This repo can be demoed at [mattshull.com/perf/](https://www.mattshull.com/perf/).

##Installation
There are two seperate scripts with Nines.  submitPerfResults.js is installed into the live site to gather and submit performance information for the user as they view the website.  No information is shown to the user about performance.

webPerfResults.js is used by the developer on the development site.  It will gather and submit the users information but also show they performance information at the bottom of the page.  There's no need to include submitPerfResults.js if webPerfResults.js is installed.

####For Live Sites
Include `<script src="js/submitPerfResults.js"></script>` in the pages you wish to track.

####For Development Sites
Include `<script src="js/webPerfResults.js"></script>` and `<link rel="stylesheet" type="text/css" href="css/perfStyle.css">`  in the pages you wish to track.

##Usage

##License
