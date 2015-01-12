<?php
$json = json_decode(file_get_contents("php://input"), true);
$clientBackend = $json['backend'];
$clientFrontend = $json['frontend'];
$clientTotal = $json['total'];
$clientURL = $json['url'];

//connect to the database so we can check, edit, or insert data to our users table
$conn = new PDO("mysql:host={{HOSTNAME}};dbname={{DATABASE-NAME}}", {{USERNAME}}, "{{PASSWORD}}");  
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$sql= $conn->prepare('INSERT INTO perf (backend, frontend, total, url) VALUES (:backend, :frontend, :total, :url)');
$sql->bindParam(':backend', $clientBackend);
$sql->bindParam(':frontend', $clientFrontend);
$sql->bindParam(':total', $clientTotal);
$sql->bindParam(':url', $clientURL);
$sql->execute();

$backend = 0;
$frontend = 0;
$total = 0;

$sql= $conn->prepare('SELECT * FROM perf WHERE url=:url');
$sql->bindParam(':url', $clientURL);
$sql->execute();
$count=$sql->rowCount();

while ($row=$sql->fetch()) {
	$backend += $row['backend'];
	$frontend += $row['frontend'];
	$total += $row['total'];
}

$backend = number_format((float)($backend/$count), 2, '.', '');
$frontend = number_format((float)($frontend/$count), 2, '.', '');
$total = number_format((float)($total/$count), 2, '.', '');

echo json_encode(array('backend' => $backend, 'frontend' => $frontend, 'total' => $total));
?>