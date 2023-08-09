<?php

    include 'db_info.php';

?>


<!DOCTYPE html>
<html>
<head>
    <title>Registrazione</title>
    <link rel="stylesheet" type="text/css" href="../css/reg_page.css">

    <script>

        //funzione che legge url della pagina e se c'è error=1, mostra un alert con il messaggio di errore
        function error(){
            let url = window.location.href;
            console.log(url);
            if(url.includes('error_duplicate_user')){
                alert('Errore: username già usato.');
            }
            if(url.includes('error_generic')){
                alert('Errore: qualcosa è andato storto.');
            }
        }



        function setup(){
            // quando clicco sul div con classe student-reg, appare il form di registrazione per studenti
            document.querySelector('.student-reg').addEventListener('click', studentRegistration);

            // quando clicco sul div con classe tutor-reg, appare il form di registrazione per tutor
            document.querySelector('.tutor-reg').addEventListener('click', tutorRegistration);

            error();
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
                //tutti i campi sono obbligatori, e livello frequentato è un menu a tendina con le opzioni "medie", "superiori", "università" e "professionale"
                //c'è anche un campo di verifica per la password
                //uso una regex per il campo password, che deve contenere almeno 8 caratteri, di cui almeno una lettera maiuscola, una minuscola e un numero
                document.querySelector('#in').innerHTML += '<form action="student_registration.php" method="post"><p>Username</p><input type="text" name="username" placeholder="Enter Username" required><p>Password</p><input type="password" name="password" placeholder="Enter Password" required><p>Verifica password</p><input type="password" name="password2" placeholder="Enter Password" required><p>Nome completo</p><input type="text" name="name" placeholder="Enter Full Name" required><p>Livello frequentato</p><select name="level"><option value="medie">Medie</option><option value="superiori">Superiori</option><option value="università">Università</option><option value="Professionale">Professionale</option></select><input type="submit" name="submit" value="Registrati"></form>';


                //aggiungo event per verificare che le due password inserite siano uguali
                document.querySelector('#in').addEventListener('input', function(e){
                    let p1 = document.querySelector('input[name="password"]').value;
                    let p2 = document.querySelector('input[name="password2"]').value;
                    if(p1 != p2){
                        e.preventDefault();
                        //coloro di rosso i bordi dei campi password
                        document.querySelector('input[name="password2"]').style.borderColor = 'red';
                        //e impedisco l'invio del form  
                        document.querySelector('#in').setAttribute('type', 'button');

                    } else {
                        //se le password sono uguali, rimetto i bordi neri
                        document.querySelector('input[name="password2"]').style.borderColor = 'black';
                        //e permetto l'invio del form
                        document.querySelector('#in').setAttribute('type', 'submit');
                    }
                });
                //inserisci scritta "Sei un tutor? Clicca qui per registrarti come tutor"
                let d = document.createElement('div');
                d.setAttribute('class', 'msg')

                d.innerHTML = 'Sei un tutor? Clicca qui per registrarti come tutor';
                d.style.cursor = 'pointer';
                d.addEventListener('click', tutorRegistration);
                document.querySelector('#in').appendChild(d);

                //aggiungo div per tornare alla pagina index
                let d2 = document.createElement('div');
                d2.setAttribute('class', 'msg')
                d2.innerHTML = 'Torna alla pagina principale';
                d2.style.cursor = 'pointer';
                d2.addEventListener('click', function(){
                    window.location.href = '../index.php';
                });
                document.querySelector('#in').appendChild(d2);

                

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
                //c'è anche un campo di verifica per la password
                //uso una regex per il campo password, che deve contenere almeno 8 caratteri, di cui almeno una lettera e un numero
                form = '<form action="tutor_registration.php" method="post"><p>Username</p><input type="text" name="username" placeholder="Enter Username" required><p>Password</p><input type="password" name="password" placeholder="Enter Password" required><p>Verifica password</p><input type="password" name="password2" placeholder="Enter Password" required><p>Nome completo</p><input type="text" name="name" placeholder="Enter Full Name" required><p>Livello insegnato</p><select name="level"><option value="medie">Medie</option><option value="superiori">Superiori</option><option value="università">Università</option><option value="Professionale">Professionale</option></select><input type="submit" name="submit" value="Registrati"></form>';
                document.querySelector('#in').innerHTML += form;



                //aggiungo event per verificare che le due password inserite siano uguali
                document.querySelector('#in').addEventListener('input', function(e){
                    let p1 = document.querySelector('input[name="password"]').value;
                    let p2 = document.querySelector('input[name="password2"]').value;
                    if(p1 != p2){
                        e.preventDefault();
                        //coloro di rosso i bordi dei campi password
                        document.querySelector('input[name="password2"]').style.borderColor = 'red';
                        //e impedisco l'invio del form  
                        document.querySelector('#in').setAttribute('type', 'button');

                    } else {
                        //se le password sono uguali, rimetto i bordi neri
                        document.querySelector('input[name="password2"]').style.borderColor = 'black';
                        //e permetto l'invio del form
                        document.querySelector('#in').setAttribute('type', 'submit');
                    }
                });
                //inserisci scritta "Sei uno studente? Clicca qui per registrarti come studente"
                let d = document.createElement('div');
                d.setAttribute('class', 'msg')
                d.innerHTML = 'Sei uno studente? Clicca qui per registrarti come studente';
                d.style.cursor = 'pointer';
                d.addEventListener('click', studentRegistration);
                document.querySelector('#in').appendChild(d);

                //aggiungo div per tornare alla pagina index
                let d2 = document.createElement('div');
                d2.setAttribute('class', 'msg')
                d2.innerHTML = 'Torna alla pagina principale';
                d2.style.cursor = 'pointer';
                d2.addEventListener('click', function(){
                    window.location.href = '../index.php';
                });
                document.querySelector('#in').appendChild(d2);
                
                
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