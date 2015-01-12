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
