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

    //controllo prima che l'username esista
    $query = "SELECT * FROM tutor WHERE username = '$username'";
    $result = mysqli_query($con, $query);
    $num_rows = mysqli_num_rows($result);

    //se l'username non esiste, reindirizzo alla pagina di login con un errore
    if($num_rows == 0){
        header('Location: ./tutor_login.php?error=no_user');
    }

    //se l'username esiste, controllo che la password sia corretta
    $query = "SELECT * FROM tutor WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($con, $query);
    $num_rows = mysqli_num_rows($result);

    //se la password è sbagliata, reindirizzo alla pagina di login con un errore
    if($num_rows == 0){
        header('Location: ./tutor_login.php?error=wrong_password');
    } else {
        //se la password è corretta, salvo l'username nella sessione e reindirizzo alla pagina principale
        $_SESSION['username'] = $username;
        header('Location: ./dashboard.php?role=tutor');
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
                return
            }

            if (url.includes('no_user')) {
                alert('Errore: username non esistente!');
                return
            }

            if (url.includes('wrong_password')) {
                alert('Errore: password errata!');
                return
            }
        }
    </script>
</head>
<body onload='succ()'>
    <div class="loginbox-teacher">
        <h1>Login Tutor</h1>
        <div id='in'>
        <form action="tutor_login.php" method="post">
            <p>Username</p>
            <input type="text" name="username" placeholder="Enter Username" required>
            <p>Password</p>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" name="submit" value="Login">
            <div id='lnk'>
                <a href="./registration.php"><br>Non hai ancora un account?</a>
            </div>
            <div id='back'>
            <a href="../index.php"><br>Torna alla pagina principale!</a>
            
        </div>
        </form>