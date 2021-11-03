<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Visits Overview</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="mainstyle.css">
        <link rel="stylesheet" href="overviewstyle.css">
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
            <button type="button" onclick="location.href='Overview.php'" style="background-color: #8497b0;">Overview</button>
            <button type="button" onclick="location.href='AddVisit.php'">Add Visit</button>
            <button type="button" onclick="location.href='Report.php'">Report</button>
            <button type="button" onclick="location.href='Settings.php'">Settings</button>
            <br><br><br><br>
            <button type="button" onclick="location.href='Logout.php';">Logout</button>
        </div>
        <img class="virus" src="watermark.png" alt="">
        <div class="maingrid">
            <form>
                <?php
                function error($value){
                    if ($value == null){
                        return "Error not found";
                    }
                    return $value;
                }

                // connect to the database
                $conn = mysqli_connect("localhost", "root","","ContactTrace");
                $id = $_SESSION["id"];
                $sql = "SELECT * FROM Locations WHERE user = '$id'";
                echo '<table> 
                <tr> 
                    <th> <font>Date</font> </th> 
                    <th> <font>Time</font> </th> 
                    <th> <font>Duration</font> </th> 
                    <th> <font>X</font> </th> 
                    <th> <font>Y</font> </th> 
                    <th> <font></font> </th> 
                </tr>';
                if ($query = mysqli_query($conn, $sql)){
                    while ($result = mysqli_fetch_assoc($query)){
                        $array = explode("-",$result["date"]);
                        $year = $array[0];
                        $array[0] = $array[2];
                        $array[2] = $year;
                        $date = error(implode("/",$array));
                        $time = error(substr($result["time"],0,5));

                        echo '<tr> 
                        <td>'.$date.'</td> 
                        <td>'.$time.'</td> 
                        <td>'.error($result["duration"]).'</td> 
                        <td>'.error($result["x"]).'</td> 
                        <td>'.error($result["y"]).'</td>
                        <td>
                        <a id='.$result["id"].' href="#" onClick="reply_click(this.id)">
                            <img class="cross" src="cross.png" alt="">
                        </a>
                        </td>
                        </tr>';
                    }
                }
                ?>
            </form>  
        </div>
        <script>
            function reply_click(id){
                //Synchronous Ajax request.
                var xhttp = new XMLHttpRequest();
                xhttp.open("POST","DeleteRow.php", false);
                xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhttp.send("id="+id);
                alert(xhttp.responseText);
                location.reload();
            }
        </script>
    </body>
</html>