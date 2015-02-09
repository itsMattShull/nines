<?php
//connect to the database so we can check, edit, or insert data to our users table
$conn = new PDO("mysql:host={{HOSTNAME}};dbname={{DATABASE-NAME}}", {{USERNAME}}, "{{PASSWORD}}");  
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$json = json_decode(file_get_contents("php://input"), true);
$clientURL = $json['url'];

$sql= $conn->prepare('SELECT * FROM perf WHERE url=:url AND datetime >= DATE_ADD(NOW(), INTERVAL 0 MINUTE)');
$sql->bindParam(':url', $clientURL);
$sql->execute();
$count = $sql->rowCount();

if ($count == 0) { 
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

    $clientBackend = $json['backend'];
    $clientFrontend = $json['frontend'];
    $clientTotal = $json['total'];

    $ipAddress=$_SERVER['REMOTE_ADDR'];
    $newjson = file_get_contents("http://ip-api.com/json/".$ipAddress);
    $obj = json_decode($newjson);
    $country=$obj->country;
    $state=$obj->regionName;
    $city=$obj->city;


    $sql= $conn->prepare('INSERT INTO perf (backend, frontend, total, url, ipAddress, country, state, city) VALUES (:backend, :frontend, :total, :url, :ipAddress, :country, :state, :city)');
    $sql->bindParam(':backend', $clientBackend);
    $sql->bindParam(':frontend', $clientFrontend);
    $sql->bindParam(':total', $clientTotal);
    $sql->bindParam(':url', $clientURL);
    $sql->bindParam(':ipAddress', $ipAddress);
    $sql->bindParam(':country', $country);
    $sql->bindParam(':state', $state);
    $sql->bindParam(':city', $city);
    $sql->execute();

    $overallbackend = array();
    $overallfrontend = array();
    $overalltotal = array();

    $sql= $conn->prepare('SELECT * FROM perf WHERE url=:url');
    $sql->bindParam(':url', $clientURL);
    $sql->execute();

    while ($row=$sql->fetch()) {
        $overallbackend[] = $row['backend'];
        $overallfrontend[] = $row['frontend'];
        $overalltotal[] = $row['total'];
    }

    $overallbackend = number_format((float)(calculate_median($overallbackend)), 2, '.', '');
    $overallfrontend = number_format((float)(calculate_median($overallfrontend)), 2, '.', '');
    $overalltotal = number_format((float)(calculate_median($overalltotal)), 2, '.', '');

    $overall = array('backend' => $overallbackend, 'frontend' => $overallfrontend, 'total' => $overalltotal);



    $sql= $conn->prepare('SELECT * FROM perf WHERE url=:url ORDER BY country');
    $sql->bindParam(':url', $clientURL);
    $sql->execute();
    $rowCount=$sql->rowCount();

    $countries = array();
    $prevCountry = "";
    $i=0;

    $countrybackend = array();
    $countryfrontend = array();
    $countrytotal = array();

    while ($row=$sql->fetch()) {
        $dbCountry=$row['country'];
        if ($i==0) {$prevCountry=$dbCountry;}

        if (($prevCountry==$dbCountry)) {
            $countrybackend[] = $row['backend'];
            $countryfrontend[] = $row['frontend'];
            $countrytotal[] = $row['total'];
        }
        elseif (($i++)==$rowCount) {
            $countrybackend = number_format((float)(calculate_median($countrybackend)), 2, '.', '');
            $countryfrontend = number_format((float)(calculate_median($countryfrontend)), 2, '.', '');
            $countrytotal = number_format((float)(calculate_median($countrytotal)), 2, '.', '');

            $countries[] = array($prevCountry => array('backend' => $countrybackend, 'frontend' => $countryfrontend, 'total' => $countrytotal));

            $countrybackend = array();
            $countryfrontend = array();
            $countrytotal = array();

            $countrybackend[] = $row['backend'];
            $countryfrontend[] = $row['frontend'];
            $countrytotal[] = $row['total'];
        }
        else {
            $countrybackend = number_format((float)(calculate_median($countrybackend)), 2, '.', '');
            $countryfrontend = number_format((float)(calculate_median($countryfrontend)), 2, '.', '');
            $countrytotal = number_format((float)(calculate_median($countrytotal)), 2, '.', '');

            $countries[] = array($prevCountry => array('backend' => $countrybackend, 'frontend' => $countryfrontend, 'total' => $countrytotal));

            $countrybackend = array();
            $countryfrontend = array();
            $countrytotal = array();

            $countrybackend[] = $row['backend'];
            $countryfrontend[] = $row['frontend'];
            $countrytotal[] = $row['total'];
        }

        $prevCountry=$dbCountry;
        $i++;
    }

    $countrybackend = number_format((float)(calculate_median($countrybackend)), 2, '.', '');
    $countryfrontend = number_format((float)(calculate_median($countryfrontend)), 2, '.', '');
    $countrytotal = number_format((float)(calculate_median($countrytotal)), 2, '.', '');

    $countries[] = array($prevCountry => array('backend' => $countrybackend, 'frontend' => $countryfrontend, 'total' => $countrytotal));

    $sendjson = array('overall' => $overall, 'countries' => $countries);

    echo json_encode($sendjson);
}
?>
