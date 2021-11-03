<?php
$window_value = $_POST["window"];
$distance_value = $_POST["distance"];

if ($window_value >= 1 && $window_value <= 4){
    setcookie("window", $window_value, time()+(86400*30),"/");
} else{
    echo "Invalid window.";
}
if ($distance_value >= 0 && $distance_value <= 500){
    setcookie("distance", $distance_value, time()+(86400*30),"/");
} else{
    echo "Invalid distance.";
}
?>