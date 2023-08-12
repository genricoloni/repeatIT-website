<?php

//file di connessione al database
include('./db_info.php');

//inizio la sessione
session_start();

$username = $_SESSION['username'];
$role = $_SESSION['role'];

//se non è stato fatto il login, reindirizzo alla pagina di login
if (!isset($username)) {
    header('Location: /index.php');
}

//inizio a creare la pagina html
echo '<!DOCTYPE html>
<html>
<head>
    <title>Cerca esercizio</title>
    <link rel="stylesheet" type="text/css" href="../css/search_page.css">
    <script src="../js/search.js"></script>
</head>
<body onload="setup()">';

//nel body ci sono due div principali:
//in alto c'è la barra di navigazione
//in basso c'è il contenuto della pagina, con tutti gli esercizi

echo '<div class="navbar">';

//la navbar contiene una serie di elementi che permettono di filtrare gli esercizi
//gli elementi sono:
// -ricerca per titolo
// -ricerca per categoria
// -ricerca per difficoltà
// -ricerca per autore

//tutti i parametri vengono utilizzati contemporaneamente per filtrare gli esercizi
//quindi il metodo migliore per passare i parametri è avere un unico form con tutti i parametri

//i dati del form vengono passati ad una funzione javascript che si occupa di aggiornare la pagina tramite ajax

//creazione del form
echo '<form id="form" action="javascript:show()" method="post">';

//metto una didascalia per ogni campo
echo '<label for="title">Titolo</label>';
echo '<input type="text" name="title" placeholder="Titolo">';

echo '<label for="category">Categoria</label>';
echo '<select name="category">';
//aggiungo un'opzione per non filtrare per categoria, che ha value any
echo '<option value="any">Qualsiasi</option>';
//recupero tutte le categorie tramite una query
$query = "SELECT * FROM Category";
$result = mysqli_query($con, $query);
//per ogni categoria, creo un elemento option, il cui valore è l'id della categoria e il cui testo è il nome della categoria
while ($row = mysqli_fetch_array($result)) {
    echo '<option value="' . $row['category_id'] . '">' . $row['name'] . '</option>';
}

echo '</select>';


echo '<label for="difficulty">Difficoltà</label>';
echo '<select name="difficulty">';
//aggiungo un'opzione per non filtrare per difficoltà, che ha value any
echo '<option value="any">Qualsiasi</option>';
//la difficoltà è un numero da 1 a 5, quindi creo 5 elementi option
for ($i = 1; $i <= 5; $i++) {
    echo '<option value="' . $i . '">' . $i . '</option>';
}


echo '</select>';

echo '<label for="creator">Autore</label>';
//per gli autori, recupero tutti i tutor tramite una query
$query = "SELECT * FROM tutor";
//metto il NomeCompleto e, tra parentesi, l'username
$result = mysqli_query($con, $query);
echo '<select name="creator">';
//aggiungo un'opzione per non filtrare per autore, che ha value any, che è anche il valore di default
echo '<option value="any">Qualsiasi</option>';
//come value metto l'id del tutor, come testo metto il NomeCompleto e tra parentesi l'username
while ($row = mysqli_fetch_array($result)) {
    echo '<option value="' . $row['tutor_id'] . '">' . $row['NomeCompleto'] . ' (' . $row['username'] . ')</option>';
}


echo '</select>';

//creo un bottone per resettare i filtri
echo '<input id="reset" type="button" value="Reset" ">';
//creo un bottone per inviare il form
echo '<input type="submit" value="Filtra">';
echo '</form>';

echo '</div>';

//creo il div che contiene tutti gli esercizi
echo '<div class="exercises">';
//recupero tutti gli esercizi tramite una query
$query = "SELECT * FROM Exercise";
$result = mysqli_query($con, $query);
//ogni esercizio ha un div con titolo, categoria, difficoltà e autore
while ($row = mysqli_fetch_array($result)) {
    $exercise_id = $row['exercise_id'];
    $title = $row['title'];
    $text = $row['text'];
    $difficulty = $row['difficulty'];
    $category = $row['category'];
    $creator_id = $row['creator_id'];

    //recupero il nome della categoria
    $query2 = "SELECT * FROM Category WHERE category_id = '$category'";
    $result2 = mysqli_query($con, $query2);
    $row2 = mysqli_fetch_array($result2);
    $category = $row2['name'];

    //recupero il nome dell'autore
    $query2 = "SELECT * FROM tutor WHERE tutor_id = '$creator_id'";
    $result2 = mysqli_query($con, $query2);
    $row2 = mysqli_fetch_array($result2);
    $creator_name = $row2['NomeCompleto'];

    //creo il div che contiene l'anteprima dell'esercizio
    echo "<div class='exercise_preview' id='$exercise_id'>";
    echo "<div class='prev' id='prev_$exercise_id'>";
    echo "<div class='title'><h1>$title</h1></div>";
    echo "<div class='category'><h2>$category</h2></div>";
    echo "<div class='difficulty'><h2>Difficoltà: $difficulty</h2></div>";
    echo "<div class='creator'><h2>Autore: $creator_name</h2></div>";
    //aggiungo un bottone per visualizzare l'esercizio
    echo "<div class='button'>";
    echo "<button class='btn' id='show_exercise$exercise_id'>Visualizza</button>";
    echo "</div>";

    //inizialmente il testo dell'esercizio è nascosto

    

    
    echo "</div>";

    echo "</div>";
}

echo '</div>';

echo '</body>
</html>';