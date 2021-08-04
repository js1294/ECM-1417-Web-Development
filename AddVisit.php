<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Visits Overview</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="mainstyle.css">
        <link rel="stylesheet" href="addvisitstyle.css">
    </head>
    <body>
        <?php session_start();
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            // Provides protection against XXS attacks.
            $data = htmlspecialchars($data);
            // Provides protection against SQL injections.
            $data = mysqli_real_escape_string($data);
            return $data; 
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
        $error = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $user = $_SESSION["id"];
            $datetime = strtotime(input($_POST["date"]));
            $date = date('Y-m-d',$datetime);
            $time = input($_POST["time"]);
            $duration = input($_POST["duration"]);
            $url = $_SERVER['REQUEST_URI'];
            $coordinate = explode(",",substr($url,19));
            $x = null;
            $y = null;
            if (isset($coordinate[0]) && isset($coordinate[1])){
                $x = $coordinate[0];
                $y = $coordinate[1];
            }

            if ($date === "1970-01-01" || $time === null || $duration === null || $x === null || $y === null){
                $error = "Error: Please enter all fields and click on the map the positon.";
            } else{    
                // connect to the database
                $conn = mysqli_connect("localhost", "root","","ContactTrace");
                $sql = "INSERT INTO Locations(user, date, time, duration, x, y)
                VALUES('$user','$date','$time','$duration','$x','$y')";    
                if (mysqli_query($conn, $sql)){
                    $error = "Successful request.";
                }else{
                    $error = "Error: " . mysqli_error($conn);
                }  
            }
        }
        ?>
        <h1>COVID - 19 Contact Tracing</h1>
        <div class="buttongrid">
            <button type="button" onclick="location.href='Home.php'" >Home</button>
            <button type="button" onclick="location.href='Overview.php'">Overview</button>
            <button type="button" onclick="location.href='AddVisit.php'" style="background-color: #8497b0;">Add Visit</button>
            <button type="button" onclick="location.href='Report.php'">Report</button>
            <button type="button" onclick="location.href='Settings.php'">Settings</button>
            <br><br><br><br>
            <button type="button" onclick="location.href='Logout.php';">Logout</button>
        </div>
        <img class="virus" src="watermark.png" alt="">
        <div class="maingrid">
            <h2>Add a new Visit</h2>
            <hr>
            <div class="textgrid">
                <form method="POST">
                    <input type="text" placeholder="Date" name="date"></button>
                    <input type="text" placeholder="Time" name="time"></button>
                    <input type="text" placeholder="Duration" name="duration"></button>
                    <p class="error"><?php echo $error;?></p>
                    <br><br><br><br><br><br><br>
                    <input type="submit" value="Add"></button>
                    <br>
                    <input type="reset" value="Cancel"></button>
                </form>
            </div>
            <a href="">  
                <img class="map" src="exeter.jpg" alt="Map of exeter" ismap>
            </a> 
        </div>
    </body>
</html>