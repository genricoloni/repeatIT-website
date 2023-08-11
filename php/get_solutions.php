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

//se l'array di soluzioni è vuoto, inserisco solo il campo 'exercise' con l'id dell'esercizio
if (mysqli_num_rows($result) == 0) {
    $solution = array(
        'exercise' => $id_exercise,
        'text' => 'no solution'
    );
    array_push($solutions, $solution);
} else {

//per ogni soluzione
while ($row = mysqli_fetch_array($result)) {
    //salvo il linguaggio e il codice
    $language = $row['language'];
    $text = $row['text'];

    //aggiungo anche id dell'esercizio
    $exercise = $row['exercise'];
    $solution_id = $row['solution_id'];


    //recupero il nome del linguaggio dalla tabella Language
    $query2 = "SELECT * FROM Language WHERE language_id = '$language'";
    $result2 = mysqli_query($con, $query2);

    //se la query è andata a buon fine, recupero il nome del linguaggio
    if ($result2) {
        $row2 = mysqli_fetch_array($result2);
        $language = $row2['name'];
    } else {
        echo 'errore';
    }
    //creo un array con i dati
    $solution = array(
        'exercise' => $exercise,
        'solution_id' => $solution_id,
        'language' => $language,
        'text' => $text
    );

    //aggiungo l'array di dati all'array di soluzioni
    array_push($solutions, $solution);


}
}

//codifico l'array di soluzioni in json
$json = json_encode($solutions);

//invio il json all'ajax che ne ha fatto richiesta
echo $json;

?>

