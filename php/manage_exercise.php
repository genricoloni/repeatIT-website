<?php

//pagina per gestire tutti gli esercizi
//includo il file di connessione al database
include('db_info.php');

//recupero le informazioni di sessione
session_start();
$username = $_SESSION['username'];
$role = $_SESSION['role'];

if ($role != 'tutor') {
    header('Location: ./index.php');
}

//reinserisco le informazioni di sessione
$_SESSION['username'] = $username;
$_SESSION['role'] = $role;

//la pagina può essere acceduta solo se si è loggati come tutor


//recupero id del tutor
$query = "SELECT * FROM tutor WHERE username = '$username'";
$result = mysqli_query($con, $query);

//se la query è andata a buon fine, recupero l'id del tutor
if ($result) {
    $row = mysqli_fetch_array($result);
    $id_tutor = $row['tutor_id'];
} else {
    echo 'errore';
}

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Gestione esercizi</title>
    <link rel="stylesheet" type="text/css" href="../css/manage_exercise.css">
    <script src="../js/manage_exercise.js"></script>
</head>

<!-- dopo il caricamento della pagina, eseguo la funzione load() -->
<body onload="onload()">

   <!-- la pagina è divisa in due div:
   -a sinistra la lista di tutti gli esercizi. ogni div che contiene l'anteprima degli esercizi si espande se viene cliccato
   -a destra c'è una parte che appare solo dopo che viene cliccato il tasto 'aggiungi soluzione' e che permette di aggiungere una soluzione con un form
    -->
    <div class="container">
        <div class="left">
            <h1>Gestione esercizi</h1>
            
            <!-- questo div contiene l'anteprima di ogni esercizio -->
            <?php
            //recupero tutti gli esercizi scritti dal tutor
            $query = "SELECT * FROM Exercise WHERE creator_id = '$id_tutor'";
            $result = mysqli_query($con, $query);

            //per ogni esercizio, mostro un div che contiene il titolo, la categoria, la difficoltà e il testo dell'esercizio
            while ($row = mysqli_fetch_array($result)) {
                $exercise_id = $row['exercise_id'];
                $title = $row['title'];
                $text = $row['text'];
                $difficulty = $row['difficulty'];
                $category = $row['category'];
                $creator_id = $row['creator_id'];

                $query2 = "SELECT * FROM Category WHERE category_id = '$category'";
                $result2 = mysqli_query($con, $query2);
                $row2 = mysqli_fetch_array($result2);
                $category = $row2['name'];

                //creo un div che contiene l'anteprima dell'esercizio
                echo "<div class='exercise_preview' id='$exercise_id'>";
                echo "<div class='prev' id='prev_$exercise_id'>";
                echo "<div class='title'><h1>$title</h1></div>";
                echo "<div class='category'><h2>$category</h2></div>";
                echo "<div class='difficulty'><h2>Difficoltà: $difficulty</h2></div>";
                echo "</div>";

                //inizialmente il testo dell'esercizio è nascosto
                echo "<div class='exercise_text' id='exercise_text_$exercise_id' style='display: none'>$text";
                
                //dopo il testo inserisco un pulsante
                echo "<div class='button'>";
                echo "<button class='btn' id='show_solution$exercise_id' onclick='showSolution($exercise_id)'>Gestisci soluzioni</button>";
                echo "</div>";
                echo "</div>";

                echo "</div>";





            
            }

        
            ?>

        <div class="right">
            <h1>Gestione soluzioni</h1>
            <p>Seleziona un esercizio per gestire le soluzioni</p>


</div>
</body>
</html>
