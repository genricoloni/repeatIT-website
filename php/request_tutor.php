<?php

//pagina per gestire le richieste di insegnamento da parte degli studenti
//includo il file di connessione al database
include('db_info.php');

//recupero le informazioni di sessione
session_start();
$tutor_username = $_SESSION['username'];

//recupero id dello studente
$query = "SELECT * FROM studente WHERE username = '$tutor_username'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);
$id_studente = $row['student_id'];

echo $id_studente;

//mostro tutti i tutor
$query = "SELECT * FROM tutor";
$result = mysqli_query($con, $query);

//

?>


<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>Richieste di insegnamento</title>
        <link rel="stylesheet" type="text/css" href="../css/request_tutor.css">
        </head>
        
        
