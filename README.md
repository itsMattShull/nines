![alt text](http://www.mattshull.com/perf/logo.png "Logo")

# nines
A web performance tool aimed to help developers find critical performance issues.  The vision is to create a performance tool that allows artists, developers, and project managers to see the impact that their changes have made on a sites performance.  Its accesible from the actual website, instead of having to look at performance data on another website.
This repo can be demoed at [mattshull.com/perf/](http://mattshull.com/perf/).
Reporting can be demoed at [mattshull.com/perf/report.html](http://mattshull.com/perf/report.html)

##Featured In:
[Perf.Rocks](http://www.perf.rocks/tools/)

[Smashing Magazine Facebook](https://www.facebook.com/smashmag/posts/10153110235587490)

[Perf-Tooling.Today](http://perf-tooling.today/tools)

[WebDesignerDepot.com](http://www.webdesignerdepot.com/2015/03/50-incredible-freebies-for-web-designers-march-2015/)

[ByPeople.com](http://www.bypeople.com/web-development-performance-tool/)
 
 
 
 
##Installation
There are two seperate scripts with Nines.  livePerf.js is installed into the live site to gather and submit performance information for the user as they view the website.  No information is shown to the user about performance.

devPerf.js is used by the developer on the development site.  It will gather and submit the users/developers performance information but also show performance information at the bottom of the page.  There's no need to include livePerf.js if devPerf.js is installed.

Include the CSS file perfStyle.css (found in the CSS folder) into your CSS.

####For Live Sites
Include `<script src="js/livePerf.js"></script>` in the pages you wish to track.

####For Development Sites
Include `<script src="js/devPerf.js"></script>` and `<link rel="stylesheet" type="text/css" href="css/perfStyle.css">`  in the pages you wish to track.  

Edit line 2 of devPerf.js and line 4 of livePerf.js to reflect the correct hostname `var liveSite = "mattshull.com"+.location.pathname;`.

Change webperfSubmit.php to include the correct database information on line 3.  Also change getWPT.php to include the correct database information on line 3 and include the WebPageTest.org API key in the url on Line 6, 19, and 32 where it says `{{ENTER API KEY HERE}}`.

####Database
Use the createDatabases.sql file to create the necessary databases.

####Reporting
To install reporting, add the correct database information in getReport.php (on lines 18, 141, 286, and 389) and do the same for getInfo.php (on line 3).  Then set up cron jobs to run getWPT.php and checkWPT.php as often as you'd like it to test the performance of your website.  Once data has been collected simply view report.html to see the results.



##Usage
Nines is a tool that helps developers pinpoint critical performance issues using performance statistics from the Navigation Timing API, Resource Timing API, median page load speeds for all users that have visited the page, an assesment of the page using WebPageTest.org, and by providing reporting via Google Charts.

There are five sections of the performance bar, located and fixed at the bottom of the screen.  **Current** refers to your performance statistics and **Page Median** refers to the median page load speed of the statistics gathered in the database. Click on **Page Median** to see median results from individual countries.  Each of those columns as three numbers seperated by /'s.  The first number refers to the backend load time, the second number refers to the frontend load time, and the third number is the total load time for that page.

The total load times will be in green font when the time is within the "performance budget" and will be red when the time is over the "performance budget".  You can set the performance budget in Line 1 of devPerf.js: `var totalBudget = 2;`.

**Resources** will show the file name and load time of each asset within the page.  Simply click on **Resources** to show and hide the box.  The list will scroll if there are numerous resources in the document.

**WebPageTest.org (Cable)** will show statistics for the page via the WebPageTest.org API.  Once you set up a cron job to run getWPT.php as often as you'd like, information from the latest webpagetest.org test will be shown in this area including: TTFB, Start Render, Speed Index, Load Time, Visually Complete, # of DOM Elements, Total Size, and a link to the webpagetest.org site for the current test. 

**WebPageTest.org Frames** will show images from different points in the files loading process.  Each image will also show the percentage of the page that has loaded and the time at which the image was taken.  Simply click on the **WebPageTest.org Frames** to show and hide the box.

**Reporting** allows you to visually see how the performance of your site is progressing over time using the Google Charts API.  On the initial load of the page it will show median data collected for all pages, in all countries, by RUM (real user measurements) and synthetic (WebPageTest) testing.  The data shown will be RUM median load times, synthetic median load times, synthetic media page size, and synthetic median # of DOM elements.  The user can look at data for specific URLs by selecting the URL from the select box, look at specific data for individual countries, and choose a start and end date to view performance data.
 
 
 
 
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
