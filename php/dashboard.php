<?php

include('./db_info.php');
//differenzio il caso in cui l'utente sia loggato o meno

//inizio la sessione
session_start();

//se l'utente non è loggato, reindirizzo alla pagina di login
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
}

//se non viene passato il parametro role, reindirizzo alla pagina di login
if (!isset($_GET['role'])) {
    header('Location: ../index.php');
}

//assegno a delle variabili il contenuto della sessione
$username = $_SESSION['username'];
$role = $_GET['role'];

//se l'utente è un tutor, mostro la pagina di dashboard per i tutor
if ($role == 'tutor') {
    //prendo i dati del tutor dal database
    $query = "SELECT * FROM tutor WHERE username = '$username'";

    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);

    //assegno a delle variabili i dati del tutor
    $name = $row['NomeCompleto'];
    $level = $row['level'];

    switch ($level) {
        case 0:
            $level = 'Medie';
            break;
        case 1:
            $level = 'Superiori';
            break;
        case 2:
            $level = 'Università';
            break;
        case 3:
            $level = 'Professionale';
            break;
        default:
            $level = 'Medie';
            break;
    }
    
    //la dashboard dei tutor ha i seguenti campi:
    // un div con il nome del tutor
    // un div con il livello del tutor
    //un div dove sono visualizzate le richieste di Insegnamento da parte degli studenti, che possono essere accettate o rifiutate
    //un div dove sono visualizzati i nomi e i livelli degli studenti che sono stati accettati dal tutor

    //un ulteriore div contiene due pulsanti:
    // uno per accedere alla pagina di gestione degli esercizi già scritti
    // uno per accedere alla pagina di aggiunta di un nuovo esercizio

    echo '<div class="upper">
            <div class="name">
                <h1>Benvenuto, ' . $name . '</h1>
            </div>
            <div class="level">
                <h1>Livello: ' . $level . '</h1>
            </div>
        </div>';

    //faccio il div lower con le richieste di insegnamento e gli insegnamenti attivi
    echo '<div class="lower">
            <div class="requests">
                <h1>Richieste di insegnamento</h1>
                <div class="request">';
                //stampo come tabella le richieste di insegnamento
                

    //prima recupero l'id del tutor
    $query = "SELECT tutor_id FROM tutor WHERE username = '$username'";
    $result = mysqli_query($con, $query);

    $row = mysqli_fetch_array($result);

    $id_tutor = $row['tutor_id'];

    //recupero le richieste di insegnamento
    $query = "SELECT student_id FROM RichiestaInsegnamento WHERE tutor_id = '$id_tutor' AND Status = 'In attesa'";
    $result = mysqli_query($con, $query);

    //creo la tabella
    echo '<table id="richiesteInsegnamento">
            <tr>
                <th>Nome</th>
                <th>Livello</th>
                <th>Accetta</th>
                <th>Rifiuta</th>
            </tr>';


    

    //se ci sono richieste di insegnamento, le mostro
    if (mysqli_num_rows($result) != 0) {


        //per ogni richiesta di insegnamento, recupero i dati dello studente
        while ($row = mysqli_fetch_array($result)) {
            $id_studente = $row['student_id'];

            $query = "SELECT * FROM studente WHERE student_id = '$id_studente'";
            $result2 = mysqli_query($con, $query);

            $row2 = mysqli_fetch_array($result2);

            $name = $row2['nomeCompleto'];
            $level = $row2['livello'];

            switch ($level) {
                case 0:
                    $level = 'Medie';
                    break;
                case 1:
                    $level = 'Superiori';
                    break;
                case 2:
                    $level = 'Università';
                    break;
                case 3:
                    $level = 'Professionale';
                    break;
                default:
                    $level = 'Medie';
                    break;
            }

            //aggiungo una riga alla tabella, avendo cura di passare id di studente e tutor
            echo '<tr>
                    <td>' . $name . '</td>
                    <td>' . $level . '</td>
                    <td><a href="./accept.php?student_id=' . $id_studente .'">Accetta</a></td>
                    <td><a href="./refuse.php?student_id=' . $id_studente .'">Rifiuta</a></td>
                </tr>';
                      

        }


        

    } else {
        //appendi alla tabella un messaggio di nessuna richiesta di insegnamento
        echo '<tr>
                <td colspan="4">Nessuna richiesta di insegnamento</td>
            </tr>';
    }


    echo '</table>
        </div>
    </div>
        <div class="active">
            <h1>Insegnamenti attivi</h1>
            <div class="activeTable">';
            //stampo come tabella gli insegnamenti attivi

    //recupero gli insegnamenti attivi dalla tabella Insegnamenti
    $query = "SELECT * FROM Insegnamento WHERE tutor_id = '$id_tutor'";
    $result = mysqli_query($con, $query);

    //creo la tabella
    echo '<table id="insegnamentiAttivi">
            <tr>
                <th>Nome</th>
                <th>Livello</th>
            </tr>';
    
    //se ci sono insegnamenti attivi, li mostro
    if (mysqli_num_rows($result) != 0) {

        //per ogni insegnamento attivo, recupero i dati dello studente
        while ($row = mysqli_fetch_array($result)) {
            $id_studente = $row['student_id'];

            $query = "SELECT * FROM studente WHERE student_id = '$id_studente'";
            $result2 = mysqli_query($con, $query);

            $row2 = mysqli_fetch_array($result2);

            $name = $row2['nomeCompleto'];
            $level = $row2['livello'];

            switch ($level) {
                case 0:
                    $level = 'Medie';
                    break;
                case 1:
                    $level = 'Superiori';
                    break;
                case 2:
                    $level = 'Università';
                    break;
                case 3:
                    $level = 'Professionale';
                    break;
                default:
                    $level = 'Medie';
                    break;
            }

            //aggiungo una riga alla tabella
            echo '<tr>
                    <td>' . $name . '</td>
                    <td>' . $level . '</td>
                </tr>';
                      

        }

    } else {
        //appendi alla tabella un messaggio di nessun insegnamento attivo
        echo '<tr>
                <td colspan="2">Nessun insegnamento attivo</td>
            </tr>';
    }
     
} 

if ($role == 'student') {
    //prendo i dati dello studente dal database
    $query = "SELECT * FROM studente WHERE username = '$username'";

    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result);

    //assegno a delle variabili i dati dello studente
    $name = $row['nomeCompleto'];
    $level = $row['livello'];

    switch ($level) {
        case 0:
            $level = 'Medie';
            break;
        case 1:
            $level = 'Superiori';
            break;
        case 2:
            $level = 'Università';
            break;
        case 3:
            $level = 'Professionale';
            break;
        default:
            $level = 'Medie';
            break;
    }

    //la dashboard degli studenti ha i seguenti campi:
    // un div con il nome dello studente
    // un div con il livello dello studente
    
    //c'è poi un div sottostante che contiene tre div:
    // uno con i tutor che hanno accettato la richiesta di insegnamento
    // uno con le richieste che non sono state accettate (in attesa o rifiutate)
    //uno con un esercizio a caso tra quelli che sono stati scritti da uno dei tutor che hanno accettato la richiesta di insegnamento
    //se lo studente non ha tutor, viene mostrata una scritta del tipo "Non hai ancora nessun tutor"

    //un ulteriore div contiene due pulsanti:
    // uno per richiedere un nuovo tutor    
    //uno per aprire lo strumento di ricerca di un esercizio

    //da valutare l'aggiunta di un'icona per la gestione del profilo 
    //e una per accedere agli esercizi preferiti

    echo '<div class="upper_student">
            <div class="name">
                <h1>Benvenuto, ' . $name . '</h1>
            </div>
            <div class="level">
                <h1>Livello: ' . $level . '</h1>
            </div>';
    //inserisco anche i pulsanti di richiesta tutor e ricerca esercizio
    //essi sono rappresentati da un'immagine che funge da pulsante, di dimensioni 50x50
    //le immagini si trovano in /css/assets

    echo '<div class="buttons">
            <a href="./request.php"><img src="../css/assets/add-user.png" alt="Richiedi tutor" title="Richiedi tutor" width="50" height="50"></a>
            <a href="./search.php"><img src="../css/assets/search.png" alt="Cerca esercizio" title="Cerca esercizio" width="50" height="50"></a>
        </div>
        </div>';



    //faccio il div lower con i tutor accettati e i tutor rifiutati
    echo '<div class="lower_student">
            <div class="accepted">
                <h1>Tutor accettati</h1>';

    //prima recupero l'id dello studente
    $query = "SELECT * FROM studente WHERE username = '$username'";
    $result = mysqli_query($con, $query);

    $row = mysqli_fetch_array($result);

    $id_studente = $row['student_id'];
    
    //fai log sulla console per vedere se l'id dello studente è stato recuperato
    echo '<script>console.log("id studente: ' . $id_studente . '")</script>';

    //recupero i tutor accettati
    $query = "SELECT * FROM Insegnamento WHERE student_id = '$id_studente'";

    $result = mysqli_query($con, $query);

    //creo la tabella
    echo '<table id="tutorAccettati">
            <tr>
                <th>Nome</th>
                <th>Livello</th>
            </tr>';

    //se ci sono tutor accettati, li mostro
    if (mysqli_num_rows($result) != 0) {

        //per ogni tutor accettato, recupero i dati del tutor
        while ($row = mysqli_fetch_array($result)) {
            $id_tutor = $row['tutor_id'];

            $query = "SELECT * FROM tutor WHERE tutor_id = '$id_tutor'";
            $result2 = mysqli_query($con, $query);

            $row2 = mysqli_fetch_array($result2);

            $name = $row2['NomeCompleto'];
            $level = $row2['livello'];

            switch ($level) {
                case 0:
                    $level = 'Medie';
                    break;
                case 1:
                    $level = 'Superiori';
                    break;
                case 2:
                    $level = 'Università';
                    break;
                case 3:
                    $level = 'Professionale';
                    break;
                default:
                    $level = 'Medie';
                    break;
            }

            //aggiungo una riga alla tabella
            echo '<tr>
                    <td>' . $name . '</td>
                    <td>' . $level . '</td>
                </tr>';
                      

        }

    } else {
        //appendi alla tabella un messaggio di nessun tutor accettato, con colspan = 2
        echo '<tr>
                <td colspan="2">Nessun tutor accettato</td>
            </tr>';
    }


    //creo un altro div, sempre dentro lower_student, per i tutor rifiutati o in attesa
    echo '</table>
        </div>
        <div class="refused">
            <h1>Tutor rifiutati</h1>';
        
        
    //creo la tabella
    echo '<table id="tutorNon">
            <tr>
                <th>Nome</th>
                <th>Livello</th>
                <th>Status</th>
            </tr>';

    //recupero i tutor rifiutati o in attesa
    $query = "SELECT * FROM RichiestaInsegnamento WHERE student_id = '$id_studente' AND status <> 'Accettato'";

    $result = mysqli_query($con, $query);

    //se ci sono tutor rifiutati o in attesa, li mostro
    if (mysqli_num_rows($result) != 0) {

        //per ogni tutor rifiutato o in attesa, recupero i dati del tutor
        while ($row = mysqli_fetch_array($result)) {
            $id_tutor = $row['tutor_id'];

            $query = "SELECT * FROM tutor WHERE tutor_id = '$id_tutor'";
            $result2 = mysqli_query($con, $query);

            $row2 = mysqli_fetch_array($result2);

            $name = $row2['NomeCompleto'];
            $level = $row2['livello'];

            switch ($level) {
                case 0:
                    $level = 'Medie';
                    break;
                case 1:
                    $level = 'Superiori';
                    break;
                case 2:
                    $level = 'Università';
                    break;
                case 3:
                    $level = 'Professionale';
                    break;
                default:
                    $level = 'Medie';
                    break;
            }

            //aggiungo una riga alla tabella
            echo '<tr>
                    <td>' . $name . '</td>
                    <td>' . $level . '</td>
                    <td>' . $row['status'] . '</td>
                </tr>';
                      

        }

    } else {
        //appendi alla tabella un messaggio di nessun tutor rifiutato o in attesa, con colspan = 3
        echo '<tr>
                <td colspan="3">Nessuna richiesta pendente</td>
            </tr>';
    }

    //creo un altro div, sempre dentro lower_student, dove metto un testo per un esercizio casuale tra quelli scritti dai tutor
    echo '</table>
        </div>
        <div class="random">
            <h1>Prova un esercizio casuale</h1>
            <p>Prova un esercizio casuale tra quelli scritti dai tutor</p>
            <button>Prova</button>
        </div>
    </div>';

    


}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
</head>

<body>


