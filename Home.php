<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Home Page</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="mainstyle.css">
        <link rel="stylesheet" href="homestyle.css">
    </head>
    <body>
        <?php session_start();

        function get_location($id, $date, $time){
            $sql = "SELECT * FROM Location WHERE user = '$id'";
            $locations = array();
            $window = $_COOKIE["window"];
            if ($query = mysqli_query($conn, $sql)){
                while ($result = mysqli_fetch_assoc($query)){
                    if (($result["date"] > $date-$window)||($result["date"] == $date-$window && $result["time"] >= $time)){
                        $coorindates = array("x" => $result["x"],"y" => $result["y"]);
                        array_push($locations, $coorindates);
                    }
                }
            }
            return $locations;
        }

        if(!isset($_SESSION["timeout"]) || empty($_SESSION["timeout"])) {
            header("Location: Logout.php");
        }
        if(time() - $_SESSION["timeout"] > 300){ 
            header("Location: Logout.php");
        }
        if(!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
            header("Location: Logout.php");
        }
        
        if(!isset($_COOKIE["window"])) {
            setcookie("window", 2, time()+(86400*30),"/", false, true);
        }
        if(!isset($_COOKIE["distance"])) {
            setcookie("distance", 250, time()+(86400*30),"/", false, true);
        }
        $conn = mysqli_connect("localhost", "root","","ContactTrace");
        $id = $_SESSION["id"];
        $sql = "SELECT forename FROM Users WHERE id ='$id'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        if ($result == null){
            $name = "ERROR: name not found";
        } else{
            $name = $result['forename'];
        }
            
        $sql = "SELECT * FROM Infections";
        $coorindate = array();
        $distance = $_COOKIE["distance"];
        $warning = "";
        if ($query = mysqli_query($conn, $sql)){
            while ($result = mysqli_fetch_assoc($query)){
                while ($coorindate = get_location($result["user"], $result["date"], $result["time"])){
                    echo '<img class="marker" src="marker_red.png" alt="">';
                    $sql = "SELECT * FROM Locations WHERE user = '$id'";
                    if ($query = mysqli_query($conn, $sql)){
                        while ($result = mysqli_fetch_assoc($query)){
                            echo '<img class="marker" src="marker_black.png" alt="">';
                            $x_distance = $result["x"] - $coorindate["x"];
                            $y_distance = $result["y"] - $coorindate["y"];
                            $result_distance = sqrt(pow($x_distance,2) + pow($y_distance,2));
                            if ($distance <= $result_distance){
                                $warning = "You need to get tested since there has been a likely exposure.";
                            }
                        }
                    }
                }
            }
        }
        ?>
        <h1>COVID - 19 Contact Tracing</h1>
        <div class="buttongrid">
            <button type="button" onclick="location.href='Home.php'" style="background-color: #8497b0;">Home</button>
            <button type="button" onclick="location.href='Overview.php'">Overview</button>
            <button type="button" onclick="location.href='AddVisit.php'">Add Visit</button>
            <button type="button" onclick="location.href='Report.php'">Report</button>
            <button type="button" onclick="location.href='Settings.php'">Settings</button>
            <br><br><br><br>
            <button type="button" onclick="location.href='Logout.php';">Logout</button>
        </div>
        <img class="virus" src="watermark.png" alt="">
        <div class="maingrid">
            <h2>Status</h2>
            <hr>
            <div class="textgrid">
                <br>
                <p>
                    Hi <?php echo $name ?>, you might have had a connection to an 
                    infected person at the location shown in red.
                </p>
                <br><br><br><br><br><br>
                <p>
                    Click on the marker to see details about the infection.
                </p>
            </div>
            <img class="map" src="exeter.jpg" alt="Map of exeter">
        </div>
    </body>
</html>