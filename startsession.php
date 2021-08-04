<?php
session_start();
$_SESSION["name"] = "";
$_SESSION["username"] = "";

$window_value = 2;
$distance_value = 250;
setcookie("window", $window_value, time()+(86400*30),"/");
setcookie("distance", $distance_value, time()+(86400*30),"/");
?>