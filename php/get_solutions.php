<?php

//connessione al database
include('db_info.php');

//si occupa di recuperare tutte le soluzioni di un esercizio da passare come json
//recupero l'id dell'esercizio
$id_exercise = $_GET['id'];

//recupero tutte le soluzioni
$query = "SELECT * FROM Solution WHERE exercise = '$id_exercise'";
$result = mysqli_query($con, $query);

//creo un array di soluzioni
$solutions = array();

//per ogni soluzione
while ($row = mysqli_fetch_array($result)) {
    //salvo il linguaggio e il codice
    $language = $row['language'];
    $text = $row['text'];

    //creo un array con i dati
    $solution = array(
        'language' => $language,
        'text' => $text
    );

    //aggiungo l'array di dati all'array di soluzioni
    array_push($solutions, $solution);


}

//codifico l'array di soluzioni in json
$json = json_encode($solutions);

//invio il json all'ajax che ne ha fatto richiesta
echo $json;

?>

