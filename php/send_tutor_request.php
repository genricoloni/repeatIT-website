<?php

//file di connessione al database
include('./db_info.php');

//inizializzo la sessione
session_start();


//se non è stato fatto il login, reindirizzo alla pagina di login
if(!isset($_SESSION['username'])){
    header('Location: ./student_login.php');
}

$username = $_SESSION['username'];

//recupero l'id dello studente
$query = "SELECT * FROM studente WHERE username = '$username'";
$result = mysqli_query($con, $query);

//se la query è andata a buon fine
if($result){
    //recupero l'id dello studente
    $row = mysqli_fetch_array($result);
    $id_student = $row['student_id'];
} else {
    //stampo l'errore
    echo mysqli_error($con)."query1";
}

//se non viene passato come parametro l'id di un tutor, reindirizzo alla pagina principale
if(!isset($_GET['id'])){
    header('Location: ./dashboard.php');
}

//faccio query al database per inserire un record nella tabella richiesteInsegnamento
$id_tutor = $_GET['id'];
$query2 = "INSERT INTO RichiestaInsegnamento (student_id, tutor_id) VALUES ('$id_student', '$id_tutor')";


//se la query è andata a buon fine
if(mysqli_query($con, $query2)){
    //faccio json di conferma
    //inserendo anche id del tutor
    $json = json_encode(array('status' => 'success', 'id' => $id_tutor));
} else {
    
    //faccio json di errore inserendo anche id del tutor
    $json = json_encode(array('status' => 'error', 'id' => $id_tutor));


}

//stampo il json
echo $json;



?>