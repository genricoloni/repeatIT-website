<?php

//pagina per gestire le richieste di insegnamento da parte degli studenti
//includo il file di connessione al database
include('db_info.php');

//recupero le informazioni di sessione
session_start();
$student_username = $_SESSION['username'];

//se non è stato fatto il login, reindirizzo alla pagina di login
if(!isset($_SESSION['username'])){
    header('Location: ./student_login.php');
}

//se non si è uno studente, reindirizzo alla dashboard
if($_SESSION['role'] != 'student'){
    header('Location: ./dashboard.php');
}


echo '<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>Richieste di insegnamento</title>
        <link rel="stylesheet" type="text/css" href="../css/request_tutor.css">
        <script src="../js/request_tutor.js"></script>
        </head>';

echo '<body onload="setup()">';

//in cima metto un button per tornare alla dashboard
echo '<button class="back" onclick="window.location.href=\'./dashboard.php\'">Torna alla dashboard</button>';




//recupero tutti i tutor tranne quelli per cui è già stata fatta una richiesta e che abbiano livello maggiore o uguale a quello dello studente
$query = "SELECT * FROM tutor WHERE tutor_id NOT IN (SELECT tutor_id FROM RichiestaInsegnamento WHERE Status = 'Accettato' AND student_id = (SELECT student_id FROM studente WHERE username = '$student_username')) AND level >= (SELECT livello FROM studente WHERE username = '$student_username')";
$result = mysqli_query($con, $query);

//creo un div che conterrà tutti i tutor
echo '<div class="tutor_container">';

//se non ci sono tutor, stampo un messaggio del tipo "non ci sono tutor disponibili"
if(mysqli_num_rows($result) == 0){
    echo '<div class="no_tutor">Non ci sono tutor disponibili</div>';
}


//per ogni tutor
while($row = mysqli_fetch_array($result)){
    //creo un div per il tutor
    echo '<div class="tutor" id="tutor'.$row['tutor_id'].'">';

    //il div conterrà il nome, l'username e il livello del tutor
    echo '<div class="tutor_info">';

    //nomeCompleto
    echo '<div class="tutor_name"> Nome: '.$row['NomeCompleto'].'</div>';

    //username
    echo '<div class="tutor_username"> Username: '.$row['username'].'</div>';

    //converto il livello in una stringa
    switch($row['level']){
        case 0:
            $level = 'Medie';
            break;
        case 1:
            $level = 'Superiori';
            break;
        case 2:
            $level = 'Università';
            break;
        default:
            $level = 'Professionale';
            break;
    }

    //livello
    echo '<div class="tutor_level"> Livello: '.$level.'</div>';

    //chiudo il div con le informazioni
    echo '</div>';

    //mostra pulsante per mostrare gli esercizi
    echo '<button class="show_exercises" id="show'.$row['tutor_id'].'">Mostra esercizi</button>';
    
    //mostro pulsante per mandare richiesta
    echo '<button class="send_request" id="send'.$row['tutor_id'].'">Invia richiesta</button>';

    echo '</div>';


}

//chiudo il div che contiene tutti i tutor
echo '</div>';



echo '</body>

</html>';





?>



        
        
