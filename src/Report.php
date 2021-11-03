<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Visits Overview</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="mainstyle.css">
        <link rel="stylesheet" href="reportstyle.css">
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
            // connect to the database
            $conn = mysqli_connect("localhost", "root","","ContactTrace");
            $sql = "INSERT INTO Infections(user, date, time)
            VALUES('$user','$date','$time')";
            if (mysqli_query($conn, $sql) && $date !== "1970-01-01" && $time){
                $error = "Successful request.\n Infection Date:$date and Time:$time";
            } else{
                $error = "Error: " . mysqli_error($conn);
            }
        }
        ?>
        <h1>COVID - 19 Contact Tracing</h1>
        <div class="buttongrid">
        <button type="button" onclick="location.href='Home.php'" >Home</button>
            <button type="button" onclick="location.href='Overview.php'">Overview</button>
            <button type="button" onclick="location.href='AddVisit.php'">Add Visit</button>
            <button type="button" onclick="location.href='Report.php'" style="background-color: #8497b0;">Report</button>
            <button type="button" onclick="location.href='Settings.php'">Settings</button>
            <br><br><br><br>
            <button type="button" onclick="location.href='Logout.php';">Logout</button>
        </div>
        <img class="virus" src="watermark.png" alt="">
        <div class="maingrid">
            <h2>Report an Infection</h2>
            <hr>
            <p>
                Please report the date and time when you were tested positive for COVID-19.
            </p>
            <p class="error"><?php echo $error;?></p>
        </div>
        <form method="POST" action="Report.php">
            <input type="text" placeholder="Date" class="date" name="date"></button>
            <br>
            <input type="text" placeholder="Time" class="time" name="time"></button>
            <br>
            <br>
            <input type="submit" value="Report" class="report"></button>
            <input type="reset" value="Cancel" class="cancel"></button>
        </form>
    </body>
</html>