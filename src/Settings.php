<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Settings</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="mainstyle.css">
        <link rel="stylesheet" href="settingstyle.css">
    </head>
    <body>
        <?php session_start();
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
        ?>
        <h1>COVID - 19 Contact Tracing</h1>
        <div class="buttongrid">
        <button type="button" onclick="location.href='Home.php'" >Home</button>
            <button type="button" onclick="location.href='Overview.php'">Overview</button>
            <button type="button" onclick="location.href='AddVisit.php'">Add Visit</button>
            <button type="button" onclick="location.href='Report.php'">Report</button>
            <button type="button" onclick="location.href='Settings.php'" style="background-color: #8497b0;">Settings</button>
            <br><br><br><br>
            <button type="button" onclick="location.href='Logout.php';">Logout</button>
        </div>
        <img class="virus" src="watermark.png" alt="">
        <div class="maingrid">
            <h2>Alert Settings</h2>
            <hr>
            <p>
                Here you may change the alert distance and time span for which the contact tracing will be performed.
            </p>
        </div>
        <p class="windowtext">window</p>
        <p class="distancetext">distance</p>
        <form method="POST" action="UpdateCookie.php">
            <select class="window" name="window">
                <option value=""></option>
                <option value=1>One Week</option>
                <option value=2>Two Weeks</option>
                <option value=1>Three Weeks</option>
                <option value=2>Four Weeks</option>
            </select>
            <input type="text" class="distance" name="distance"></button>
            <br>
            <input type="submit" value="Report" class="report"></button>
            <input type="reset" value="Cancel" class="cancel"></button>
        </form>
    </body>
</html>