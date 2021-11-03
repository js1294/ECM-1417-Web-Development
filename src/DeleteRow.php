<?php
    // connect to the database
    $conn = mysqli_connect("localhost", "root","","ContactTrace");

    function query($conn, $sql){
        if (mysqli_query($conn, $sql)) {
          echo "Successfully removed.";
        } else {
          echo "Error: " . mysqli_error($conn);
        }
      }
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = $_POST['id'];
        $sql = "DELETE FROM Locations WHERE id='$id'";
        query($conn, $sql);
    }else{
        echo "Error: Incorrect http request.";
    }

?>