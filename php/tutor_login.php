<?php

//includo il file di connessione al database
include('./db_info.php');

//inizio la sessione
session_start();

//se è stato inviato il form
if(isset($_POST['submit'])){

    //assegno a delle variabili il contenuto del form
    $username = $_POST['username'];
    $password = $_POST['password'];

    //calcolo l'hash non troppo lungo da calcolare della password
    $password = hash('md5', $password);

    //seleziono dal database tutti gli utenti con username e password uguali a quelli inseriti nel form
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND role = 'tut'";
    $result = mysqli_query($conn, $query);

    //se l'utente esiste
    if(mysqli_num_rows($result) > 0){

        //salvo nella sessione il suo id
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $row['id'];

        //lo reindirizzo alla pagina riservata
        header('Location: ../php/tutor_page.php');
        exit;
    }else{
        //altrimenti lo reindirizzo al login con un messaggio di errore
        header('Location: ../php/tutor_login.php?error=1');
        exit;
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Login tutor</title>
    <link rel="stylesheet" type="text/css" href="../css/in_pages.css">

    <script>
        function succ() {
            //recupero url corrente
            var url = window.location.href;

            //se l'url contiene il parametro success, mostra un messaggio di successo
            if (url.includes('success')) {
                alert('Registrazione avvenuta con successo!');
            }

        }
    </script>
</head>
<body onload='succ()'>
    <div class="loginbox-teacher">
        <h1>Login Docenti</h1>
        <div id='in'>
        <form action="tutor_login.php" method="post">
            <p>Username</p>
            <input type="text" name="username" placeholder="Enter Username" required>
            <p>Password</p>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" name="submit" value="Login">
            <div id='lnk'>
                <a href="teacher_registration.php"><br>Non hai ancora un account?</a>
            </div>
            <div id='back'>
            <a href="../index.php"><br>Torna alla pagina principale!</a>
            
        </div>
        </form>