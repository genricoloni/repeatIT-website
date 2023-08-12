<?php

//file di connessione al database
include('./db_info.php');

//se non viene passato un id o un tutor_id, reindirizzo alla pagina principale
if(!isset($_GET['id']) && !isset($_GET['tutor_id'])){
    header('Location: ./dashboard.php');
}

//se viene passato tutot_id
if(isset($_GET['tutor_id'])){
        //recupero l'id del tutor
    $id_tutor = $_GET['tutor_id'];

    //recupero tutti gli esercizi del tutor
    $query = "SELECT * FROM Exercise WHERE creator_id = '$id_tutor'";
    $result = mysqli_query($con, $query);


    //creo un array vuoto
    $exercises = array();

    //se non ci sono esercizi, metto un solo campo text con scritto "no_exercises"
    if(mysqli_num_rows($result) == 0){
        $exercises[] = array('text' => 'no_exercises');
    } else {
        //per ogni esercizio
        while($row = mysqli_fetch_array($result)){
            //creo un array aggiungendo i campi exercise_id, title, difficulty, tutor_id e category
            //per la categoria faccio una query per recuperare il nome della categoria
            $query = "SELECT * FROM Category WHERE category_id = '".$row['category']."'";
            $result2 = mysqli_query($con, $query);

            //recupero il nome della categoria
            $row2 = mysqli_fetch_array($result2);

            //creo l'array
            $exercise = array(
                'exercise_id' => $row['exercise_id'],
                'title' => $row['title'],
                'difficulty' => $row['difficulty'],
                'tutor_id' => $row['creator_id'],
                'category' => $row2['name']
            );
            //aggiungo l'array all'array di esercizi
            $exercises[] = $exercise;

        }

        //creo il json
        $json = json_encode($exercises);

        //stampo il json
        echo $json;

    }
}

?>