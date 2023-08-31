<?php

//file di connessione al database
include('db_info.php');

session_start();

//se è stato inviato il form
if (isset($_POST['code'])) {
    //recupero i dati dal form
    $language = $_POST['language'];
    $code = $_POST['code'];
    $id_exercise = $_POST['id_exercise'];

    $code = str_replace("'", "\\'", $code);
    $code = mysqli_real_escape_string($con, $code);



    //inserisco la soluzione nel database
    $query = "INSERT INTO Solution (exercise, language, text) VALUES ('$id_exercise', '$language', '$code')";
    $result = mysqli_query($con, $query);

    //se la query non è andata a buon fine

    echo  mysqli_error($con);
    if (!$result) {
        //ricarico la pagina con un messaggio di errore
        header('Location: ./add_solution.php?id=' . $id_exercise . '&status=' . mysqli_error($con));
        exit();
    } else {
        //altrimenti reindirizzo alla pagina delle soluzioni
        header('Location: ./manage_exercise.php');
        exit();
    }
}

//se non è stato passato un id, esco
if (!isset($_GET['id'])) {
    header('Location: ./dashboard.php');
} 

//se arrivo con un messaggio di errore
if (isset($_GET['status'])) {
    //se dentro il parametro c'è la parola 'duplicate'
    if (strpos($_GET['status'], 'Duplicate') !== false) {
        //stampo un messaggio di errore
        echo "<script>alert('Hai già inserito una soluzione in questo linguaggio.')</script>";
    }
}

//recupero l'id dell'esercizio
$id_exercise = $_GET['id'];



//recupero le info di sessione
$tutor_username = $_SESSION['username'];

//recupero l'id del tutor
$query = "SELECT * FROM tutor WHERE username = '$tutor_username'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);
$id_tutor = $row['tutor_id'];

//recupero le info dell'esercizio
$query = "SELECT * FROM Exercise WHERE exercise_id = '$id_exercise' AND creator_id = '$id_tutor'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);



//se l'esercizio non è stato creato dal tutor loggato, esco
if (!$row) {
    header('Location: ./dashboard.php?status=not_creator');
    exit();
}

//recupero i linguaggi dal database
$query = "SELECT * FROM Language";
$result = mysqli_query($con, $query);

//creo un array di linguaggi
$languages = array();

//per ogni linguaggio
while ($row = mysqli_fetch_array($result)) {
    //recupero il nome e l'id
    $name = $row['name'];
    $language_id = $row['language_id'];

    //creo un array con i dati
    $language = array(
        'name' => $name,
        'language_id' => $language_id
    );

    //aggiungo l'array di dati all'array di linguaggi
    array_push($languages, $language);
}

//codifico l'array di linguaggi in json
$json = json_encode($languages);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Aggiungi soluzione</title>
    <link rel="stylesheet" type="text/css" href="../css/add_solution.css">
</head>
<body>

<h1>Aggiungi soluzione</h1>

<div id="form">
    <form action="add_solution.php" method="post">
        <input type="hidden" name="id_exercise" value="<?php echo $id_exercise; ?>">
        <label for="language">Linguaggio</label>
        <select id="language" name="language"></select>
        <label for="code">Codice</label>
        <textarea id="code" name="code"></textarea>
        <input type="submit" value="Aggiungi">
    </form>
</div>

<script>
    var languages = <?php echo $json; ?>;
    var select = document.getElementById("language");
    for (var i = 0; i < languages.length; i++) {
        var option = document.createElement("option");
        option.value = languages[i].language_id;
        option.text = languages[i].name;
        select.appendChild(option);
    }
</script>

<?php



?>
</body>
</html>
