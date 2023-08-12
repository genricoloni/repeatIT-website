<?php

//file di connessione al database
include('./db_info.php');

//inizio la sessione
session_start();

//se non si è loggati, reindirizzo alla pagina di login
if (!isset($_SESSION['username'])) {
    header('Location: ./login.php');
}

//mostro tutte le informazioni dell'esercizio
if (isset($_GET['exercise_id'])) {
    $id = $_GET['exercise_id'];
}

//recupero i dati dell'esercizio
$query = "SELECT * FROM Exercise WHERE exercise_id = '$id'";
$result = mysqli_query($con, $query);

//se non ci sono risultati, reindirizzo alla pagina principale
if (mysqli_num_rows($result) == 0) {
    header('Location: ./dashboard.php');
}

//recupero i dati dell'esercizio
$exercise = mysqli_fetch_assoc($result);
$title = $exercise['title'];
$text = $exercise['text'];
$difficulty = $exercise['difficulty'];
$creator_id = $exercise['creator_id'];
$category = $exercise['category'];

//recupero i dati del creatore dell'esercizio
$query = "SELECT * FROM tutor WHERE tutor_id = '$creator_id'";
$result = mysqli_query($con, $query);
$tutor = mysqli_fetch_assoc($result);
$creator_name = $tutor['NomeCompleto'];

//creo la pagina html
echo '<!DOCTYPE html>
<html>
<head>
    <title>' . $title . '</title>
    <link rel="stylesheet" type="text/css" href="../css/show_exercise.css">';
echo '<script src="../js/show_exercise.js"></script>';
echo '
</head>

<body onload="onload()">
    <div class="container">
        <div class="left">
            <h1>' . $title . '</h1>
            <h2>Difficoltà: ' . $difficulty . '</h2>
            <h2>Categoria: ' . $category . '</h2>
            <h2>Autore: ' . $creator_name . '</h2>
            <p>' . $text . '</p>
        </div>
    </div>
</body>
</html>';


