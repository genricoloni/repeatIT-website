<?php


//connessione al database
include('db_info.php');

//verifico connessione al database
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
} 
if (isset($_POST['submit'])) {
    //prendi i dati dal form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $level = $_POST['level'];

    
    //recupero l'ultimo id inserito nella tabella users
    $sql = "SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1";
    $result = mysqli_query($con, $sql);

    //se la query è andata a buon fine
    if ($result) {
        //prendo l'ultimo id inserito
        $row = mysqli_fetch_assoc($result);
        $last_id = $row['user_id'];

        //incremento l'id

        $last_id++;


        //controllo che l'username non sia già presente nel database
        $sql = "SELECT username FROM users WHERE username = '$username'";
        $result = mysqli_query($con, $sql);

        //se la query è andata a buon fine
        if ($result) {
            //se l'username è già presente nel database
            if (mysqli_num_rows($result) > 0) {
                //reindirizzo alla pagina di registrazione per tutor
                header('Location: ./registration.php?error_username');
                return;
            } 
        } else {
            echo 'Rilevato errore del tipo' . mysqli_error($con);

        }

        //faccio l'hash della password
        $password = password_hash($password, PASSWORD_DEFAULT);


        //inserisco i dati nella tabella users
        $sql = "INSERT INTO users VALUES ('$last_id', '$username', '$password', 'tut', '$name', '$level')";
        $result = mysqli_query($con, $sql);

        //se l'inserimento è andato a buon fine, reindirizzo alla pagina di login
        if ($result) {
            //nella pagina di login, mostro un messaggio di successo
            header('Location: ./tutor_login.php?success=1');

        }
        //altrimenti, mostra un messaggio di errore
        else {
            //ritorna alla pagina di registrazione con un messaggio di errore
            header('Location: ./registration.php?error_generic');

        }
    } else {
        echo 'errore';
    }








    //inserisci i dati nel database
    

    //se l'inserimento è andato a buon fine, reindirizza alla pagina di login per tutor


} else {
    echo 'Si è  tardi.';
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrazione</title>

    <script>
        function setup() {
            // quando clicco sul div con classe student-reg, appare il form di registrazione per studenti
            document.querySelector('.student-reg').addEventListener('click', studentRegistration);

            // quando clicco sul div con classe tutor-reg, appare il form di registrazione per tutor
            document.querySelector('.tutor-reg').addEventListener('click', tutorRegistration);
        }

        function studentRegistration() {
            // nascondi il div con classe student-reg
            document.querySelector('.student-reg').style.display = 'none';

            // mostra il form di registrazione per studenti
            document.querySelector('.student-form').style.display = 'block';
        }

        function tutorRegistration() {
            // nascondi il div con classe tutor-reg
            document.querySelector('.tutor-reg').style.display = 'none';

            // mostra il form di registrazione per tutor
            document.querySelector('.tutor-form').style.display = 'block';
        }
    </script>