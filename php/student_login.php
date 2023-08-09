<?php

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
    $query = "SELECT * FROM studente WHERE username = '$username'";
    $result = mysqli_query($con, $query);
    $num_rows = mysqli_num_rows($result);

    //se l'username non esiste, reindirizzo alla pagina di login con un errore
    if($num_rows == 0){
        header('Location: ./student_login.php?error=no_user');
    }

    //se l'username esiste, controllo che la password sia corretta
    $query = "SELECT * FROM studente WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($con, $query);
    $num_rows = mysqli_num_rows($result);

    //se la password è sbagliata, reindirizzo alla pagina di login con un errore
    if($num_rows == 0){
        header('Location: ./student_login.php?error=wrong_password');
    } else {
        //se la password è corretta, salvo l'username nella sessione e reindirizzo alla pagina principale
        $_SESSION['username'] = $username;
        header('Location: ./dashboard.php');
    }
}

//controllo eventuali errori passati come parametri nell'url
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'no_user':
            echo '<script>alert("Errore: username non esistente!")</script>';
            break;
        case 'wrong_password':
            echo '<script>alert("Errore: password errata!")</script>';
            break;
        default:
            break;
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Login studenti</title>
    <link rel="stylesheet" type="text/css" href="../css/in_pages.css">

    <script>

        function check(){
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
<body onload='check()'>
    <div class="loginbox-student">
        <h1>Login Studenti</h1>
        <div id='in'>
        <form action="student_login.php" method="post">
            <p>Username</p>
            <input type="text" name="username" placeholder="Enter Username" required>
            <p>Password</p>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" name="submit" value="Login">
            <div id='lnk'>
                <a href="student_registration.php"><br>Non hai ancora un account?</a>
            </div>
            <div id='back'>
            <a href="../index.php"><br>Torna alla pagina principale!</a>
            
        </div>
        </form>



    </div>


    </div>
</body>