<?php

//file di connessione al database
include('db_info.php');

session_start();

//recupero le info di sessione
$tutor_username = $_SESSION['username'];
$role = $_SESSION['role'];

//reinserisco le info di sessione
$_SESSION['username'] = $tutor_username;
$_SESSION['role'] = $role;

//se non è stato passato un id, esco
if (!isset($_GET['id'])) {
    header('Location: ./dashboard.php');
} 

//se arrivo con un messaggio di errore
if (isset($_GET['status'])) {
    //se dentro il parametro c'è la parola 'duplicate'
    if (strpos($_GET['status'], 'Duplicate') !== false) {
        //stampo un messaggio di errore
        echo "<script>alert('Hai già inserito una soluzione in questo linguaggio.')</script>";}
}

//recupero l'id dell'esercizio
$id_exercise = $_GET['id'];

//costruisco il form con un campo per il codice e uno per il linguaggio
//il campo per il linguaggio è un menu a tendina che recupera i linguaggi dal database
//il campo per il codice è un editor di codice

//recupero le info di sessione
$tutor_username = $_SESSION['username'];

//recupero l'id del tutor
$query = "SELECT * FROM tutor WHERE username = '$tutor_username'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);
$id_tutor = $row['tutor_id'];

//se lesercizio non è stato creato dal tutor loggato, esco
$query = "SELECT * FROM Exercise WHERE exercise_id = '$id_exercise' AND creator_id = '$id_tutor'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);

if (!$row) {
    header('Location: ./dashboard.php?status=not_creator');
}

//se l'id dell'esercizio non è un numero, oppure non equivale a nessun esercizio, esco
if (!is_numeric($id_exercise)) {
    header('Location: ./dashboard.php?status=invalid_id');
}

//recupero le info dell'esercizio
$query = "SELECT * FROM Exercise WHERE exercise_id = '$id_exercise'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);

//se l'esercizio non esiste, esco
if (!$row) {
    header('Location: ./dashboard.php?status=invalid_id');
}

//recupero i linguaggi dal database
$query = "SELECT * FROM Language";
$result = mysqli_query($con, $query);

//creo un array di linguaggi
$languages = array();

//per ogni linguaggio
while ($row = mysqli_fetch_array($result)) {
    //recupero il nome e l'id
    $name = $row['name'];
    $language_id = $row['language_id'];

    //creo un array con i dati
    $language = array(
        'name' => $name,
        'language_id' => $language_id
    );

    //aggiungo l'array di dati all'array di linguaggi
    array_push($languages, $language);
}

//codifico l'array di linguaggi in json
$json = json_encode($languages);

//prima del form c'è un titolo 'Aggiungi soluzione'
echo '<h1>Aggiungi soluzione</h1>';

//creo un div contenente il form
echo '<div id="form">';
echo '<form action="add_solution.php" method="post">';
echo '<input type="hidden" name="id_exercise" value="' . $id_exercise . '">';
echo '<label for="language">Linguaggio</label>';
echo '<select id="language" name="language"></select>';
echo '<label for="code">Codice</label>';
echo '<textarea id="code" name="code"></textarea>';
echo '<input type="submit" value="Aggiungi">';
echo '</form>';
echo '</div>';

//inserisco i linguaggi nel menu a tendina
echo '<script>';
echo 'var languages = ' . $json . ';';
echo 'var select = document.getElementById("language");';
echo 'for (var i = 0; i < languages.length; i++) {';
echo 'var option = document.createElement("option");';
echo 'option.value = languages[i].language_id;';
echo 'option.text = languages[i].name;';
echo 'select.appendChild(option);';
echo '}';
echo '</script>';

//se è stato inviato il form
if (isset($_POST['code'])) {
    //recupero i dati dal form
    $language = $_POST['language'];
    $code = $_POST['code'];
    $id_exercise = $_POST['id_exercise'];

    //inserisco la soluzione nel database
    $query = "INSERT INTO Solution (exercise, language, text) VALUES ('$id_exercise', '$language', '$code')";
    $result = mysqli_query($con, $query);

    //se la query non è andata a buon fine
    if (!$result) {
        //ricarico la pagina con un messaggio di errore
        header('Location: ./add_solution.php?id=' . $id_exercise . '&status='. mysqli_error($con) );
        
    } else {
        //altrimenti reindirizzo alla pagina delle soluzioni
        header('Location: ./manage_exercise.php');
    }


}





?>

<!DOCTYPE html>
<html lang="it">
    <head >
        <title>Aggiungi soluzione</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../css/add_solution.css">


    </head>

    <body>


