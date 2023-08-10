<?php

//includo il file di connessione al database
include('db_info.php');

//accetto la richiesta di insegnamento da parte di uno studente

if (isset($_GET['student_id'])) {
    //recupero l'id dello studente
    $student_id = $_GET['student_id'];

    //recupero le informazioni di sessione
    session_start();
    $tutor_username = $_SESSION['username'];

    //recupero id del tutor 
    $query = "SELECT * FROM tutor WHERE username = '$tutor_username'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);



    //verifico che l'id dello studente sia un numero
    if (is_numeric($student_id)) {
        //recupero le informazioni del tutor dal database
        $query = "SELECT * FROM tutor WHERE username = '$tutor_username'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result);

        //assegno a delle variabili i dati del tutor
        $tutor_id = $row['tutor_id'];
        $tutor_level = $row['level'];

        //stampo la query
        echo $query;

        //stampa do debug dei dati del tutor
        echo $tutor_id . ' ' . $tutor_level;

        //recupero le informazioni dello studente dal database
        $query = "SELECT * FROM studente WHERE student_id = '$student_id'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result);

        //assegno a delle variabili i dati dello studente
        $student_name = $row['NomeCompleto'];
        $student_level = $row['livello'];

        //prima modifico il record in RichiestaInsegnamento, modificando il campo status
        $query = "UPDATE RichiestaInsegnamento SET status = 'Accettato' WHERE student_id = '$student_id' AND tutor_id = '$tutor_id'";
        $result = mysqli_query($con, $query);

        //se la query è andata a buon fine, inserisco il record nella tabella Insegnamento
        if ($result) {
            $query = "INSERT INTO Insegnamento (student_id, tutor_id) VALUES ($student_id, $tutor_id)";
            echo $query;

            $result = mysqli_query($con, $query);


            //se la query è andata a buon fine, reindirizzo alla pagina di dashboard
            if ($result) {
                //mostra un messaggio di successo e dopo 5 secondi reindirizza alla pagina di dashboard
                echo '<div class="alert alert-success" role="alert">
                        Richiesta di insegnamento accettata con successo!
                    </div>';
                header('Location: ./dashboard.php?');
            } else {
                //stampo l'errore
                echo mysqli_error($con);

            }
        } else {
            //se la query non è andata a buon fine, reindirizzo alla pagina di dashboard
            header('Location: ./dashboard.php?role=tutor?unlucky');
        }


    } else {
        //se l'id dello studente non è un numero, reindirizzo alla pagina di dashboard
        header('Location: ./dashboard.php?role=tutor');
    }


    
} else {
    //se non è stato passato l'id dello studente, reindirizzo alla pagina di dashboard
    header('Location: ./dashboard.php');
}