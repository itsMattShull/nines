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

$sql= $conn->prepare('SELECT * FROM webpagetest_results ORDER BY datetime DESC LIMIT 1');
$sql->bindParam(':url', $getURL);
$sql->execute();
$row=$sql->fetch();

$getResultsURL = $row['json'];
$newjson = file_get_contents($getResultsURL);
$newobj = json_decode($newjson);
echo $newjson;
exit;
?>
