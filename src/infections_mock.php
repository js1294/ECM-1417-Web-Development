<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD']!="GET") {
    http_response_code(405);
    die();
}

$span=$_GET["ts"];
if (!is_numeric($span)) {
    http_response_code(400);
    die("Wrong format for time span. Must be integer.");
}

$json[0] = array("x"=>50, "y"=>30, "date"=>"2020-01-20", "time"=>"16:00:00", "duration"=>30);
$json[1] = array("x"=>80, "y"=>45, "date"=>"2020-12-15", "time"=>"08:00:00", "duration"=>15);

echo json_encode($json);
?>