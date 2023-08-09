<?php

    include 'db_info.php';

    //se il form è stato inviato
    if(isset($_POST['submit'])){
        //prendi i dati dal form
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $level = $_POST['level'];

        //se il form è stato inviato da uno studente
        if(isset($_POST['submit']) && $_POST['submit'] == 'Registrati'){
            //inserisci i dati nel database
            $sql = "INSERT INTO student (username, password, name, level) VALUES ('$username', '$password', '$name', '$level')";
            $result = mysqli_query($con, $sql);

            //se l'inserimento è andato a buon fine, reindirizza alla pagina di login per studenti
            if($result){
                header('Location: student_login.php');
            }
            //altrimenti, mostra un messaggio di errore
            else{
                echo 'Si è verificato un errore. Riprova più tardi.';
            }
        }

        //se il form è stato inviato da un tutor
        else if(isset($_POST['submit']) && $_POST['submit'] == 'Registrati come tutor'){
            echo 'ok tutor';
            //inserisci i dati nel database
            $sql = "INSERT INTO tutor (username, password, name, level) VALUES ('$username', '$password', '$name', '$level')";
            $result = mysqli_query($con, $sql);

            //se l'inserimento è andato a buon fine, reindirizza alla pagina di login per tutor
            if($result){
                header('Location: tutor_login.php');
            }
            //altrimenti, mostra un messaggio di errore
            else{
                echo 'Si è verificato un errore. Riprova più tardi.';
            }
        }
    }


?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrazione</title>
    <link rel="stylesheet" type="text/css" href="../css/reg_page.css">

    <script>
        function setup(){
            // quando clicco sul div con classe student-reg, appare il form di registrazione per studenti
            document.querySelector('.student-reg').addEventListener('click', studentRegistration);

            // quando clicco sul div con classe tutor-reg, appare il form di registrazione per tutor
            document.querySelector('.tutor-reg').addEventListener('click', tutorRegistration);
        }

        function studentRegistration(){
            // con una transizione la scritta "Registrati come:" scompare
            document.querySelector('#in').style.opacity = '0';
            document.querySelector('#in').style.transition = 'opacity 0.5s ease-in-out';

            // con una transizione il div con classe tutor-reg scompare
            document.querySelector('.tutor-reg').style.opacity = '0';
            document.querySelector('.tutor-reg').style.transition = 'opacity 0.5s ease-in-out';

            //cancello tutti i div con una transizione
            document.querySelector('#roles').style.opacity = '0';
            document.querySelector('#roles').style.transition = 'opacity 0.5s ease-in-out';

            //coloro il background di role-reg con rgb(144, 50, 173);
            document.querySelector('.role-reg').style.backgroundColor = 'rgb(223, 114, 25)';
            document.querySelector('.role-reg').style.transition = 'background-color 0.5s ease-in-out';

            //attendo che le transizioni finiscano
            setTimeout(() => {
                document.querySelector('#in').innerHTML = '<h1>Registrati come studente</h1>';
                document.querySelector('#in').style.opacity = '1';

                //costruisco il form di registrazione per studenti
                //esso contiene i campi username, password, nome completo, livello frequentato
                //tutti i campi sono obbligatori, e livello frequentato è un menu a tendina con le opzioni "medie", "superiori", "università" e "lavoratore"
                document.querySelector('#in').innerHTML += '<form action="student_registration.php" method="post"><p>Username</p><input type="text" name="username" placeholder="Enter Username" required><p>Password</p><input type="password" name="password" placeholder="Enter Password" required><p>Nome completo</p><input type="text" name="name" placeholder="Enter Full Name" required><p>Livello frequentato</p><select name="level"><option value="medie">Medie</option><option value="superiori">Superiori</option><option value="università">Università</option><option value="lavoro">lavoro</option></select><input type="submit" name="submit" value="Registrati"></form>';
                
                //inserisci scritta "Sei un tutor? Clicca qui per registrarti come tutor"
                let d = document.createElement('div');
                d.innerHTML = 'Sei un tutor? Clicca qui per registrarti come tutor';
                d.style.cursor = 'pointer';
                d.addEventListener('click', tutorRegistration);
                document.querySelector('#in').appendChild(d);
                

            }, 500);
        }

        function tutorRegistration(){
            // con una transizione la scritta "Registrati come:" scompare
            document.querySelector('#in').style.opacity = '0';
            document.querySelector('#in').style.transition = 'opacity 0.5s ease-in-out';

            // con una transizione il div con classe tutor-reg scompare
            document.querySelector('.tutor-reg').style.opacity = '0';
            document.querySelector('.tutor-reg').style.transition = 'opacity 0.5s ease-in-out';

            //cancello tutti i div con una transizione
            document.querySelector('#roles').style.opacity = '0';
            document.querySelector('#roles').style.transition = 'opacity 0.5s ease-in-out';

            //coloro il background di role-reg con rgb(144, 50, 173);
            document.querySelector('.role-reg').style.backgroundColor = 'rgb(50, 107, 173)';
            document.querySelector('.role-reg').style.transition = 'background-color 0.5s ease-in-out';

            setTimeout(() => {
                document.querySelector('#in').innerHTML = '<h1>Registrati come tutor</h1>';
                document.querySelector('#in').style.opacity = '1';

                //costruisco il form di registrazione per tutor
                //esso contiene i campi username, password, nome completo, livello di insegnamento
                //tutti i campi sono obbligatori, e livello di insegnamento è un menu a tendina con le opzioni "medie", "superiori", "università" e "lavoro"
                //il pulsante 'registrati' è sostituito con "registrati come tutor" con id 'reg-tutor'
                document.querySelector('#in').innerHTML += '<form action="tutor_registration.php" method="post"><p>Username</p><input type="text" name="username" placeholder="Enter Username" required><p>Password</p><input type="password" name="password" placeholder="Enter Password" required><p>Nome completo</p><input type="text" name="name" placeholder="Enter Full Name" required><p>Livello di insegnamento</p><select name="level"><option value="medie">Medie</option><option value="superiori">Superiori</option><option value="università">Università</option><option value="lavoro">lavoro</option></select><input type="submit" name="submit" value="Registrati come tutor" id="reg-tutor"></form>';

                //inserisci scritta "Sei uno studente? Clicca qui per registrarti come studente"
                let d = document.createElement('div');
                d.innerHTML = 'Sei uno studente? Clicca qui per registrarti come studente';
                d.style.cursor = 'pointer';
                d.addEventListener('click', studentRegistration);
                document.querySelector('#in').appendChild(d);




                
                
            }, 500);
        }
    
    </script>
</head>

<body onload='setup()'>
    <div class="role-reg">
        <div id='in'>
        <h1>Registrati come:</h1>
        </div>

        <!-- prima ci sono due div, uno con su scritto "studente" e l'altro con su scritto "docente" -->
        <!-- cliccando su uno dei due, appare il form di registrazione per studenti o per docenti -->
        <div id='roles'>
            <div class="student-reg">
                <h1>Studente</h1>
            </div>

            <div class='tutor-reg'>
                <h1>Tutor</h1>
            </div>

        </div>
    </div>

</body>