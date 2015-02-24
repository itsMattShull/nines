<?php
//connect to the database so we can check, edit, or insert data to our users table
$conn = new PDO("mysql:host={{HOSTNAME}};dbname={{DATABASE-NAME}}", {{USERNAME}}, "{{PASSWORD}}");  
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$sql= $conn->prepare('SELECT DISTINCT url FROM webpagetest_results');
$sql->execute();
$urlarray = array();
while ($row=$sql->fetch()) {
    $url=$row['url'];
    $urlarray[] = array($url);
}
$sql= $conn->prepare('SELECT DISTINCT country FROM webpagetest_results');
$sql->execute();
$countryarray = array();
while ($row=$sql->fetch()) {
    $country=$row['country'];
    $countryarray[] = array($country);
}
echo json_encode(array('urls'=>$urlarray, 'countries'=>$countryarray));
?>
