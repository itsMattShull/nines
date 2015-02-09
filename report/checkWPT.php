<?php
$conn = new PDO("mysql:host={{HOSTNAME}};dbname={{DATABASE-NAME}}", {{USERNAME}}, "{{PASSWORD}}");  
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$sql= $conn->prepare('SELECT * FROM webpagetest');
$sql->execute();

while ($row=$sql->fetch()) {
    $getResultsURL = $row['jsonUrl'];
    $url=$row['url'];
    $id = $row['ID'];
    $datetime = $row['datetime'];

    $json = file_get_contents($getResultsURL);
    $obj = json_decode($json);

    if ($obj->statusCode==200) {
        if ($obj->data->successfulFVRuns==0) {
            $sql2= $conn->prepare('DELETE FROM webpagetest WHERE ID=:id');
            $sql2->bindParam(':id', $id);
            $sql2->execute();
        }
        else {

            if ($obj->data->location=="Dulles:Chrome") {
                $country = "United States";
                $state = "Virginia";
                $city = "Dulles";
            }
            if ($obj->data->location=="Chicago:Chrome") {
                $country = "United States";
                $state = "Illinois";
                $city = "Chicago";
            }
            if ($obj->data->location=="LosAngeles:Chrome") {
                $country = "United States";
                $state = "California";
                $city = "Los Angeles";
            }

            $one=1;
            $results = $obj->data->runs->$one->firstView;
            $firstByte = $results->TTFB;
            $startRender = $results->render;
            $speedIndex = $results->SpeedIndex;
            $loadTime = $results->loadTime;
            $visuallyComplete = $results->visualComplete;
            $domElements = $results->domElements;
            $totalSize = $results->bytesIn;
            $link = $results->pages->details;

            $sql1= $conn->prepare('INSERT INTO webpagetest_results (country, state, city, firstByte, startRender, speedIndex, loadTime, visuallyComplete, domElements, totalSize, link, json, url, datetime) VALUES (:country, :state, :city, :firstByte, :startRender, :speedIndex, :loadTime, :visuallyComplete, :domElements, :totalSize, :link, :json, :url, :datetime)');
            $sql1->bindParam(':country', $country);
            $sql1->bindParam(':state', $state);
            $sql1->bindParam(':city', $city);
            $sql1->bindParam(':firstByte', $firstByte);
            $sql1->bindParam(':startRender', $startRender);
            $sql1->bindParam(':speedIndex', $speedIndex);
            $sql1->bindParam(':loadTime', $loadTime);
            $sql1->bindParam(':visuallyComplete', $visuallyComplete);
            $sql1->bindParam(':domElements', $domElements);
            $sql1->bindParam(':totalSize', $totalSize);
            $sql1->bindParam(':link', $link);
            $sql1->bindParam(':json', $getResultsURL);
            $sql1->bindParam(':url', $url);
            $sql1->bindParam(':datetime', $datetime);
            $sql1->execute();

            $sql2= $conn->prepare('DELETE FROM webpagetest WHERE ID=:id');
            $sql2->bindParam(':id', $id);
            $sql2->execute();
        }
    }
}
?>
