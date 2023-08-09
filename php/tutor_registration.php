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

    //controllo che non ci sia già un utente con lo stesso username
    $query = "SELECT * FROM tutor WHERE username = '$username'";
    $result = mysqli_query($con, $query);
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
        header('Location: ./registration.php?error_duplicate_user=true');
    }
    
    //calcolo l'hash della password
    $password = hash('md5', $password);


    //traduco il livello in un numero da 0 a 3
    switch ($level) {
        case 'Medie':
            $level = 0;
            break;
        case 'Superiori':
            $level = 1;
            break;
        case 'Università':
            $level = 2;
            break;
        case 'Professionale':
            $level = 3;
            break;
        default:
            $level = 0;
            break;
    }

    //inserisco i dati nel database
    $query = "INSERT INTO tutor(`username`, `password`, `NomeCompleto`, `level`) VALUES ('$username', '$password', '$name', '$level')";
    $result = mysqli_query($con, $query);

    //se l'inserimento è andato a buon fine, reindirizzo alla pagina di login
    if ($result) {
        header('Location: ./tutor_login.php?success=true');
    } else {
        echo 'Errore durante la registrazione: ' . mysqli_error($con);
    }








} else {
    echo 'Si è  tardi.';
}

?>

