<?php
function makeRequest($clientURL) {
	$conn = new PDO("mysql:host={{HOSTNAME}};dbname={{DATABASE-NAME}}", {{USERNAME}}, "{{PASSWORD}}");  
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	$url="http://www.webpagetest.org/runtest.php?url=".$clientURL."&f=json&k={{ENTER API KEY HERE}}&fvonly=1&location=Dulles:Chrome.Cable&video=1";
	$json = file_get_contents($url);
	$obj = json_decode($json);

	if ($obj->statusCode==400) {}
	else {
	    $theUrl = $obj->data->jsonUrl;
	    $sql= $conn->prepare('INSERT INTO webpagetest (url, datetime, jsonUrl) VALUES (:url, NOW(), :jsonUrl)');
	    $sql->bindParam(':url', $clientURL);
	    $sql->bindParam(':jsonUrl', $theUrl);
	    $sql->execute();
	}

	$url="http://www.webpagetest.org/runtest.php?url=".$clientURL."&f=json&k={{ENTER API KEY HERE}}&fvonly=1&location=Chicago:Chrome.Cable&video=1";
	$json = file_get_contents($url);
	$obj = json_decode($json);

	if ($obj->statusCode==400) {}
	else {
	    $theUrl = $obj->data->jsonUrl;
	    $sql= $conn->prepare('INSERT INTO webpagetest (url, datetime, jsonUrl) VALUES (:url, NOW(), :jsonUrl)');
	    $sql->bindParam(':url', $clientURL);
	    $sql->bindParam(':jsonUrl', $theUrl);
	    $sql->execute();
	}

	$url="http://www.webpagetest.org/runtest.php?url=".$clientURL."&f=json&k={{ENTER API KEY HERE}}&fvonly=1&location=LosAngeles:Chrome.Cable&video=1";
	$json = file_get_contents($url);
	$obj = json_decode($json);

	if ($obj->statusCode==400) {}
	else {
	    $theUrl = $obj->data->jsonUrl;
	    $sql= $conn->prepare('INSERT INTO webpagetest (url, datetime, jsonUrl) VALUES (:url, NOW(), :jsonUrl)');
	    $sql->bindParam(':url', $clientURL);
	    $sql->bindParam(':jsonUrl', $theUrl);
	    $sql->execute();
	}
}

makeRequest("http://mattshull.com/perf");
makeRequest("http://mattshull.com");
?>
