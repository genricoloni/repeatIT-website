<?php

//file di connessione al database
include('db_info.php');

//info di sessione
session_start();


//recupero le info di sessione
$tutor_username = $_SESSION['username'];
$role = $_SESSION['role'];

//stampo tutti i parametri ricevuti
//print_r($_POST

//i dati vengono passati tramite parametri nell'url
//se non vengono passati parametri, reindirizzo alla pagina principale
if (isset($_GET['student']) && isset($_GET['exercise'])) {


    //i parametri passati sono id dello studente e id dell'esercizio
    $id_student = $_GET['student'];
    $id_exercise = $_GET['exercise'];

    //recupero id del tutor
    $query = "SELECT * FROM tutor WHERE username = '$tutor_username'";
    $result = mysqli_query($con, $query);

    //se la query è andata a buon fine
    if ($result) {
        //recupero l'id del tutor
        $row = mysqli_fetch_array($result);
        $id_tutor = $row['tutor_id'];

        //recupero l'id dell'insegnamento
        $query = "SELECT teach_id FROM Insegnamento WHERE tutor_id = '$id_tutor' AND student_id = '$id_student'";
        $result = mysqli_query($con, $query);

        //se la query è andata a buon fine
        if ($result) {
            //recupero l'id dell'insegnamento
            $row = mysqli_fetch_array($result);
            $id_teach = $row['teach_id'];
        } else {
            //faccio json di errore e termino lo script
            $json = json_encode(array('status' => 'error on teach id'));
            echo $json;
            die();
        }
        //inserisco un nuovo record in suggestion

        $query = "INSERT INTO Suggestion (exercise_id, teaching_id) VALUES ('$id_exercise', '$id_teach')";
        $result = mysqli_query($con, $query);


        //se la query è andata a buon fine
        if ($result) {
            //faccio json di conferma
            //inserendo anche id del tutor
            $json = json_encode(array('status' => 'success'));
        } else {
            //faccio json di errore
            $json = json_encode(array('status' => 'error'));
        }
    } else {
        //faccio json di errore
        $json = json_encode(array('status' => 'error on tutor id'));

               

    }

    //stampo il json
    echo $json;
} else {
    //faccio json di errore con anche i parametri passati
    $json = json_encode(array('status' => 'error', 'student' => $_POST['student'], 'exercise' => $_POST['exercise']));

    //stampo il json
    echo $json;
}

?>
