<?php

//file di connessione al database
include('./db_info.php');

//inizio la sessione
session_start();


//recupero i parametri passati nell'url, che sono titolo, categoria, difficoltà e creatore

//se non ci sono parametri, reindirizzo alla pagina principale
if (!isset($_GET['title']) || !isset($_GET['category']) || !isset($_GET['difficulty']) || !isset($_GET['creator'])) {
    header('Location: ./dashboard.php');
}

//assegno a delle variabili i parametri passati nell'url
$title = $_GET['title'];
$category = $_GET['category'];
$difficulty = $_GET['difficulty'];
$creator = $_GET['creator'];

//se ci sono parametri impostati a any, li devo escludere dalla query
$query = "SELECT * FROM Exercise WHERE ";

//se il titolo non è impostato a any, lo aggiungo alla query
if ($title != 'any') {
    //la ricerca viene fatta usando il LIKE, che permette di cercare una stringa all'interno di un'altra
    $query .= "title LIKE '%$title%' AND ";

}

//se la categoria non è impostata a any, la aggiungo alla query
if ($category != 'any') {
    $query .= "category = '$category' AND ";
}

//se la difficoltà non è impostata a any, la aggiungo alla query
if ($difficulty != 'any') {
    $query .= "difficulty = '$difficulty' AND ";
}

//se il creatore non è impostato a any, lo aggiungo alla query
if ($creator != 'any') {
    $query .= "creator_id = '$creator'";
}

//se la query finisce con AND, devo togliere l'AND finale
if (substr($query, -4) == 'AND ') {
    $query = substr($query, 0, -4);
}

//se la query finisce con WHERE, devo togliere il WHERE finale
if (substr($query, -6) == 'WHERE ') {
    $query = substr($query, 0, -6);
}


//eseguo la query
$result = mysqli_query($con, $query);

//se la query è andata a buon fine
if ($result) {

    //se il resultset è vuoto, faccio json vuoto
    if (mysqli_num_rows($result) == 0) {
        $json = json_encode(array());
        echo $json;
        die();
    }

    //creo un array vuoto
    $exercises = array();

    //per ogni riga del risultato
    while ($row = mysqli_fetch_assoc($result)) {
        //aggiungo id, titolo, difficoltà, categoria e creatore all'array
        $exercise_id = $row['exercise_id'];
        $title = $row['title'];
        $difficulty = $row['difficulty'];
        $category = $row['category'];
        $creator_id = $row['creator_id'];

        //recupero il nome della categoria
        $query2 = "SELECT * FROM Category WHERE category_id = '$category'";
        $result2 = mysqli_query($con, $query2);
        $row2 = mysqli_fetch_array($result2);
        $category = $row2['name'];

        //recupero il nome completo del creatore
        $query2 = "SELECT * FROM tutor WHERE tutor_id = '$creator_id'";
        $result2 = mysqli_query($con, $query2);
        $row2 = mysqli_fetch_array($result2);
        $creator = $row2['NomeCompleto'];

        //creo un array con i dati dell'esercizio
        $exercise = array('exercise_id' => $exercise_id, 'title' => $title, 'difficulty' => $difficulty, 'category' => $category, 'creator' => $creator);
        
        //aggiungo l'esercizio all'array
        array_push($exercises, $exercise);
    }

    //faccio il json dell'array
    $json = json_encode($exercises);

    //stampo il json
    echo $json;
} else {
    //json vuoto
    $json = json_encode(array());
    echo $json;
}
