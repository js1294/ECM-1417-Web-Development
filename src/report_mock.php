<?php
header("Content-Type: text/plain; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD']!="POST") {
    http_response_code(405);
    die();
}
$putfp = fopen('php://input', 'r');
$putdata = '';
while($data = fread($putfp, 1024)){
    $putdata .= $data;
}
fclose($putfp);

$json = json_decode($putdata, true);
$x=$json["x"];
$y=$json["y"];
if (!is_numeric($x) or !is_numeric($y)) {
    http_response_code(400);
    die("Wrong format for coordinates. Must be integer.");
}
$date=$json["date"];
if (!DateTime::createFromFormat('Y-m-d', $date)) {
    http_response_code(400);
    die("Wrong date format. Must be yyyy-mm-dd");
}
$time=$json["time"];
if (!DateTime::createFromFormat('H:i:s', $time)) {
    http_response_code(400);
    die("Wrong time format. Must be hh:mm:ss");
}
$duration=$json["duration"];
if (!is_numeric($duration)){
    http_response_code(400);
    die("Wrong format for duration. Must be integer.");
}

http_response_code(200);
?>