<?php

    //connessione al database
    $con = new mysqli('localhost', 'enrico_admin', '', 'db application');

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    } 
    ?>