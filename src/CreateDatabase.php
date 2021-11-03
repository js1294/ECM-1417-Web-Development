<?php
    function query($conn, $sql){
      if (mysqli_query($conn, $sql)) {
        echo "Successful query.";
      } else {
        echo "Error: " . mysqli_error($conn);
      }
    }

    // This is run to create the database
    // and tables ready for records to be added.

    // Create connection
    $conn = mysqli_connect("localhost", "root");
    // Check connection
    if (!$conn) {
      die("Connection error: ".mysqli_connect_error());
    }
    // Creates the entire database where tables can be stored.
    $sql = "CREATE DATABASE ContactTrace";
    query($conn, $sql);  
    // Close the connection
    mysqli_close($conn);

    // reconnect to the database
    $conn = mysqli_connect("localhost", "root","","ContactTrace");

    $sql = "DROP TABLE Users";
    query($conn, $sql);

    // Create the table users, storing all the users data.
    // Password is the salted hash in the table.
    $sql = "CREATE TABLE Users (
      id INT(6) UNSIGNED AUTO_INCREMENT,
      forename VARCHAR(30) NOT NULL,
      surname VARCHAR(30),
      username VARCHAR(30) NOT NULL,
      password VARCHAR(100) NOT NULL,
      salt VARBINARY(30) NOT NULL,
      pepper VARBINARY(30) NOT NULL,
      PRIMARY KEY (id))";
    query($conn, $sql);

    $sql = "DROP TABLE Locations";
    query($conn, $sql);

    // A table to store the visits of each users.
    $sql = "CREATE TABLE Locations (
      id INT(6) UNSIGNED AUTO_INCREMENT,
      user INT(6) NOT NULL,
      date DATE NOT NULL,
      time TIME(0) NOT NULL,
      duration INT(6) NOT NULL,
      x INT(6) NOT NULL,
      y INT(6) NOT NULL,
      PRIMARY KEY (id))";
    query($conn, $sql);

    $sql = "DROP TABLE Infections";
    query($conn, $sql);

     // A table to store the infection of each user. 
    $sql = "CREATE TABLE Infections (
      id INT(6) UNSIGNED AUTO_INCREMENT,
      user INT(6),
      date DATE NOT NULL,
      time TIME(0) NOT NULL,
      PRIMARY KEY (id))";
    query($conn, $sql);
?>