<?php
function calculate_median($arr) {
    sort($arr);
    $count = count($arr); //total numbers in array
    $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
    if($count % 2) { // odd number, middle is the median
        $median = $arr[$middleval];
    } else { // even number, calculate avg of 2 medians
        $low = $arr[$middleval];
        $high = $arr[$middleval+1];
        $median = (($low+$high)/2);
    }
    return $median;
}

function getMedian() {
    //connect to the database so we can check, edit, or insert data to our users table
    $conn = new PDO("mysql:host={{HOSTNAME}};dbname={{DATABASE-NAME}}", {{USERNAME}}, "{{PASSWORD}}");  
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    $from=$_GET['from'];
    $to = $_GET['to'];
    $url = $_GET['url'];
    $country = $_GET['country'];

    if (!$from && !$to) {
        $from=date('Ym01');
        $to=date("Ymd");
    }
    else {
        $from = strtotime($from);
        $from = date('Ymd',$from);

        $to = strtotime($to);
        $to = date('Ymd',$to);
    }

    $array = array(array("Date", "Backend", "Frontend", "Total"));

    if ($url=="" && $country=="") {
        $sql= $conn->prepare('SELECT * FROM perf WHERE DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->execute();
    }
    elseif ($url!="" && $country=="") {
        $sql= $conn->prepare('SELECT * FROM perf WHERE url=:url AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':url', $url);
        $sql->execute();
    }
    elseif ($url=="" && $country!="") {
        $sql= $conn->prepare('SELECT * FROM perf WHERE country=:country AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':country', $country);
        $sql->execute();
    }
    else {
        $sql= $conn->prepare('SELECT * FROM perf WHERE url=:url AND country=:country AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':url', $url);
        $sql->bindParam(':country', $country);
        $sql->execute();
    }

    $rowCount=$sql->rowCount();

    $backendMedian = array();
    $frontendMedian = array();
    $totalMedian = array();

    $prevDate;
    $i=0;

    while ($row=$sql->fetch()) {
        $id=$row['ID'];
        $datetime=$row['datetime'];
        $backend=$row['backend'];
        $frontend=$row['frontend'];
        $total=$row['total'];
        $extractDate = date_parse($datetime);
        $day = $extractDate['day'];
        $month = date('M', strtotime($datetime));
        $date="$month $day";

        if ($i==0) {$prevDate=$date;}

        if ($date==$prevDate) {
            $backendMedian[] = $row['backend'];
            $frontendMedian[] = $row['frontend'];
            $totalMedian[] = $row['total'];
        }
        elseif (($i++)==$rowCount) {
            $backendMedian = number_format((float)(calculate_median($backendMedian)), 2, '.', '');
            $frontendMedian = number_format((float)(calculate_median($frontendMedian)), 2, '.', '');
            $totalMedian = number_format((float)(calculate_median($totalMedian)), 2, '.', '');

            $array[] = array($prevDate, (float)$backendMedian, (float)$frontendMedian, (float)$totalMedian);

            $backendMedian = array();
            $frontendMedian = array();
            $totalMedian = array();

            $backendMedian[] = $row['backend'];
            $frontendMedian[] = $row['frontend'];
            $totalMedian[] = $row['total'];
        }
        else {
            $backendMedian = number_format((float)(calculate_median($backendMedian)), 2, '.', '');
            $frontendMedian = number_format((float)(calculate_median($frontendMedian)), 2, '.', '');
            $totalMedian = number_format((float)(calculate_median($totalMedian)), 2, '.', '');

            $array[] = array($prevDate, (float)$backendMedian, (float)$frontendMedian, (float)$totalMedian);

            $backendMedian = array();
            $frontendMedian = array();
            $totalMedian = array();

            $backendMedian[] = $row['backend'];
            $frontendMedian[] = $row['frontend'];
            $totalMedian[] = $row['total'];
        }
        $i++;
        $prevDate=$date;
    }

    $backendMedian = number_format((float)(calculate_median($backendMedian)), 2, '.', '');
    $frontendMedian = number_format((float)(calculate_median($frontendMedian)), 2, '.', '');
    $totalMedian = number_format((float)(calculate_median($totalMedian)), 2, '.', '');

    $array[] = array($prevDate, (float)$backendMedian, (float)$frontendMedian, (float)$totalMedian);

    return $array;
}

function getMedianWPT() {
    //connect to the database so we can check, edit, or insert data to our users table
    $conn = new PDO("mysql:host={{HOSTNAME}};dbname={{DATABASE-NAME}}", {{USERNAME}}, "{{PASSWORD}}");  
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    $from=$_GET['from'];
    $to = $_GET['to'];
    $url = $_GET['url'];
    $country = $_GET['country'];

    if (!$from && !$to) {
        $from=date('Ym01');
        $to=date("Ymd");
    }
    else {
        $from = strtotime($from);
        $from = date('Ymd',$from);

        $to = strtotime($to);
        $to = date('Ymd',$to);
    }

    $array = array(array("Date", "TTFB", "Start Render", "Speed Index", "Load Time", "Visually Complete"));


    if ($url=="" && $country=="") {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->execute();
    }
    elseif ($url!="" && $country=="") {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE url=:url AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':url', $url);
        $sql->execute();
    }
    elseif ($url=="" && $country!="") {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE country=:country AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':country', $country);
        $sql->execute();
    }
    else {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE url=:url AND country=:country AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':url', $url);
        $sql->bindParam(':country', $country);
        $sql->execute();
    }

    $rowCount=$sql->rowCount();

    $ttfbMedian = array();
    $startRenderMedian = array();
    $speedIndexMedian = array();
    $loadTimeMedian = array();
    $vcMedian = array();

    $prevDate;
    $i=0;

    while ($row=$sql->fetch()) {
        $id=$row['ID'];
        $datetime=$row['datetime'];
        $firstByte=$row['firstByte'];
        $startRender=$row['startRender'];
        $speedIndex=$row['speedIndex'];
        $loadTime=$row['loadTime'];
        $visuallyComplete=$row['visuallyComplete'];
        $extractDate = date_parse($datetime);
        $day = $extractDate['day'];
        $month = date('M', strtotime($datetime));
        $date="$month $day";

        if ($i==0) {$prevDate=$date;}

        if ($date==$prevDate) {
            $ttfbMedian[] = $row['firstByte'];
            $startRenderMedian[] = $row['startRender'];
            $speedIndexMedian[] = $row['speedIndex'];
            $loadTimeMedian[] = $row['loadTime'];
            $vcMedian[] = $row['visuallyComplete'];
        }
        elseif (($i++)==$rowCount) {
            $ttfbMedian = number_format((float)(calculate_median($ttfbMedian)/1000), 2, '.', '');
            $startRenderMedian = number_format((float)(calculate_median($startRenderMedian)/1000), 2, '.', '');
            $speedIndexMedian = number_format((float)(calculate_median($speedIndexMedian)/1000), 2, '.', '');
            $loadTimeMedian = number_format((float)(calculate_median($loadTimeMedian)/1000), 2, '.', '');
            $vcMedian = number_format((float)(calculate_median($vcMedian)/1000), 2, '.', '');

            $array[] = array($prevDate, (float)$ttfbMedian, (float)$startRenderMedian, (float)$speedIndexMedian, (float)$loadTimeMedian, (float)$vcMedian);

            $ttfbMedian = array();
            $startRenderMedian = array();
            $speedIndexMedian = array();
            $loadTimeMedian = array();
            $vcMedian = array();

            $ttfbMedian[] = $row['firstByte'];
            $startRenderMedian[] = $row['startRender'];
            $speedIndexMedian[] = $row['speedIndex'];
            $loadTimeMedian[] = $row['loadTime'];
            $vcMedian[] = $row['visuallyComplete'];
        }
        else {
            $ttfbMedian = number_format((float)(calculate_median($ttfbMedian)/1000), 2, '.', '');
            $startRenderMedian = number_format((float)(calculate_median($startRenderMedian)/1000), 2, '.', '');
            $speedIndexMedian = number_format((float)(calculate_median($speedIndexMedian)/1000), 2, '.', '');
            $loadTimeMedian = number_format((float)(calculate_median($loadTimeMedian)/1000), 2, '.', '');
            $vcMedian = number_format((float)(calculate_median($vcMedian)/1000), 2, '.', '');

            $array[] = array($prevDate, (float)$ttfbMedian, (float)$startRenderMedian, (float)$speedIndexMedian, (float)$loadTimeMedian, (float)$vcMedian);

            $ttfbMedian = array();
            $startRenderMedian = array();
            $speedIndexMedian = array();
            $loadTimeMedian = array();
            $vcMedian = array();

            $ttfbMedian[] = $row['firstByte'];
            $startRenderMedian[] = $row['startRender'];
            $speedIndexMedian[] = $row['speedIndex'];
            $loadTimeMedian[] = $row['loadTime'];
            $vcMedian[] = $row['visuallyComplete'];
        }
        $i++;
        $prevDate=$date;
    }

    $ttfbMedian = number_format((float)(calculate_median($ttfbMedian)/1000), 2, '.', '');
    $startRenderMedian = number_format((float)(calculate_median($startRenderMedian)/1000), 2, '.', '');
    $speedIndexMedian = number_format((float)(calculate_median($speedIndexMedian)/1000), 2, '.', '');
    $loadTimeMedian = number_format((float)(calculate_median($loadTimeMedian)/1000), 2, '.', '');
    $vcMedian = number_format((float)(calculate_median($vcMedian)/1000), 2, '.', '');

    $array[] = array($prevDate, (float)$ttfbMedian, (float)$startRenderMedian, (float)$speedIndexMedian, (float)$loadTimeMedian, (float)$vcMedian);


    return $array;
}

function getPageSize() {
    //connect to the database so we can check, edit, or insert data to our users table
    $conn = new PDO("mysql:host={{HOSTNAME}};dbname={{DATABASE-NAME}}", {{USERNAME}}, "{{PASSWORD}}");  
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    $from=$_GET['from'];
    $to = $_GET['to'];
    $url = $_GET['url'];
    $country = $_GET['country'];

    if (!$from && !$to) {
        $from=date('Ym01');
        $to=date("Ymd");
    }
    else {
        $from = strtotime($from);
        $from = date('Ymd',$from);

        $to = strtotime($to);
        $to = date('Ymd',$to);
    }

    $array = array(array("Date", "Page Size"));

    if ($url=="" && $country=="") {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->execute();
    }
    elseif ($url!="" && $country=="") {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE url=:url AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':url', $url);
        $sql->execute();
    }
    elseif ($url=="" && $country!="") {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE country=:country AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':country', $country);
        $sql->execute();
    }
    else {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE url=:url AND country=:country AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':url', $url);
        $sql->bindParam(':country', $country);
        $sql->execute();
    }

    $rowCount=$sql->rowCount();

    $pageSizeMedian = array();

    $prevDate;
    $i=0;

    while ($row=$sql->fetch()) {
        $id=$row['ID'];
        $datetime=$row['datetime'];
        $pageSize=$row['totalSize'];
        $extractDate = date_parse($datetime);
        $day = $extractDate['day'];
        $month = date('M', strtotime($datetime));
        $date="$month $day";

        if ($i==0) {$prevDate=$date;}

        if ($date==$prevDate) {
            $pageSizeMedian[] = $row['totalSize'];
        }
        elseif (($i++)==$rowCount) {
            $pageSizeMedian = number_format((float)(calculate_median($pageSizeMedian)/1000), 2, '.', '');

            $array[] = array($prevDate, (float)$pageSizeMedian);

            $pageSizeMedian = array();

            $pageSizeMedian[] = $row['totalSize'];
        }
        else {
            $pageSizeMedian = number_format((float)(calculate_median($pageSizeMedian)/1000), 2, '.', '');

            $array[] = array($prevDate, (float)$pageSizeMedian);

            $pageSizeMedian = array();

            $pageSizeMedian[] = $row['totalSize'];
        }
        $i++;
        $prevDate=$date;
    }

    $pageSizeMedian = number_format((float)(calculate_median($pageSizeMedian)/1000), 2, '.', '');

    $array[] = array($prevDate, (float)$pageSizeMedian);

    return $array;
}

function getDOMElements() {
    //connect to the database so we can check, edit, or insert data to our users table
    $conn = new PDO("mysql:host={{HOSTNAME}};dbname={{DATABASE-NAME}}", {{USERNAME}}, "{{PASSWORD}}");  
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    $array = array(array("Date", "# of DOM Elements"));

    $from=$_GET['from'];
    $to = $_GET['to'];
    $url = $_GET['url'];
    $country = $_GET['country'];

    if (!$from && !$to) {
        $from=date('Ym01');
        $to=date("Ymd");
    }
    else {
        $from = strtotime($from);
        $from = date('Ymd',$from);

        $to = strtotime($to);
        $to = date('Ymd',$to);
    }
    
    if ($url=="" && $country=="") {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->execute();
    }
    elseif ($url!="" && $country=="") {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE url=:url AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':url', $url);
        $sql->execute();
    }
    elseif ($url=="" && $country!="") {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE country=:country AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':country', $country);
        $sql->execute();
    }
    else {
        $sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE url=:url AND country=:country AND DATE(datetime) BETWEEN :fromDate AND :toDate ORDER BY datetime');
        $sql->bindParam(':fromDate', $from);
        $sql->bindParam(':toDate', $to);
        $sql->bindParam(':url', $url);
        $sql->bindParam(':country', $country);
        $sql->execute();
    }

    $rowCount=$sql->rowCount();

    $domElementsMedian = array();

    $prevDate;
    $i=0;

    while ($row=$sql->fetch()) {
        $id=$row['ID'];
        $datetime=$row['datetime'];
        $domElements=$row['domElements'];
        $extractDate = date_parse($datetime);
        $day = $extractDate['day'];
        $month = date('M', strtotime($datetime));
        $date="$month $day";

        if ($i==0) {$prevDate=$date;}

        if ($date==$prevDate) {
            $domElementsMedian[] = $row['domElements'];
        }
        elseif (($i++)==$rowCount) {
            $domElementsMedian = number_format((float)(calculate_median($domElementsMedian)), 2, '.', '');

            $array[] = array($prevDate, (float)$domElementsMedian);

            $domElementsMedian = array();

            $domElementsMedian[] = $row['domElements'];
        }
        else {
            $domElementsMedian = number_format((float)(calculate_median($domElementsMedian)), 2, '.', '');

            $array[] = array($prevDate, (float)$domElementsMedian);

            $domElementsMedian = array();

            $domElementsMedian[] = $row['domElements'];
        }
        $i++;
        $prevDate=$date;
    }

    $domElementsMedian = number_format((float)(calculate_median($domElementsMedian)), 2, '.', '');

    $array[] = array($prevDate, (float)$domElementsMedian);

    return $array;
}

echo json_encode(array("median"=>getMedian(), "medianwpt"=>getMedianWPT(), "domelements"=>getDOMElements(), "pagesize"=>getPageSize()));
?>
