<?php

//file di connessione al database
include('./db_info.php');

//inizio la sessione
session_start();

$username = $_SESSION['username'];
$role = $_SESSION['role'];

//se non c'è un username o il ruolo non è tutor, reindirizzo alla pagina di login
if (!isset($username) || $role != 'tutor') {
    header('Location: ./tutor_login.php');
}

//recupero i parametri passati nell'url
if (isset($_GET['student_id'])) {
    $id = $_GET['student_id'];
} else {
    header('Location: ./dashboard.php');
}

//recupero i dati dello studente
$query = "SELECT * FROM studente WHERE student_id = '$id'";
$result = mysqli_query($con, $query);
$num_rows = mysqli_num_rows($result);

//se non ci sono risultati, reindirizzo alla pagina principale
if ($num_rows == 0) {
    header('Location: ./dashboard.php');
}

//recupero ID del tutor
$query = "SELECT * FROM tutor WHERE username = '$username'";
$result = mysqli_query($con, $query);
$num_rows = mysqli_num_rows($result);

//se non ci sono risultati, reindirizzo alla pagina principale
if ($num_rows == 0) {
    header('Location: ./dashboard.php');
}

//recupero i dati del tutor
$tutor = mysqli_fetch_assoc($result);
$tutor_id = $tutor['tutor_id'];

//devo aggiornare il database per indicare che lo studente è stato rifiutato
$query = "UPDATE RichiestaInsegnamento SET Status = 'Rifiutato' WHERE student_id = '$id' AND tutor_id = '$tutor_id'";
$result = mysqli_query($con, $query);

//se l'aggiornamento è andato a buon fine, reindirizzo alla pagina principale
if ($result) {
    header('Location: ./dashboard.php');
} else {
    echo 'Errore durante il rifiuto dello studente: ' . mysqli_error($con);
}

?>