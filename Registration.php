<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Registration</title>
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

      function query($conn, $sql){
        if (mysqli_query($conn, $sql)) {
          echo "Successful query.";
        } else {
          echo "Error: " . mysqli_error($conn);
        }
      }

      $forname = "";
      $surname = "";
      $username = "";
      $password = "";
      $password_error = "";
      $valid = true;
      if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $forname = input($_POST["forename"]);
        $surname = input($_POST["surname"]);
        $username = input($_POST["username"]);
        $password = input($_POST["password"]);
         // Check's the length of the password
        if(strlen($password) < 8){
          $password_error = "Password must be greater than 7 characters long.";
          $valid = false;
        }
         // Checks only numbers, spaces and letters are used
        if (!preg_match("/^[0-9a-zA-Z-' ]*$/",$password)) {
          $password_error = "Only uppercase, lowercase and numbers are allowed.";
          $valid = false;
        }
        // Checks the password is not a commonly used password
        $file = fopen("common.txt", "r") or die("Unable to open the file!");
        $found = false;
        while(!($found||feof($file))){
          $word = fgets($file,1024);
          //Make sure there are no empty lines in the file otherwise will always be true.
          if (str_contains($password, $word)){
            $found = true;
            $valid = false;
            $password_error = "No commonly used passwords allowed.";
          }
        }
        fclose($file);
        if ($valid){
          $error = false;
          $salt = openssl_random_pseudo_bytes(10);
          $pepper = openssl_random_pseudo_bytes(15);
          $pepper_pass = $salt.$password.$pepper;

          // Used bcrypt since it uses the secure vesion of crypt, $2y$ and isn't too fast.
          $hash = password_hash($pepper_pass, PASSWORD_BCRYPT);
          
          // connect to the database
          $conn = mysqli_connect("localhost", "root","","ContactTrace");
          // Insert the new user into the table.
          $sql = "INSERT INTO Users(forename, surname, username, password, salt, pepper)
          VALUES('$forname', '$surname', '$username', '$hash', '$salt', '$pepper')";
          query($conn, $sql);
          session_start();
          $sql = "SELECT * FROM Users ORDER BY id DESC LIMIT 1";
          if ($query = mysqli_query($conn, $sql)) {
            $result = mysqli_fetch_assoc($query);
            $_SESSION["id"] = $result['id'];
          } else {
            echo "Error: " . mysqli_error($conn);
          }
          // Close the connection
          mysqli_close($conn);
          if ($valid){
            $_SESSION["timeout"] = time(); //Used to check if the session should be closed, if inactive for too long
            header("Location: Home.php");
          }
        }
      }
      ?>
        <h1>COVID - 19 Contact Tracing</h1>
        <img src="watermark.png" alt="">
        <form name="regForm" method="POST" action="Registration.php" onsubmit="return validateForm()">
            <br><br><br>
            <input type = "text" placeholder="Name" name="forename">
            <p class="hidden" id="forename" ></p>
            <input type = "text" placeholder="Surname" name="surname">
            <br>
            <input type = "text" placeholder="Username" name="username">
            <p class="hidden" id="username" ></p>
            <input type = "text" placeholder="Password" name="password">
            <p class="hidden" id="password" ></p>
            <span class="error"><?php echo $password_error;?></span>
            <br>
            <input type="submit" value="Register" id="Register">
        </form>
        <script>
        function validateForm() {
          var forname = document.forms["regForm"]["forename"].value;
          var username = document.forms["regForm"]["username"].value;
          var password = document.forms["regForm"]["password"].value;
          var valid = true;
          if (forname == "") {
            document.getElementById("forename").innerHTML = "A first name is required.";
            valid = false;
          }
          if (username == "") {
            document.getElementById("username").innerHTML = "A username is required.";
            valid = false;
          }
          if (password == "") {
            document.getElementById("password").innerHTML = "A password is required.";
            valid = false;
          }
          return valid;
        }
        </script>
    </body>
</html>