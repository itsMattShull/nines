![alt text](http://www.mattshull.com/perf/logo.png "Logo")

# nines
A web performance tool aimed to help developers find critical performance issues.
This repo can be demoed at [mattshull.com/perf/](http://mattshull.com/perf/).
 
 
 
 
 
##Installation
There are two seperate scripts with Nines.  submitPerfResults.js is installed into the live site to gather and submit performance information for the user as they view the website.  No information is shown to the user about performance.

webPerfResults.js is used by the developer on the development site.  It will gather and submit the users information but also show they performance information at the bottom of the page.  There's no need to include submitPerfResults.js if webPerfResults.js is installed.

Include the CSS file perfStyle.css into your CSS.

####For Live Sites
Include `<script src="js/submitPerfResults.js"></script>` in the pages you wish to track.

####For Development Sites
Include `<script src="js/webPerfResults.js"></script>` and `<link rel="stylesheet" type="text/css" href="css/perfStyle.css">`  in the pages you wish to track.  

Edit line 2 of webPerfResults.js to reflect the correct hostname `var liveSite = "mattshull.com"+.location.pathname;`.

Change webperfSubmit.php to include the correct database information on Line 9.  Also change getWebPageTest.php to include the correct database information on Line 5 and include the WebPageTest.org API key in the url on Line 15 where it says `{{INSERT-API-KEY-HERE}}`.



##Usage
Nines is a tool that helps developers pinpoint critical performance issues using performance statistics from the Navigation Timing API, Resource Timing API, median page load speeds for all users that have visited the page, and an assesment of the page using WebPageTest.org.

There are five sections of the performance bar, located and fixed at the bottom of the screen.  **Current** refers to your performance statistics and **Page Median** refers to the median page load speed of the statistics gathered in the database. Click on **Page Median** to see median results from individual countries.  Each of those columns as three numbers seperated by /'s.  The first number refers to the backend load time, the second number refers to the frontend load time, and the third number is the total load time for that page.

The total load times will be in green font when the time is within the "performance budget" and will be red when the time is over the "performance budget".  You can set the performance budget in Line 1 of webPerfResults.js: `var totalBudget = 2;`.

**Resources** will show the file name and load time of each asset within the page.  Simply click on **Resources** to show and hide the box.  The list will scroll if there are numerous resources in the document.

**WebPageTest.org (Cable)** will show statistics for the page via the WebPageTest.org API.  It's set to show results from a Cable connection, using Chrome, and from Dulles, VA.  The tool will display time to first byte, start render, speed index, total load time, time to visually complete, number of DOM elements in the document, total document size, and give a link to the webpagetest.org results.  Simply click on the **WebPageTest.org (Cable)** to show and hide the box.

**WebPageTest.org Frames** will show images from different points in the files loading process.  Each image will also show the percentage of the page that has loaded and the time at which the image was taken.  Simply click on the **WebPageTest.org Frames** to show and hide the box.
 
 
 
 
##License
The MIT License (MIT)

Copyright (c) 2015 Matt Shull

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
