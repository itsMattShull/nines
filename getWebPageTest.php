<?php
$getURL=$_GET['url'];

//connect to the database so we can check, edit, or insert data to our users table
$conn = new PDO("mysql:host={{HOSTNAME}};dbname={{DATABASE-NAME}}", {{USERNAME}}, "{{PASSWORD}}");  
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$ipAddress=$_SERVER['REMOTE_ADDR'];
$json = file_get_contents("http://ip-api.com/json/".$ipAddress);
$obj = json_decode($json);
$country=$obj->country;
$state=$obj->regionName;
$city=$obj->city;

$sql= $conn->prepare('SELECT * FROM webpagetest WHERE url=:url AND datetime >= DATE_ADD(NOW(), INTERVAL -10 MINUTE)');
$sql->bindParam(':url', $getURL);
$sql->execute();

$count = $sql->rowCount();

if ($count == 0) { 
	$url="http://www.webpagetest.org/runtest.php?url=".$getURL."&f=json&k={{WEBPAGETEST.ORG API KEY HERE}}&fvonly=1&location=Dulles:Chrome.Cable&video=1";
	$json = file_get_contents($url);
	$obj = json_decode($json);


	if ($obj->statusCode==400) {
		$send=json_encode(array('statusCode' => 400));

		echo $send; 
		exit;
	}
	else {
		$theUrl = $obj->data->jsonUrl;
		$sql= $conn->prepare('INSERT INTO webpagetest (url, datetime, jsonUrl) VALUES (:url, NOW(), :jsonUrl)');
		$sql->bindParam(':url', $getURL);
		$sql->bindParam(':jsonUrl', $theUrl);
		$sql->execute();

		$send=json_encode(array('statusCode' => 100));

		echo $send;
		exit;
	}
}
else {
	$row=$sql->fetch();

	$getResultsURL = $row['jsonUrl'];
	$url=$row['url'];
	$json = file_get_contents($getResultsURL);
	$obj = json_decode($json);

	if ($obj->statusCode==200) {
		echo $json;

		$sql= $conn->prepare('SELECT * FROM webpagetest_results WHERE json=:json');
		$sql->bindParam(':json', $getResultsURL);
		$sql->execute();

		$count=$sql->rowCount();

		if ($count==0) {
			$one=1;
			$row=$sql->fetch();
			//$results = $obj->data->runs->1->firstView;
			$results = $obj->data->runs->$one->firstView;
			$firstByte = $results->TTFB;
			$startRender = $results->render;
			$speedIndex = $results->SpeedIndex;
			$loadTime = $results->loadTime;
			$visuallyComplete = $results->visualComplete;
			$domElements = $results->domElements;
			$totalSize = $results->bytesIn;
			$link = $results->pages->details;

			$sql= $conn->prepare('INSERT INTO webpagetest_results (country, state, city, firstByte, startRender, speedIndex, loadTime, visuallyComplete, domElements, totalSize, link, json, ipAddress, url) VALUES (:country, :state, :city, :firstByte, :startRender, :speedIndex, :loadTime, :visuallyComplete, :domElements, :totalSize, :link, :json, :ipAddress, :url)');
			$sql->bindParam(':country', $country);
			$sql->bindParam(':state', $state);
			$sql->bindParam(':city', $city);
			$sql->bindParam(':firstByte', $firstByte);
			$sql->bindParam(':startRender', $startRender);
			$sql->bindParam(':speedIndex', $speedIndex);
			$sql->bindParam(':loadTime', $loadTime);
			$sql->bindParam(':visuallyComplete', $visuallyComplete);
			$sql->bindParam(':domElements', $domElements);
			$sql->bindParam(':totalSize', $totalSize);
			$sql->bindParam(':link', $link);
			$sql->bindParam(':json', $getResultsURL);
			$sql->bindParam(':ipAddress', $ipAddress);
			$sql->bindParam(':url', $url);
			$sql->execute();
		}
		exit;
	}
	else {
		$send=json_encode(array('statusCode' => 100));
		echo $send;
		exit;
	}

}
?>
