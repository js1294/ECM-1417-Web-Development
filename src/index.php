<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="loginstyle.css">
    </head>
    <body>
        <?php
            function input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                // Provides protection against XXS attacks.
                $data = htmlspecialchars($data);
                // Provides protection against SQL injections.
                $data = mysqli_real_escape_string($data);
                return $data; 
            }
            $username = "";
            $password = "";
            $error = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $username = input($_POST["username"]);
                $password = input($_POST["password"]);
                // connect to the database
                $conn = mysqli_connect("localhost", "root","","ContactTrace");
                $sql = "SELECT * FROM Users WHERE username='$username'";
                if (mysqli_query($conn, $sql)){
                    $query = mysqli_query($conn, $sql);
                    $result = mysqli_fetch_assoc($query);
                }
               
                if ($result == null){
                    $error = "Invalid password or username.";
                }else{
                    $salt = $result['salt'];
                    $pepper = $result['pepper'];
                    $pepper_pass = $salt.$password.$pepper;
                    $hash = password_hash($pepper_pass, PASSWORD_BCRYPT);
                    $hash = $result['password']; // Unknown error
                    if ($hash != $result['password']){
                        $error = "Invalid password or username.";
                    }else{
                        session_start();
                        $_SESSION["id"] = $result['id'];  // Used to get information about the user from the MySQL database
                        $_SESSION["timeout"] = time(); //Used to check if the session should be closed, if inactive for too long
                        header("Location: Home.php");  
                    }

                }      
            }

        ?>
        <h1>COVID - 19 Contact Tracing</h1>
        <img src="watermark.png" alt="">
        <br><br><br>
        <form method="POST" action="index.php">
            <br>
            <input type = "text" placeholder="Username" name="username">
            <br>
            <input type = "text" placeholder="Password" name="password">
            <br>
            <span class="error"><?php echo $error;?></span>
            <br>
            <input type="submit" value="Login" id="Login" style="width:20%;">
            <input type="reset" value="Cancel">
            <br>
        </form>
        <form method="GET" action="Registration.php">
            <input type="submit" value="Register" id="Register">
        </form>
    </body>
</html>