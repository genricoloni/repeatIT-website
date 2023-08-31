<?php

//file di connessione al database
include('db_info.php');

//info di sessione
session_start();

//recupero le info di sessione
$tutor_username = $_SESSION['username'];
$role = $_SESSION['role'];

//reinserisco le info di sessione
$_SESSION['username'] = $tutor_username;
$_SESSION['role'] = $role;

//recupero l'id del tutor
$query = "SELECT * FROM tutor WHERE username = '$tutor_username'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);
$id_tutor = $row['tutor_id'];

//la pagina può essere acceduta solo se si è loggati come tutor
if (!isset($_SESSION['username'])) {
    header('Location: ./login.php');
}

//se il form è stato inviato
if (isset($_POST['submit'])) {
    //recupero i dati dal form
    $title = $_POST['title'];
    $text = $_POST['text'];
    $difficulty = $_POST['difficulty'];
    $category = $_POST['category'];

    $text = str_replace("'", "\\'", $text);
    $text = mysqli_real_escape_string($con, $text);





    //inserisco i dati nel database
    $query = "INSERT INTO Exercise (title, text, difficulty, creator_id, category) VALUES ('$title', '$text', '$difficulty', '$id_tutor', '$category')";
    $result = mysqli_query($con, $query);

    //se la query è andata a buon fine, reindirizzo alla pagina di gestione degli esercizi
    if ($result) {
        //stampo un messaggio di successo
        echo "<script>alert('Esercizio aggiunto con successo.')</script>";
        header('Location: ./manage_exercise.php');
    } else {
        //stampo l'errore
        echo mysqli_error($con);
    }
}

//se si è verificato un errore generico
if (isset($_GET['generic_error'])) {
    echo "<script>alert('Si è verificato un errore generico. Riprova.')</script>";
}

//se si è loggati come studente
if (isset($_SESSION['student_id'])) {
    header('Location: ./dashboard.php');
}






?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Aggiungi esercizio</title>
        <link rel="stylesheet" href="../css/add_exercise.css">
    </head>
<!-- la pagina è come un form, i cui campi sono il titolo, il testo, la categoria scelta da un elenco la difficoltà dell'esercizio (un numero da 1 a 5)-->

    <body>
        <div class="container">
            <div class="header">
                <h1>Aggiungi esercizio</h1>
            </div>
            <div class="content">
                <form action="add_exercise.php" method="POST">
                    <div class="input-group">
                        <label for="title">Titolo</label>
                        <input type="text" name="title" id="title" required>
                    </div>
                    <div class="input-group">
                        <label for="text">Testo</label>
                        <textarea name="text" id="text" cols="30" rows="10" required></textarea>
                    </div>
                    <div class="input-group">
                        <label for="difficulty">Difficoltà</label>
                        <input type="number" name="difficulty" id="difficulty" min="1" max="5" required>
                    </div>
                    <!-- l'elenco delle categorie è recuperato dal database -->
                    <div class="input-group">
                        <label for="category">Categoria</label>
                        <select name="category" id="category">
                            <?php
                            $query = "SELECT * FROM Category";
                            $result = mysqli_query($con, $query);
                            
                            //lla tabella è formata da due colonne, id e nome, viene mostrato il nome e il category_id viene passato come valore

                            while ($row = mysqli_fetch_array($result)) {

                                echo '<option value="' . $row['category_id'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    <div class="input-group">
                        <button type="submit" name="submit" class="btn">Aggiungi</button>
                    </div>
                </form>
            </div>
        </div>
    </body>

