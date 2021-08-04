<?php
    function input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      return htmlspecialchars($data); // Provides protection against XXS
    }
    
    $window = input($_POST["window"]);
    $distance = input($_POST["distance"]);;
    if ($window !== ""){
        setcookie("window", $window, time()+(86400*30),"/");
    }
    if ($distance >= 0 && $distance <= 500){
        setcookie("distance", $distance, time()+(86400*30),"/");
    }
    Header("Location: Settings.php")
?>