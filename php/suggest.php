<?php

//file di connessione al database
include('db_info.php');

//info di sessione
session_start();


//recupero le info di sessione
$tutor_username = $_SESSION['username'];
$role = $_SESSION['role'];

//i dati vengono passati tramite post di una submit del form
//recupero i dati dal form
if (isset($_POST['submit'])) {


    //i parametri passati sono id dello studente e id dell'esercizio
    $id_student = $_POST['student'];
    $id_exercise = $_POST['exercise'];

    echo $id_student . "<br>";
    echo $id_exercise . "<br>";



    //recupero id del tutor
    $query = "SELECT * FROM tutor WHERE username = '$tutor_username'";
    $result = mysqli_query($con, $query);

    //se la query è andata a buon fine
    if ($result) {
        //recupero l'id del tutor
        $row = mysqli_fetch_array($result);
        $id_tutor = $row['tutor_id'];
        echo $id_tutor . "<br>";

        //recupero l'id dell'insegnamento
        $query = "SELECT teach_id FROM Insegnamento WHERE tutor_id = '$id_tutor' AND student_id = '$id_student'";
        $result = mysqli_query($con, $query);

        //se la query è andata a buon fine
        if ($result) {
            //recupero l'id dell'insegnamento
            $row = mysqli_fetch_array($result);
            $id_teach = $row['teach_id'];
            echo $id_teach . "<br>";
        } else {
            //stampo l'errore
            echo mysqli_error($con);
            
        }
        //inserisco un nuovo record in suggestion

        $query = "INSERT INTO Suggestion (exercise_id, teaching_id) VALUES ('$id_exercise', '$id_teach')";
        $result = mysqli_query($con, $query);


        //se la query è andata a buon fine
        if ($result) {
            //stampo un messaggio di successo
            echo "<script>alert('Esercizio suggerito con successo.')</script>";
        } else {
            //stampo l'errore
            echo mysqli_error($con);
        }
    } else {
        //stampo l'errore
        echo mysqli_error($con);
               

    }
}

?>

<!DOCTYPE html>
<html lang="it">
